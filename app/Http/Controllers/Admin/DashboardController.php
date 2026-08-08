<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Survey;
use App\Models\Respondent;
use App\Models\Answer;
use App\Models\Question;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalSurveys    = Survey::count();
        $publishedSurveys = Survey::where('status', 'PUBLISHED')->count();
        $totalRespondents = Respondent::count();

        // Eager load all surveys with counts & nested data for analytics
        $surveys = Survey::withCount(['questions', 'respondents'])
            ->with(['questions', 'respondents.answers'])
            ->latest()
            ->get();

        // ─── Market Intelligence Aggregation ─────────────────────────────
        $totalPriceAnswers  = [];
        $totalLikertScores  = [];

        // Sentiment buckets — keyed by label, filled dynamically
        $sentimentBuckets = [];

        foreach ($surveys as $survey) {
            $numQIds    = $survey->questions->where('tipe_pertanyaan', 'NUMBER')->pluck('id');
            $likertQIds = $survey->questions->where('tipe_pertanyaan', 'LIKERT')->pluck('id');
            $choiceQIds = $survey->questions->whereIn('tipe_pertanyaan', ['SINGLE_CHOICE', 'MULTIPLE_CHOICE'])->pluck('id');

            foreach ($survey->respondents as $respondent) {
                foreach ($respondent->answers as $ans) {
                    if (!$ans->jawaban) continue;

                    // WTP Numeric aggregation
                    if ($numQIds->contains($ans->question_id) && is_numeric($ans->jawaban)) {
                        $totalPriceAnswers[] = (float) $ans->jawaban;
                    }

                    // Likert satisfaction aggregation
                    if ($likertQIds->contains($ans->question_id)) {
                        preg_match('/^[1-5]/', $ans->jawaban, $m);
                        if (isset($m[0])) {
                            $totalLikertScores[] = (int) $m[0];
                        }
                    }

                    // Choice sentiment aggregation (dynamic bucketing)
                    if ($choiceQIds->contains($ans->question_id)) {
                        $decoded = json_decode($ans->jawaban, true);
                        $options = is_array($decoded) ? $decoded : [$ans->jawaban];
                        foreach ($options as $opt) {
                            $opt = trim($opt);
                            if ($opt === '') continue;
                            $lc = strtolower($opt);
                            if (str_contains($lc, 'sangat tertarik') || str_contains($lc, 'sangat setuju')) {
                                $key = 'Sangat Tertarik';
                            } elseif (str_contains($lc, 'tertarik') || str_contains($lc, 'setuju')) {
                                $key = 'Tertarik';
                            } elseif (str_contains($lc, 'cukup')) {
                                $key = 'Cukup Tertarik';
                            } elseif (str_contains($lc, 'kurang') || str_contains($lc, 'tidak')) {
                                $key = 'Kurang Tertarik';
                            } else {
                                $key = 'Lainnya';
                            }
                            $sentimentBuckets[$key] = ($sentimentBuckets[$key] ?? 0) + 1;
                        }
                    }
                }
            }
        }

        // ─── Synthesize KPIs ─────────────────────────────────────────────
        $avgPriceWTP  = count($totalPriceAnswers) > 0 ? round(array_sum($totalPriceAnswers) / count($totalPriceAnswers)) : 0;
        $minPriceWTP  = count($totalPriceAnswers) > 0 ? min($totalPriceAnswers) : 0;
        $maxPriceWTP  = count($totalPriceAnswers) > 0 ? max($totalPriceAnswers) : 0;

        $avgSatisfactionScore = count($totalLikertScores) > 0
            ? number_format(array_sum($totalLikertScores) / count($totalLikertScores), 2)
            : '0.00';

        $positiveCount   = ($sentimentBuckets['Sangat Tertarik'] ?? 0) + ($sentimentBuckets['Tertarik'] ?? 0);
        $totalSentiments = array_sum($sentimentBuckets);
        $marketDemandIndex = $totalSentiments > 0 ? round(($positiveCount / $totalSentiments) * 100) : 0;

        // Ensure consistent order for the chart
        $orderedSentiment = [
            'Sangat Tertarik' => $sentimentBuckets['Sangat Tertarik'] ?? 0,
            'Tertarik'        => $sentimentBuckets['Tertarik'] ?? 0,
            'Cukup Tertarik'  => $sentimentBuckets['Cukup Tertarik'] ?? 0,
            'Kurang Tertarik' => $sentimentBuckets['Kurang Tertarik'] ?? 0,
            'Lainnya'         => $sentimentBuckets['Lainnya'] ?? 0,
        ];

        return view('admin.dashboard', compact(
            'totalSurveys',
            'publishedSurveys',
            'totalRespondents',
            'surveys',
            'avgPriceWTP',
            'minPriceWTP',
            'maxPriceWTP',
            'avgSatisfactionScore',
            'marketDemandIndex',
            'orderedSentiment'
        ));
    }
}
