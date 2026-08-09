<?php

namespace App\Http\Controllers;

use App\Models\Survey;
use App\Models\Respondent;
use App\Models\Answer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class StudentSurveyController extends Controller
{
    public function show(Request $request, $id)
    {
        $survey = Survey::with(['questions' => function ($q) {
            $q->orderBy('urutan', 'asc');
        }])->findOrFail($id);

        $isClosed = !$survey->isAcceptingResponses();
        $closedMessage = $survey->getClosedMessage();

        $alreadySubmittedCookie = $request->cookie("survey_submitted_{$survey->id}");
        $isAlreadySubmitted = $survey->limit_one_response && !empty($alreadySubmittedCookie);

        $formattedQuestions = $survey->questions->map(function ($q) {
            return [
                'id'                   => $q->id,
                'tipe_pertanyaan'      => $q->tipe_pertanyaan,
                'teks_pertanyaan'      => $q->teks_pertanyaan,
                'deskripsi_pertanyaan' => $q->deskripsi_pertanyaan ?? '',
                'opsi_jawaban'         => $q->opsi_jawaban,
                'parsed_options'       => $q->parsed_options,
                'wajib_diisi'          => (bool) $q->wajib_diisi,
                'urutan'               => (int) $q->urutan,
            ];
        });

        return view('student.survey', compact('survey', 'formattedQuestions', 'isClosed', 'closedMessage', 'isAlreadySubmitted'));
    }

    public function submit(Request $request, $id)
    {
        $survey = Survey::with('questions')->findOrFail($id);

        if (!$survey->isAcceptingResponses()) {
            return response()->json(['error' => $survey->getClosedMessage()], 403);
        }

        // Validate NISN
        $request->validate([
            'nisn' => 'required|string|min:3|max:20',
        ]);

        $nisn = trim($request->nisn);

        // Check duplicate submission ONLY IF limit_one_response is enabled by admin
        if ($survey->limit_one_response) {
            $existing = Respondent::where('survey_id', $survey->id)
                ->where('nisn', $nisn)
                ->first();

            if ($existing) {
                return response()->json([
                    'error' => "NISN / Responden {$nisn} telah pernah menanggapi survey ini. Pembatasan 1x tanggapan diaktifkan.",
                ], 422);
            }
        }

        $answersData = $request->input('answers', []);
        $filesData   = $request->file('answers', []);

        // Validate required questions
        foreach ($survey->questions as $q) {
            if ($q->wajib_diisi) {
                $hasText = isset($answersData[$q->id]) && $answersData[$q->id] !== '';
                $hasFile = isset($filesData[$q->id]);

                if (!$hasText && !$hasFile) {
                    return response()->json([
                        'error' => "Pertanyaan \"{$q->teks_pertanyaan}\" wajib diisi.",
                    ], 422);
                }
            }
        }

        DB::transaction(function () use ($survey, $nisn, $answersData, $filesData, $request) {
            $respondent = Respondent::create([
                'survey_id'    => $survey->id,
                'nisn'         => $nisn,
                'submitted_at' => now()->setTimezone('Asia/Jakarta'),
            ]);

            foreach ($survey->questions as $q) {
                $value = null;

                if (in_array($q->tipe_pertanyaan, ['IMAGE_UPLOAD', 'FILE_UPLOAD']) && $request->hasFile("answers.{$q->id}")) {
                    $file  = $request->file("answers.{$q->id}");
                    $path  = $file->store('survey_uploads', 'public');
                    $value = Storage::url($path);
                } elseif (isset($answersData[$q->id])) {
                    $raw   = $answersData[$q->id];
                    $value = is_array($raw) ? json_encode(array_values($raw)) : (string) $raw;
                }

                if ($value !== null && $value !== '') {
                    Answer::create([
                        'respondent_id' => $respondent->id,
                        'question_id'   => $q->id,
                        'jawaban'       => $value,
                    ]);
                }
            }
        });

        $res = response()->json([
            'success' => true,
            'message' => 'Jawaban survey Anda berhasil disimpan. Terima kasih atas partisipasi Anda!',
        ]);

        if ($survey->limit_one_response) {
            $res->cookie("survey_submitted_{$survey->id}", $nisn, 525600); // 1 year cookie
        }

        return $res;
    }
}
