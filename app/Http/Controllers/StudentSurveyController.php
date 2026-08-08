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
    public function show($id)
    {
        $survey = Survey::with(['questions' => function ($q) {
            $q->orderBy('urutan', 'asc');
        }])->findOrFail($id);

        if ($survey->status !== 'PUBLISHED') {
            return view('student.error', [
                'message' => 'Survey ini sedang tidak aktif atau belum dibuka untuk umum.',
                'survey'  => $survey,
            ]);
        }

        $formattedQuestions = $survey->questions->map(function ($q) {
            return [
                'id'              => $q->id,
                'tipe_pertanyaan' => $q->tipe_pertanyaan,
                'teks_pertanyaan' => $q->teks_pertanyaan,
                'opsi_jawaban'    => $q->opsi_jawaban,
                'parsed_options'  => $q->parsed_options,
                'wajib_diisi'     => (bool) $q->wajib_diisi,
                'urutan'          => (int) $q->urutan,
            ];
        });

        return view('student.survey', compact('survey', 'formattedQuestions'));
    }

    public function submit(Request $request, $id)
    {
        $survey = Survey::with('questions')->findOrFail($id);

        if ($survey->status !== 'PUBLISHED') {
            return response()->json(['error' => 'Survey ini sudah tidak aktif.'], 403);
        }

        // Validate NISN
        $request->validate([
            'nisn' => 'required|string|min:5|max:20',
        ]);

        $nisn = trim($request->nisn);

        // Check duplicate submission
        $existing = Respondent::where('survey_id', $survey->id)
            ->where('nisn', $nisn)
            ->first();

        if ($existing) {
            return response()->json([
                'error' => "NISN {$nisn} sudah pernah mengisi survey ini sebelumnya. Setiap siswa hanya boleh mengisi satu kali.",
            ], 422);
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
                'submitted_at' => now(),
            ]);

            foreach ($survey->questions as $q) {
                $value = null;

                if ($q->tipe_pertanyaan === 'IMAGE_UPLOAD' && $request->hasFile("answers.{$q->id}")) {
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

        return response()->json([
            'success' => true,
            'message' => 'Jawaban survey Anda berhasil disimpan. Terima kasih atas partisipasi Anda!',
        ]);
    }
}
