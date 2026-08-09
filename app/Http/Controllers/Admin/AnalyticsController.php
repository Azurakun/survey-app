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
                    foreach ($answers as $ans) {
                        preg_match('/^[1-5]/', $ans, $m);
                        $digit = $m[0] ?? null;
                        if ($digit && isset($counts[$digit])) {
                            $counts[$digit]++;
                        }
                    }
                    $item['counts']      = $counts;
                    $item['raw_answers'] = $answers;
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
                    $item['stats'] = [
                        'count' => count($nums),
                        'min'   => count($nums) > 0 ? min($nums) : 0,
                        'max'   => count($nums) > 0 ? max($nums) : 0,
                    ];
                    $item['raw_answers'] = $answers;
                    break;

                default:
                    // SHORT_TEXT, LONG_TEXT, DATE, IMAGE_UPLOAD
                    $item['responses'] = $answers;
                    break;
            }

            $analytics[] = $item;
        }

        // Generate AI analysis for unified display alongside answer choice data
        $aiAnalysis = $this->aiService->generateAnalysis($survey);

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
}
