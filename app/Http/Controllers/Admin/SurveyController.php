<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Survey;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SurveyController extends Controller
{
    public function index()
    {
        $surveys = Survey::withCount(['questions', 'respondents'])
            ->latest()
            ->get();

        return view('admin.surveys.index', compact('surveys'));
    }

    public function create()
    {
        $survey = Survey::create([
            'user_id'   => Auth::id(),
            'judul'     => '',
            'deskripsi' => null,
            'status'    => 'DRAFT',
        ]);

        // Default 1 blank question for builder start
        Question::create([
            'survey_id'            => $survey->id,
            'tipe_pertanyaan'      => 'SINGLE_CHOICE',
            'teks_pertanyaan'      => '',
            'deskripsi_pertanyaan' => null,
            'opsi_jawaban'         => json_encode(['Opsi 1', 'Opsi 2']),
            'wajib_diisi'          => true,
            'urutan'               => 1,
        ]);

        return redirect()->route('admin.surveys.builder', $survey->id);
    }

    public function builder($id)
    {
        $survey = Survey::with(['questions' => function ($q) {
            $q->orderBy('urutan', 'asc');
        }])->findOrFail($id);

        $formattedQuestions = $survey->questions->map(function ($q) {
            return [
                'id'                   => $q->id,
                'tipe_pertanyaan'      => $q->tipe_pertanyaan,
                'teks_pertanyaan'      => $q->teks_pertanyaan,
                'deskripsi_pertanyaan' => $q->deskripsi_pertanyaan ?? '',
                'opsi_jawaban'         => $q->parsed_options ?: ['Opsi 1', 'Opsi 2'],
                'wajib_diisi'          => (bool) $q->wajib_diisi,
                'urutan'               => (int) $q->urutan,
            ];
        });

        if ($formattedQuestions->isEmpty()) {
            $formattedQuestions = collect([[
                'id'                   => null,
                'tipe_pertanyaan'      => 'SINGLE_CHOICE',
                'teks_pertanyaan'      => '',
                'deskripsi_pertanyaan' => '',
                'opsi_jawaban'         => ['Opsi 1', 'Opsi 2'],
                'wajib_diisi'          => true,
                'urutan'               => 1,
            ]]);
        }

        return view('admin.surveys.builder', compact('survey', 'formattedQuestions'));
    }

    public function update(Request $request, $id)
    {
        $survey = Survey::findOrFail($id);

        // Convert empty string dates to null before validation to prevent 422 errors
        $request->merge([
            'closed_at'       => $request->closed_at ?: null,
            'tanggal_mulai'   => $request->tanggal_mulai ?: null,
            'tanggal_selesai' => $request->tanggal_selesai ?: null,
            'status'          => $request->status ?: $survey->status,
        ]);

        $request->validate([
            'judul'                 => $request->status === 'PUBLISHED' ? 'required|string|max:255' : 'nullable|string|max:255',
            'deskripsi'             => 'nullable|string',
            'status'                => 'required|in:DRAFT,PUBLISHED,CLOSED',
            'accepting_responses'   => 'nullable|boolean',
            'closed_at'             => 'nullable',
            'custom_closed_message' => 'nullable|string',
            'limit_one_response'    => 'nullable|boolean',
            'tanggal_mulai'         => 'nullable',
            'tanggal_selesai'       => 'nullable',
            'questions'             => 'nullable|array',
        ]);

        try {
            DB::transaction(function () use ($survey, $request) {
                $survey->update([
                    'judul'                 => $request->judul ? trim($request->judul) : 'Survey Tanpa Judul',
                    'deskripsi'             => $request->deskripsi ? trim($request->deskripsi) : null,
                    'status'                => $request->status,
                    'accepting_responses'   => $request->has('accepting_responses') ? (bool)$request->accepting_responses : $survey->accepting_responses,
                    'closed_at'             => $request->closed_at ?: null,
                    'custom_closed_message' => $request->custom_closed_message ? trim($request->custom_closed_message) : null,
                    'limit_one_response'    => $request->has('limit_one_response') ? (bool)$request->limit_one_response : $survey->limit_one_response,
                    'tanggal_mulai'         => $request->tanggal_mulai ?: null,
                    'tanggal_selesai'       => $request->tanggal_selesai ?: null,
                ]);

                if ($request->has('questions') && is_array($request->questions)) {
                    $incomingIds = [];

                    foreach ($request->questions as $idx => $qData) {
                        if (empty($qData['teks_pertanyaan'])) continue;

                        $opsi = null;
                        if (!empty($qData['opsi_jawaban'])) {
                            $opsi = is_array($qData['opsi_jawaban'])
                                ? json_encode(array_values(array_filter($qData['opsi_jawaban'], fn($o) => trim($o) !== '')))
                                : $qData['opsi_jawaban'];
                        }

                        $qId = !empty($qData['id']) ? $qData['id'] : null;

                        if ($qId) {
                            $existingQ = Question::where('survey_id', $survey->id)->where('id', $qId)->first();
                            if ($existingQ) {
                                $existingQ->update([
                                    'tipe_pertanyaan'      => $qData['tipe_pertanyaan'] ?? 'SHORT_TEXT',
                                    'teks_pertanyaan'      => trim($qData['teks_pertanyaan']),
                                    'deskripsi_pertanyaan' => !empty($qData['deskripsi_pertanyaan']) ? trim($qData['deskripsi_pertanyaan']) : null,
                                    'opsi_jawaban'         => $opsi,
                                    'wajib_diisi'          => isset($qData['wajib_diisi']) ? (bool) $qData['wajib_diisi'] : true,
                                    'urutan'               => $idx + 1,
                                ]);
                                $incomingIds[] = $existingQ->id;
                                continue;
                            }
                        }

                        $newQ = Question::create([
                            'survey_id'            => $survey->id,
                            'tipe_pertanyaan'      => $qData['tipe_pertanyaan'] ?? 'SHORT_TEXT',
                            'teks_pertanyaan'      => trim($qData['teks_pertanyaan']),
                            'deskripsi_pertanyaan' => !empty($qData['deskripsi_pertanyaan']) ? trim($qData['deskripsi_pertanyaan']) : null,
                            'opsi_jawaban'         => $opsi,
                            'wajib_diisi'          => isset($qData['wajib_diisi']) ? (bool) $qData['wajib_diisi'] : true,
                            'urutan'               => $idx + 1,
                        ]);
                        $incomingIds[] = $newQ->id;
                    }

                    if (!empty($incomingIds)) {
                        // Delete only questions that were removed by admin
                        Question::where('survey_id', $survey->id)
                            ->whereNotIn('id', $incomingIds)
                            ->delete();
                    }
                }
            });
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Survey update error: ' . $e->getMessage());
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
            }
            return redirect()->back()->with('error', 'Gagal menyimpan: ' . $e->getMessage());
        }

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'survey' => $survey->fresh(['questions'])]);
        }

        return redirect()->back()->with('success', 'Survey berhasil diperbarui!');
    }

    public function updateStatus(Request $request, $id)
    {
        $survey = Survey::findOrFail($id);
        $request->validate(['status' => 'required|in:DRAFT,PUBLISHED,CLOSED']);
        $survey->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'Status survey berhasil diubah menjadi ' . $request->status . '.');
    }

    public function destroy($id)
    {
        $survey = Survey::findOrFail($id);
        $judul  = $survey->judul;
        $survey->delete();

        return redirect()->route('admin.surveys.index')
            ->with('success', "Survey \"{$judul}\" berhasil dihapus beserta seluruh datanya.");
    }

    public function toggleAcceptingResponses(Request $request, $id)
    {
        $survey = Survey::findOrFail($id);
        $survey->accepting_responses = !$survey->accepting_responses;
        $survey->save();

        return response()->json([
            'success' => true,
            'accepting_responses' => (bool)$survey->accepting_responses,
            'message' => $survey->accepting_responses ? 'Survey sekarang menerima tanggapan.' : 'Survey ditutup dari menerima tanggapan baru.'
        ]);
    }

    public function destroyQuestion($id)
    {
        $question = Question::findOrFail($id);
        $question->delete();

        if (request()->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Pertanyaan berhasil dihapus.']);
        }

        return redirect()->back()->with('success', 'Pertanyaan berhasil dihapus.');
    }
}
