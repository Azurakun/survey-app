<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Survey;
use App\Models\Respondent;
use App\Models\Question;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // ─── Platform-Level Management Metrics ──────────────────────────────
        $totalSurveys     = Survey::count();
        $publishedSurveys = Survey::where('status', 'PUBLISHED')->count();
        $draftSurveys     = Survey::where('status', 'DRAFT')->count();
        $closedSurveys    = Survey::where('status', 'CLOSED')->count();
        $totalRespondents = Respondent::count();
        $totalQuestions   = Question::count();

        // Average questions per survey
        $avgQuestionsPerSurvey = $totalSurveys > 0 ? round($totalQuestions / $totalSurveys, 1) : 0;

        // Fetch all surveys with question & respondent counts for the main table
        $surveys = Survey::withCount(['questions', 'respondents'])
            ->latest()
            ->get();

        return view('admin.dashboard', compact(
            'totalSurveys',
            'publishedSurveys',
            'draftSurveys',
            'closedSurveys',
            'totalRespondents',
            'totalQuestions',
            'avgQuestionsPerSurvey',
            'surveys'
        ));
    }
}

