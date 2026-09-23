<?php

namespace App\Http\Controllers;

use App\Models\Survey;
use App\Services\SurveyAnalyticsService;
use App\Exports\SurveyRawExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ShareAnalyticsController extends Controller
{
    protected SurveyAnalyticsService $analyticsService;

    public function __construct(SurveyAnalyticsService $analyticsService)
    {
        $this->analyticsService = $analyticsService;
    }

    /**
     * Public read-only analytics view via shareable token.
     */
    public function show(Request $request, string $token)
    {
        $survey = Survey::with(['questions.answers', 'respondents.answers'])
            ->where('share_token', $token)
            ->firstOrFail();

        // Check if public access is enabled by administrator
        if (!$survey->public_analytics_enabled) {
            return view('share.disabled', compact('survey'));
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

        $analytics  = $this->analyticsService->buildQuestionAnalytics($survey, $respondents);
        $aiAnalysis = $survey->ai_analysis;
        $isPublicView = true;

        return view('share.analytics', compact(
            'survey',
            'respondents',
            'totalRespondents',
            'analytics',
            'aiAnalysis',
            'isPublicView'
        ));
    }

    /**
     * Public print/PDF view for shared survey.
     */
    public function printAi(string $token)
    {
        $survey = Survey::with(['questions', 'respondents'])
            ->where('share_token', $token)
            ->firstOrFail();

        if (!$survey->public_analytics_enabled) {
            return view('share.disabled', compact('survey'));
        }

        $totalRespondents = $survey->respondents->count();
        $aiAnalysis       = $survey->ai_analysis;
        $isPublicView     = true;

        return view('admin.surveys.print_ai', compact('survey', 'totalRespondents', 'aiAnalysis', 'isPublicView'));
    }

    /**
     * Public Excel export for shared survey.
     */
    public function export(string $token)
    {
        $survey = Survey::with(['questions', 'respondents.answers'])
            ->where('share_token', $token)
            ->firstOrFail();

        if (!$survey->public_analytics_enabled) {
            return view('share.disabled', compact('survey'));
        }

        $filename = 'Hasil_Survey_' . preg_replace('/[^A-Za-z0-9_]/', '_', $survey->judul) . '.xlsx';
        return Excel::download(new SurveyRawExport($survey), $filename);
    }
}
