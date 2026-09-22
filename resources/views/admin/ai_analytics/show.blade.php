@extends('layouts.admin')
@section('title', 'Analisa AI: ' . $survey->judul)

@section('content')
<div x-data="aiAnalyticsShowPage()" class="space-y-6 pb-16">

    <!-- ── Print-only CSS styling for Executive Document ───────────────── -->
    <style>
        @media print {
            body * {
                visibility: hidden;
            }
            #executive-document-sheet, #executive-document-sheet * {
                visibility: visible;
            }
            #executive-document-sheet {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                margin: 0;
                padding: 20px;
                background: white !important;
                box-shadow: none !important;
                border: none !important;
            }
            .no-print {
                display: none !important;
            }
        }
    </style>

    <!-- ── Top Header Bar ──────────────────────────────────────────────── -->
    <div class="card p-6 space-y-4 no-print">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="space-y-1">
                <div class="flex items-center gap-2 flex-wrap">
                    <span class="px-2.5 py-0.5 rounded-md text-[10px] font-bold uppercase tracking-wider border flex items-center gap-1.5"
                          style="background:#FAF6EE; border-color:#E5E0D8; color:#C89D54;">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                        <span>✨ AI MARKET INTELLIGENCE</span>
                    </span>

                    <!-- Model Badge -->
                    <div class="px-2.5 py-0.5 rounded-md text-[11px] font-semibold border flex items-center gap-1.5"
                         style="background:#EDFAF2; border-color:#A3E6CD; color:#1A5C38;">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                        <span>gemini-2.5-flash</span>
                    </div>
                </div>

                <h1 class="font-serif font-bold text-2xl text-ink leading-tight pt-1">
                    {{ $survey->judul }}
                </h1>
                <p class="text-xs max-w-3xl" style="color:#6E675F;">
                    {{ $survey->deskripsi ?? 'Topik riset pasar kewirausahaan.' }}
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-2.5 self-start md:self-auto shrink-0">
                <!-- Toggle View Mode Button -->
                <div class="inline-flex p-1 rounded-xl border bg-stone-100 border-stone-200">
                    <button @click="viewMode = 'document'"
                            :class="viewMode === 'document' ? 'bg-white text-stone-900 shadow-xs font-bold' : 'text-stone-600 font-medium'"
                            class="px-3 py-1.5 rounded-lg text-xs transition">
                        📄 Dokumen Eksekutif
                    </button>
                    <button @click="viewMode = 'dashboard'"
                            :class="viewMode === 'dashboard' ? 'bg-white text-stone-900 shadow-xs font-bold' : 'text-stone-600 font-medium'"
                            class="px-3 py-1.5 rounded-lg text-xs transition">
                        📊 Dashboard Card
                    </button>
                </div>

                <a href="{{ route('admin.ai-analytics.index') }}"
                   class="px-3.5 py-2 rounded-xl border text-xs font-bold transition flex items-center gap-1.5"
                   style="background:#FAF8F5; border-color:#E5E0D8; color:#1C1917;">
                    ← Pilih Survey
                </a>

                <button @click="regenerateAi()" :disabled="loading"
                        class="px-4 py-2 rounded-xl text-white font-bold text-xs shadow-xs transition flex items-center gap-2 disabled:opacity-50"
                        style="background:#1C1917;">
                    <svg x-show="loading" class="animate-spin w-3.5 h-3.5 text-amber-400" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span x-text="loading ? 'AI Memproses Request...' : 'Generate Ulang AI'">Generate Ulang AI</span>
                    <span style="color:#C89D54;">✨</span>
                </button>
            </div>
        </div>

        <!-- Animated Processing Banner when AI is generating -->
        <div x-show="loading" x-transition
             class="p-3.5 rounded-xl border text-xs font-semibold flex items-center justify-between gap-3 animate-pulse"
             style="background:#FAF6EE; border-color:#E5E0D8; color:#C89D54;">
            <div class="flex items-center gap-2">
                <span class="text-sm">⚡</span>
                <span>Gemini AI (gemini-2.5-flash) sedang menganalisis {{ $kpis['total_respondents'] }} data responden dan menyusun dokumen rekomendasi...</span>
            </div>
            <span class="text-[10px] font-mono uppercase tracking-wider">Memproses JSON...</span>
        </div>
    </div>

    @if ($kpis['total_respondents'] === 0)
        <!-- ── Insufficient Data Warning Card ────────────────────────────── -->
        <div class="card p-8 sm:p-12 text-center space-y-5 border-2" style="background:#FAF6EE; border-color:#E5E0D8;">
            <div class="w-16 h-16 rounded-2xl border text-3xl mx-auto flex items-center justify-center shadow-2xs"
                 style="background:#FFFFFF; border-color:#E5E0D8; color:#C89D54;">
                ⚠️
            </div>
            <div class="space-y-2 max-w-lg mx-auto">
                <h3 class="font-serif font-bold text-xl text-ink">Data Responden Belum Terkumpul</h3>
                <p class="text-xs leading-relaxed" style="color:#6E675F;">
                    Survey <strong>"{{ $survey->judul }}"</strong> belum memiliki responden. Sesuai <strong>rekomendasi pakar riset pasar kewirausahaan</strong>, disarankan mengumpulkan minimal <strong>10–30 responden</strong> agar estimasi daya beli (WTP), sentimen pasar, dan rekomendasi produk memiliki tingkat akurasi data yang presisi.
                </p>
            </div>
            <div class="pt-2 flex flex-wrap items-center justify-center gap-3">
                <a href="{{ route('admin.surveys.builder', $survey->id) }}"
                   class="px-4 py-2.5 rounded-xl border font-bold text-xs shadow-xs transition"
                   style="background:#FFFFFF; border-color:#E5E0D8; color:#1C1917;">
                    ✏️ Builder Survey / Kelola Pertanyaan
                </a>
                <a href="{{ route('admin.ai-analytics.index') }}"
                   class="px-4 py-2.5 rounded-xl font-bold text-xs text-white shadow-xs transition"
                   style="background:#1C1917;">
                    ← Kembali ke Pilih Survey
                </a>
            </div>
        </div>
    @else

    <!-- =================================================================== -->
    <!-- VIEW MODE 1: DOCUMENT-FRIENDLY EXECUTIVE MEMORANDUM FORMAT         -->
    <!-- =================================================================== -->
    <div x-show="viewMode === 'document'" id="executive-document-sheet" class="space-y-8">

        <!-- Executive Sheet Container -->
        <div class="bg-white border border-stone-300 rounded-2xl p-8 sm:p-12 shadow-sm space-y-8 text-stone-900 font-sans">

            <!-- Formal Header Section -->
            <div class="border-b-2 border-stone-900 pb-6 space-y-4">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center font-bold text-lg"
                             style="background:#1C1917; color:#C89D54;">RP</div>
                        <div>
                            <div class="text-[10px] font-mono uppercase tracking-widest font-bold text-stone-500">
                                SISTEM INTELIJEN PASAR & KEWIRAUSAHAAN
                            </div>
                            <h2 class="font-serif font-bold text-xl text-stone-900">
                                LAPORAN EKSEKUTIF ANALISIS PASAR & REKOMENDASI STRATEGI
                            </h2>
                        </div>
                    </div>

                    <div class="text-right font-mono text-[11px] text-stone-500 space-y-0.5 self-end sm:self-auto no-print">
                        <button @click="window.print()"
                                class="px-4 py-2 rounded-xl text-white font-sans font-bold text-xs transition inline-flex items-center gap-2 shadow-xs"
                                style="background:#1C1917;">
                            <span>🖨️ Cetak / Ekspor PDF</span>
                        </button>
                    </div>
                </div>

                <!-- Document Metadata Table -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 p-4 rounded-xl border border-stone-200 bg-stone-50 text-xs font-mono">
                    <div>
                        <span class="text-stone-400 uppercase text-[9px] block">NO. DOKUMEN</span>
                        <span class="font-bold text-stone-800">DOC-AI/{{ date('Ym') }}/{{ substr($survey->id, 0, 8) }}</span>
                    </div>
                    <div>
                        <span class="text-stone-400 uppercase text-[9px] block">TANGGAL PENERBITAN</span>
                        <span class="font-bold text-stone-800">{{ now()->format('d M Y - H:i') }} WIB</span>
                    </div>
                    <div>
                        <span class="text-stone-400 uppercase text-[9px] block">TOTAL RESPONDEN</span>
                        <span class="font-bold text-stone-800">{{ $kpis['total_respondents'] }} Siswa / Konsumen</span>
                    </div>
                    <div>
                        <span class="text-stone-400 uppercase text-[9px] block">ENGINE ANALISIS</span>
                        <span class="font-bold text-amber-700">gemini-2.5-flash</span>
                    </div>
                </div>
            </div>

            <!-- Expert Recommendation Banner -->
            <div class="p-4 rounded-xl border border-amber-300 bg-amber-50/70 text-xs text-stone-800 font-medium flex items-start gap-3">
                <span class="text-base leading-none">💡</span>
                <div class="space-y-0.5">
                    <span class="font-bold font-mono uppercase tracking-wider text-[10px] text-amber-900 block">REKOMENDASI PAKAR RISET PASAR UNTUK AKURASI DATA:</span>
                    @if ($kpis['total_respondents'] < 10)
                        <span>Jumlah sampel saat ini (<strong>{{ $kpis['total_respondents'] }} responden</strong>) masih dalam kategori uji coba awal. Pakar riset pasar merekomendasikan mengumpulkan minimal <strong>10–30 responden</strong> untuk akurasi data yang lebih presisi (Margin of Error &lt; 10%).</span>
                    @elseif ($kpis['total_respondents'] < 30)
                        <span>Jumlah sampel saat ini (<strong>{{ $kpis['total_respondents'] }} responden</strong>) sudah mencukupi untuk analisis tingkat menengah. Tambahkan hingga 30+ responden untuk presisi pasar yang maksimal.</span>
                    @else
                        <span>Jumlah sampel saat ini (<strong>{{ $kpis['total_respondents'] }} responden</strong>) telah memenuhi standar validitas riset pasar tingkat pakar kewirausahaan (Akurasi Sangat Tinggi).</span>
                    @endif
                </div>
            </div>

            <!-- Subject / Project Metadata -->
            <div class="space-y-1 bg-stone-50/80 p-5 rounded-xl border border-stone-200">
                <div class="text-[10px] font-mono uppercase font-bold tracking-wider text-amber-800">SUBJEK PROYEK RISET PASAR:</div>
                <h3 class="font-serif font-bold text-lg text-stone-900">{{ $survey->judul }}</h3>
                <p class="text-xs text-stone-600 leading-relaxed">{{ $survey->deskripsi ?? 'Tidak ada deskripsi spesifik.' }}</p>
            </div>

            <!-- Executive KPI Summary Table -->
            <div class="space-y-3">
                <h4 class="font-mono text-xs font-bold uppercase tracking-wider text-stone-700 flex items-center gap-2">
                    <span class="w-2 h-2 bg-stone-900 rounded-full"></span>
                    <span>METRIK UTAMA INDIKATOR KINERJA PASAR (KPI)</span>
                </h4>

                <div class="overflow-x-auto">
                    <table class="w-full text-xs border border-stone-300 rounded-xl overflow-hidden">
                        <thead class="bg-stone-900 text-white font-mono text-[11px]">
                            <tr>
                                <th class="p-3 text-left">METRIK PASAR</th>
                                <th class="p-3 text-center">NILAI HASIL UKUR</th>
                                <th class="p-3 text-left">SKALA / SATUAN</th>
                                <th class="p-3 text-left">INTERPRETASI STRATEGIS</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-stone-200 bg-white">
                            <tr>
                                <td class="p-3 font-bold text-stone-900">Indeks Minat Pasar (Potensi)</td>
                                <td class="p-3 text-center font-bold text-emerald-700 text-sm" x-text="(analysis.skor_potensi || '{{ $analysis['skor_potensi'] ?? $kpis['interest_index'] }}') + '%'">
                                    {{ $analysis['skor_potensi'] ?? $kpis['interest_index'] }}%
                                </td>
                                <td class="p-3 text-stone-600">Persentase (0 - 100%)</td>
                                <td class="p-3 font-semibold text-emerald-800" x-text="analysis.analisis_sentimen ? analysis.analisis_sentimen.tingkat_minat : 'Tinggi (Sangat Positif)'">
                                    {{ $analysis['analisis_sentimen']['tingkat_minat'] ?? 'Tinggi (Sangat Positif)' }}
                                </td>
                            </tr>
                            <tr>
                                <td class="p-3 font-bold text-stone-900">Rata-Rata WTP (Daya Beli)</td>
                                <td class="p-3 text-center font-bold text-stone-900 text-sm" x-text="analysis.strategi_harga_wtp ? analysis.strategi_harga_wtp.sweet_spot_harga : 'Rp {{ number_format($kpis['avg_wtp'], 0, ',', '.') }}'">
                                    Rp {{ number_format($kpis['avg_wtp'], 0, ',', '.') }}
                                </td>
                                <td class="p-3 text-stone-600">Rupiah (IDR / Unit)</td>
                                <td class="p-3 text-stone-700">Rentang Harga Manis Konsumen Ideal</td>
                            </tr>
                            <tr>
                                <td class="p-3 font-bold text-stone-900">Skor Kepuasan Persepsi Produk</td>
                                <td class="p-3 text-center font-bold text-stone-900 text-sm">{{ $kpis['avg_likert'] }} / 5.0</td>
                                <td class="p-3 text-stone-600">Skala Likert (1.0 - 5.0)</td>
                                <td class="p-3 text-stone-700">Persepsi Kualitas Awal Calon Konsumen</td>
                            </tr>
                            <tr>
                                <td class="p-3 font-bold text-stone-900">Ukuran Sampel Responden</td>
                                <td class="p-3 text-center font-bold text-stone-900 text-sm">{{ $kpis['total_respondents'] }}</td>
                                <td class="p-3 text-stone-600">Orang Responden</td>
                                <td class="p-3 text-stone-700">Data Sampel Validasi Terverifikasi</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- SECTION I: RINGKASAN EKSEKUTIF -->
            <div class="space-y-3 pt-2">
                <h4 class="font-mono text-xs font-bold uppercase tracking-wider text-stone-900 border-b border-stone-300 pb-2">
                    BAGIAN I: RINGKASAN EKSEKUTIF & EVALUASI PASAR
                </h4>
                <div class="p-5 rounded-xl border border-stone-200 bg-amber-50/40 text-stone-800 text-xs sm:text-sm leading-relaxed font-medium"
                     x-text="analysis.ringkasan_eksekutif">
                    {{ $analysis['ringkasan_eksekutif'] }}
                </div>
            </div>

            <!-- SECTION II: ANALISIS SENTIMEN & HARGA WTP -->
            <div class="space-y-4 pt-2">
                <h4 class="font-mono text-xs font-bold uppercase tracking-wider text-stone-900 border-b border-stone-300 pb-2">
                    BAGIAN II: ANALISIS SENTIMEN & STRATEGI HARGA WTP
                </h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-xs">
                    <div class="p-5 rounded-xl border border-stone-200 bg-stone-50 space-y-2">
                        <div class="font-bold text-stone-900 uppercase font-mono text-[11px]">2.1 Sentimen & Penerimaan Konsumen</div>
                        <div class="font-semibold text-emerald-800 text-sm"
                             x-text="'Tingkat Minat: ' + (analysis.analisis_sentimen ? analysis.analisis_sentimen.tingkat_minat : '{{ $analysis['analisis_sentimen']['tingkat_minat'] ?? 'Tinggi' }}')">
                            Tingkat Minat: {{ $analysis['analisis_sentimen']['tingkat_minat'] ?? 'Tinggi' }}
                        </div>
                        <p class="text-stone-600 leading-relaxed"
                           x-text="analysis.analisis_sentimen ? analysis.analisis_sentimen.penjelasan : '{{ $analysis['analisis_sentimen']['penjelasan'] ?? '' }}'">
                            {{ $analysis['analisis_sentimen']['penjelasan'] ?? '' }}
                        </p>
                    </div>

                    <div class="p-5 rounded-xl border border-stone-200 bg-stone-50 space-y-2">
                        <div class="font-bold text-stone-900 uppercase font-mono text-[11px]">2.2 Strategi Penetapan Harga Manis (WTP)</div>
                        <div class="font-semibold text-stone-900 text-sm"
                             x-text="'Sweet Spot: ' + (analysis.strategi_harga_wtp ? analysis.strategi_harga_wtp.sweet_spot_harga : 'Rp {{ number_format($kpis['avg_wtp'], 0, ',', '.') }}')">
                            Sweet Spot: {{ $analysis['strategi_harga_wtp']['sweet_spot_harga'] ?? ('Rp ' . number_format($kpis['avg_wtp'], 0, ',', '.')) }}
                        </div>
                        <p class="text-stone-600 leading-relaxed"
                           x-text="analysis.strategi_harga_wtp ? analysis.strategi_harga_wtp.rekomendasi_margin : '{{ $analysis['strategi_harga_wtp']['rekomendasi_margin'] ?? '' }}'">
                            {{ $analysis['strategi_harga_wtp']['rekomendasi_margin'] ?? '' }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- SECTION III & IV: REKOMENDASI PRODUK & PEMASARAN -->
            <div class="space-y-4 pt-2">
                <h4 class="font-mono text-xs font-bold uppercase tracking-wider text-stone-900 border-b border-stone-300 pb-2">
                    BAGIAN III & IV: REKOMENDASI FITUR PRODUK & PEMASARAN
                </h4>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-xs">
                    <!-- Produk -->
                    <div class="space-y-3">
                        <div class="font-bold text-stone-900 uppercase font-mono text-[11px]">3.1 Rekomendasi Fitur & Kualitas Produk</div>
                        <div class="space-y-2">
                            <template x-for="(rec, rIdx) in (analysis.rekomendasi_produk || [])" :key="rIdx">
                                <div class="p-3.5 rounded-xl border border-stone-200 bg-stone-50 text-stone-800 leading-relaxed flex items-start gap-2.5">
                                    <span class="font-bold font-mono text-stone-900 shrink-0" x-text="(rIdx + 1) + '.'"></span>
                                    <span x-text="rec"></span>
                                </div>
                            </template>

                            @foreach ($analysis['rekomendasi_produk'] ?? [] as $rIdx => $rec)
                                <div x-show="false" class="p-3.5 rounded-xl border border-stone-200 bg-stone-50 text-stone-800 leading-relaxed flex items-start gap-2.5">
                                    <span class="font-bold font-mono text-stone-900 shrink-0">{{ $rIdx + 1 }}.</span>
                                    <span>{{ $rec }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Pemasaran -->
                    <div class="space-y-3">
                        <div class="font-bold text-stone-900 uppercase font-mono text-[11px]">4.1 Strategi Pemasaran & Saluran Distribusi</div>
                        <div class="space-y-2">
                            <template x-for="(mkt, mIdx) in (analysis.strategi_pemasaran || [])" :key="mIdx">
                                <div class="p-3.5 rounded-xl border border-stone-200 bg-stone-50 text-stone-800 leading-relaxed flex items-start gap-2.5">
                                    <span class="font-bold font-mono text-stone-900 shrink-0" x-text="(mIdx + 1) + '.'"></span>
                                    <span x-text="mkt"></span>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SECTION V: ACTION PLAN OPERASIONAL -->
            <div class="space-y-3 pt-2">
                <h4 class="font-mono text-xs font-bold uppercase tracking-wider text-stone-900 border-b border-stone-300 pb-2">
                    BAGIAN V: RENCANA AKSI OPERASIONAL (ACTION PLAN EKSEKUSI)
                </h4>

                <div class="space-y-2 text-xs">
                    <template x-for="(step, sIdx) in (analysis.action_plan || [])" :key="sIdx">
                        <div class="p-4 rounded-xl border border-stone-300 bg-stone-900 text-white flex items-start gap-3">
                            <span class="px-2 py-0.5 rounded font-mono font-bold text-[10px] bg-amber-400 text-stone-900 shrink-0 mt-0.5"
                                  x-text="'TAHAP ' + (sIdx + 1)"></span>
                            <span x-text="step" class="leading-relaxed font-medium"></span>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Formal Sign-Off Footer -->
            <div class="pt-8 border-t-2 border-stone-300 flex flex-col sm:flex-row items-center justify-between text-xs font-mono text-stone-500 gap-4">
                <div>
                    <div>DITERBITKAN SECARA OTOMATIS OLEH ENGINE ANALISA PASAR</div>
                    <div class="text-[10px]">Dokumen ini sah digunakan untuk bahan evaluasi bisnis kewirausahaan.</div>
                </div>
                <div class="p-3 border border-stone-300 rounded-lg text-center bg-stone-50">
                    <div class="text-[9px] uppercase font-bold text-stone-400">STATUS VERIFIKASI</div>
                    <div class="font-bold text-stone-800">STAMP: VERIFIED INTEL</div>
                </div>
            </div>

        </div>

    </div>

    <!-- =================================================================== -->
    <!-- VIEW MODE 2: INTERACTIVE DASHBOARD CARD FORMAT                      -->
    <!-- =================================================================== -->
    <div x-show="viewMode === 'dashboard'" class="space-y-6 no-print">

        <!-- KPI Cards Grid -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="card p-5 space-y-1">
                <div class="text-[10px] font-sans font-bold uppercase tracking-widest" style="color:#A19A91;">INDEKS MINAT PASAR</div>
                <div class="font-serif text-3xl font-bold text-ink" x-text="(analysis.skor_potensi || '{{ $analysis['skor_potensi'] ?? $kpis['interest_index'] }}') + '%'">
                    {{ $analysis['skor_potensi'] ?? $kpis['interest_index'] }}%
                </div>
            </div>

            <div class="card p-5 space-y-1">
                <div class="text-[10px] font-sans font-bold uppercase tracking-widest" style="color:#A19A91;">RATA-RATA WTP (DAYA BELI)</div>
                <div class="font-serif text-3xl font-bold text-ink" x-text="analysis.strategi_harga_wtp ? analysis.strategi_harga_wtp.sweet_spot_harga : 'Rp {{ number_format($kpis['avg_wtp'], 0, ',', '.') }}'">
                    Rp {{ number_format($kpis['avg_wtp'], 0, ',', '.') }}
                </div>
            </div>

            <div class="card p-5 space-y-1">
                <div class="text-[10px] font-sans font-bold uppercase tracking-widest" style="color:#A19A91;">SKOR KEPUASAN (LIKERT)</div>
                <div class="font-serif text-3xl font-bold text-ink">{{ $kpis['avg_likert'] }} / 5.0</div>
            </div>

            <div class="card p-5 space-y-1">
                <div class="text-[10px] font-sans font-bold uppercase tracking-widest" style="color:#A19A91;">TOTAL RESPONDEN</div>
                <div class="font-serif text-3xl font-bold text-ink">{{ $kpis['total_respondents'] }}</div>
            </div>
        </div>

        <!-- Executive Summary Card -->
        <div class="card p-6 sm:p-8 space-y-4">
            <h2 class="font-serif font-bold text-xl text-ink">Ringkasan Eksekutif AI</h2>
            <p class="text-sm leading-relaxed text-ink font-medium" x-text="analysis.ringkasan_eksekutif">
                {{ $analysis['ringkasan_eksekutif'] }}
            </p>
        </div>

        <!-- Sentiment & Pricing Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="card p-6 space-y-3">
                <h3 class="font-serif font-bold text-lg text-ink">Analisis Sentimen Konsumen</h3>
                <div class="text-sm font-bold text-emerald-800" x-text="'Tingkat Minat: ' + (analysis.analisis_sentimen ? analysis.analisis_sentimen.tingkat_minat : 'Tinggi')"></div>
                <p class="text-xs leading-relaxed text-stone-600" x-text="analysis.analisis_sentimen ? analysis.analisis_sentimen.penjelasan : ''"></p>
            </div>

            <div class="card p-6 space-y-3">
                <h3 class="font-serif font-bold text-lg text-ink">Strategi Harga & Margin (WTP)</h3>
                <div class="text-sm font-bold text-stone-900" x-text="'Sweet Spot: ' + (analysis.strategi_harga_wtp ? analysis.strategi_harga_wtp.sweet_spot_harga : '')"></div>
                <p class="text-xs leading-relaxed text-stone-600" x-text="analysis.strategi_harga_wtp ? analysis.strategi_harga_wtp.rekomendasi_margin : ''"></p>
            </div>
        </div>

        <!-- Product & Marketing Card -->
        <div class="card p-6 sm:p-8 space-y-6">
            <h3 class="font-serif font-bold text-lg text-ink">Rekomendasi Produk & Strategi Pemasaran AI</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-xs">
                <div class="space-y-3">
                    <div class="font-bold text-stone-900 uppercase">🛍️ Fitur & Kualitas Produk:</div>
                    <template x-for="(rec, rIdx) in (analysis.rekomendasi_produk || [])" :key="rIdx">
                        <div class="p-3 rounded-xl border border-stone-200 bg-stone-50 flex items-start gap-2">
                            <span class="font-bold text-stone-900" x-text="rIdx + 1 + '.'"></span>
                            <span x-text="rec"></span>
                        </div>
                    </template>
                </div>
                <div class="space-y-3">
                    <div class="font-bold text-stone-900 uppercase">📢 Strategi Pemasaran:</div>
                    <template x-for="(mkt, mIdx) in (analysis.strategi_pemasaran || [])" :key="mIdx">
                        <div class="p-3 rounded-xl border border-stone-200 bg-stone-50 flex items-start gap-2">
                            <span class="font-bold text-stone-900" x-text="mIdx + 1 + '.'"></span>
                            <span x-text="mkt"></span>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        <!-- Action Plan Card -->
        <div class="card p-6 sm:p-8 space-y-4" style="background:#1C1917; color:#FFFFFF;">
            <h3 class="font-serif font-bold text-lg text-white">Action Plan AI</h3>
            <div class="space-y-3">
                <template x-for="(step, sIdx) in (analysis.action_plan || [])" :key="sIdx">
                    <div class="p-3.5 rounded-xl border border-stone-800 bg-stone-900 text-xs text-stone-200 flex items-start gap-3">
                        <span class="px-2 py-0.5 rounded font-mono font-bold text-[10px] bg-amber-400 text-stone-900 shrink-0 mt-0.5" x-text="'Langkah ' + (sIdx + 1)"></span>
                        <span x-text="step" class="leading-relaxed"></span>
                    </div>
                </template>
            </div>
        </div>

    </div>
    @endif

</div>

<script>
function aiAnalyticsShowPage() {
    return {
        surveyId: "{{ $survey->id }}",
        loading: false,
        copied: false,
        viewMode: 'document',
        analysis: @json($analysis),

        async regenerateAi() {
            this.loading = true;
            try {
                const res = await fetch(`/admin/ai-analytics/${this.surveyId}/generate`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });

                const contentType = res.headers.get('content-type') || '';
                if (!contentType.includes('application/json')) {
                    throw new Error('Server mengembalikan respon HTTP ' + res.status + '. Silakan coba beberapa saat lagi.');
                }

                const data = await res.json();
                if (data.success && data.analysis) {
                    this.analysis = data.analysis;
                } else {
                    throw new Error(data.message || 'Gagal memproses analisa AI.');
                }
            } catch (e) {
                alert('Gagal meregenerasi data AI: ' + e.message);
            } finally {
                this.loading = false;
            }
        },

        copyAnalysisText() {
            const text = `LAPORAN DOKUMEN EKSEKUTIF ANALISA PASAR AI\n` +
                         `Subjek Proyek: {{ $survey->judul }}\n` +
                         `No Dokumen: DOC-AI/{{ date('Ym') }}/{{ substr($survey->id, 0, 8) }}\n\n` +
                         `BAGIAN I: RINGKASAN EKSEKUTIF\n${this.analysis.ringkasan_eksekutif}\n\n` +
                         `BAGIAN II: STRATEGI HARGA WTP\n${this.analysis.strategi_harga_wtp ? this.analysis.strategi_harga_wtp.sweet_spot_harga : ''}\n\n` +
                         `BAGIAN III: REKOMENDASI PRODUK\n` + (this.analysis.rekomendasi_produk || []).join('\n') + `\n\n` +
                         `BAGIAN V: ACTION PLAN EKSEKUSI\n` + (this.analysis.action_plan || []).join('\n');

            navigator.clipboard.writeText(text);
            this.copied = true;
            setTimeout(() => this.copied = false, 3000);
        }
    };
}
</script>
@endsection
