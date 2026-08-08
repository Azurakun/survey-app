<?php

namespace App\Exports;

use App\Models\Survey;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Support\Collection;

class SurveyRawExport implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize
{
    protected Survey $survey;

    public function __construct(Survey $survey)
    {
        $this->survey = $survey;
    }

    public function headings(): array
    {
        $headers = ['No', 'NISN Siswa', 'Waktu Pengisian'];

        foreach ($this->survey->questions as $q) {
            $headers[] = '[' . $q->tipe_pertanyaan . '] ' . $q->teks_pertanyaan;
        }

        return $headers;
    }

    public function collection(): Collection
    {
        $rows = [];

        foreach ($this->survey->respondents as $idx => $respondent) {
            $row = [
                $idx + 1,
                $respondent->nisn,
                $respondent->submitted_at?->format('d/m/Y H:i:s'),
            ];

            foreach ($this->survey->questions as $q) {
                $ansRecord = $respondent->answers->firstWhere('question_id', $q->id);
                $val       = '-';

                if ($ansRecord && $ansRecord->jawaban !== null) {
                    $decoded = json_decode($ansRecord->jawaban, true);
                    $val     = is_array($decoded)
                        ? implode(', ', $decoded)
                        : $ansRecord->jawaban;
                }

                $row[] = $val;
            }

            $rows[] = $row;
        }

        return collect($rows);
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            // Bold header row
            1 => [
                'font'      => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
                'fill'      => ['fillType' => 'solid', 'color' => ['argb' => 'FF2F5233']],
                'alignment' => ['horizontal' => 'center'],
            ],
        ];
    }
}
