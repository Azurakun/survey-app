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
            'judul'     => 'Survey Riset Pasar Produk Kewirausahaan Baru',
            'deskripsi' => 'Survei ini bertujuan mengukur minat pasar, estimasi harga, dan masukan siswa terhadap produk inovasi kewirausahaan SMKN 2 Indramayu.',
            'status'    => 'DRAFT',
        ]);

        // Default questions to kick-start the builder
        Question::create([
            'survey_id'       => $survey->id,
            'tipe_pertanyaan' => 'SINGLE_CHOICE',
            'teks_pertanyaan' => 'Seberapa tertarik Anda mencoba produk baru ini?',
            'opsi_jawaban'    => json_encode(['Sangat Tertarik', 'Cukup Tertarik', 'Kurang Tertarik', 'Tidak Tertarik']),
            'wajib_diisi'     => true,
            'urutan'          => 1,
        ]);

        Question::create([
            'survey_id'       => $survey->id,
            'tipe_pertanyaan' => 'NUMBER',
            'teks_pertanyaan' => 'Berapa harga (Rupiah) yang bersedia Anda bayar untuk produk ini?',
            'wajib_diisi'     => true,
            'urutan'          => 2,
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
                'id'              => $q->id,
                'tipe_pertanyaan' => $q->tipe_pertanyaan,
                'teks_pertanyaan' => $q->teks_pertanyaan,
                'opsi_jawaban'    => $q->parsed_options ?: ['Opsi 1', 'Opsi 2'],
                'wajib_diisi'     => (bool) $q->wajib_diisi,
                'urutan'          => (int) $q->urutan,
            ];
        });

        return view('admin.surveys.builder', compact('survey', 'formattedQuestions'));
    }

    public function update(Request $request, $id)
    {
        $survey = Survey::findOrFail($id);

        $request->validate([
            'judul'           => 'required|string|max:255',
            'deskripsi'       => 'nullable|string',
            'status'          => 'required|in:DRAFT,PUBLISHED,CLOSED',
            'tanggal_mulai'   => 'nullable|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
            'questions'       => 'nullable|array',
        ]);

        DB::transaction(function () use ($survey, $request) {
            $survey->update([
                'judul'           => trim($request->judul),
                'deskripsi'       => $request->deskripsi ? trim($request->deskripsi) : null,
                'status'          => $request->status,
                'tanggal_mulai'   => $request->tanggal_mulai ?: null,
                'tanggal_selesai' => $request->tanggal_selesai ?: null,
            ]);

            if ($request->has('questions') && is_array($request->questions)) {
                // Delete all existing questions and re-create from payload
                $survey->questions()->delete();

                foreach ($request->questions as $idx => $qData) {
                    if (empty($qData['teks_pertanyaan'])) continue;

                    $opsi = null;
                    if (!empty($qData['opsi_jawaban'])) {
                        $opsi = is_array($qData['opsi_jawaban'])
                            ? json_encode(array_values(array_filter($qData['opsi_jawaban'], fn($o) => trim($o) !== '')))
                            : $qData['opsi_jawaban'];
                    }

                    Question::create([
                        'survey_id'       => $survey->id,
                        'tipe_pertanyaan' => $qData['tipe_pertanyaan'] ?? 'SHORT_TEXT',
                        'teks_pertanyaan' => trim($qData['teks_pertanyaan']),
                        'opsi_jawaban'    => $opsi,
                        'wajib_diisi'     => isset($qData['wajib_diisi']) ? (bool) $qData['wajib_diisi'] : true,
                        'urutan'          => $idx + 1,
                    ]);
                }
            }
        });

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
