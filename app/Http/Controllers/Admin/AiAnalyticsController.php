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
     * AJAX endpoint to regenerate AI analysis
     */
    public function generate(Request $request, $id)
    {
        try {
            $survey = Survey::findOrFail($id);
            $analysis = $this->aiService->generateAnalysis($survey);

            return response()->json([
                'success'  => true,
                'analysis' => $analysis,
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memproses analisa: ' . $e->getMessage(),
            ], 500);
        }
    }
}
