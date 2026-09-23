<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Survey;
use App\Services\GeminiAiService;
use Illuminate\Http\Request;

class AiAnalyticsController extends Controller
{
    protected GeminiAiService $aiService;

    public function __construct(GeminiAiService $aiService)
    {
        $this->aiService = $aiService;
    }

    /**
     * Step 1: Survey Selection Hub for AI Analytics & Survey Research
     */
    public function index(Request $request)
    {
        $query = Survey::withCount(['questions', 'respondents'])
            ->orderBy('created_at', 'desc');

        if ($request->filled('search')) {
            $query->where('judul', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $surveys = $query->paginate(9);

        return view('admin.ai_analytics.index', compact('surveys'));
    }

    /**
     * Redirect directly to unified Survey Analytics view on the AI tab
     */
    public function show($id)
    {
        return redirect()->route('admin.surveys.analytics', ['id' => $id, 'tab' => 'AI_ANALYTICS']);
    }

    /**
     * AJAX endpoint to generate / regenerate AI analysis and save to DB
     */
    public function generate(Request $request, $id)
    {
        try {
            $survey = Survey::findOrFail($id);
            $respondentCount = $survey->respondents()->count();

            if ($respondentCount < 5) {
                return response()->json([
                    'success' => false,
                    'message' => "Jumlah responden belum memenuhi syarat minimal (saat ini {$respondentCount} responden, minimal 5 responden). Kumpulkan setidaknya 5 responden sebelum melakukan analisa AI agar hasil analisis objektif, valid, dan tidak mengalami bias data.",
                ], 422);
            }

            $analysis = $this->aiService->generateAnalysis($survey);

            // Persist generated analysis to survey DB record
            $survey->update([
                'ai_analysis'    => $analysis,
                'ai_analyzed_at' => now(),
            ]);

            return response()->json([
                'success'  => true,
                'analysis' => $analysis,
                'message'  => 'Analisa AI berhasil dibuat dan disimpan.',
            ]);
        } catch (\InvalidArgumentException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memproses analisa: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * AJAX / HTTP endpoint to delete saved AI analysis from DB
     */
    public function deleteAnalysis(Request $request, $id)
    {
        try {
            $survey = Survey::findOrFail($id);
            $survey->update([
                'ai_analysis'    => null,
                'ai_analyzed_at' => null,
            ]);

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Hasil analisa AI berhasil dihapus.',
                ]);
            }

            return redirect()->route('admin.surveys.analytics', ['id' => $id, 'tab' => 'AI_ANALYTICS'])
                ->with('success', 'Hasil analisa AI berhasil dihapus.');
        } catch (\Throwable $e) {
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menghapus analisa: ' . $e->getMessage(),
                ], 500);
            }

            return redirect()->back()->with('error', 'Gagal menghapus analisa: ' . $e->getMessage());
        }
    }
}
