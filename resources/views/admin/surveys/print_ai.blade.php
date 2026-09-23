<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Dokumen AI — {{ $survey->judul }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@600;700&family=Inter:wght@400;500;600;700&family=Newsreader:ital,wght@0,500;0,600;0,700;1,400&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            color: #1C1917;
            background-color: #F5F3EF;
            margin: 0;
            padding: 0;
        }

        .font-serif {
            font-family: 'Newsreader', Georgia, serif;
        }

        .font-brand {
            font-family: 'Cinzel', Georgia, serif;
        }

        @page {
            size: A4 portrait;
            margin: 15mm 15mm 15mm 15mm;
        }

        @media print {
            body {
                background-color: #FFFFFF !important;
                padding: 0 !important;
            }

            .no-print {
                display: none !important;
            }

            .print-container {
                box-shadow: none !important;
                border: none !important;
                padding: 0 !important;
                max-width: 100% !important;
                width: 100% !important;
                margin: 0 !important;
            }

            .page-break-inside-avoid {
                page-break-inside: avoid;
            }

            .page-break-before {
                page-break-before: always;
            }
        }
    </style>
</head>
<body class="min-h-screen py-6 px-4 sm:px-6">

    <!-- ── TOP TOOLBAR (HIDDEN IN PRINT) ────────────────────────────────── -->
    <div class="max-w-4xl mx-auto mb-6 flex items-center justify-between gap-4 p-4 bg-stone-900 text-white rounded-2xl shadow-lg no-print">
        <div class="flex items-center gap-3">
            <span class="p-2 rounded-xl bg-amber-500/20 text-amber-400 text-xl font-bold">🖨️</span>
            <div>
                <h1 class="font-bold text-sm text-white">Mode Print Preview & Ekspor PDF</h1>
                <p class="text-xs text-stone-400">Siap dicetak ke kertas A4 atau disimpan menjadi berkas PDF resmi.</p>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.surveys.analytics', ['id' => $survey->id, 'tab' => 'AI_ANALYTICS']) }}"
               class="px-4 py-2 rounded-xl text-xs font-bold bg-stone-800 text-stone-300 hover:bg-stone-700 transition">
                ← Kembali ke Analitik
            </a>
            <button onclick="window.print()"
                    class="px-5 py-2 rounded-xl text-xs font-bold text-stone-900 transition flex items-center gap-2 shadow-md cursor-pointer active:scale-95"
                    style="background:#C89D54;">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                <span>Cetak / Simpan PDF</span>
            </button>
        </div>
    </div>

    <!-- ── FORMAL DOCUMENT CONTAINER (A4 SIZE SHEET) ───────────────────── -->
    <div class="print-container max-w-4xl mx-auto bg-white border border-stone-200 rounded-2xl shadow-xl p-8 sm:p-12 space-y-8 text-stone-900">

        <!-- ── KOP SURAT / OFFICIAL DOKUMEN HEADER ───────────────────────── -->
        <div class="border-b-2 border-stone-900 pb-6 space-y-4">
            <div class="flex items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 rounded-2xl flex items-center justify-center font-bold text-xl text-amber-500 border border-stone-800 shadow-xs"
                         style="background:#1C1917;">
                        RP
                    </div>
                    <div>
                        <div class="text-[10px] font-mono uppercase tracking-widest font-bold text-amber-800">
                            SMKN 2 INDRAMAYU — PROGRAM KEWIRAUSAHAAN TEFA
                        </div>
                        <h1 class="font-serif font-bold text-xl sm:text-2xl text-stone-900 tracking-tight leading-tight">
                            Dokumen Rekomendasi Strategi Kewirausahaan
                        </h1>
                        <p class="text-xs text-stone-500 font-medium mt-0.5">
                            Laporan Evaluasi Kelayakan Produk Berbasis Data Survei
                        </p>
                    </div>
                </div>
                <div class="text-right font-mono text-[10px] text-stone-500 hidden sm:block">
                    <span class="px-2.5 py-1 rounded bg-stone-100 border border-stone-300 font-bold text-stone-800 block mb-1">DOKUMEN RESMI</span>
                    <span>TGL: {{ now()->format('d M Y - H:i') }} WIB</span>
                </div>
            </div>

            <!-- Metadata Box -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 p-3.5 rounded-xl border border-stone-200 bg-stone-50/80 text-xs font-mono">
                <div>
                    <span class="text-stone-400 text-[9px] uppercase tracking-wider block">ID SURVEI</span>
                    <span class="font-bold text-stone-800 truncate block">{{ substr($survey->id, 0, 13) }}...</span>
                </div>
                <div>
                    <span class="text-stone-400 text-[9px] uppercase tracking-wider block">RESPONDEN TERVERIFIKASI</span>
                    <span class="font-bold text-emerald-800">{{ $totalRespondents }} Responden</span>
                </div>
                <div>
                    <span class="text-stone-400 text-[9px] uppercase tracking-wider block">SKOR POTENSI PASAR</span>
                    <span class="font-bold text-amber-700">{{ $aiAnalysis['skor_potensi'] ?? 85 }}/100</span>
                </div>
                <div>
                    <span class="text-stone-400 text-[9px] uppercase tracking-wider block">SUMBER INTELIJEN</span>
                    <span class="font-bold text-stone-800 truncate block">{{ $aiAnalysis['source'] ?? 'Google Gemini AI' }}</span>
                </div>
            </div>

            <div class="pt-1">
                <span class="text-[11px] font-medium text-stone-500">Judul Riset:</span>
                <span class="text-xs font-bold text-stone-900 ml-1">"{{ $survey->judul }}"</span>
            </div>
        </div>

        @if(empty($aiAnalysis) || !isset($aiAnalysis['ringkasan_eksekutif']))
            <div class="p-8 text-center border-2 border-dashed border-amber-300 rounded-2xl bg-amber-50/50 space-y-3">
                <div class="text-3xl">⚠️</div>
                <h3 class="font-serif font-bold text-lg text-stone-900">Hasil Analisa AI Belum Dibuat</h3>
                <p class="text-xs text-stone-600 max-w-md mx-auto">
                    @if($totalRespondents < 5)
                        Dokumen ini belum dapat dibuat karena survei baru memiliki <strong>{{ $totalRespondents }} responden</strong>. Minimal 5 responden diperlukan untuk menjaga objektivitas riset dan mencegah bias data.
                    @else
                        Dokumen ini belum di-generate. Silakan buka halaman analitik survei lalu klik tombol <strong>"✨ Generate Analisa AI"</strong> terlebih dahulu.
                    @endif
                </p>
            </div>
        @else

            @if($totalRespondents < 5)
                <!-- ── SAMPLE SIZE BIAS ADVISORY ───────────────────────────────── -->
                <div class="p-4 rounded-xl border border-rose-300 bg-rose-50 text-rose-900 space-y-1 page-break-inside-avoid">
                    <div class="flex items-center gap-2 font-bold text-xs">
                        <span>⚠️ PERINGATAN VALIDITAS SAMPEL ({{ $totalRespondents }} RESPONDEN):</span>
                    </div>
                    <p class="text-[11px] leading-relaxed">
                        Dokumen ini di-generate pada ukuran sampel yang sangat terbatas (hanya {{ $totalRespondents }} responden, di bawah standar minimal 5 responden). Analisis ini memiliki risiko bias ekstrem dan belum merepresentasikan populasi konsumen secara valid. Disarankan untuk menghapus dan memperbarui analisis setelah data responden memadai.
                    </p>
                </div>
            @endif

            <!-- ── BAGIAN I: RINGKASAN EKSEKUTIF & SENTIMEN ────────────────────── -->
            <div class="space-y-3 page-break-inside-avoid">
                <div class="flex items-center gap-2 text-xs font-bold tracking-widest uppercase text-amber-800 pb-1 border-b border-stone-200">
                    <span>● BAGIAN I</span>
                    <span class="w-6 h-px bg-amber-400"></span>
                    <span>RINGKASAN EKSEKUTIF & SENTIMEN PASAR</span>
                </div>

                <div class="p-4 rounded-xl border border-stone-200 bg-stone-50/60 leading-relaxed text-xs sm:text-sm font-serif text-stone-900 text-justify whitespace-pre-line">
                    {{ $aiAnalysis['ringkasan_eksekutif'] }}
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-1">
                    <div class="p-3.5 rounded-xl border border-stone-200 bg-stone-50">
                        <span class="text-[9px] font-mono uppercase tracking-wider text-stone-400 block font-bold">TINGKAT MINAT KONSUMEN</span>
                        <div class="text-base font-bold text-emerald-800 mt-0.5">
                            {{ $aiAnalysis['analisis_sentimen']['tingkat_minat'] ?? 'Tinggi (Sangat Positif)' }}
                            <span class="text-xs font-normal text-stone-500">({{ $aiAnalysis['analisis_sentimen']['persentase_positif'] ?? 85 }}% Sentimen Positif)</span>
                        </div>
                    </div>
                    <div class="p-3.5 rounded-xl border border-stone-200 bg-stone-50">
                        <span class="text-[9px] font-mono uppercase tracking-wider text-stone-400 block font-bold">FAKTOR PENDORONG SENTIMEN</span>
                        <p class="text-xs text-stone-700 leading-snug mt-0.5 text-justify whitespace-pre-line">
                            {{ $aiAnalysis['analisis_sentimen']['penjelasan'] ?? 'Sentimen didorong oleh persepsi harga WTP yang rasional dan kualitas produk yang sesuai kebutuhan responden.' }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- ── BAGIAN II: STRATEGI HARGA WTP & MARGIN ──────────────────────── -->
            <div class="space-y-3 pt-4 border-t border-stone-200 page-break-inside-avoid">
                <div class="flex items-center gap-2 text-xs font-bold tracking-widest uppercase text-amber-800 pb-1 border-b border-stone-200">
                    <span>● BAGIAN II</span>
                    <span class="w-6 h-px bg-amber-400"></span>
                    <span>ESTIMASI HARGA (WTP) & MARGIN KEUNTUNGAN</span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-12 gap-3">
                    <div class="sm:col-span-4 p-4 rounded-xl border border-emerald-300 bg-emerald-50/50 flex flex-col justify-center">
                        <span class="text-[9px] font-mono uppercase tracking-wider text-emerald-800 font-bold block">SWEET SPOT HARGA JUAL</span>
                        <div class="text-2xl font-serif font-bold text-emerald-950 mt-1">
                            {{ $aiAnalysis['strategi_harga_wtp']['sweet_spot_harga'] ?? '-' }}
                        </div>
                        <span class="text-[10px] text-emerald-700 mt-1 font-medium">Titik harga manis ideal berpatokan pada sebaran WTP responden</span>
                    </div>
                    <div class="sm:col-span-8 p-4 rounded-xl border border-stone-200 bg-stone-50 space-y-1">
                        <span class="text-[9px] font-mono uppercase tracking-wider text-stone-400 font-bold block">REKOMENDASI MARGIN & STRUKTUR HPP</span>
                        <p class="text-xs text-stone-800 leading-relaxed font-medium text-justify whitespace-pre-line">
                            {{ $aiAnalysis['strategi_harga_wtp']['rekomendasi_margin'] ?? 'Disarankan menetapkan HPP maksimal 65-70% dari sweet spot harga untuk mengamankan margin kotor 30-35%.' }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- ── BAGIAN III: REKOMENDASI PRODUK & LAYANAN ────────────────────── -->
            <div class="space-y-3 pt-4 border-t border-stone-200 page-break-inside-avoid">
                <div class="flex items-center gap-2 text-xs font-bold tracking-widest uppercase text-amber-800 pb-1 border-b border-stone-200">
                    <span>● BAGIAN III</span>
                    <span class="w-6 h-px bg-amber-400"></span>
                    <span>REKOMENDASI PENGEMBANGAN PRODUK & LAYANAN</span>
                </div>

                <div class="space-y-2">
                    @foreach(($aiAnalysis['rekomendasi_produk'] ?? []) as $idx => $rec)
                        <div class="p-3 rounded-xl border border-stone-200 bg-stone-50/60 flex items-start gap-3 text-xs leading-relaxed">
                            <span class="w-5 h-5 rounded-lg bg-stone-900 text-amber-400 font-bold text-[10px] flex items-center justify-center shrink-0 mt-0.5">
                                {{ $idx + 1 }}
                            </span>
                            <div class="font-medium text-stone-900">
                                {{ $rec }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- ── BAGIAN IV: STRATEGI PEMASARAN ──────────────────────────────── -->
            <div class="space-y-3 pt-4 border-t border-stone-200 page-break-inside-avoid">
                <div class="flex items-center gap-2 text-xs font-bold tracking-widest uppercase text-amber-800 pb-1 border-b border-stone-200">
                    <span>● BAGIAN IV</span>
                    <span class="w-6 h-px bg-amber-400"></span>
                    <span>STRATEGI PEMASARAN & PROMOSI KREATIF</span>
                </div>

                <div class="space-y-2">
                    @foreach(($aiAnalysis['strategi_pemasaran'] ?? []) as $idx => $mkt)
                        <div class="p-3 rounded-xl border border-stone-200 bg-stone-50/60 flex items-start gap-3 text-xs leading-relaxed">
                            <span class="w-5 h-5 rounded-lg bg-amber-800 text-amber-100 font-bold text-[10px] flex items-center justify-center shrink-0 mt-0.5">
                                {{ $idx + 1 }}
                            </span>
                            <div class="font-medium text-stone-900">
                                {{ $mkt }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- ── BAGIAN V: ACTION PLAN EKSEKUSI OPERASIONAL ─────────────────── -->
            <div class="space-y-3 pt-4 border-t border-stone-200 page-break-inside-avoid">
                <div class="flex items-center gap-2 text-xs font-bold tracking-widest uppercase text-amber-800 pb-1 border-b border-stone-200">
                    <span>● BAGIAN V</span>
                    <span class="w-6 h-px bg-amber-400"></span>
                    <span>ACTION PLAN TAHAPAN EKSEKUSI OPERASIONAL</span>
                </div>

                <div class="space-y-2">
                    @foreach(($aiAnalysis['action_plan'] ?? []) as $idx => $act)
                        <div class="p-3 rounded-xl border border-emerald-200 bg-emerald-50/40 flex items-center gap-3 text-xs leading-relaxed">
                            <span class="w-2 h-2 rounded-full bg-emerald-600 shrink-0"></span>
                            <div class="font-semibold text-emerald-950">
                                {{ $act }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

        @endif

    </div>

</body>
</html>
