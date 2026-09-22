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
        <template x-if="aiAnalysis && aiAnalysis.ringkasan_eksekutif">
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
                            <span class="font-bold text-base text-ink" x-text="aiAnalysis.strategi_harga_wtp ? aiAnalysis.strategi_harga_wtp.sweet_spot_harga : 'Rp 0'"></span>
                        </div>
                        <div class="pt-2 border-t" style="border-color:#F4F0E8;">
                            <span class="text-[10px] font-bold uppercase tracking-wider block" style="color:#A19A91;">TINGKAT MINAT KONSUMEN</span>
                            <span class="font-bold text-sm text-emerald-700" x-text="aiAnalysis.analisis_sentimen ? aiAnalysis.analisis_sentimen.tingkat_minat : 'Belum Dihitung'"></span>
                        </div>
                    </div>
                </div>
            </div>
        </template>

        <template x-if="!aiAnalysis || !aiAnalysis.ringkasan_eksekutif">
            <div class="card p-5 sm:p-6 flex flex-col sm:flex-row items-center justify-between gap-4 border" style="background:#FAF6EE; border-color:#E5E0D8;">
                <div class="flex items-center gap-3">
                    <div class="p-2.5 rounded-xl bg-amber-500/10 text-amber-800 text-xl font-bold">✨</div>
                    <div>
                        <span class="text-[10px] font-bold uppercase tracking-widest block" style="color:#C89D54;">INTELIJEN AI & REKOMENDASI PASAR</span>
                        <h3 class="font-serif font-bold text-base text-ink">Dokumen Analisa AI Belum Dibuat</h3>
                        <p class="text-xs text-stone-600 mt-0.5">Analisis rekomendasi bisnis & WTP dari {{ $totalRespondents }} responden belum di-generate.</p>
                    </div>
                </div>
                <button type="button" @click="activeView = 'AI_ANALYTICS'; regenerateAi();"
                        class="px-4 py-2.5 text-xs font-bold rounded-xl text-white transition shadow-xs whitespace-nowrap cursor-pointer flex items-center gap-2"
                        style="background:#C89D54;">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    ✨ Generate Analisa AI
                </button>
            </div>
        </template>

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

        <!-- Question Overview Cards Grid with Beautiful Interactive Graphs -->
        @php $chartsConfig = []; @endphp
        <div class="space-y-6">
            @foreach($analytics as $index => $item)
                @php
                    $q = $item['question'];
                    $totalResp = $item['total_responses'];
                @endphp
                <div class="card p-5 sm:p-6 space-y-5 border" style="background:#FFFFFF; border-color:#E5E0D8;">
                    <!-- Question Card Header -->
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-4 border-b" style="border-color:#E5E0D8;">
                        <div class="flex items-start gap-3">
                            <span class="w-7 h-7 rounded-lg flex items-center justify-center font-bold text-xs text-white shrink-0 mt-0.5" style="background:#1C1917;">
                                {{ $index + 1 }}
                            </span>
                            <div>
                                <h3 class="font-bold text-base text-ink leading-snug">{{ $q->teks_pertanyaan }}</h3>
                                @if(!empty($q->deskripsi_pertanyaan))
                                    <p class="text-xs text-stone-500 mt-0.5">{{ $q->deskripsi_pertanyaan }}</p>
                                @endif
                                <div class="flex items-center gap-2 mt-1.5 flex-wrap">
                                    <span class="text-[10px] uppercase font-bold px-2.5 py-0.5 rounded-md border"
                                          :class="getTypeBadgeClass('{{ $q->tipe_pertanyaan }}')">
                                        {{ $q->tipe_pertanyaan }}
                                    </span>
                                    @if($q->wajib_diisi)
                                        <span class="text-[10px] font-semibold px-2 py-0.5 rounded bg-rose-50 text-rose-700 border border-rose-200">Wajib Diisi</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 self-start sm:self-auto shrink-0">
                            <span class="text-xs font-bold px-3 py-1.5 rounded-lg border flex items-center gap-1.5" style="background:#FAF8F5; border-color:#E5E0D8; color:#1C1917;">
                                <svg class="w-3.5 h-3.5 text-stone-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                <span>{{ $totalResp }} Responden</span>
                            </span>
                        </div>
                    </div>

                    @if($totalResp === 0)
                        <!-- Empty Responses State -->
                        <div class="p-8 rounded-xl border border-dashed text-center space-y-2" style="background:#FAF8F5; border-color:#E5E0D8;">
                            <span class="text-2xl block">📊</span>
                            <p class="text-xs font-bold text-stone-700">Belum Ada Respon Terkumpul</p>
                            <p class="text-[11px] text-stone-500">Grafik visualisasi otomatis akan muncul saat responden mulai mengisi pertanyaan ini.</p>
                        </div>

                    {{-- ── 1. SINGLE CHOICE QUESTION GRAPH ────────────────────────── --}}
                    @elseif($q->tipe_pertanyaan === 'SINGLE_CHOICE')
                        @php
                            $totalVotes = array_sum($item['counts'] ?? []);
                            $sortedCounts = $item['counts'] ?? [];
                            arsort($sortedCounts);
                            $topChoice = !empty($sortedCounts) && $totalVotes > 0 ? array_key_first($sortedCounts) : null;
                            $topChoicePct = $topChoice && $totalVotes > 0 ? round(($sortedCounts[$topChoice] / $totalVotes) * 100, 1) : 0;
                            $labels = array_keys($sortedCounts);
                            $dataVals = array_values($sortedCounts);
                            $chartsConfig[$q->id] = [
                                'kind' => 'SINGLE_CHOICE',
                                'defaultType' => 'doughnut',
                                'labels' => $labels,
                                'data' => $dataVals,
                            ];
                        @endphp
                        <div class="space-y-4">
                            <!-- Highlight Bar & Chart Type Toggle -->
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 p-3 rounded-xl border text-xs" style="background:#FAF8F5; border-color:#E5E0D8;">
                                <div class="flex items-center gap-2">
                                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                                    <span class="font-bold text-ink">🏆 Pilihan Terbanyak:</span>
                                    <span class="font-semibold text-emerald-800">"{{ $topChoice }}" ({{ $sortedCounts[$topChoice] ?? 0 }} responden &bull; {{ $topChoicePct }}%)</span>
                                </div>
                                <div id="toggle-{{ $q->id }}" class="flex items-center gap-1 bg-white p-1 rounded-lg border border-stone-200 shrink-0 self-end sm:self-auto">
                                    <button type="button" data-chart-type="doughnut" onclick="toggleChart('{{ $q->id }}', 'doughnut')"
                                            class="px-2.5 py-1 rounded text-[11px] font-bold transition flex items-center gap-1 bg-stone-900 text-white shadow-xs">
                                        <span>🍩 Donat</span>
                                    </button>
                                    <button type="button" data-chart-type="bar" onclick="toggleChart('{{ $q->id }}', 'bar')"
                                            class="px-2.5 py-1 rounded text-[11px] font-bold transition flex items-center gap-1 text-stone-600 hover:text-stone-900">
                                        <span>📊 Batang</span>
                                    </button>
                                </div>
                            </div>

                            <!-- Chart Canvas & Breakdown Grid -->
                            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-center pt-2">
                                <div class="lg:col-span-6 flex flex-col items-center justify-center">
                                    <div class="relative w-full h-64">
                                        <canvas id="chart-{{ $q->id }}"></canvas>
                                    </div>
                                    <span class="text-[10px] text-stone-400 font-mono uppercase tracking-wider mt-2">Diagram Distribusi Proporsi Jawaban</span>
                                </div>

                                <div class="lg:col-span-6 space-y-2.5">
                                    @php
                                        $colorPalette = ['#C89D54', '#10B981', '#3B82F6', '#8B5CF6', '#F59E0B', '#EC4899', '#14B8A6', '#6366F1', '#E11D48', '#0D9488'];
                                    @endphp
                                    @foreach($sortedCounts as $optName => $optCount)
                                        @php
                                            $pct = $totalVotes > 0 ? round(($optCount / $totalVotes) * 100, 1) : 0;
                                            $c = $colorPalette[$loop->index % count($colorPalette)];
                                            $isTop = ($loop->first && $optCount > 0);
                                        @endphp
                                        <div class="p-3 rounded-xl border transition-all duration-200 {{ $isTop ? 'bg-amber-50/50 border-amber-300' : 'bg-white border-stone-200' }}">
                                            <div class="flex items-center justify-between text-xs mb-1.5">
                                                <div class="flex items-center gap-2 font-bold text-ink">
                                                    <span class="w-3 h-3 rounded-full shrink-0" style="background: {{ $c }};"></span>
                                                    <span>{{ $optName }}</span>
                                                    @if($isTop)
                                                        <span class="text-[10px] px-1.5 py-0.2 rounded font-bold bg-amber-500/20 text-amber-900">#1</span>
                                                    @endif
                                                </div>
                                                <span class="font-bold text-stone-800 tabular-nums">{{ $optCount }} Responden ({{ $pct }}%)</span>
                                            </div>
                                            <div class="w-full bg-stone-100 rounded-full h-2 overflow-hidden">
                                                <div class="h-2 rounded-full transition-all duration-500" style="width: {{ $pct }}%; background: {{ $c }};"></div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                    {{-- ── 2. MULTIPLE CHOICE QUESTION GRAPH ──────────────────────── --}}
                    @elseif($q->tipe_pertanyaan === 'MULTIPLE_CHOICE')
                        @php
                            $totalSelections = array_sum($item['counts'] ?? []);
                            $sortedCounts = $item['counts'] ?? [];
                            arsort($sortedCounts);
                            $topChoice = !empty($sortedCounts) && $totalSelections > 0 ? array_key_first($sortedCounts) : null;
                            $topChoicePct = $topChoice && $totalResp > 0 ? round(($sortedCounts[$topChoice] / $totalResp) * 100, 1) : 0;
                            $labels = array_keys($sortedCounts);
                            $dataVals = array_values($sortedCounts);
                            $chartsConfig[$q->id] = [
                                'kind' => 'MULTIPLE_CHOICE',
                                'defaultType' => 'bar',
                                'labels' => $labels,
                                'data' => $dataVals,
                                'totalResp' => max(1, $totalResp),
                            ];
                        @endphp
                        <div class="space-y-4">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">
                                <div class="p-3 rounded-xl border flex items-center gap-2.5" style="background:#FAF8F5; border-color:#E5E0D8;">
                                    <span class="text-base">⭐</span>
                                    <div>
                                        <span class="text-[10px] uppercase font-bold text-stone-400 block">PILIHAN TERFAVORIT</span>
                                        <span class="font-bold text-ink">"{{ $topChoice }}" ({{ $sortedCounts[$topChoice] ?? 0 }} responden &bull; {{ $topChoicePct }}%)</span>
                                    </div>
                                </div>
                                <div class="p-3 rounded-xl border flex items-center gap-2.5" style="background:#FAF8F5; border-color:#E5E0D8;">
                                    <span class="text-base">☑️</span>
                                    <div>
                                        <span class="text-[10px] uppercase font-bold text-stone-400 block">TOTAL OPSI DIPILIH</span>
                                        <span class="font-bold text-ink">{{ $totalSelections }} centang (Rata-rata {{ $totalResp > 0 ? round($totalSelections / $totalResp, 1) : 0 }} opsi/responden)</span>
                                    </div>
                                </div>
                            </div>

                            <div class="p-4 rounded-xl border bg-white" style="border-color:#E5E0D8;">
                                <div class="relative w-full" style="height: {{ max(200, count($labels) * 45) }}px;">
                                    <canvas id="chart-{{ $q->id }}"></canvas>
                                </div>
                                <div class="flex items-center justify-between text-[11px] text-stone-400 pt-3 border-t mt-3" style="border-color:#F4F0E8;">
                                    <span>Grafik Batang Horizontal Frekuensi Pilihan (Urutan Tertinggi ke Terendah)</span>
                                    <span>Responden dapat memilih lebih dari satu opsi</span>
                                </div>
                            </div>
                        </div>

                    {{-- ── 3. LIKERT SCALE QUESTION GRAPH ────────────────────────── --}}
                    @elseif($q->tipe_pertanyaan === 'LIKERT')
                        @php
                            $avgScore = $item['avg_score'] ?? 0;
                            $positivePct = $item['positive_pct'] ?? 0;
                            $stars = round($avgScore);
                            $chartsConfig[$q->id] = [
                                'kind' => 'LIKERT',
                                'defaultType' => 'bar',
                                'labels' => ['1 (Sangat Tidak Setuju)', '2 (Tidak Setuju)', '3 (Netral)', '4 (Setuju)', '5 (Sangat Setuju)'],
                                'data' => [
                                    $item['counts']['1'] ?? 0,
                                    $item['counts']['2'] ?? 0,
                                    $item['counts']['3'] ?? 0,
                                    $item['counts']['4'] ?? 0,
                                    $item['counts']['5'] ?? 0,
                                ],
                            ];
                        @endphp
                        <div class="space-y-4">
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                <div class="p-3.5 rounded-xl border flex items-center justify-between" style="background:#FAF6EE; border-color:#E5E0D8;">
                                    <div>
                                        <span class="text-[10px] uppercase font-bold text-stone-500 block">SKOR RATA-RATA LIKERT</span>
                                        <div class="flex items-baseline gap-2 mt-0.5">
                                            <span class="font-serif font-bold text-2xl" style="color:#C89D54;">{{ number_format($avgScore, 2) }}</span>
                                            <span class="text-xs text-stone-500 font-bold">/ 5.0</span>
                                        </div>
                                    </div>
                                    <div class="text-amber-500 text-sm">
                                        @for($s=1; $s<=5; $s++)
                                            {{ $s <= $stars ? '★' : '☆' }}
                                        @endfor
                                    </div>
                                </div>

                                <div class="p-3.5 rounded-xl border flex items-center justify-between" style="background:#EDFAF2; border-color:#A3D9BA;">
                                    <div>
                                        <span class="text-[10px] uppercase font-bold text-emerald-800 block">SENTIMEN POSITIF (PUAS)</span>
                                        <div class="flex items-baseline gap-2 mt-0.5">
                                            <span class="font-serif font-bold text-2xl text-emerald-950">{{ $positivePct }}%</span>
                                            <span class="text-xs text-emerald-800 font-medium">(Skor 4 & 5)</span>
                                        </div>
                                    </div>
                                    <span class="w-8 h-8 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-700 font-bold text-xs">👍</span>
                                </div>

                                <div class="p-3.5 rounded-xl border flex items-center justify-between" style="background:#FAF8F5; border-color:#E5E0D8;">
                                    <div>
                                        <span class="text-[10px] uppercase font-bold text-stone-500 block">KATEGORI PENILAIAN</span>
                                        <div class="font-bold text-sm mt-1 text-ink">
                                            @if($avgScore >= 4.0)
                                                <span class="text-emerald-700">🟢 Sangat Positif</span>
                                            @elseif($avgScore >= 3.0)
                                                <span class="text-amber-700">🟡 Cukup Baik / Netral</span>
                                            @else
                                                <span class="text-rose-700">🔴 Perlu Evaluasi</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div id="toggle-{{ $q->id }}" class="flex items-center gap-1 bg-white p-1 rounded-lg border border-stone-200">
                                        <button type="button" data-chart-type="bar" onclick="toggleChart('{{ $q->id }}', 'bar')" class="px-2 py-0.5 rounded text-[10px] font-bold bg-stone-900 text-white shadow-xs">Bar</button>
                                        <button type="button" data-chart-type="doughnut" onclick="toggleChart('{{ $q->id }}', 'doughnut')" class="px-2 py-0.5 rounded text-[10px] font-bold text-stone-600">Donat</button>
                                    </div>
                                </div>
                            </div>

                            <div class="p-4 rounded-xl border bg-white" style="border-color:#E5E0D8;">
                                <div class="relative w-full h-64">
                                    <canvas id="chart-{{ $q->id }}"></canvas>
                                </div>
                                <div class="grid grid-cols-5 gap-2 pt-3 border-t mt-3 text-center text-xs">
                                    <div class="text-rose-700 font-semibold">1: {{ $item['counts']['1'] ?? 0 }} ({{ $totalResp > 0 ? round((($item['counts']['1'] ?? 0)/$totalResp)*100) : 0 }}%)</div>
                                    <div class="text-orange-700 font-semibold">2: {{ $item['counts']['2'] ?? 0 }} ({{ $totalResp > 0 ? round((($item['counts']['2'] ?? 0)/$totalResp)*100) : 0 }}%)</div>
                                    <div class="text-amber-700 font-semibold">3: {{ $item['counts']['3'] ?? 0 }} ({{ $totalResp > 0 ? round((($item['counts']['3'] ?? 0)/$totalResp)*100) : 0 }}%)</div>
                                    <div class="text-emerald-700 font-semibold">4: {{ $item['counts']['4'] ?? 0 }} ({{ $totalResp > 0 ? round((($item['counts']['4'] ?? 0)/$totalResp)*100) : 0 }}%)</div>
                                    <div class="text-emerald-900 font-bold">5: {{ $item['counts']['5'] ?? 0 }} ({{ $totalResp > 0 ? round((($item['counts']['5'] ?? 0)/$totalResp)*100) : 0 }}%)</div>
                                </div>
                            </div>
                        </div>

                    {{-- ── 4. NUMBER (WTP / ANGGARAN HARGA) GRAPH ────────────────── --}}
                    @elseif($q->tipe_pertanyaan === 'NUMBER')
                        @php
                            $stats = $item['stats'] ?? [];
                            $sweetSpot = $stats['sweet_spot'] ?? null;
                            $labels = array_keys($item['counts'] ?? []);
                            $dataVals = array_values($item['counts'] ?? []);
                            $chartsConfig[$q->id] = [
                                'kind' => 'NUMBER',
                                'defaultType' => 'bar',
                                'labels' => $labels,
                                'data' => $dataVals,
                                'sweetSpot' => $sweetSpot,
                            ];
                        @endphp
                        <div class="space-y-4">
                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                                <div class="p-3 rounded-xl border" style="background:#FAF8F5; border-color:#E5E0D8;">
                                    <span class="text-[10px] uppercase font-bold text-stone-500 block">HARGA MINIMUM</span>
                                    <span class="font-bold text-sm text-ink">Rp {{ number_format($stats['min'] ?? 0, 0, ',', '.') }}</span>
                                </div>
                                <div class="p-3 rounded-xl border" style="background:#FAF8F5; border-color:#E5E0D8;">
                                    <span class="text-[10px] uppercase font-bold text-stone-500 block">RATA-RATA (MEAN)</span>
                                    <span class="font-bold text-sm" style="color:#C89D54;">Rp {{ number_format($stats['avg'] ?? 0, 0, ',', '.') }}</span>
                                </div>
                                <div class="p-3 rounded-xl border" style="background:#FAF8F5; border-color:#E5E0D8;">
                                    <span class="text-[10px] uppercase font-bold text-stone-500 block">NILAI TENGAH (MEDIAN)</span>
                                    <span class="font-bold text-sm text-ink">Rp {{ number_format($stats['median'] ?? 0, 0, ',', '.') }}</span>
                                </div>
                                <div class="p-3 rounded-xl border" style="background:#FAF8F5; border-color:#E5E0D8;">
                                    <span class="text-[10px] uppercase font-bold text-stone-500 block">HARGA MAKSIMUM</span>
                                    <span class="font-bold text-sm text-ink">Rp {{ number_format($stats['max'] ?? 0, 0, ',', '.') }}</span>
                                </div>
                            </div>

                            @if(!empty($sweetSpot))
                                <div class="p-3 rounded-xl border flex items-center justify-between text-xs" style="background:#EDFAF2; border-color:#A3D9BA;">
                                    <div class="flex items-center gap-2">
                                        <span class="text-base">🔥</span>
                                        <span class="font-bold text-emerald-950">Titik Temu Konsumen Terbanyak (Sweet Spot WTP):</span>
                                        <span class="font-bold text-emerald-800 text-sm">{{ $sweetSpot }}</span>
                                    </div>
                                    <span class="font-semibold text-emerald-800">{{ $item['counts'][$sweetSpot] ?? 0 }} Pemilih</span>
                                </div>
                            @endif

                            <div class="p-4 rounded-xl border bg-white" style="border-color:#E5E0D8;">
                                <div class="relative w-full h-64">
                                    <canvas id="chart-{{ $q->id }}"></canvas>
                                </div>
                                <div class="flex items-center justify-between text-[11px] text-stone-400 pt-3 border-t mt-3" style="border-color:#F4F0E8;">
                                    <span>Distribusi Frekuensi Nominal Tarif/Harga Riil WTP</span>
                                    <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-emerald-500 inline-block"></span> Batang hijau menandakan sweet spot tarif</span>
                                </div>
                            </div>
                        </div>

                    {{-- ── 5. DATE QUESTION GRAPH ────────────────────────────────── --}}
                    @elseif($q->tipe_pertanyaan === 'DATE')
                        @php
                            $labels = array_keys($item['counts'] ?? []);
                            $dataVals = array_values($item['counts'] ?? []);
                            $chartsConfig[$q->id] = [
                                'kind' => 'DATE',
                                'defaultType' => 'bar',
                                'labels' => $labels,
                                'data' => $dataVals,
                            ];
                        @endphp
                        <div class="space-y-4">
                            <div class="p-4 rounded-xl border bg-white" style="border-color:#E5E0D8;">
                                <div class="relative w-full h-56">
                                    <canvas id="chart-{{ $q->id }}"></canvas>
                                </div>
                                <span class="text-[10px] text-stone-400 font-mono uppercase tracking-wider block mt-2 text-center">Kurva Distribusi Tanggal Rencana Transaksi</span>
                            </div>
                        </div>

                    {{-- ── 6. TEXT RESPONSES (WITH KEYWORD INSIGHTS) ──────────────── --}}
                    @elseif(in_array($q->tipe_pertanyaan, ['SHORT_TEXT', 'LONG_TEXT']))
                        <div class="space-y-3" x-data="{ filter: '' }">
                            @if(!empty($item['top_words']))
                                <div class="p-3.5 rounded-xl border space-y-2" style="background:#FAF8F5; border-color:#E5E0D8;">
                                    <span class="text-[10px] font-bold uppercase tracking-wider text-stone-500 block">💡 KATA KUNCI PALING SERING DISEBUT RESPONDEN:</span>
                                    <div class="flex flex-wrap gap-1.5">
                                        @foreach($item['top_words'] as $w => $freq)
                                            <span class="px-2.5 py-1 rounded-lg text-xs font-semibold border flex items-center gap-1.5 cursor-pointer hover:border-amber-400 transition"
                                                  @click="filter = filter === '{{ $w }}' ? '' : '{{ $w }}'"
                                                  :class="filter === '{{ $w }}' ? 'bg-stone-900 text-white' : 'bg-white text-stone-800 border-stone-200'">
                                                <span>#{{ $w }}</span>
                                                <span class="text-[10px] px-1 rounded-full" :class="filter === '{{ $w }}' ? 'bg-stone-700 text-white' : 'bg-stone-100 text-stone-600'">{{ $freq }}x</span>
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <div class="space-y-2">
                                <div class="flex items-center justify-between text-xs text-stone-500">
                                    <span>Koleksi Jawaban Narasi ({{ count($item['responses'] ?? []) }} tanggapan)</span>
                                    <span x-show="filter" class="font-bold text-amber-700">Memfilter: "<span x-text="filter"></span>" (<button type="button" @click="filter = ''" class="underline">reset</button>)</span>
                                </div>
                                <div class="space-y-2 max-h-60 overflow-y-auto pr-1">
                                    @foreach($item['responses'] ?? [] as $txt)
                                        <div x-show="!filter || '{{ strtolower(addslashes($txt)) }}'.includes(filter.toLowerCase())"
                                             class="p-3 rounded-xl border text-xs font-medium bg-white border-stone-200 text-ink leading-relaxed">
                                            "{{ $txt }}"
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                    {{-- ── 7. IMAGE UPLOAD RESPONSES ──────────────────────────────── --}}
                    @elseif($q->tipe_pertanyaan === 'IMAGE_UPLOAD')
                        <div class="space-y-3">
                            <span class="text-[10px] font-bold uppercase tracking-wider text-stone-500 block">GALERI BERKAS & GAMBAR UNGGAHAN RESPONDEN:</span>
                            <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-6 gap-3">
                                @forelse($item['responses'] ?? [] as $imgUrl)
                                    <a href="{{ $imgUrl }}" target="_blank" class="block aspect-square rounded-xl overflow-hidden border border-stone-200 hover:border-amber-500 transition group relative">
                                        <img src="{{ $imgUrl }}" alt="Upload Responden" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                                        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition flex items-center justify-center text-white text-xs font-bold">
                                            Lihat 🔍
                                        </div>
                                    </a>
                                @empty
                                    <p class="text-xs text-stone-400 italic col-span-full">Belum ada gambar yang diunggah.</p>
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

        <!-- EMPTY STATE CARD: BEFORE AI GENERATION -->
        <template x-if="(!aiAnalysis || !aiAnalysis.ringkasan_eksekutif) && !aiLoading">
            <div class="card p-8 sm:p-12 text-center space-y-5 border" style="background:#FFFFFF; border-color:#E5E0D8;">
                <div class="w-16 h-16 rounded-2xl mx-auto flex items-center justify-center text-3xl shadow-xs" style="background:#FAF6EE; border: 1px solid #E5E0D8; color:#C89D54;">
                    ✨
                </div>
                <div class="max-w-lg mx-auto space-y-2">
                    <h3 class="font-serif font-bold text-2xl text-ink">Dokumen Analisa AI Belum Dibuat</h3>
                    <p class="text-xs sm:text-sm text-stone-600 leading-relaxed font-medium">
                        Data survei dari <span class="font-bold text-stone-900">{{ $totalRespondents }} responden</span> telah terkumpul. Klik tombol di bawah ini untuk memulai analisa AI dalam mengevaluasi sentimen pasar, estimasi harga WTP, serta menyusun rekomendasi strategi kewirausahaan.
                    </p>
                </div>
                <div class="pt-2">
                    <button type="button" @click="regenerateAi()" :disabled="aiLoading"
                            class="px-6 py-3 rounded-xl font-bold text-sm text-white shadow-sm transition-all duration-200 inline-flex items-center gap-2 cursor-pointer disabled:opacity-50"
                            style="background:#C89D54;">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        ✨ Generate Analisa AI Sekarang
                    </button>
                </div>
            </div>
        </template>

        <!-- LOADING STATE BANNER BEFORE DOCUMENT -->
        <div x-show="aiLoading" x-transition
             class="card p-6 text-center space-y-4 border animate-pulse"
             style="background:#FAF6EE; border-color:#E5E0D8; color:#C89D54;">
            <div class="flex items-center justify-center gap-3 text-base font-bold">
                <svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span>Google Gemini AI sedang menganalisis {{ $totalRespondents }} data responden dan menyusun rekomendasi...</span>
            </div>
            <p class="text-xs text-stone-500 font-mono">Memproses Live Business Intelligence Prompt...</p>
        </div>

        <!-- AI Executive Document Card (POPULATED AFTER GENERATION) -->
        <template x-if="aiAnalysis && aiAnalysis.ringkasan_eksekutif">
            <div class="card p-6 sm:p-8 space-y-6 border" style="background:#FFFFFF; border-color:#E5E0D8;">
                
                <!-- Document Header -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b" style="border-color:#E5E0D8;">
                    <div>
                        <div class="flex items-center gap-2 mb-1">
                            <span class="px-2.5 py-0.5 rounded-md text-[11px] font-semibold border flex items-center gap-1.5"
                                  style="background:#EDFAF2; border-color:#A3E6CD; color:#1A5C38;">
                                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                <span x-text="aiAnalysis.model_used || (aiAnalysis.debug && aiAnalysis.debug.model) || 'gemini-2.5-flash'"></span>
                            </span>
                            <span class="text-[10px] font-mono uppercase tracking-wider text-stone-400">STATUS: TERDOKUMENTASI</span>
                        </div>
                        <h2 class="font-serif font-bold text-2xl text-ink">Dokumen Rekomendasi Strategi Kewirausahaan</h2>
                        <p class="text-xs mt-1" style="color:#6E675F;">Berdasarkan evaluasi terisolasi dari {{ $totalRespondents }} data responden terverifikasi</p>
                    </div>
                    <div class="flex items-center gap-2 flex-wrap">
                        <a href="{{ route('admin.surveys.print-ai', $survey->id) }}" target="_blank"
                           class="px-4 py-2.5 rounded-xl text-xs font-bold transition flex items-center gap-1.5 shadow-xs"
                           style="background:#1C1917; color:#FFFFFF;">
                            <svg class="w-3.5 h-3.5" style="color:#C89D54;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                            <span>Cetak / PDF</span>
                        </a>

                        <button type="button" @click="regenerateAi()" :disabled="aiLoading"
                                class="px-4 py-2.5 rounded-xl border text-xs font-bold transition flex items-center gap-2 cursor-pointer disabled:opacity-50"
                                style="background:#FAF8F5; border-color:#E5E0D8; color:#1C1917;">
                            <template x-if="!aiLoading">
                                <span class="flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5" style="color:#C89D54;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                    Generate Ulang AI
                                </span>
                            </template>
                            <template x-if="aiLoading">
                                <span class="flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5 animate-spin" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    Memproses...
                                </span>
                            </template>
                        </button>

                        <button type="button" @click="deleteAi()" :disabled="aiLoading"
                                class="px-3.5 py-2.5 rounded-xl border text-xs font-bold transition flex items-center gap-1.5 cursor-pointer disabled:opacity-50"
                                style="background:#FDECEA; border-color:#F5B7B1; color:#922B21;">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            <span>Hapus Hasil</span>
                        </button>
                    </div>
                </div>

                <!-- VERIFIKASI KELAYAKAN DATA SURVEI -->
                <template x-if="aiAnalysis && aiAnalysis.validasi_dokumen">
                    <div class="p-5 rounded-2xl border space-y-3"
                         :style="aiAnalysis.validasi_dokumen.is_valid_for_analysis ? 'background:#EDFAF2; border-color:#A3E6CD;' : 'background:#FDECEA; border-color:#F5B7B1;'">
                        <div class="flex items-center justify-between gap-3">
                            <div class="flex items-center gap-2">
                                <span class="text-xl" x-text="aiAnalysis.validasi_dokumen.is_valid_for_analysis ? '✅' : '⚠️'"></span>
                                <span class="font-bold text-xs" :class="aiAnalysis.validasi_dokumen.is_valid_for_analysis ? 'text-emerald-900' : 'text-red-900'" x-text="aiAnalysis.validasi_dokumen.status_kelayakan"></span>
                            </div>
                            <div class="text-xs font-bold" :class="aiAnalysis.validasi_dokumen.is_valid_for_analysis ? 'text-emerald-800' : 'text-red-800'">
                                Kelayakan Data: <span x-text="(aiAnalysis.validasi_dokumen.persentase_kelayakan_data || 90) + '%'"></span>
                            </div>
                        </div>
                        <p class="text-xs leading-relaxed" :class="aiAnalysis.validasi_dokumen.is_valid_for_analysis ? 'text-emerald-800' : 'text-red-900'" x-text="aiAnalysis.validasi_dokumen.catatan_kelayakan"></p>
                    </div>
                </template>

                <!-- SECTION 1: RINGKASAN EKSEKUTIF & SENTIMEN -->
            <div class="space-y-4">
                <div class="flex items-center gap-2 text-xs font-bold tracking-widest uppercase" style="color:#C89D54;">
                    <span>● BAGIAN I</span>
                    <span class="w-8 h-px bg-amber-300"></span>
                    <span>RINGKASAN EKSEKUTIF & SENTIMEN PASAR</span>
                </div>
                <div class="p-5 rounded-2xl border space-y-3" style="background:#FAF6EE; border-color:#E5E0D8;">
                    <p class="text-sm sm:text-base text-stone-800 leading-relaxed font-medium text-justify whitespace-pre-line" x-text="aiAnalysis.ringkasan_eksekutif"></p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-2">
                    <div class="p-4 rounded-xl border space-y-1.5" style="background:#FAF8F5; border-color:#E5E0D8;">
                        <span class="text-[10px] font-bold uppercase tracking-wider block" style="color:#C89D54;">INDEX GROWTH LIKELIHOOD</span>
                        <div class="text-lg font-bold text-stone-900" x-text="aiAnalysis.growth_likelihood || (aiAnalysis.skor_potensi ? aiAnalysis.skor_potensi + '% (High Potential)' : 'Sangat Tinggi (High Growth)')"></div>
                    </div>
                    <div class="p-4 rounded-xl border space-y-1.5" style="background:#FAF8F5; border-color:#E5E0D8;">
                        <span class="text-[10px] font-bold uppercase tracking-wider block" style="color:#A19A91;">TINGKAT MINAT KONSUMEN</span>
                        <div class="text-lg font-bold text-emerald-800" x-text="aiAnalysis.analisis_sentimen ? aiAnalysis.analisis_sentimen.tingkat_minat : 'Tinggi (Sangat Positif)'"></div>
                    </div>
                    <div class="p-4 rounded-xl border space-y-1.5" style="background:#FAF8F5; border-color:#E5E0D8;">
                        <span class="text-[10px] font-bold uppercase tracking-wider block" style="color:#A19A91;">FAKTOR PENDORONG SENTIMEN</span>
                        <p class="text-xs text-stone-700 font-medium leading-relaxed text-justify whitespace-pre-line" x-text="aiAnalysis.analisis_sentimen ? aiAnalysis.analisis_sentimen.penjelasan : 'Penjelasan sentimen konsumen...'"></p>
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
                        <p class="text-xs sm:text-sm text-stone-700 font-medium leading-relaxed text-justify whitespace-pre-line" x-text="aiAnalysis.strategi_harga_wtp ? aiAnalysis.strategi_harga_wtp.rekomendasi_margin : 'Rekomendasi margin kotor 30-35%...'"></p>
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
                            <p class="text-xs text-stone-800 font-medium leading-relaxed text-justify whitespace-pre-line" x-text="rec"></p>
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
                            <p class="text-xs text-stone-800 font-medium leading-relaxed text-justify whitespace-pre-line" x-text="mkt"></p>
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
    </template>
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
    <div x-show="detailOpen" x-transition.opacity class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/25" style="display:none;">
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
window.surveyCharts = window.surveyCharts || {};
const chartConfigs = @json($chartsConfig ?? []);

function renderChartById(qId, overrideType = null) {
    const config = chartConfigs[qId];
    if (!config) return;
    const canvas = document.getElementById('chart-' + qId);
    if (!canvas) return;

    if (window.surveyCharts[qId]) {
        try {
            window.surveyCharts[qId].destroy();
        } catch (e) {
            console.warn('Error destroying chart', e);
        }
        delete window.surveyCharts[qId];
    }

    const type = overrideType || config.currentType || config.defaultType || 'bar';
    config.currentType = type;

    const palette = ['#C89D54', '#10B981', '#3B82F6', '#8B5CF6', '#F59E0B', '#EC4899', '#14B8A6', '#6366F1', '#E11D48', '#06B6D4'];
    const likertColors = ['#EF4444', '#F97316', '#FBBF24', '#34D399', '#059669'];

    let chartJsConfig = null;

    if (config.kind === 'SINGLE_CHOICE') {
        const bgColors = config.labels.map((_, i) => palette[i % palette.length]);
        if (type === 'doughnut') {
            chartJsConfig = {
                type: 'doughnut',
                data: {
                    labels: config.labels,
                    datasets: [{
                        data: config.data,
                        backgroundColor: bgColors,
                        borderWidth: 2,
                        borderColor: '#FFFFFF',
                        hoverOffset: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '62%',
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                boxWidth: 12,
                                padding: 14,
                                font: { family: 'Plus Jakarta Sans', size: 11, weight: '600' },
                                color: '#44403C'
                            }
                        },
                        tooltip: {
                            backgroundColor: '#1C1917',
                            padding: 10,
                            cornerRadius: 8,
                            callbacks: {
                                label: function(ctx) {
                                    const total = ctx.dataset.data.reduce((a, b) => a + b, 0);
                                    const pct = total > 0 ? Math.round((ctx.raw / total) * 100) : 0;
                                    return ` ${ctx.label}: ${ctx.raw} (${pct}%)`;
                                }
                            }
                        }
                    }
                }
            };
        } else {
            chartJsConfig = {
                type: 'bar',
                data: {
                    labels: config.labels,
                    datasets: [{
                        label: 'Responden',
                        data: config.data,
                        backgroundColor: bgColors,
                        borderRadius: 8,
                        borderSkipped: false,
                        barPercentage: 0.65
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: { backgroundColor: '#1C1917', padding: 10, cornerRadius: 8 }
                    },
                    scales: {
                        y: { beginAtZero: true, grid: { color: '#F4F0E8' }, ticks: { precision: 0 } },
                        x: { grid: { display: false }, ticks: { font: { family: 'Plus Jakarta Sans', size: 11, weight: '600' }, color: '#44403C' } }
                    }
                }
            };
        }
    } else if (config.kind === 'MULTIPLE_CHOICE') {
        chartJsConfig = {
            type: 'bar',
            data: {
                labels: config.labels,
                datasets: [{
                    label: 'Jumlah Pemilih',
                    data: config.data,
                    backgroundColor: '#C89D54',
                    hoverBackgroundColor: '#A37A3E',
                    borderRadius: 6,
                    borderSkipped: false,
                    barPercentage: 0.7
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#1C1917',
                        padding: 10,
                        cornerRadius: 8,
                        callbacks: {
                            label: function(ctx) {
                                const total = config.totalResp || 1;
                                const pct = Math.round((ctx.raw / total) * 100);
                                return ` ${ctx.raw} Responden (${pct}% dari total responden)`;
                            }
                        }
                    }
                },
                scales: {
                    x: { beginAtZero: true, grid: { color: '#F4F0E8' }, ticks: { precision: 0 } },
                    y: { grid: { display: false }, ticks: { font: { family: 'Plus Jakarta Sans', size: 11, weight: '600' }, color: '#1C1917' } }
                }
            }
        };
    } else if (config.kind === 'LIKERT') {
        if (type === 'doughnut') {
            chartJsConfig = {
                type: 'doughnut',
                data: {
                    labels: config.labels,
                    datasets: [{
                        data: config.data,
                        backgroundColor: likertColors,
                        borderWidth: 2,
                        borderColor: '#FFFFFF',
                        hoverOffset: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '62%',
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: { boxWidth: 12, padding: 12, font: { family: 'Plus Jakarta Sans', size: 10, weight: '600' }, color: '#44403C' }
                        },
                        tooltip: { backgroundColor: '#1C1917', padding: 10, cornerRadius: 8 }
                    }
                }
            };
        } else {
            chartJsConfig = {
                type: 'bar',
                data: {
                    labels: config.labels,
                    datasets: [{
                        label: 'Frekuensi',
                        data: config.data,
                        backgroundColor: likertColors,
                        borderRadius: 8,
                        borderSkipped: false,
                        barPercentage: 0.65
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: { backgroundColor: '#1C1917', padding: 10, cornerRadius: 8 }
                    },
                    scales: {
                        y: { beginAtZero: true, grid: { color: '#F4F0E8' }, ticks: { precision: 0 } },
                        x: { grid: { display: false }, ticks: { font: { family: 'Plus Jakarta Sans', size: 10, weight: '600' }, color: '#44403C' } }
                    }
                }
            };
        }
    } else if (config.kind === 'NUMBER') {
        const sw = config.sweetSpot;
        const bgColors = config.labels.map(l => l === sw ? '#10B981' : '#C89D54');
        const hoverColors = config.labels.map(l => l === sw ? '#059669' : '#A37A3E');
        chartJsConfig = {
            type: 'bar',
            data: {
                labels: config.labels,
                datasets: [{
                    label: 'Responden',
                    data: config.data,
                    backgroundColor: bgColors,
                    hoverBackgroundColor: hoverColors,
                    borderRadius: 8,
                    borderSkipped: false,
                    barPercentage: 0.65
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#1C1917',
                        padding: 10,
                        cornerRadius: 8,
                        callbacks: {
                            label: function(ctx) {
                                const isSw = ctx.label === sw;
                                return ` ${ctx.raw} Responden` + (isSw ? ' (🔥 Sweet Spot Tarif WTP)' : '');
                            }
                        }
                    }
                },
                scales: {
                    y: { beginAtZero: true, grid: { color: '#F4F0E8' }, ticks: { precision: 0 } },
                    x: { grid: { display: false }, ticks: { font: { family: 'Plus Jakarta Sans', size: 11, weight: 'bold' }, color: '#1C1917' } }
                }
            }
        };
    } else if (config.kind === 'DATE') {
        chartJsConfig = {
            type: 'bar',
            data: {
                labels: config.labels,
                datasets: [{
                    label: 'Jumlah Responden',
                    data: config.data,
                    backgroundColor: '#6366F1',
                    hoverBackgroundColor: '#4F46E5',
                    borderRadius: 6,
                    borderSkipped: false,
                    barPercentage: 0.65
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: { backgroundColor: '#1C1917', padding: 10, cornerRadius: 8 }
                },
                scales: {
                    y: { beginAtZero: true, grid: { color: '#F4F0E8' }, ticks: { precision: 0 } },
                    x: { grid: { display: false }, ticks: { font: { family: 'Plus Jakarta Sans', size: 11, weight: '600' }, color: '#44403C' } }
                }
            }
        };
    }

    if (chartJsConfig) {
        window.surveyCharts[qId] = new Chart(canvas, chartJsConfig);
    }
}

function initAllCharts() {
    if (typeof chartConfigs !== 'undefined' && chartConfigs) {
        Object.keys(chartConfigs).forEach(qId => {
            renderChartById(qId);
        });
    }
}

function toggleChart(qId, newType) {
    renderChartById(qId, newType);
    const container = document.getElementById('toggle-' + qId);
    if (container) {
        container.querySelectorAll('button').forEach(btn => {
            if (btn.dataset.chartType === newType) {
                btn.className = 'px-2.5 py-1 rounded text-[11px] font-bold transition flex items-center gap-1 bg-stone-900 text-white shadow-xs';
            } else {
                btn.className = 'px-2.5 py-1 rounded text-[11px] font-bold transition flex items-center gap-1 text-stone-600 hover:text-stone-900';
            }
        });
    }
}

document.addEventListener('DOMContentLoaded', () => {
    setTimeout(() => initAllCharts(), 150);
});

function analyticsApp() {
    return {
        activeView: '{{ request("tab", "SUMMARY") }}', // 'SUMMARY' | 'AI_ANALYTICS' | 'QUESTIONS' | 'INDIVIDUAL'
        aiAnalysis: @json($aiAnalysis),
        aiLoading: false,
        searchNisn: '',
        detailOpen: false,
        detailNisn: '',
        detailAnswers: [],

        init() {
            this.$watch('activeView', (view) => {
                if (view === 'SUMMARY') {
                    this.$nextTick(() => {
                        setTimeout(() => initAllCharts(), 60);
                    });
                }
            });
            this.$nextTick(() => {
                setTimeout(() => initAllCharts(), 120);
            });
        },

        async regenerateAi() {
            const surveyId   = '{{ $survey->id }}';
            const surveyJudul = '{{ addslashes($survey->judul) }}';
            const respondents = {{ $totalRespondents }};

            console.group('%c[AI CONSULTANT] Business Intelligence Analysis', 'color:#C89D54; font-weight:bold; font-size:13px;');
            console.log('%c═══════════════════════════════════════════════════', 'color:#C89D54;');
            console.log('%c🚀 STARTING ANALYSIS', 'color:#16a34a; font-weight:bold;');
            console.log('  Survey ID   :', surveyId);
            console.log('  Survey Title:', surveyJudul);
            console.log('  Respondents :', respondents);
            console.log('  Timestamp   :', new Date().toLocaleString('id-ID'));
            console.log('%c═══════════════════════════════════════════════════', 'color:#C89D54;');

            this.aiLoading = true;
            try {
                console.log('%c[STEP 1/4] Preparing AJAX request to Gemini AI endpoint...', 'color:#2563eb; font-weight:bold;');
                console.log('  Endpoint : POST /admin/ai-analytics/' + surveyId + '/generate');
                console.log('  Mode     : Dynamic — uses live survey data from database');
                console.time('AI Analysis Duration');

                const res = await fetch(`/admin/ai-analytics/${surveyId}/generate`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });

                console.log('%c[STEP 2/4] HTTP response received', 'color:#2563eb; font-weight:bold;');
                console.log('  HTTP Status :', res.status, res.ok ? '✅ OK' : '❌ Error');

                const contentType = res.headers.get('content-type') || '';
                if (!contentType.includes('application/json')) {
                    throw new Error('Server returned non-JSON response (HTTP ' + res.status + '). Check Laravel logs for details.');
                }

                console.log('%c[STEP 3/4] Parsing AI analysis JSON response...', 'color:#2563eb; font-weight:bold;');
                const data = await res.json();

                if (data.success && data.analysis) {
                    const analysis = data.analysis;
                    console.log('%c[STEP 4/4] ✅ AI Analysis Successful!', 'color:#16a34a; font-weight:bold;');
                    console.log('%c─── Analysis Summary ──────────────────────────────', 'color:#C89D54;');
                    console.log('  Source         :', analysis.source || 'N/A');
                    console.log('  Model Used     :', analysis.model_used || (analysis.debug && analysis.debug.model) || 'N/A');
                    console.log('  Skor Potensi   :', analysis.skor_potensi, '/ 100');
                    console.log('  Minat Pasar    :', analysis.analisis_sentimen?.tingkat_minat || 'N/A');
                    console.log('  Sentimen +     :', (analysis.analisis_sentimen?.persentase_positif || 0) + '%');
                    console.log('  Sweet Spot WTP :', analysis.strategi_harga_wtp?.sweet_spot_harga || 'N/A');
                    console.log('  Rekomendasi    :', (analysis.rekomendasi_produk?.length || 0) + ' poin produk,' , (analysis.strategi_pemasaran?.length || 0) + ' poin pemasaran');
                    console.log('  Action Plan    :', (analysis.action_plan?.length || 0) + ' tahap');
                    if (analysis.debug) {
                        console.log('%c─── Debug Info ────────────────────────────────────', 'color:#C89D54;');
                        console.log('  Execution Time:', analysis.debug.execution_time);
                        console.log('  Prompt Chars  :', analysis.debug.prompt_chars);
                        console.log('  Status        :', analysis.debug.status);
                    }
                    console.timeEnd('AI Analysis Duration');
                    console.log('%c═══════════════════════════════════════════════════', 'color:#C89D54;');
                    console.groupEnd();

                    this.aiAnalysis = analysis;
                } else {
                    throw new Error(data.message || 'AI returned unsuccessful response. Check server logs.');
                }
            } catch (e) {
                console.error('%c[AI CONSULTANT] ❌ Analysis Failed:', 'color:#dc2626; font-weight:bold;', e.message);
                console.log('  Tip: Check Laravel logs with: docker exec survey-pasar-app tail -f storage/logs/laravel.log');
                console.timeEnd('AI Analysis Duration');
                console.groupEnd();
                alert('Gagal meregenerasi data AI:\n' + e.message);
            } finally {
                this.aiLoading = false;
            }
        },

        async deleteAi() {
            if (!confirm('Apakah Anda yakin ingin menghapus hasil analisa AI ini? Halaman akan kembali ke tampilan belum di-generate.')) {
                return;
            }
            this.aiLoading = true;
            try {
                const res = await fetch(`/admin/ai-analytics/{{ $survey->id }}/delete`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });
                const data = await res.json();
                if (data.success) {
                    this.aiAnalysis = null;
                } else {
                    alert('Gagal menghapus analisa AI: ' + (data.message || 'Error'));
                }
            } catch (e) {
                alert('Terjadi kesalahan: ' + e.message);
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
