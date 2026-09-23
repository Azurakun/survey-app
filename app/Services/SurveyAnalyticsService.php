<?php

namespace App\Services;

use App\Models\Survey;
use Illuminate\Database\Eloquent\Collection;

class SurveyAnalyticsService
{
    /**
     * Build structured analytics data per question for a given survey and set of respondents.
     */
    public function buildQuestionAnalytics(Survey $survey, Collection $respondents): array
    {
        $analytics = [];

        foreach ($survey->questions as $q) {
            $answers = [];
            foreach ($respondents as $r) {
                $ansRecord = $r->answers->firstWhere('question_id', $q->id);
                if ($ansRecord && $ansRecord->jawaban !== null && $ansRecord->jawaban !== '') {
                    $answers[] = $ansRecord->jawaban;
                }
            }

            $item = [
                'question'        => $q,
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

        return $analytics;
    }
}
