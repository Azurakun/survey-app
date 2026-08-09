@extends('layouts.admin')
@section('title', 'Analitik — ' . $survey->judul)

@section('content')
<div x-data="analyticsApp()" class="space-y-6 pb-10">

    <!-- ── Page Header ─────────────────────────────────────────────────── -->
    <div class="card p-5 sm:p-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-start gap-4">
            <a href="{{ route('admin.ai-analytics.index') }}"
               class="mt-0.5 p-2.5 rounded-xl border transition"
               style="background:#FAF8F5; border-color:#E5E0D8; color:#1C1917;"
               onmouseover="this.style.background='#F4F0E8';"
               onmouseout="this.style.background='#FAF8F5';">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <div>
                <div class="text-[10px] font-sans uppercase tracking-widest font-bold mb-1" style="color:#C89D54;">ANALITIK SURVEY & INTELIJEN PASAR</div>
                <h1 class="font-bold text-xl md:text-2xl text-ink leading-tight">{{ $survey->judul }}</h1>
                <div class="flex items-center gap-2 mt-2">
                    @if($survey->status === 'PUBLISHED')
                        <span class="text-[10px] font-bold px-2.5 py-0.5 rounded-full border uppercase" style="background:#EDFAF2; border-color:#A3D9BA; color:#1A5C38;">● Published</span>
                    @elseif($survey->status === 'DRAFT')
                        <span class="text-[10px] font-bold px-2.5 py-0.5 rounded-full border uppercase" style="background:#FAF8F5; border-color:#E5E0D8; color:#A19A91;">○ Draft</span>
                    @else
                        <span class="text-[10px] font-bold px-2.5 py-0.5 rounded-full border uppercase" style="background:#FDECEA; border-color:#F5B7B1; color:#922B21;">✕ Closed</span>
                    @endif
                    <span class="text-xs font-semibold" style="color:#6E675F;">{{ $totalRespondents }} responden terkumpul</span>
                </div>
            </div>
        </div>
        <div class="flex items-center gap-2.5 flex-wrap">
            <a href="{{ route('admin.surveys.export', $survey->id) }}"
               class="inline-flex items-center gap-2 px-4 py-2.5 text-white font-bold text-xs rounded-xl transition shadow-xs"
               style="background:#1C1917;"
               onmouseover="this.style.background='#2C2723';"
               onmouseout="this.style.background='#1C1917';">
                <svg class="w-4 h-4" style="color:#C89D54;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                <span>Ekspor XLSX</span>
            </a>
            <a href="{{ route('admin.surveys.builder', $survey->id) }}"
               class="inline-flex items-center gap-2 px-4 py-2.5 border font-bold text-xs rounded-xl transition"
               style="background:#FAF8F5; border-color:#E5E0D8; color:#1C1917;"
               onmouseover="this.style.background='#F4F0E8';"
               onmouseout="this.style.background='#FAF8F5';">
                <svg class="w-4 h-4" style="color:#C89D54;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                <span>Builder</span>
            </a>
        </div>
    </div>

    <!-- ── View Mode Filter Switcher Bar ───────────────────────────────── -->
    <div class="card p-2 flex items-center gap-2 flex-wrap sm:flex-nowrap" style="background:#FFFFFF; border-color:#E5E0D8;">
        <!-- Tab 1: Ringkasan (Summary) -->
        <button type="button" @click="activeView = 'SUMMARY'"
                :class="activeView === 'SUMMARY'
                    ? 'shadow-xs font-bold'
                    : 'text-ink-subtle hover:bg-surface-subtle'"
                :style="activeView === 'SUMMARY'
                    ? 'background:#1C1917; color:#FFFFFF;'
                    : 'color:#6E675F;'"
                class="flex-1 py-2.5 px-3 rounded-xl text-xs font-bold transition flex items-center justify-center gap-2 border"
                :style="activeView === 'SUMMARY' ? 'border-color:#1C1917;' : 'border-color:transparent;'">
            <svg class="w-4 h-4 shrink-0" :style="activeView === 'SUMMARY' ? 'color:#C89D54;' : 'color:#A19A91;'" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
            </svg>
            <span>Ringkasan (Summary)</span>
        </button>

        <!-- Tab 2: Analisa & Intelijen AI ✨ -->
        <button type="button" @click="activeView = 'AI_ANALYTICS'"
                :class="activeView === 'AI_ANALYTICS'
                    ? 'shadow-xs font-bold'
                    : 'text-ink-subtle hover:bg-surface-subtle'"
                :style="activeView === 'AI_ANALYTICS'
                    ? 'background:#1C1917; color:#FFFFFF;'
                    : 'color:#6E675F;'"
                class="flex-1 py-2.5 px-3 rounded-xl text-xs font-bold transition flex items-center justify-center gap-2 border relative"
                :style="activeView === 'AI_ANALYTICS' ? 'border-color:#1C1917;' : 'border-color:transparent;'">
            <span style="color:#C89D54;">✨</span>
            <span>Analisa & Intelijen AI</span>
        </button>

        <!-- Tab 3: Jawaban Per Pertanyaan -->
        <button type="button" @click="activeView = 'QUESTIONS'"
                :class="activeView === 'QUESTIONS'
                    ? 'shadow-xs font-bold'
                    : 'text-ink-subtle hover:bg-surface-subtle'"
                :style="activeView === 'QUESTIONS'
                    ? 'background:#1C1917; color:#FFFFFF;'
                    : 'color:#6E675F;'"
                class="flex-1 py-2.5 px-3 rounded-xl text-xs font-bold transition flex items-center justify-center gap-2 border"
                :style="activeView === 'QUESTIONS' ? 'border-color:#1C1917;' : 'border-color:transparent;'">
            <svg class="w-4 h-4 shrink-0" :style="activeView === 'QUESTIONS' ? 'color:#C89D54;' : 'color:#A19A91;'" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span>Jawaban Per Pertanyaan</span>
        </button>

        <!-- Tab 4: Detail Jawaban Individual -->
        <button type="button" @click="activeView = 'INDIVIDUAL'"
                :class="activeView === 'INDIVIDUAL'
                    ? 'shadow-xs font-bold'
                    : 'text-ink-subtle hover:bg-surface-subtle'"
                :style="activeView === 'INDIVIDUAL'
                    ? 'background:#1C1917; color:#FFFFFF;'
                    : 'color:#6E675F;'"
                class="flex-1 py-2.5 px-3 rounded-xl text-xs font-bold transition flex items-center justify-center gap-2 border"
                :style="activeView === 'INDIVIDUAL' ? 'border-color:#1C1917;' : 'border-color:transparent;'">
            <svg class="w-4 h-4 shrink-0" :style="activeView === 'INDIVIDUAL' ? 'color:#C89D54;' : 'color:#A19A91;'" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            <span>Detail Jawaban Individual</span>
        </button>
    </div>

    <!-- ── Date Filter Form ────────────────────────────────────────────── -->
    <form method="GET" action="{{ route('admin.surveys.analytics', $survey->id) }}"
          class="card p-4 flex flex-wrap items-end gap-4">
        <div>
            <label class="block text-[10px] font-sans uppercase tracking-widest font-bold mb-1" style="color:#6E675F;">Dari Tanggal</label>
            <input type="date" name="start_date" value="{{ request('start_date') }}"
                   class="px-3 py-2 border rounded-xl text-xs font-medium text-ink focus:outline-none focus:ring-2"
                   style="background:#FAF8F5; border-color:#E5E0D8;">
        </div>
        <div>
            <label class="block text-[10px] font-sans uppercase tracking-widest font-bold mb-1" style="color:#6E675F;">Sampai Tanggal</label>
            <input type="date" name="end_date" value="{{ request('end_date') }}"
                   class="px-3 py-2 border rounded-xl text-xs font-medium text-ink focus:outline-none focus:ring-2"
                   style="background:#FAF8F5; border-color:#E5E0D8;">
        </div>
        <button type="submit"
                class="px-4 py-2 text-white font-bold text-xs rounded-xl transition shadow-xs"
                style="background:#1C1917;"
                onmouseover="this.style.background='#2C2723';"
                onmouseout="this.style.background='#1C1917';">
            Filter Data
        </button>
        @if(request('start_date') || request('end_date'))
            <a href="{{ route('admin.surveys.analytics', $survey->id) }}"
               class="px-4 py-2 border font-bold text-xs rounded-xl transition"
               style="background:#FAF8F5; border-color:#E5E0D8; color:#6E675F;">
                Reset Filter
            </a>
        @endif
    </form>

    <!-- ================================================================= -->
    <!-- VIEW MODE 1: SUMMARY / RINGKASAN DATA & AI INSIGHTS              -->
    <!-- ================================================================= -->
    <div x-show="activeView === 'SUMMARY'" x-transition class="space-y-6">

        <!-- ── UNIFIED AI INTELLIGENCE EXECUTIVE BANNER CARD ───────────── -->
        <div class="card p-5 sm:p-6 space-y-4" style="background:#FAF6EE; border-color:#E5E0D8;">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3 border-b" style="border-color:#E5E0D8;">
                <div class="flex items-center gap-2">
                    <span class="p-1.5 rounded-lg bg-amber-500/10 text-amber-700 text-base">✨</span>
                    <div>
                        <span class="text-[10px] font-bold uppercase tracking-widest block" style="color:#C89D54;">INTELIJEN AI & RINGKASAN PASAR</span>
                        <h2 class="font-serif font-bold text-lg text-ink">Naratif Evaluasi Kelayakan Produk</h2>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <div class="text-right">
                        <span class="text-[9px] uppercase tracking-wider block" style="color:#A19A91;">SKOR POTENSI PASAR</span>
                        <span class="font-serif font-bold text-xl" style="color:#C89D54;" x-text="(aiAnalysis.skor_potensi || 85) + '/100'"></span>
                    </div>
                    <button type="button" @click="activeView = 'AI_ANALYTICS'"
                            class="px-3.5 py-2 text-xs font-bold rounded-xl text-white transition shadow-xs"
                            style="background:#1C1917;">
                        Dokumen AI Lengkap →
                    </button>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-5 pt-1">
                <!-- Executive Summary Narrative -->
                <div class="lg:col-span-8 space-y-2">
                    <p class="text-xs sm:text-sm text-stone-700 leading-relaxed font-medium" x-text="aiAnalysis.ringkasan_eksekutif"></p>
                </div>
                <!-- WTP & Sentiment Quick Highlights -->
                <div class="lg:col-span-4 p-4 rounded-xl space-y-3 border" style="background:#FFFFFF; border-color:#E5E0D8;">
                    <div>
                        <span class="text-[10px] font-bold uppercase tracking-wider block" style="color:#A19A91;">SWEET SPOT HARGA (WTP)</span>
                        <span class="font-bold text-base text-ink" x-text="aiAnalysis.strategi_harga_wtp ? aiAnalysis.strategi_harga_wtp.sweet_spot_harga : 'Rp 25.000'"></span>
                    </div>
                    <div class="pt-2 border-t" style="border-color:#F4F0E8;">
                        <span class="text-[10px] font-bold uppercase tracking-wider block" style="color:#A19A91;">TINGKAT MINAT KONSUMEN</span>
                        <span class="font-bold text-sm text-emerald-700" x-text="aiAnalysis.analisis_sentimen ? aiAnalysis.analisis_sentimen.tingkat_minat : 'Tinggi (Sangat Positif)'"></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- 4 KPI Stat Cards Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Card 1: Total Responden -->
            <div class="card p-5 space-y-2">
                <div class="flex items-center justify-between">
                    <span class="text-[10px] font-sans uppercase tracking-widest font-bold" style="color:#6E675F;">TOTAL RESPONDEN</span>
                    <div class="w-8 h-8 rounded-xl flex items-center justify-center" style="background:#F8F4EC; border:1px solid #E5E0D8;">
                        <svg class="w-4 h-4" style="color:#C89D54;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    </div>
                </div>
                <div class="text-3xl font-bold text-ink tabular-nums">{{ number_format($totalRespondents, 0, ',', '.') }}</div>
                <p class="text-xs" style="color:#A19A91;">Siswa mengisi survey ini</p>
            </div>

            <!-- Card 2: Total Pertanyaan -->
            <div class="card p-5 space-y-2">
                <div class="flex items-center justify-between">
                    <span class="text-[10px] font-sans uppercase tracking-widest font-bold" style="color:#6E675F;">TOTAL PERTANYAAN</span>
                    <div class="w-8 h-8 rounded-xl flex items-center justify-center" style="background:#F8F4EC; border:1px solid #E5E0D8;">
                        <svg class="w-4 h-4" style="color:#C89D54;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </div>
                <div class="text-3xl font-bold text-ink tabular-nums">{{ count($analytics) }}</div>
                <p class="text-xs" style="color:#A19A91;">Item pertanyaan aktif</p>
            </div>

            <!-- Card 3: Status Survey -->
            <div class="card p-5 space-y-2">
                <div class="flex items-center justify-between">
                    <span class="text-[10px] font-sans uppercase tracking-widest font-bold" style="color:#6E675F;">STATUS PUBLIKASI</span>
                    <div class="w-8 h-8 rounded-xl flex items-center justify-center" style="background:#F8F4EC; border:1px solid #E5E0D8;">
                        <svg class="w-4 h-4" style="color:#C89D54;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </div>
                <div class="pt-1">
                    @if($survey->status === 'PUBLISHED')
                        <span class="text-xs font-bold px-3 py-1 rounded-full border uppercase" style="background:#EDFAF2; border-color:#A3D9BA; color:#1A5C38;">Published</span>
                    @elseif($survey->status === 'DRAFT')
                        <span class="text-xs font-bold px-3 py-1 rounded-full border uppercase" style="background:#FAF8F5; border-color:#E5E0D8; color:#A19A91;">Draft</span>
                    @else
                        <span class="text-xs font-bold px-3 py-1 rounded-full border uppercase" style="background:#FDECEA; border-color:#F5B7B1; color:#922B21;">Closed</span>
                    @endif
                </div>
                <p class="text-xs mt-1" style="color:#A19A91;">Siap menerima respon</p>
            </div>

            <!-- Card 4: Periode Tanggal -->
            <div class="card p-5 space-y-2">
                <div class="flex items-center justify-between">
                    <span class="text-[10px] font-sans uppercase tracking-widest font-bold" style="color:#6E675F;">PERIODE SURVEY</span>
                    <div class="w-8 h-8 rounded-xl flex items-center justify-center" style="background:#F8F4EC; border:1px solid #E5E0D8;">
                        <svg class="w-4 h-4" style="color:#C89D54;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                </div>
                <div class="text-xs font-bold text-ink">
                    {{ $survey->tanggal_mulai ? \Carbon\Carbon::parse($survey->tanggal_mulai)->format('d M Y') : 'Mulai Baru' }}
                    –
                    {{ $survey->tanggal_selesai ? \Carbon\Carbon::parse($survey->tanggal_selesai)->format('d M Y') : 'Fleksibel' }}
                </div>
                <p class="text-xs" style="color:#A19A91;">Rentang aktif survey</p>
            </div>
        </div>

        <!-- Question Overview Cards Grid -->
        <div class="space-y-6">
            @foreach($analytics as $index => $item)
                @php $q = $item['question']; @endphp
                <div class="card p-5 sm:p-6 space-y-4">
                    <div class="flex items-start justify-between gap-4 pb-3 border-b" style="border-color:#E5E0D8;">
                        <div class="flex items-center gap-3">
                            <span class="w-7 h-7 rounded-lg flex items-center justify-center font-bold text-xs text-white" style="background:#1C1917;">
                                {{ $index + 1 }}
                            </span>
                            <div>
                                <h3 class="font-bold text-base text-ink">{{ $q->teks_pertanyaan }}</h3>
                                <span class="text-[10px] uppercase font-bold px-2 py-0.5 rounded border inline-block mt-1"
                                      :class="getTypeBadgeClass('{{ $q->tipe_pertanyaan }}')">
                                    {{ $q->tipe_pertanyaan }}
                                </span>
                            </div>
                        </div>
                        <span class="text-xs font-semibold" style="color:#6E675F;">{{ $item['total_responses'] }} jawaban</span>
                    </div>

                    <!-- Single / Multiple Choice Distribution -->
                    @if(in_array($q->tipe_pertanyaan, ['SINGLE_CHOICE', 'MULTIPLE_CHOICE']))
                        <div class="space-y-2.5">
                            @php
                                $total = array_sum($item['counts']);
                            @endphp
                            @foreach($item['counts'] as $optName => $optCount)
                                @php $pct = $total > 0 ? round(($optCount / $total) * 100, 1) : 0; @endphp
                                <div>
                                    <div class="flex justify-between text-xs font-semibold mb-1">
                                        <span class="text-ink">{{ $optName }}</span>
                                        <span style="color:#6E675F;">{{ $optCount }} Responden ({{ $pct }}%)</span>
                                    </div>
                                    <div class="w-full bg-stone-100 rounded-full h-2.5 overflow-hidden">
                                        <div class="bg-amber-600 h-2.5 rounded-full transition-all duration-500" style="width: {{ $pct }}%"></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                    <!-- Likert Score Breakdown -->
                    @elseif($q->tipe_pertanyaan === 'LIKERT')
                        <div class="space-y-2">
                            <span class="text-[10px] font-bold uppercase tracking-wider block" style="color:#C89D54;">DISTRIBUSI FREKUENSI MENTAH SKOR LIKERT</span>
                            <div class="space-y-2">
                                @php $likertLabels = ['5' => '5 (Sangat Setuju / Sangat Puas)', '4' => '4 (Setuju / Puas)', '3' => '3 (Netral / Cukup)', '2' => '2 (Tidak Setuju / Kurang)', '1' => '1 (Sangat Tidak Setuju)']; @endphp
                                @foreach($item['counts'] as $scoreDigit => $scoreCount)
                                    @php $pct = $item['total_responses'] > 0 ? round(($scoreCount / $item['total_responses']) * 100, 1) : 0; @endphp
                                    <div class="flex items-center text-xs gap-3">
                                        <span class="w-48 font-bold text-ink">{{ $likertLabels[$scoreDigit] ?? "Skor $scoreDigit" }}</span>
                                        <div class="flex-1 bg-stone-100 rounded-full h-2 overflow-hidden">
                                            <div class="bg-emerald-600 h-2 rounded-full" style="width: {{ $pct }}%"></div>
                                        </div>
                                        <span class="w-24 text-right font-mono font-bold" style="color:#C89D54;">{{ $scoreCount }} Responden ({{ $pct }}%)</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                    <!-- WTP Price Stats -->
                    @elseif($q->tipe_pertanyaan === 'NUMBER')
                        <div class="space-y-3">
                            <div class="flex items-center justify-between text-xs font-bold" style="color:#A19A91;">
                                <span>DISTRIBUSI HARGA MENTAH (WTP)</span>
                                <span>Rentang: Rp {{ number_format($item['stats']['min'], 0, ',', '.') }} – Rp {{ number_format($item['stats']['max'], 0, ',', '.') }}</span>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-2.5">
                                @foreach($item['counts'] as $pKey => $pCount)
                                    @php $pct = $item['total_responses'] > 0 ? round(($pCount / $item['total_responses']) * 100, 1) : 0; @endphp
                                    <div class="p-3 rounded-xl border flex items-center justify-between text-xs" style="background:#EDFAF2; border-color:#A3D9BA;">
                                        <span class="font-bold text-emerald-950">{{ $pKey }}</span>
                                        <span class="font-bold text-emerald-800">{{ $pCount }} Responden ({{ $pct }}%)</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                    <!-- Text Responses Sample -->
                    @else
                        <div class="space-y-2">
                            <span class="text-[10px] font-bold uppercase tracking-widest block" style="color:#A19A91;">SAMPEL JAWABAN KONSUMEN TERKUMPUL</span>
                            <div class="space-y-1.5 max-h-48 overflow-y-auto pr-1">
                                @forelse(array_slice($item['responses'] ?? [], 0, 6) as $txt)
                                    <div class="p-2.5 rounded-lg border text-xs font-medium" style="background:#FAF8F5; border-color:#E5E0D8; color:#1C1917;">
                                        "{{ $txt }}"
                                    </div>
                                @empty
                                    <p class="text-xs text-stone-400 italic">Belum ada tanggapan teks terkumpul.</p>
                                @endforelse
                            </div>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>


    <!-- ================================================================= -->
    <!-- VIEW MODE 2: DOKUMEN REKOMENDASI & ANALISA AI LENGKAP             -->
    <!-- ================================================================= -->
    <div x-show="activeView === 'AI_ANALYTICS'" x-transition class="space-y-6">

        <!-- AI Executive Document Card -->
        <div class="card p-6 sm:p-8 space-y-6 border" style="background:#FFFFFF; border-color:#E5E0D8;">
            
            <!-- Document Header -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b" style="border-color:#E5E0D8;">
                <div>
                    <div class="flex items-center gap-2 mb-1">
                        <span class="px-2.5 py-0.5 rounded-md text-[11px] font-semibold border flex items-center gap-1.5"
                              style="background:#EDFAF2; border-color:#A3E6CD; color:#1A5C38;">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                            <span>gemini-3.5-flash</span>
                        </span>
                        <span class="text-[10px] font-mono uppercase tracking-wider text-stone-400">STATUS: TERDOKUMENTASI</span>
                    </div>
                    <h2 class="font-serif font-bold text-2xl text-ink">Dokumen Rekomendasi Strategi Kewirausahaan</h2>
                    <p class="text-xs mt-1" style="color:#6E675F;">Berdasarkan evaluasi terisolasi dari {{ $totalRespondents }} data responden terverifikasi</p>
                </div>
                <div class="flex items-center gap-2">
                    <button type="button" @click="regenerateAi()" :disabled="aiLoading"
                            class="px-4 py-2.5 rounded-xl border text-xs font-bold transition flex items-center gap-2 cursor-pointer disabled:opacity-50"
                            style="background:#FAF8F5; border-color:#E5E0D8; color:#1C1917;">
                        <span x-show="!aiLoading">⚡ Generate Ulang AI</span>
                        <span x-show="aiLoading" class="animate-spin">⌛ Memproses...</span>
                    </button>
                </div>
            </div>

            <!-- Loading State Banner -->
            <div x-show="aiLoading" x-transition
                 class="p-4 rounded-xl border text-xs font-semibold flex items-center justify-between gap-3 animate-pulse"
                 style="background:#FAF6EE; border-color:#E5E0D8; color:#C89D54;">
                <div class="flex items-center gap-2">
                    <span class="text-sm">⚡</span>
                    <span>Gemini AI (gemini-3.5-flash) sedang menganalisis {{ $totalRespondents }} data responden dan menyusun dokumen rekomendasi...</span>
                </div>
                <span class="text-[10px] font-mono uppercase tracking-wider">Memproses JSON...</span>
            </div>

            <!-- SECTION 1: RINGKASAN EKSEKUTIF & SENTIMEN -->
            <div class="space-y-4">
                <div class="flex items-center gap-2 text-xs font-bold tracking-widest uppercase" style="color:#C89D54;">
                    <span>● BAGIAN I</span>
                    <span class="w-8 h-px bg-amber-300"></span>
                    <span>RINGKASAN EKSEKUTIF & SENTIMEN PASAR</span>
                </div>
                <div class="p-5 rounded-2xl border space-y-3" style="background:#FAF6EE; border-color:#E5E0D8;">
                    <p class="text-sm sm:text-base text-stone-800 leading-relaxed font-medium" x-text="aiAnalysis.ringkasan_eksekutif"></p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                    <div class="p-4 rounded-xl border space-y-1.5" style="background:#FAF8F5; border-color:#E5E0D8;">
                        <span class="text-[10px] font-bold uppercase tracking-wider block" style="color:#A19A91;">TINGKAT MINAT KONSUMEN</span>
                        <div class="text-lg font-bold text-emerald-800" x-text="aiAnalysis.analisis_sentimen ? aiAnalysis.analisis_sentimen.tingkat_minat : 'Tinggi (Sangat Positif)'"></div>
                    </div>
                    <div class="p-4 rounded-xl border space-y-1.5" style="background:#FAF8F5; border-color:#E5E0D8;">
                        <span class="text-[10px] font-bold uppercase tracking-wider block" style="color:#A19A91;">FAKTOR PENDORONG SENTIMEN</span>
                        <p class="text-xs text-stone-700 font-medium leading-relaxed" x-text="aiAnalysis.analisis_sentimen ? aiAnalysis.analisis_sentimen.penjelasan : 'Penjelasan sentimen konsumen...'"></p>
                    </div>
                </div>
            </div>

            <!-- SECTION 2: STRATEGI HARGA WTP & PROFIT MARGIN -->
            <div class="space-y-4 pt-4 border-t" style="border-color:#F4F0E8;">
                <div class="flex items-center gap-2 text-xs font-bold tracking-widest uppercase" style="color:#C89D54;">
                    <span>● BAGIAN II</span>
                    <span class="w-8 h-px bg-amber-300"></span>
                    <span>ESTIMASI HARGA (WTP) & MARGIN KEUNTUNGAN</span>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                    <div class="md:col-span-5 p-5 rounded-2xl border flex flex-col justify-center space-y-2" style="background:#EDFAF2; border-color:#A3D9BA;">
                        <span class="text-[10px] font-bold uppercase tracking-widest" style="color:#1A5C38;">SWEET SPOT HARGA JUAL</span>
                        <div class="text-3xl font-serif font-bold text-emerald-900" x-text="aiAnalysis.strategi_harga_wtp ? aiAnalysis.strategi_harga_wtp.sweet_spot_harga : 'Rp 25.000'"></div>
                        <span class="text-[11px] font-medium text-emerald-700">Rekomendasi harga optimal berpatokan pada sebaran WTP responden</span>
                    </div>
                    <div class="md:col-span-7 p-5 rounded-2xl border space-y-2" style="background:#FAF8F5; border-color:#E5E0D8;">
                        <span class="text-[10px] font-bold uppercase tracking-widest block" style="color:#A19A91;">REKOMENDASI MARGIN & HPP</span>
                        <p class="text-xs sm:text-sm text-stone-700 font-medium leading-relaxed" x-text="aiAnalysis.strategi_harga_wtp ? aiAnalysis.strategi_harga_wtp.rekomendasi_margin : 'Rekomendasi margin kotor 30-35%...'"></p>
                    </div>
                </div>
            </div>

            <!-- SECTION 3: REKOMENDASI FITUR & KUALITAS PRODUK -->
            <div class="space-y-4 pt-4 border-t" style="border-color:#F4F0E8;">
                <div class="flex items-center gap-2 text-xs font-bold tracking-widest uppercase" style="color:#C89D54;">
                    <span>● BAGIAN III</span>
                    <span class="w-8 h-px bg-amber-300"></span>
                    <span>REKOMENDASI MUTU & KEMASAN PRODUK</span>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <template x-for="(rec, idx) in (aiAnalysis.rekomendasi_produk || [])" :key="idx">
                        <div class="p-4 rounded-xl border flex items-start gap-3" style="background:#FAF8F5; border-color:#E5E0D8;">
                            <span class="w-6 h-6 rounded-lg flex items-center justify-center font-bold text-xs text-white shrink-0" style="background:#1C1917;" x-text="idx + 1"></span>
                            <p class="text-xs text-stone-800 font-medium leading-relaxed" x-text="rec"></p>
                        </div>
                    </template>
                </div>
            </div>

            <!-- SECTION 4: STRATEGI PEMASARAN & SALURAN DISTRIBUSI -->
            <div class="space-y-4 pt-4 border-t" style="border-color:#F4F0E8;">
                <div class="flex items-center gap-2 text-xs font-bold tracking-widest uppercase" style="color:#C89D54;">
                    <span>● BAGIAN IV</span>
                    <span class="w-8 h-px bg-amber-300"></span>
                    <span>STRATEGI PEMASARAN & PROMOSI KREATIF</span>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <template x-for="(mkt, idx) in (aiAnalysis.strategi_pemasaran || [])" :key="idx">
                        <div class="p-4 rounded-xl border flex items-start gap-3" style="background:#FAF8F5; border-color:#E5E0D8;">
                            <span class="w-6 h-6 rounded-lg flex items-center justify-center font-bold text-xs text-white shrink-0" style="background:#C89D54;" x-text="idx + 1"></span>
                            <p class="text-xs text-stone-800 font-medium leading-relaxed" x-text="mkt"></p>
                        </div>
                    </template>
                </div>
            </div>

            <!-- SECTION 5: ACTION PLAN EKSEKUSI OPERASIONAL -->
            <div class="space-y-4 pt-4 border-t" style="border-color:#F4F0E8;">
                <div class="flex items-center gap-2 text-xs font-bold tracking-widest uppercase" style="color:#C89D54;">
                    <span>● BAGIAN V</span>
                    <span class="w-8 h-px bg-amber-300"></span>
                    <span>ACTION PLAN TAHAPAN EKSEKUSI OPERASIONAL</span>
                </div>
                <div class="space-y-2.5">
                    <template x-for="(act, idx) in (aiAnalysis.action_plan || [])" :key="idx">
                        <div class="p-3.5 rounded-xl border flex items-center gap-3" style="background:#EDFAF2; border-color:#A3D9BA;">
                            <span class="w-2 h-2 rounded-full bg-emerald-500 shrink-0"></span>
                            <p class="text-xs font-semibold text-emerald-950" x-text="act"></p>
                        </div>
                    </template>
                </div>
            </div>

        </div>
    </div>


    <!-- ================================================================= -->
    <!-- VIEW MODE 3: PERTANYAAN DETIL (DATA MENTAH 100% TANPA RATA-RATA) -->
    <!-- ================================================================= -->
    <div x-show="activeView === 'QUESTIONS'" x-transition class="space-y-6">
        @foreach($analytics as $index => $item)
            @php $q = $item['question']; @endphp
            <div class="card p-5 sm:p-6 space-y-4">
                <div class="flex items-start justify-between gap-4 pb-3 border-b" style="border-color:#E5E0D8;">
                    <div class="flex items-center gap-3">
                        <span class="w-7 h-7 rounded-lg flex items-center justify-center font-bold text-xs text-white" style="background:#1C1917;">
                            {{ $index + 1 }}
                        </span>
                        <div>
                            <h3 class="font-bold text-base text-ink">{{ $q->teks_pertanyaan }}</h3>
                            <span class="text-[10px] uppercase font-bold px-2 py-0.5 rounded border inline-block mt-1"
                                  :class="getTypeBadgeClass('{{ $q->tipe_pertanyaan }}')">
                                {{ $q->tipe_pertanyaan }}
                            </span>
                        </div>
                    </div>
                    <span class="text-xs font-semibold" style="color:#6E675F;">{{ $item['total_responses'] }} responden</span>
                </div>

                <!-- Single / Multiple Choice Raw Distribution -->
                @if(in_array($q->tipe_pertanyaan, ['SINGLE_CHOICE', 'MULTIPLE_CHOICE']))
                    <div class="space-y-2">
                        @php $total = array_sum($item['counts']); @endphp
                        @foreach($item['counts'] as $optName => $optCount)
                            @php $pct = $total > 0 ? round(($optCount / $total) * 100, 1) : 0; @endphp
                            <div class="flex items-center justify-between p-3 rounded-xl border text-xs font-medium" style="background:#FAF8F5; border-color:#E5E0D8;">
                                <span class="text-ink font-semibold">{{ $optName }}</span>
                                <span class="font-bold" style="color:#C89D54;">{{ $optCount }} Responden ({{ $pct }}%)</span>
                            </div>
                        @endforeach
                    </div>

                <!-- Likert Raw Distribution (Strictly No Averages) -->
                @elseif($q->tipe_pertanyaan === 'LIKERT')
                    <div class="space-y-2.5">
                        <span class="text-[10px] font-bold uppercase tracking-widest block" style="color:#C89D54;">DISTRIBUSI FREKUENSI MENTAH SKOR LIKERT</span>
                        <div class="space-y-2">
                            @php $likertLabels = ['5' => '5 (Sangat Setuju / Sangat Puas)', '4' => '4 (Setuju / Puas)', '3' => '3 (Netral / Cukup)', '2' => '2 (Tidak Setuju / Kurang)', '1' => '1 (Sangat Tidak Setuju)']; @endphp
                            @foreach($item['counts'] as $scoreDigit => $scoreCount)
                                @php $pct = $item['total_responses'] > 0 ? round(($scoreCount / $item['total_responses']) * 100, 1) : 0; @endphp
                                <div class="p-3 rounded-xl border flex items-center justify-between text-xs font-medium" style="background:#FAF8F5; border-color:#E5E0D8;">
                                    <span class="text-ink font-semibold">{{ $likertLabels[$scoreDigit] ?? "Skor $scoreDigit" }}</span>
                                    <span class="font-bold" style="color:#C89D54;">{{ $scoreCount }} Responden ({{ $pct }}%)</span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                <!-- Number / WTP Raw Price Breakdown (Strictly No Averages) -->
                @elseif($q->tipe_pertanyaan === 'NUMBER')
                    <div class="space-y-3">
                        <div class="flex items-center justify-between text-xs font-bold" style="color:#1A5C38;">
                            <span>DISTRIBUSI VARIAN HARGA MENTAH (WTP)</span>
                            <span>Rentang: Rp {{ number_format($item['stats']['min'], 0, ',', '.') }} – Rp {{ number_format($item['stats']['max'], 0, ',', '.') }}</span>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-2.5">
                            @foreach($item['counts'] as $pKey => $pCount)
                                @php $pct = $item['total_responses'] > 0 ? round(($pCount / $item['total_responses']) * 100, 1) : 0; @endphp
                                <div class="p-3 rounded-xl border flex items-center justify-between text-xs" style="background:#EDFAF2; border-color:#A3D9BA;">
                                    <span class="font-bold text-emerald-950">{{ $pKey }}</span>
                                    <span class="font-bold text-emerald-800">{{ $pCount }} Responden ({{ $pct }}%)</span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                <!-- Text Answers Raw List -->
                @else
                    <div class="space-y-2">
                        <span class="text-[10px] font-bold uppercase tracking-widest block" style="color:#A19A91;">DAFTAR JAWABAN TEKS MENTAH RESPONDEN</span>
                        <div class="space-y-2">
                            @forelse($item['responses'] ?? [] as $respTxt)
                                <div class="p-3 rounded-xl border text-xs font-medium" style="background:#FAF8F5; border-color:#E5E0D8;">
                                    "{{ $respTxt }}"
                                </div>
                            @empty
                                <p class="text-xs text-stone-400 italic">Belum ada jawaban terkumpul.</p>
                            @endforelse
                        </div>
                    </div>
                @endif
            </div>
        @endforeach
    </div>


    <!-- ================================================================= -->
    <!-- VIEW MODE 4: DETAIL JAWABAN INDIVIDUAL                            -->
    <!-- ================================================================= -->
    <div x-show="activeView === 'INDIVIDUAL'" x-transition class="space-y-4">
        <!-- Search Bar -->
        <div class="card p-4 flex items-center gap-3">
            <svg class="w-4 h-4" style="color:#A19A91;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            <input type="text" x-model="searchNisn" placeholder="Cari berdasarkan NISN responden..."
                   class="w-full text-xs font-medium focus:outline-none text-ink bg-transparent">
        </div>

        <!-- Table -->
        <div class="card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="uppercase font-sans font-bold text-[10px] tracking-wider border-b" style="background:#FAF8F5; border-color:#E5E0D8; color:#6E675F;">
                        <tr>
                            <th class="p-4">No</th>
                            <th class="p-4">NISN Responden</th>
                            <th class="p-4">Waktu Mengisi</th>
                            <th class="p-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y font-medium text-ink" style="border-color:#F4F0E8;">
                        @forelse($respondents as $idx => $resp)
                            <tr x-show="!searchNisn || '{{ $resp->nisn }}'.includes(searchNisn)" class="hover:bg-amber-500/5 transition">
                                <td class="p-4 font-mono text-stone-400">{{ $idx + 1 }}</td>
                                <td class="p-4 font-bold text-ink">{{ $resp->nisn }}</td>
                                <td class="p-4 text-stone-500">{{ $resp->submitted_at ? \Carbon\Carbon::parse($resp->submitted_at)->format('d M Y, H:i') : '-' }}</td>
                                <td class="p-4 text-right flex items-center justify-end gap-2">
                                    <button type="button" @click="openDetail('{{ $resp->id }}', '{{ $resp->nisn }}', {{ json_encode($resp->answers) }})"
                                            class="px-3 py-1.5 rounded-lg border font-bold text-[11px] transition"
                                            style="background:#FAF8F5; border-color:#E5E0D8; color:#1C1917;">
                                        Lihat Jawaban
                                    </button>
                                    <form method="POST" action="{{ route('admin.respondents.destroy', $resp->id) }}" onsubmit="return confirm('Hapus responden NISN {{ $resp->nisn }}?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 rounded-lg border text-red-600 hover:bg-red-50 transition" style="border-color:#F5B7B1;">
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="p-8 text-center text-stone-400 italic">Belum ada data responden terkumpul.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- ── Modal Detail Individual ────────────────────────────────────── -->
    <div x-show="detailOpen" x-transition.opacity class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-xs" style="display:none;">
        <div @click.away="detailOpen = false" class="card w-full max-w-2xl p-6 space-y-4 max-h-[85vh] overflow-y-auto" style="background:#FFFFFF;">
            <div class="flex items-center justify-between pb-3 border-b" style="border-color:#E5E0D8;">
                <div>
                    <span class="text-[10px] font-bold uppercase tracking-widest block" style="color:#C89D54;">DETAIL JAWABAN INDIVIDUAL</span>
                    <h3 class="font-bold text-lg text-ink">Responden NISN: <span x-text="detailNisn"></span></h3>
                </div>
                <button type="button" @click="detailOpen = false" class="p-2 text-stone-400 hover:text-stone-700">✕</button>
            </div>
            <div class="space-y-3">
                <template x-for="(ans, i) in detailAnswers" :key="i">
                    <div class="p-3.5 rounded-xl border space-y-1" style="background:#FAF8F5; border-color:#E5E0D8;">
                        <span class="text-[10px] font-bold uppercase tracking-wider block" style="color:#A19A91;" x-text="'Jawaban Pertanyaan ID: ' + ans.question_id"></span>
                        <p class="text-xs font-semibold text-ink" x-text="ans.jawaban || '(Tidak Diisi)'"></p>
                    </div>
                </template>
            </div>
            <div class="pt-3 border-t text-right" style="border-color:#E5E0D8;">
                <button type="button" @click="detailOpen = false" class="px-4 py-2 font-bold text-xs rounded-xl border" style="background:#1C1917; color:#FFFFFF;">Tutup</button>
            </div>
        </div>
    </div>

</div>
@endsection

@section('scripts')
<script>
function analyticsApp() {
    return {
        activeView: '{{ request("tab", "SUMMARY") }}', // 'SUMMARY' | 'AI_ANALYTICS' | 'QUESTIONS' | 'INDIVIDUAL'
        aiAnalysis: @json($aiAnalysis),
        aiLoading: false,
        searchNisn: '',
        detailOpen: false,
        detailNisn: '',
        detailAnswers: [],

        async regenerateAi() {
            this.aiLoading = true;
            try {
                const res = await fetch(`/admin/ai-analytics/{{ $survey->id }}/generate`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });
                const contentType = res.headers.get('content-type') || '';
                if (!contentType.includes('application/json')) {
                    throw new Error('Server error HTTP ' + res.status);
                }
                const data = await res.json();
                if (data.success && data.analysis) {
                    this.aiAnalysis = data.analysis;
                } else {
                    throw new Error(data.message || 'Gagal memproses AI.');
                }
            } catch (e) {
                alert('Gagal meregenerasi data AI: ' + e.message);
            } finally {
                this.aiLoading = false;
            }
        },

        getTypeBadgeClass(type) {
            const styles = {
                'SINGLE_CHOICE':   'bg-blue-500/10 text-blue-800 border-blue-200 font-bold',
                'MULTIPLE_CHOICE': 'bg-purple-500/10 text-purple-800 border-purple-200 font-bold',
                'LIKERT':          'bg-amber-500/10 text-amber-800 border-amber-200 font-bold',
                'NUMBER':          'bg-emerald-500/10 text-emerald-800 border-emerald-200 font-bold',
                'SHORT_TEXT':      'bg-sky-500/10 text-sky-800 border-sky-200 font-bold',
                'LONG_TEXT':       'bg-teal-500/10 text-teal-800 border-teal-200 font-bold',
                'IMAGE_UPLOAD':    'bg-rose-500/10 text-rose-800 border-rose-200 font-bold',
                'DATE':            'bg-indigo-500/10 text-indigo-800 border-indigo-200 font-bold'
            };
            return styles[type] || 'bg-stone-100 text-stone-800 border-stone-200 font-bold';
        },

        openDetail(id, nisn, answers) {
            this.detailNisn = nisn;
            try {
                this.detailAnswers = typeof answers === 'string' ? JSON.parse(answers) : answers;
            } catch(e) {
                this.detailAnswers = [];
            }
            this.detailOpen = true;
        }
    }
}
</script>
@endsection
