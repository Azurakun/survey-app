<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Survey;
use App\Models\Respondent;
use App\Services\SurveyAnalyticsService;
use App\Services\GeminiAiService;
use App\Exports\SurveyRawExport;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class AnalyticsController extends Controller
{
    protected GeminiAiService $aiService;
    protected SurveyAnalyticsService $analyticsService;

    public function __construct(GeminiAiService $aiService, SurveyAnalyticsService $analyticsService)
    {
        $this->aiService = $aiService;
        $this->analyticsService = $analyticsService;
    }

    public function show(Request $request, $id)
    {
        $survey = Survey::with(['questions.answers', 'respondents.answers'])->findOrFail($id);

        // Ensure share_token exists
        if (empty($survey->share_token)) {
            $survey->update(['share_token' => Str::random(32)]);
        }

        $query = $survey->respondents();

        if ($request->filled('start_date')) {
            $query->whereDate('submitted_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('submitted_at', '<=', $request->end_date);
        }

        $respondents      = $query->latest('submitted_at')->get();
        $totalRespondents = $respondents->count();

        // ─── Build analytics per question using SurveyAnalyticsService ───
        $analytics = $this->analyticsService->buildQuestionAnalytics($survey, $respondents);
        $aiAnalysis = $survey->ai_analysis;

        return view('admin.surveys.analytics', compact(
            'survey',
            'respondents',
            'totalRespondents',
            'analytics',
            'aiAnalysis'
        ));
    }

    /**
     * AJAX endpoint to toggle public shareable analytics link on/off
     */
    public function toggleShare(Request $request, $id)
    {
        $survey = Survey::findOrFail($id);

        if (empty($survey->share_token)) {
            $survey->share_token = Str::random(32);
        }

        $newState = $request->has('enabled')
            ? $request->boolean('enabled')
            : !$survey->public_analytics_enabled;

        $survey->update([
            'public_analytics_enabled' => $newState,
            'share_token'              => $survey->share_token,
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'enabled' => $survey->public_analytics_enabled,
                'share_url' => $survey->share_url,
                'message' => $survey->public_analytics_enabled
                    ? 'Tautan hasil survei publik berhasil diaktifkan.'
                    : 'Tautan hasil survei publik berhasil dinonaktifkan.',
            ]);
        }

        return redirect()->back()->with('success', $survey->public_analytics_enabled
            ? 'Tautan publik diaktifkan.'
            : 'Tautan publik dinonaktifkan.');
    }

    /**
     * AJAX endpoint to regenerate share token (invalidate old link)
     */
    public function regenerateShareToken(Request $request, $id)
    {
        $survey = Survey::findOrFail($id);
        $survey->update([
            'share_token' => Str::random(32),
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'success'   => true,
                'share_url' => $survey->share_url,
                'message'   => 'Tautan publik baru berhasil digenerate.',
            ]);
        }

        return redirect()->back()->with('success', 'Tautan publik baru berhasil dibuat.');
    }

    public function export(Request $request, $id)
    {
        $survey   = Survey::with(['questions', 'respondents.answers'])->findOrFail($id);
        $filename = 'Hasil_Survey_' . preg_replace('/[^A-Za-z0-9_]/', '_', $survey->judul) . '.xlsx';

        return Excel::download(new SurveyRawExport($survey), $filename);
    }

    public function destroyRespondent($id)
    {
        $respondent = Respondent::findOrFail($id);
        $surveyId   = $respondent->survey_id;
        $respondent->delete();

        return redirect()->route('admin.surveys.analytics', $surveyId)
            ->with('success', 'Data jawaban responden NISN ' . $respondent->nisn . ' berhasil dihapus.');
    }

    public function printAi($id)
    {
        $survey = Survey::with(['questions', 'respondents'])->findOrFail($id);
        $totalRespondents = $survey->respondents->count();

        // Load saved analysis or generate if missing and sufficient respondents exist
        $aiAnalysis = $survey->ai_analysis;
        if (empty($aiAnalysis) && $totalRespondents >= 5) {
            try {
                $aiAnalysis = $this->aiService->generateAnalysis($survey);
                $survey->update([
                    'ai_analysis'    => $aiAnalysis,
                    'ai_analyzed_at' => now(),
                ]);
            } catch (\Throwable $e) {
                $aiAnalysis = null;
            }
        }

        return view('admin.surveys.print_ai', compact('survey', 'totalRespondents', 'aiAnalysis'));
    }
}
