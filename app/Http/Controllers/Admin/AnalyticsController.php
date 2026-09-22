<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Survey;
use App\Models\Respondent;
use App\Services\GeminiAiService;
use App\Exports\SurveyRawExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class AnalyticsController extends Controller
{
    protected GeminiAiService $aiService;

    public function __construct(GeminiAiService $aiService)
    {
        $this->aiService = $aiService;
    }

    public function show(Request $request, $id)
    {
        $survey = Survey::with(['questions.answers', 'respondents.answers'])->findOrFail($id);

        $query = $survey->respondents();

        if ($request->filled('start_date')) {
            $query->whereDate('submitted_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('submitted_at', '<=', $request->end_date);
        }

        $respondents      = $query->latest('submitted_at')->get();
        $totalRespondents = $respondents->count();

        // ─── Build analytics per question ────────────────────────────────
        $analytics = [];

        foreach ($survey->questions as $q) {
            // Collect raw answer strings for this question from filtered respondents
            $answers = [];
            foreach ($respondents as $r) {
                $ansRecord = $r->answers->firstWhere('question_id', $q->id);
                if ($ansRecord && $ansRecord->jawaban !== null && $ansRecord->jawaban !== '') {
                    $answers[] = $ansRecord->jawaban;
                }
            }

            $item = [
                'question'       => $q,
                'total_responses' => count($answers),
            ];

            switch ($q->tipe_pertanyaan) {
                case 'SINGLE_CHOICE':
                    $options = $q->parsed_options;
                    $counts  = array_fill_keys($options, 0);
                    foreach ($answers as $ans) {
                        $counts[$ans] = ($counts[$ans] ?? 0) + 1;
                    }
                    $item['counts'] = $counts;
                    break;

                case 'MULTIPLE_CHOICE':
                    $options = $q->parsed_options;
                    $counts  = array_fill_keys($options, 0);
                    foreach ($answers as $ans) {
                        $decoded = json_decode($ans, true);
                        $opts    = is_array($decoded) ? $decoded : [$ans];
                        foreach ($opts as $opt) {
                            $opt = trim($opt);
                            if ($opt !== '') {
                                $counts[$opt] = ($counts[$opt] ?? 0) + 1;
                            }
                        }
                    }
                    $item['counts'] = $counts;
                    break;

                case 'LIKERT':
                    $counts = ['5' => 0, '4' => 0, '3' => 0, '2' => 0, '1' => 0];
                    $totalScore = 0;
                    $scoreCount = 0;
                    foreach ($answers as $ans) {
                        preg_match('/^[1-5]/', $ans, $m);
                        $digit = $m[0] ?? null;
                        if ($digit && isset($counts[$digit])) {
                            $counts[$digit]++;
                            $totalScore += (int) $digit;
                            $scoreCount++;
                        }
                    }
                    $item['counts']       = $counts;
                    $item['avg_score']    = $scoreCount > 0 ? round($totalScore / $scoreCount, 2) : 0;
                    $posCount             = ($counts['5'] ?? 0) + ($counts['4'] ?? 0);
                    $item['positive_pct'] = $scoreCount > 0 ? round(($posCount / $scoreCount) * 100, 1) : 0;
                    $item['raw_answers']  = $answers;
                    break;

                case 'NUMBER':
                    $nums = array_map('floatval', array_filter($answers, 'is_numeric'));
                    $priceCounts = [];
                    foreach ($nums as $num) {
                        $pKey = 'Rp ' . number_format($num, 0, ',', '.');
                        $priceCounts[$pKey] = ($priceCounts[$pKey] ?? 0) + 1;
                    }
                    ksort($priceCounts);
                    $item['counts'] = $priceCounts;
                    $numCount = count($nums);
                    $medianVal = 0;
                    if ($numCount > 0) {
                        $sortedNums = $nums;
                        sort($sortedNums);
                        $middle = (int) floor($numCount / 2);
                        $medianVal = ($numCount % 2 === 0)
                            ? round(($sortedNums[$middle - 1] + $sortedNums[$middle]) / 2, 2)
                            : (float) $sortedNums[$middle];
                    }

                    // Find most frequent price point (Sweet Spot / Mode)
                    $maxFreq = 0;
                    $sweetSpotPrice = null;
                    foreach ($priceCounts as $pLabel => $freq) {
                        if ($freq > $maxFreq) {
                            $maxFreq = $freq;
                            $sweetSpotPrice = $pLabel;
                        }
                    }

                    $item['stats'] = [
                        'count'      => $numCount,
                        'min'        => $numCount > 0 ? min($nums) : 0,
                        'max'        => $numCount > 0 ? max($nums) : 0,
                        'avg'        => $numCount > 0 ? round(array_sum($nums) / $numCount, 2) : 0,
                        'median'     => $medianVal,
                        'sweet_spot' => $sweetSpotPrice,
                    ];
                    $item['raw_answers'] = $answers;
                    break;

                case 'DATE':
                    $dateCounts = [];
                    foreach ($answers as $ans) {
                        $d = trim($ans);
                        if ($d !== '') {
                            $dateCounts[$d] = ($dateCounts[$d] ?? 0) + 1;
                        }
                    }
                    ksort($dateCounts);
                    $item['counts']    = $dateCounts;
                    $item['responses'] = $answers;
                    break;

                case 'SHORT_TEXT':
                case 'LONG_TEXT':
                    $item['responses'] = $answers;
                    $stopWords = ['yang', 'untuk', 'pada', 'ke', 'para', 'namun', 'menurut', 'antara', 'dia', 'mereka', 'anda', 'kita', 'aku', 'kamu', 'bisa', 'akan', 'ada', 'dari', 'dalam', 'dan', 'di', 'ini', 'itu', 'dengan', 'saya', 'karena', 'oleh', 'saat', 'agar', 'jika', 'bukan', 'hanya', 'sangat', 'lebih', 'sudah', 'juga', 'atau', 'saja', 'harus', 'bila', 'kami'];
                    $wordFreq = [];
                    foreach ($answers as $ans) {
                        $words = preg_split('/[\s,\.\?\!\:\;\-\(\)\"\']+/u', mb_strtolower($ans));
                        foreach ($words as $w) {
                            $w = trim($w);
                            if (mb_strlen($w) >= 4 && !in_array($w, $stopWords) && !is_numeric($w)) {
                                $wordFreq[$w] = ($wordFreq[$w] ?? 0) + 1;
                            }
                        }
                    }
                    arsort($wordFreq);
                    $item['top_words'] = array_slice($wordFreq, 0, 8, true);
                    break;

                default:
                    // IMAGE_UPLOAD, FILE_UPLOAD
                    $item['responses'] = $answers;
                    break;
            }

            $analytics[] = $item;
        }

        // Load saved AI analysis from database if present; otherwise keep null until user generates.
        $aiAnalysis = $survey->ai_analysis;

        return view('admin.surveys.analytics', compact(
            'survey',
            'respondents',
            'totalRespondents',
            'analytics',
            'aiAnalysis'
        ));
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

        // Load saved analysis or generate if missing
        $aiAnalysis = $survey->ai_analysis;
        if (empty($aiAnalysis) && $totalRespondents > 0) {
            $aiAnalysis = $this->aiService->generateAnalysis($survey);
            $survey->update([
                'ai_analysis'    => $aiAnalysis,
                'ai_analyzed_at' => now(),
            ]);
        }

        return view('admin.surveys.print_ai', compact('survey', 'totalRespondents', 'aiAnalysis'));
    }
}
