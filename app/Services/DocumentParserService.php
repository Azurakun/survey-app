<?php

namespace App\Services;

use ZipArchive;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Log;

class DocumentParserService
{
    /**
     * Parse document file and return metadata, extracted headers, preview rows, and raw text
     */
    public function parseDocument(string $filePath, string $fileType): array
    {
        $fileType = strtolower($fileType);

        switch ($fileType) {
            case 'xlsx':
            case 'xls':
            case 'csv':
                return $this->parseSpreadsheet($filePath);

            case 'docx':
            case 'doc':
                return $this->parseDocx($filePath);

            case 'pptx':
            case 'ppt':
                return $this->parsePptx($filePath);

            case 'pdf':
                return $this->parsePdf($filePath);

            case 'txt':
            case 'md':
            default:
                return $this->parseTextFile($filePath);
        }
    }

    /**
     * Parse Excel / CSV spreadsheets using PhpSpreadsheet
     */
    private function parseSpreadsheet(string $filePath): array
    {
        try {
            $spreadsheet = IOFactory::load($filePath);
            $worksheet   = $spreadsheet->getActiveSheet();
            $allRows     = $worksheet->toArray(null, true, true, false);

            if (empty($allRows)) {
                return [
                    'total_baris_halaman' => 0,
                    'headers'             => [],
                    'preview_data'        => [],
                    'extracted_text'      => 'Dokumen Excel tidak memiliki data.',
                ];
            }

            $rawHeaders = array_shift($allRows);
            $headers    = [];
            foreach ($rawHeaders as $idx => $h) {
                $hTrim = trim((string)$h);
                $headers[] = $hTrim !== '' ? $hTrim : ('Kolom ' . ($idx + 1));
            }

            $previewData = array_slice($allRows, 0, 50); // Store up to 50 rows for preview & AI
            $totalRows   = count($allRows);

            // Construct readable summary for AI
            $summaryLines = ["=== STRUKTUR & DATA SPREADSHEET EXCEL ==="];
            $summaryLines[] = "Total Baris Data: " . $totalRows;
            $summaryLines[] = "Header / Kolom Pertanyaan: " . implode(" | ", $headers);
            $summaryLines[] = "\nSampel 15 Data Responden Pertama:";

            foreach (array_slice($previewData, 0, 15) as $rIdx => $row) {
                $rowStr = [];
                foreach ($headers as $cIdx => $hName) {
                    $val = trim((string)($row[$cIdx] ?? ''));
                    if ($val !== '') {
                        $rowStr[] = "{$hName}: {$val}";
                    }
                }
                $summaryLines[] = "Responden #" . ($rIdx + 1) . ": " . implode("; ", $rowStr);
            }

            return [
                'total_baris_halaman' => $totalRows,
                'headers'             => $headers,
                'preview_data'        => $previewData,
                'extracted_text'      => implode("\n", $summaryLines),
            ];
        } catch (\Throwable $e) {
            Log::error("[DOCUMENT PARSER] Failed to parse Excel: " . $e->getMessage());
            return $this->parseTextFile($filePath);
        }
    }

    /**
     * Parse Word DOCX documents via ZipArchive XML extraction
     */
    private function parseDocx(string $filePath): array
    {
        $text = '';
        $zip  = new ZipArchive();

        if ($zip->open($filePath) === true) {
            if (($index = $zip->locateName('word/document.xml')) !== false) {
                $xmlData = $zip->getFromIndex($index);
                $zip->close();

                // Format paragraphs & line breaks
                $xmlData = str_replace(['<w:p>', '<w:br/>', '<w:br>', '</w:p>'], ["\n", "\n", "\n", "\n"], $xmlData);
                $text    = trim(strip_tags($xmlData));
                $text    = preg_replace("/\n\s*\n+/", "\n\n", $text); // Normalize empty lines
            } else {
                $zip->close();
            }
        }

        if (empty($text)) {
            $text = "Dokumen Word berhasil diunggah. Teks belum dapat diekstrak penuh.";
        }

        $lines   = explode("\n", $text);
        $headers = array_values(array_filter(array_map('trim', array_slice($lines, 0, 10)), fn($l) => !empty($l)));

        return [
            'total_baris_halaman' => count($lines),
            'headers'             => array_slice($headers, 0, 5),
            'preview_data'        => array_slice($lines, 0, 30),
            'extracted_text'      => $text,
        ];
    }

    /**
     * Parse PowerPoint PPTX presentations slide by slide
     */
    private function parsePptx(string $filePath): array
    {
        $text       = '';
        $slidesText = [];
        $zip        = new ZipArchive();

        if ($zip->open($filePath) === true) {
            $slideIndex = 1;
            for ($i = 0; $i < $zip->numFiles; $i++) {
                $name = $zip->getNameIndex($i);
                if (str_contains($name, 'ppt/slides/slide') && str_ends_with($name, '.xml')) {
                    $xmlData  = $zip->getFromIndex($i);
                    $xmlData  = str_replace(['<a:p>', '<a:br/>', '</a:p>'], ["\n", "\n", "\n"], $xmlData);
                    $slideTxt = trim(strip_tags($xmlData));
                    if (!empty($slideTxt)) {
                        $slidesText[] = "=== SLIDE {$slideIndex} ===\n" . $slideTxt;
                        $slideIndex++;
                    }
                }
            }
            $zip->close();
            $text = implode("\n\n", $slidesText);
        }

        if (empty($text)) {
            $text = "Presentasi PowerPoint berhasil diunggah.";
        }

        return [
            'total_baris_halaman' => count($slidesText) ?: 1,
            'headers'             => ["Slide Presentations (" . count($slidesText) . " Slides)"],
            'preview_data'        => array_slice($slidesText, 0, 10),
            'extracted_text'      => $text,
        ];
    }

    /**
     * Parse PDF documents using native string stream reader
     */
    private function parsePdf(string $filePath): array
    {
        $content = @file_get_contents($filePath);
        $text    = '';

        if ($content) {
            // Extract text from BT ... ET streams
            preg_match_all('/BT[\s\S]*?ET/', $content, $matches);
            if (!empty($matches[0])) {
                foreach ($matches[0] as $block) {
                    preg_match_all('/\((.*?)\)\s*Tj/s', $block, $tjMatches);
                    if (!empty($tjMatches[1])) {
                        $text .= implode(" ", $tjMatches[1]) . "\n";
                    }
                    preg_match_all('/\[(.*?)\]\s*TJ/s', $block, $arrayMatches);
                    if (!empty($arrayMatches[1])) {
                        foreach ($arrayMatches[1] as $arrItem) {
                            preg_match_all('/\((.*?)\)/s', $arrItem, $subMatches);
                            if (!empty($subMatches[1])) {
                                $text .= implode("", $subMatches[1]) . " ";
                            }
                        }
                        $text .= "\n";
                    }
                }
            }
        }

        // Clean up extracted text
        $text = trim(strip_tags($text));
        if (empty($text)) {
            // Fallback plain content filter
            $text = preg_replace('/[^\x20-\x7E\x0A\x0D]/', ' ', substr($content, 0, 15000));
            $text = trim(preg_replace('/\s+/', ' ', $text));
        }

        $lines = explode("\n", $text);

        return [
            'total_baris_halaman' => count($lines),
            'headers'             => ["Dokumen PDF Business Report"],
            'preview_data'        => array_slice($lines, 0, 25),
            'extracted_text'      => mb_substr($text, 0, 20000), // Max 20k chars for AI prompt
        ];
    }

    /**
     * Parse plain text / markdown files
     */
    private function parseTextFile(string $filePath): array
    {
        $content = @file_get_contents($filePath) ?: '';
        $lines   = explode("\n", $content);

        return [
            'total_baris_halaman' => count($lines),
            'headers'             => ["Dokumen Teks / Markdown"],
            'preview_data'        => array_slice($lines, 0, 30),
            'extracted_text'      => mb_substr($content, 0, 20000),
        ];
    }
}
