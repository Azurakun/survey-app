@extends('layouts.admin')
@section('title', 'Dashboard — Platform Survey Riset Pasar')

@section('content')
<div class="space-y-6 pb-6">

    <!-- ── Page Header ─────────────────────────────────────────────────── -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="text-[10px] font-sans uppercase tracking-widest font-bold mb-1" style="color:#C89D54;">PLATFORM SURVEY RISET PASAR</div>
            <h1 class="font-serif font-bold text-2xl xl:text-3xl text-ink leading-tight">Dashboard Pengelolaan Survey</h1>
            <p class="text-xs mt-1 max-w-xl" style="color:#6E675F;">
                Pusat manajemen survey riset pasar, statistik aktivitas, dan intelijen bisnis kewirausahaan siswa SMKN 2 Indramayu.
            </p>
        </div>
        <a href="{{ route('admin.surveys.create') }}"
           class="inline-flex items-center gap-2 px-5 py-2.5 text-xs font-bold rounded-xl transition shrink-0 active:scale-95 shadow-sm"
           style="background:#1C1917; color:#FFFFFF;"
           onmouseover="this.style.background='#2C2723'" onmouseout="this.style.background='#1C1917'">
            <svg class="w-4 h-4" style="color:#C89D54;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Buat Survey Baru
        </a>
    </div>

    <!-- ── Management KPI Cards (Staggered Animation) ────────────────── -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

        <!-- 01 Total Surveys -->
        <div class="kpi-card-1 card p-5">
            <div class="flex items-center justify-between mb-3">
                <div class="text-[9px] font-bold uppercase tracking-widest" style="color:#A19A91;">TOTAL SURVEY</div>
                <div class="w-7 h-7 rounded-lg flex items-center justify-center border" style="background:#F8F4EC; border-color:#E5E0D8;">
                    <svg class="w-3.5 h-3.5" style="color:#C89D54;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                </div>
            </div>
            <div class="font-serif text-3xl font-bold text-ink tabular-nums">{{ $totalSurveys }}</div>
            <div class="flex items-center gap-1.5 mt-2">
                <div class="w-1.5 h-1.5 rounded-full" style="background:#C89D54;"></div>
                <span class="text-[10px] font-medium" style="color:#6E675F;">{{ $publishedSurveys }} Dipublikasi, {{ $draftSurveys + $closedSurveys }} Draf/Closed</span>
            </div>
        </div>

        <!-- 02 Published Surveys -->
        <div class="kpi-card-2 card p-5">
            <div class="flex items-center justify-between mb-3">
                <div class="text-[9px] font-bold uppercase tracking-widest" style="color:#A19A91;">SURVEY DIPUBLIKASI</div>
                <div class="w-7 h-7 rounded-lg flex items-center justify-center border" style="background:#EDFAF2; border-color:#A3D9BA;">
                    <svg class="w-3.5 h-3.5" style="color:#1A5C38;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                </div>
            </div>
            <div class="font-serif text-3xl font-bold text-ink tabular-nums">{{ $publishedSurveys }}</div>
            <div class="text-[10px] font-medium mt-2" style="color:#6E675F;">Aktif & Siap Menerima Responden</div>
        </div>

        <!-- 03 Total Respondents -->
        <div class="kpi-card-3 card p-5">
            <div class="flex items-center justify-between mb-3">
                <div class="text-[9px] font-bold uppercase tracking-widest" style="color:#A19A91;">TOTAL RESPONDEN</div>
                <div class="w-7 h-7 rounded-lg flex items-center justify-center border" style="background:#FAF8F5; border-color:#E5E0D8;">
                    <svg class="w-3.5 h-3.5" style="color:#6E675F;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
            </div>
            <div class="font-serif text-3xl font-bold text-ink tabular-nums">{{ $totalRespondents }}</div>
            <div class="text-[10px] font-medium mt-2" style="color:#6E675F;">Siswa Terverifikasi NISN</div>
        </div>

        <!-- 04 Total Questions -->
        <div class="kpi-card-4 card p-5">
            <div class="flex items-center justify-between mb-3">
                <div class="text-[9px] font-bold uppercase tracking-widest" style="color:#A19A91;">TOTAL PERTANYAAN</div>
                <div class="w-7 h-7 rounded-lg flex items-center justify-center border" style="background:#F8F4EC; border-color:#E5E0D8;">
                    <svg class="w-3.5 h-3.5" style="color:#C89D54;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <div class="font-serif text-3xl font-bold text-ink tabular-nums">{{ $totalQuestions }}</div>
            <div class="text-[10px] font-medium mt-2" style="color:#6E675F;">Rata-rata {{ $avgQuestionsPerSurvey }} pert. / survey</div>
        </div>
    </div>

    <!-- ── System Activity & AI Intelligence Hub ──────────────────────── -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-5">

        <!-- Status & Activity Overview Card -->
        <div class="lg:col-span-7 card p-5 sm:p-6 space-y-4">
            <div class="flex items-center justify-between pb-4 border-b" style="border-color:#EBE6DE;">
                <div>
                    <div class="text-[9px] font-bold uppercase tracking-widest mb-0.5" style="color:#C89D54;">MANAJEMEN PROYEK</div>
                    <h2 class="font-serif font-bold text-lg text-ink">Status & Distribusi Proyek Survey</h2>
                </div>
                <span class="text-[10px] font-bold px-2.5 py-1 rounded-full border" style="background:#F8F4EC; border-color:#E5E0D8; color:#1C1917;">RINGKASAN PLATFORM</span>
            </div>

            <!-- Status Bars -->
            <div class="space-y-4 py-2">
                <div>
                    <div class="flex items-center justify-between text-xs mb-1.5 font-medium">
                        <span class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full" style="background:#1A5C38;"></span>
                            <span class="text-ink font-semibold">Survey Dipublikasi (Published)</span>
                        </span>
                        <span class="font-bold text-ink tabular-nums">{{ $publishedSurveys }} / {{ $totalSurveys }}</span>
                    </div>
                    @php $pubPct = $totalSurveys > 0 ? round(($publishedSurveys / $totalSurveys) * 100) : 0; @endphp
                    <div class="w-full h-2.5 rounded-full overflow-hidden" style="background:#F4F0E8;">
                        <div class="h-full rounded-full transition-all duration-500" style="width: {{ $pubPct }}%; background:#1A5C38;"></div>
                    </div>
                </div>

                <div>
                    <div class="flex items-center justify-between text-xs mb-1.5 font-medium">
                        <span class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full" style="background:#C89D54;"></span>
                            <span class="text-ink font-semibold">Survey Draf (Dalam Penyusunan)</span>
                        </span>
                        <span class="font-bold text-ink tabular-nums">{{ $draftSurveys }} / {{ $totalSurveys }}</span>
                    </div>
                    @php $draftPct = $totalSurveys > 0 ? round(($draftSurveys / $totalSurveys) * 100) : 0; @endphp
                    <div class="w-full h-2.5 rounded-full overflow-hidden" style="background:#F4F0E8;">
                        <div class="h-full rounded-full transition-all duration-500" style="width: {{ $draftPct }}%; background:#C89D54;"></div>
                    </div>
                </div>

                <div>
                    <div class="flex items-center justify-between text-xs mb-1.5 font-medium">
                        <span class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full" style="background:#A19A91;"></span>
                            <span class="text-ink font-semibold">Survey Selesai (Closed)</span>
                        </span>
                        <span class="font-bold text-ink tabular-nums">{{ $closedSurveys }} / {{ $totalSurveys }}</span>
                    </div>
                    @php $closedPct = $totalSurveys > 0 ? round(($closedSurveys / $totalSurveys) * 100) : 0; @endphp
                    <div class="w-full h-2.5 rounded-full overflow-hidden" style="background:#F4F0E8;">
                        <div class="h-full rounded-full transition-all duration-500" style="width: {{ $closedPct }}%; background:#A19A91;"></div>
                    </div>
                </div>
            </div>

            <!-- Operational Workflow Info -->
            <div class="p-4 rounded-xl border text-xs space-y-2 mt-2" style="background:#FAF8F5; border-color:#E5E0D8;">
                <div class="font-bold text-ink flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>Alur Kerja Pengelolaan Riset Pasar Kewirausahaan:</span>
                </div>
                <p class="leading-relaxed" style="color:#6E675F;">
                    1. <strong>Buat Survey & Pertanyaan:</strong> Gunakan builder interaktif untuk merancang pertanyaan riset.<br>
                    2. <strong>Publikasikan:</strong> Bagikan link survey ke siswa/responden.<br>
                    3. <strong>Analisa Per Survey:</strong> Setiap survey memiliki halaman <strong>Analitik Data</strong> & <strong>Analisa AI</strong> independen untuk daya beli (WTP), Likert, dan strategi produk.
                </p>
            </div>
        </div>

        <!-- AI Analytics Hub Card -->
        <div class="lg:col-span-5 card p-5 sm:p-6 space-y-4 flex flex-col justify-between" style="background:#1C1917; border-color:#E5E0D8;">
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-[9px] font-bold uppercase tracking-widest" style="color:#C89D54;">AI MARKET INTELLIGENCE</span>
                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-md border" style="background:rgba(200,157,84,0.15); border-color:rgba(200,157,84,0.3); color:#C89D54;">GEMINI AI</span>
                </div>
                <h2 class="font-serif font-bold text-xl text-white leading-tight">Analisa AI per Produk & Survey</h2>
                <p class="text-xs leading-relaxed" style="color:rgba(255,255,255,0.7);">
                    Setiap produk kewirausahaan dianalisis secara terpisah agar perhitungan harga <strong>WTP (Willingness to Pay)</strong>, skor <strong>Kepuasan Likert</strong>, dan rekomendasi <strong>Action Plan</strong> memiliki akurasi presisi tinggi.
                </p>

                <div class="space-y-2.5 text-xs border-t pt-4" style="border-color:rgba(255,255,255,0.15); color:rgba(255,255,255,0.85);">
                    <div class="flex items-start gap-2.5">
                        <span class="text-amber-400 font-bold">✓</span>
                        <span><strong>Estimasi Daya Beli (WTP):</strong> Menghitung harga manis ideal per unit produk.</span>
                    </div>
                    <div class="flex items-start gap-2.5">
                        <span class="text-amber-400 font-bold">✓</span>
                        <span><strong>Sentimen Konsumen:</strong> Menganalisis preferensi & daya tarik spesifik produk.</span>
                    </div>
                    <div class="flex items-start gap-2.5">
                        <span class="text-amber-400 font-bold">✓</span>
                        <span><strong>Dokumen Eksekutif AI:</strong> Menghasilkan laporan & rekomendasi strategi siap cetak.</span>
                    </div>
                </div>
            </div>

            <div class="pt-2 border-t" style="border-color:rgba(255,255,255,0.15);">
                <a href="{{ route('admin.ai-analytics.index') }}"
                   class="w-full py-2.5 px-4 rounded-xl text-xs font-bold transition flex items-center justify-center gap-2 active:scale-95 shadow-sm"
                   style="background:#C89D54; color:#1C1917;"
                   onmouseover="this.style.background='#D9AB55'" onmouseout="this.style.background='#C89D54'">
                    <span>Pilih Survey untuk Analisa AI</span>
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </a>
            </div>
        </div>
    </div>


    <!-- ── Survey Performance Table ────────────────────────────────────── -->
    <div class="card p-5 sm:p-6 space-y-4">
        <div class="flex items-center justify-between pb-4 border-b" style="border-color:#EAE5DC;">
            <div>
                <div class="text-[9px] font-bold uppercase tracking-widest mb-0.5" style="color:#A09488;">PERFORMA PROYEK</div>
                <h2 class="font-serif font-bold text-lg text-ink">Daftar Survey Riset Pasar</h2>
            </div>
            <a href="{{ route('admin.surveys.index') }}"
               class="text-xs font-semibold transition" style="color:#C89D54;"
               onmouseover="this.style.color='#1C1917'" onmouseout="this.style.color='#C89D54'">
                Lihat Semua →
            </a>
        </div>

        @if($surveys->isEmpty())
            <div class="text-center py-14 rounded-xl border-2 border-dashed" style="border-color:#E5E0D8; background:#FAF8F5;">
                <div class="text-5xl mb-3">📋</div>
                <h3 class="font-serif font-bold text-lg text-ink">Belum Ada Survey</h3>
                <p class="text-xs mt-1 mb-5" style="color:#6E675F;">Mulai buat survey pertama untuk mengumpulkan data riset pasar siswa.</p>
                <a href="{{ route('admin.surveys.create') }}"
                   class="inline-flex items-center gap-2 px-4 py-2 text-xs font-bold rounded-xl transition"
                   style="background:#1C1917; color:#FFFFFF;">
                    Buat Survey Pertama
                </a>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="border-b text-[10px] font-bold uppercase tracking-wider" style="border-color:#EBE6DE; color:#A19A91;">
                            <th class="pb-3 px-1">Survey</th>
                            <th class="pb-3 px-1">Status</th>
                            <th class="pb-3 px-1">Pertanyaan</th>
                            <th class="pb-3 px-1">Responden</th>
                            <th class="pb-3 px-1 text-right">Aksi Analisis</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y" style="border-color:#EBE6DE;">
                        @foreach($surveys as $survey)
                        <tr class="hover:bg-surface-subtle transition" style="--tw-bg-opacity:1;">
                            <td class="py-3 px-1">
                                <div class="font-serif font-bold text-sm text-ink">{{ Str::limit($survey->judul, 45) }}</div>
                                <div class="text-[11px] mt-0.5 line-clamp-1" style="color:#A19A91;">{{ $survey->deskripsi ?? '–' }}</div>
                            </td>
                            <td class="py-3 px-1">
                                @if($survey->status === 'PUBLISHED')
                                    <span class="text-[10px] font-bold px-2 py-1 rounded-full border" style="background:#EDFAF2; border-color:#A3D9BA; color:#1A5C38;">Published</span>
                                @elseif($survey->status === 'DRAFT')
                                    <span class="text-[10px] font-bold px-2 py-1 rounded-full border" style="background:#FAF8F5; border-color:#E5E0D8; color:#A19A91;">Draft</span>
                                @else
                                    <span class="text-[10px] font-bold px-2 py-1 rounded-full border" style="background:#FDECEA; border-color:#F5B7B1; color:#922B21;">Closed</span>
                                @endif
                            </td>
                            <td class="py-3 px-1 font-semibold text-ink tabular-nums">{{ $survey->questions_count }}</td>
                            <td class="py-3 px-1 font-bold tabular-nums" style="color:#C89D54;">{{ $survey->respondents_count }}</td>
                            <td class="py-3 px-1 text-right">
                                <div class="inline-flex items-center gap-1.5">
                                    <a href="{{ route('admin.surveys.analytics', $survey->id) }}"
                                       class="px-2.5 py-1 text-[11px] font-bold rounded-lg border transition"
                                       style="background:#FAF8F5; border-color:#E5E0D8; color:#1C1917;"
                                       onmouseover="this.style.background='#1C1917';this.style.color='#FFF';this.style.borderColor='#1C1917'"
                                       onmouseout="this.style.background='#FAF8F5';this.style.color='#1C1917';this.style.borderColor='#E5E0D8'">
                                        Analitik Data
                                    </a>
                                    <a href="{{ route('admin.ai-analytics.show', $survey->id) }}"
                                       class="px-2.5 py-1 text-[11px] font-bold rounded-lg border transition flex items-center gap-1"
                                       style="background:#FAF6EE; border-color:#E5E0D8; color:#C89D54;"
                                       onmouseover="this.style.background='#C89D54';this.style.color='#1C1917';this.style.borderColor='#C89D54'"
                                       onmouseout="this.style.background='#FAF6EE';this.style.color='#C89D54';this.style.borderColor='#E5E0D8'">
                                        <span>Analisa</span>
                                        <span>✨</span>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

</div>
@endsection
