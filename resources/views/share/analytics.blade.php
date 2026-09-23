<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Riset Pasar — {{ $survey->judul }}</title>
    <meta name="description" content="Hasil survei dan analisis intelijen riset pasar: {{ $survey->judul }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        accent: {
                            DEFAULT: '#C89D54',
                            light:   '#D9AB55',
                            tint:    '#F8F4EC',
                        },
                        danger: {
                            DEFAULT: '#C0392B',
                            tint:    '#FDECEA',
                        },
                        ink: {
                            DEFAULT: '#1C1917',
                            muted:   '#6E675F',
                            subtle:  '#A19A91',
                        },
                        surface: {
                            DEFAULT: '#FFFFFF',
                            raised:  '#FFFFFF',
                            base:    '#FAF8F5',
                            subtle:  '#F4F0E8',
                            border:  '#E5E0D8',
                            divider: '#EBE6DE',
                        }
                    },
                    fontFamily: {
                        serif: ['Plus Jakarta Sans', 'Inter', 'system-ui', 'sans-serif'],
                        sans:  ['Plus Jakarta Sans', 'Inter', 'system-ui', 'sans-serif'],
                    },
                }
            }
        }
    </script>

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }
        * { box-sizing: border-box; }
        body {
            font-family: 'Plus Jakarta Sans', 'Inter', system-ui, sans-serif;
            background-color: #FAF8F5;
            color: #1C1917;
            margin: 0;
            padding: 0;
        }
        .card {
            background: #FFFFFF;
            border: 1px solid #E5E0D8;
            border-radius: 14px;
        }
        ::-webkit-scrollbar { width: 5px; height: 5px; }
        ::-webkit-scrollbar-track { background: #F4F0E8; }
        ::-webkit-scrollbar-thumb { background: #D5CEC4; border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: #C89D54; }
    </style>
</head>
<body class="min-h-screen antialiased flex flex-col justify-between">

<div x-data="publicAnalyticsApp()" class="w-full">

    <!-- ── Public Top Bar ──────────────────────────────────────────────── -->
    <header class="sticky top-0 z-40 bg-white/95 backdrop-blur-md border-b border-surface-border transition-all">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 py-3.5 flex items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl flex items-center justify-center font-bold text-xs shrink-0 shadow-xs"
                     style="background:#1C1917; color:#C89D54;">
                    RP
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <span class="font-serif font-bold text-sm text-ink leading-tight">SMKN 2 Indramayu</span>
                        <span class="text-[9px] font-bold px-2 py-0.5 rounded-full border uppercase" style="background:#FAF6EE; border-color:#E5E0D8; color:#C89D54;">
                            Publikasi Riset
                        </span>
                    </div>
                    <span class="text-[10px] text-ink-muted hidden sm:block">Platform Riset Pasar & Intelijen Bisnis Kewirausahaan</span>
                </div>
            </div>

            <div class="flex items-center gap-2.5">
                <a href="{{ route('analytics.share.export', $survey->share_token) }}"
                   class="inline-flex items-center gap-1.5 px-3.5 py-2 text-white font-bold text-xs rounded-xl transition shadow-xs"
                   style="background:#1C1917;"
                   onmouseover="this.style.background='#2C2723';"
                   onmouseout="this.style.background='#1C1917';">
                    <svg class="w-3.5 h-3.5" style="color:#C89D54;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    <span>Ekspor XLSX</span>
                </a>

                @if($aiAnalysis)
                <a href="{{ route('analytics.share.print-ai', $survey->share_token) }}" target="_blank"
                   class="inline-flex items-center gap-1.5 px-3.5 py-2 border font-bold text-xs rounded-xl transition"
                   style="background:#FAF8F5; border-color:#E5E0D8; color:#1C1917;"
                   onmouseover="this.style.background='#F4F0E8';"
                   onmouseout="this.style.background='#FAF8F5';">
                    <svg class="w-3.5 h-3.5" style="color:#C89D54;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                    <span class="hidden sm:inline">Cetak AI</span>
                </a>
                @endif

                <a href="{{ route('admin.login') }}"
                   class="inline-flex items-center gap-1 px-3 py-2 text-xs font-semibold rounded-xl text-stone-500 hover:text-stone-900 hover:bg-stone-100 transition"
                   title="Masuk sebagai Administrator / Pengamat">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
                    <span class="hidden sm:inline">Masuk</span>
                </a>
            </div>
        </div>
    </header>

    <!-- ── Main Content Container ──────────────────────────────────────── -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 py-6 sm:py-8 space-y-6">

        <!-- ── Survey Title & Hero Card ─────────────────────────────────── -->
        <div class="card p-6 sm:p-8 space-y-4">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="space-y-1.5 flex-1 min-w-0">
                    <div class="flex items-center gap-2 flex-wrap">
                        <span class="text-[10px] font-sans uppercase tracking-widest font-bold" style="color:#C89D54;">LAPORAN ANALITIK RISET PASAR</span>
                        <span class="text-stone-300">•</span>
                        @if($survey->status === 'PUBLISHED')
                            <span class="text-[10px] font-bold px-2.5 py-0.5 rounded-full border uppercase" style="background:#EDFAF2; border-color:#A3D9BA; color:#1A5C38;">● Publikasi Aktif</span>
                        @elseif($survey->status === 'DRAFT')
                            <span class="text-[10px] font-bold px-2.5 py-0.5 rounded-full border uppercase" style="background:#FAF8F5; border-color:#E5E0D8; color:#A19A91;">○ Draft</span>
                        @else
                            <span class="text-[10px] font-bold px-2.5 py-0.5 rounded-full border uppercase" style="background:#FDECEA; border-color:#F5B7B1; color:#922B21;">✕ Selesai (Closed)</span>
                        @endif
                    </div>
                    <h1 class="font-serif font-bold text-2xl sm:text-3xl text-ink leading-tight">{{ $survey->judul }}</h1>
                    @if($survey->deskripsi)
                        <p class="text-xs sm:text-sm text-stone-600 leading-relaxed max-w-4xl pt-1">{{ $survey->deskripsi }}</p>
                    @endif
                </div>

                <div class="flex items-center sm:flex-col sm:items-end justify-between sm:justify-center p-3 sm:p-4 rounded-xl border bg-stone-50/80 border-stone-200 shrink-0">
                    <span class="text-[10px] uppercase font-bold tracking-wider text-stone-500">Total Responden</span>
                    <span class="font-serif font-extrabold text-2xl sm:text-3xl text-ink tabular-nums">{{ number_format($totalRespondents, 0, ',', '.') }}</span>
                    <span class="text-[10px] text-stone-400">Data Terverifikasi</span>
                </div>
            </div>
        </div>

        <!-- ── View Mode Filter Switcher Bar ───────────────────────────── -->
        <div class="card p-2 flex items-center gap-2 flex-wrap sm:flex-nowrap" style="background:#FFFFFF; border-color:#E5E0D8;">
            <!-- Tab 1: Ringkasan (Summary) -->
            <button type="button" @click="activeView = 'SUMMARY'"
                    :class="activeView === 'SUMMARY'
                        ? 'shadow-xs font-bold'
                        : 'text-ink-subtle hover:bg-surface-subtle'"
                    :style="activeView === 'SUMMARY'
                        ? 'background:#1C1917; color:#FFFFFF;'
                        : 'color:#6E675F;'"
                    class="flex-1 py-2.5 px-3 rounded-xl text-xs font-bold transition flex items-center justify-center gap-2 border cursor-pointer"
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
                    class="flex-1 py-2.5 px-3 rounded-xl text-xs font-bold transition flex items-center justify-center gap-2 border relative cursor-pointer"
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
                    class="flex-1 py-2.5 px-3 rounded-xl text-xs font-bold transition flex items-center justify-center gap-2 border cursor-pointer"
                    :style="activeView === 'QUESTIONS' ? 'border-color:#1C1917;' : 'border-color:transparent;'">
                <svg class="w-4 h-4 shrink-0" :style="activeView === 'QUESTIONS' ? 'color:#C89D54;' : 'color:#A19A91;'" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>Per Pertanyaan</span>
            </button>

            <!-- Tab 4: Detail Jawaban Individual -->
            <button type="button" @click="activeView = 'INDIVIDUAL'"
                    :class="activeView === 'INDIVIDUAL'
                        ? 'shadow-xs font-bold'
                        : 'text-ink-subtle hover:bg-surface-subtle'"
                    :style="activeView === 'INDIVIDUAL'
                        ? 'background:#1C1917; color:#FFFFFF;'
                        : 'color:#6E675F;'"
                    class="flex-1 py-2.5 px-3 rounded-xl text-xs font-bold transition flex items-center justify-center gap-2 border cursor-pointer"
                    :style="activeView === 'INDIVIDUAL' ? 'border-color:#1C1917;' : 'border-color:transparent;'">
                <svg class="w-4 h-4 shrink-0" :style="activeView === 'INDIVIDUAL' ? 'color:#C89D54;' : 'color:#A19A91;'" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <span>Jawaban Individual</span>
            </button>
        </div>

        <!-- ── Date Filter Form ────────────────────────────────────────── -->
        <form method="GET" action="{{ route('analytics.share', $survey->share_token) }}"
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
                    class="px-4 py-2 text-white font-bold text-xs rounded-xl transition shadow-xs cursor-pointer"
                    style="background:#1C1917;"
                    onmouseover="this.style.background='#2C2723';"
                    onmouseout="this.style.background='#1C1917';">
                Filter Data
            </button>
            @if(request('start_date') || request('end_date'))
                <a href="{{ route('analytics.share', $survey->share_token) }}"
                   class="px-4 py-2 border font-bold text-xs rounded-xl transition"
                   style="background:#FAF8F5; border-color:#E5E0D8; color:#6E675F;">
                    Reset Filter
                </a>
            @endif
        </form>

        <!-- ============================================================= -->
        <!-- VIEW MODE 1: SUMMARY / RINGKASAN DATA & GRAFIK                -->
        <!-- ============================================================= -->
        <div x-show="activeView === 'SUMMARY'" x-transition class="space-y-6">

            <!-- AI Intelligence Narrative Banner (if available) -->
            @if($aiAnalysis && !empty($aiAnalysis['ringkasan_eksekutif']))
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
                                <span class="font-serif font-bold text-xl" style="color:#C89D54;">{{ $aiAnalysis['skor_potensi'] ?? 85 }}/100</span>
                            </div>
                            <button type="button" @click="activeView = 'AI_ANALYTICS'"
                                    class="px-3.5 py-2 text-xs font-bold rounded-xl text-white transition shadow-xs cursor-pointer"
                                    style="background:#1C1917;">
                                Dokumen AI Lengkap →
                            </button>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-5 pt-1">
                        <div class="lg:col-span-8 space-y-2">
                            <p class="text-xs sm:text-sm text-stone-700 leading-relaxed font-medium">{{ $aiAnalysis['ringkasan_eksekutif'] }}</p>
                        </div>
                        <div class="lg:col-span-4 p-4 rounded-xl space-y-3 border" style="background:#FFFFFF; border-color:#E5E0D8;">
                            <div>
                                <span class="text-[10px] font-bold uppercase tracking-wider block" style="color:#A19A91;">SWEET SPOT HARGA (WTP)</span>
                                <span class="font-bold text-base text-ink">{{ $aiAnalysis['strategi_harga_wtp']['sweet_spot_harga'] ?? '-' }}</span>
                            </div>
                            <div class="pt-2 border-t" style="border-color:#F4F0E8;">
                                <span class="text-[10px] font-bold uppercase tracking-wider block" style="color:#A19A91;">TINGKAT MINAT KONSUMEN</span>
                                <span class="font-bold text-sm text-emerald-700">{{ $aiAnalysis['analisis_sentimen']['tingkat_minat'] ?? 'Tinggi' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- 4 KPI Stat Cards Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
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
                    <p class="text-xs mt-1" style="color:#A19A91;">Status ketersediaan survey</p>
                </div>

                <div class="card p-5 space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="text-[10px] font-sans uppercase tracking-widest font-bold" style="color:#6E675F;">PERIODE SURVEY</span>
                        <div class="w-8 h-8 rounded-xl flex items-center justify-center" style="background:#F8F4EC; border:1px solid #E5E0D8;">
                            <svg class="w-4 h-4" style="color:#C89D54;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                    </div>
                    <div class="text-xs font-bold text-ink">
                        {{ $survey->tanggal_mulai ? \Carbon\Carbon::parse($survey->tanggal_mulai)->format('d M Y') : 'Fleksibel' }}
                        –
                        {{ $survey->tanggal_selesai ? \Carbon\Carbon::parse($survey->tanggal_selesai)->format('d M Y') : 'Fleksibel' }}
                    </div>
                    <p class="text-xs" style="color:#A19A91;">Rentang aktif survey</p>
                </div>
            </div>

            <!-- Question Overview Cards Grid with Graphs -->
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
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center gap-2 self-start sm:self-auto shrink-0">
                                <span class="text-xs font-bold px-3 py-1.5 rounded-lg border flex items-center gap-1.5" style="background:#FAF8F5; border-color:#E5E0D8; color:#1C1917;">
                                    <svg class="w-3.5 h-3.5 text-stone-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    <span>{{ $totalResp }} Responden</span>
                                </span>
                            </div>
                        </div>

                        @if($totalResp === 0)
                            <div class="p-8 rounded-xl border border-dashed text-center space-y-2" style="background:#FAF8F5; border-color:#E5E0D8;">
                                <span class="text-2xl block">📊</span>
                                <p class="text-xs font-bold text-stone-700">Belum Ada Respon Terkumpul</p>
                                <p class="text-[11px] text-stone-500">Visualisasi otomatis akan ditampilkan setelah responden mengisi pertanyaan ini.</p>
                            </div>

                        {{-- ── SINGLE CHOICE GRAPH ── --}}
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
                                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 p-3 rounded-xl border text-xs" style="background:#FAF8F5; border-color:#E5E0D8;">
                                    <div class="flex items-center gap-2">
                                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                                        <span class="font-bold text-ink">🏆 Pilihan Terbanyak:</span>
                                        <span class="font-semibold text-emerald-800">"{{ $topChoice }}" ({{ $sortedCounts[$topChoice] ?? 0 }} responden &bull; {{ $topChoicePct }}%)</span>
                                    </div>
                                    <div id="toggle-{{ $q->id }}" class="flex items-center gap-1 bg-white p-1 rounded-lg border border-stone-200 shrink-0 self-end sm:self-auto">
                                        <button type="button" data-chart-type="doughnut" onclick="toggleChart('{{ $q->id }}', 'doughnut')"
                                                class="px-2.5 py-1 rounded text-[11px] font-bold transition flex items-center gap-1 bg-stone-900 text-white shadow-xs cursor-pointer">
                                            <span>🍩 Donat</span>
                                        </button>
                                        <button type="button" data-chart-type="bar" onclick="toggleChart('{{ $q->id }}', 'bar')"
                                                class="px-2.5 py-1 rounded text-[11px] font-bold transition flex items-center gap-1 text-stone-600 hover:text-stone-900 cursor-pointer">
                                            <span>📊 Batang</span>
                                        </button>
                                    </div>
                                </div>

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

                        {{-- ── MULTIPLE CHOICE GRAPH ── --}}
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

                                <div class="relative w-full h-72 pt-2">
                                    <canvas id="chart-{{ $q->id }}"></canvas>
                                </div>
                            </div>

                        {{-- ── LIKERT SCALE GRAPH ── --}}
                        @elseif($q->tipe_pertanyaan === 'LIKERT')
                            @php
                                $counts = $item['counts'] ?? [];
                                $labels = ['1 (Sangat Tidak Setuju)', '2 (Tidak Setuju)', '3 (Netral)', '4 (Setuju)', '5 (Sangat Setuju)'];
                                $dataVals = [
                                    $counts[1] ?? 0,
                                    $counts[2] ?? 0,
                                    $counts[3] ?? 0,
                                    $counts[4] ?? 0,
                                    $counts[5] ?? 0,
                                ];
                                $avg = $item['average'] ?? 0;
                                $chartsConfig[$q->id] = [
                                    'kind' => 'LIKERT',
                                    'defaultType' => 'bar',
                                    'labels' => $labels,
                                    'data' => $dataVals,
                                ];
                            @endphp
                            <div class="space-y-4">
                                <div class="flex items-center justify-between p-3 rounded-xl border" style="background:#FAF8F5; border-color:#E5E0D8;">
                                    <div>
                                        <span class="text-[10px] uppercase font-bold text-stone-400 block">RATA-RATA SKOR LIKERT</span>
                                        <span class="font-bold text-base text-ink">{{ number_format($avg, 2) }} / 5.00</span>
                                    </div>
                                    <div id="toggle-{{ $q->id }}" class="flex items-center gap-1 bg-white p-1 rounded-lg border border-stone-200">
                                        <button type="button" data-chart-type="bar" onclick="toggleChart('{{ $q->id }}', 'bar')"
                                                class="px-2.5 py-1 rounded text-[11px] font-bold transition flex items-center gap-1 bg-stone-900 text-white shadow-xs cursor-pointer">
                                            <span>📊 Batang</span>
                                        </button>
                                        <button type="button" data-chart-type="doughnut" onclick="toggleChart('{{ $q->id }}', 'doughnut')"
                                                class="px-2.5 py-1 rounded text-[11px] font-bold transition flex items-center gap-1 text-stone-600 hover:text-stone-900 cursor-pointer">
                                            <span>🍩 Donat</span>
                                        </button>
                                    </div>
                                </div>
                                <div class="relative w-full h-64 pt-2">
                                    <canvas id="chart-{{ $q->id }}"></canvas>
                                </div>
                            </div>

                        {{-- ── NUMBER / WTP GRAPH ── --}}
                        @elseif($q->tipe_pertanyaan === 'NUMBER')
                            @php
                                $sortedNums = $item['distribution'] ?? [];
                                ksort($sortedNums);
                                $labels = array_map(fn($v) => 'Rp ' . number_format($v, 0, ',', '.'), array_keys($sortedNums));
                                $dataVals = array_values($sortedNums);
                                $sweetSpot = $aiAnalysis['strategi_harga_wtp']['sweet_spot_harga'] ?? null;
                                $chartsConfig[$q->id] = [
                                    'kind' => 'NUMBER',
                                    'defaultType' => 'bar',
                                    'labels' => $labels,
                                    'data' => $dataVals,
                                    'sweetSpot' => $sweetSpot,
                                ];
                            @endphp
                            <div class="space-y-4">
                                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-xs">
                                    <div class="p-3 rounded-xl border" style="background:#FAF8F5; border-color:#E5E0D8;">
                                        <span class="text-[10px] uppercase font-bold text-stone-400 block">RATA-RATA</span>
                                        <span class="font-bold text-ink">Rp {{ number_format($item['average'] ?? 0, 0, ',', '.') }}</span>
                                    </div>
                                    <div class="p-3 rounded-xl border" style="background:#FAF8F5; border-color:#E5E0D8;">
                                        <span class="text-[10px] uppercase font-bold text-stone-400 block">MEDIAN (TENGAH)</span>
                                        <span class="font-bold text-ink">Rp {{ number_format($item['median'] ?? 0, 0, ',', '.') }}</span>
                                    </div>
                                    <div class="p-3 rounded-xl border" style="background:#FAF8F5; border-color:#E5E0D8;">
                                        <span class="text-[10px] uppercase font-bold text-stone-400 block">MINIMUM</span>
                                        <span class="font-bold text-ink">Rp {{ number_format($item['min'] ?? 0, 0, ',', '.') }}</span>
                                    </div>
                                    <div class="p-3 rounded-xl border" style="background:#FAF8F5; border-color:#E5E0D8;">
                                        <span class="text-[10px] uppercase font-bold text-stone-400 block">MAKSIMUM</span>
                                        <span class="font-bold text-ink">Rp {{ number_format($item['max'] ?? 0, 0, ',', '.') }}</span>
                                    </div>
                                </div>
                                <div class="relative w-full h-64 pt-2">
                                    <canvas id="chart-{{ $q->id }}"></canvas>
                                </div>
                            </div>

                        {{-- ── TEXT & OTHER RESPONSES LIST ── --}}
                        @else
                            <div class="space-y-2">
                                <span class="text-[10px] uppercase font-bold text-stone-400 block">SAMPEL JAWABAN TERBARU</span>
                                <div class="space-y-2 max-h-56 overflow-y-auto pr-1">
                                    @forelse(array_slice($item['responses'] ?? [], 0, 10) as $respText)
                                        <div class="p-3 rounded-xl border text-xs" style="background:#FAF8F5; border-color:#E5E0D8;">
                                            <p class="font-medium text-ink">{{ $respText }}</p>
                                        </div>
                                    @empty
                                        <p class="text-xs text-stone-400 italic">Belum ada jawaban teks.</p>
                                    @endforelse
                                </div>
                            </div>
                        @endif

                    </div>
                @endforeach
            </div>

        </div>

        <!-- ============================================================= -->
        <!-- VIEW MODE 2: ANALISA & INTELIJEN AI                          -->
        <!-- ============================================================= -->
        <div x-show="activeView === 'AI_ANALYTICS'" x-transition class="space-y-6">
            @if($aiAnalysis && !empty($aiAnalysis['ringkasan_eksekutif']))
                <div class="card p-6 sm:p-8 space-y-6 border" style="background:#FFFFFF; border-color:#E5E0D8;">
                    <!-- Document Header -->
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b" style="border-color:#E5E0D8;">
                        <div>
                            <div class="flex items-center gap-2 mb-1">
                                <span class="px-2.5 py-0.5 rounded-md text-[11px] font-semibold border flex items-center gap-1.5"
                                      style="background:#EDFAF2; border-color:#A3E6CD; color:#1A5C38;">
                                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                    <span>{{ $aiAnalysis['model_used'] ?? 'Google Gemini AI' }}</span>
                                </span>
                                <span class="text-[10px] font-mono uppercase tracking-wider text-stone-400">STATUS: TERDOKUMENTASI</span>
                            </div>
                            <h2 class="font-serif font-bold text-2xl text-ink">Dokumen Rekomendasi Strategi Kewirausahaan</h2>
                            <p class="text-xs mt-1" style="color:#6E675F;">Berdasarkan evaluasi terisolasi dari {{ $totalRespondents }} data responden terverifikasi</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <a href="{{ route('analytics.share.print-ai', $survey->share_token) }}" target="_blank"
                               class="px-4 py-2.5 rounded-xl text-xs font-bold transition flex items-center gap-1.5 shadow-xs"
                               style="background:#1C1917; color:#FFFFFF;">
                                <svg class="w-3.5 h-3.5" style="color:#C89D54;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                                <span>Cetak Dokumen PDF</span>
                            </a>
                        </div>
                    </div>

                    <!-- Ringkasan Eksekutif -->
                    <div class="p-5 sm:p-6 rounded-2xl border space-y-3" style="background:#FAF6EE; border-color:#E5E0D8;">
                        <span class="text-[10px] font-bold uppercase tracking-widest block" style="color:#C89D54;">RINGKASAN EKSEKUTIF</span>
                        <p class="text-xs sm:text-sm text-stone-800 leading-relaxed font-medium">{{ $aiAnalysis['ringkasan_eksekutif'] }}</p>
                    </div>

                    <!-- Highlight Grid: Skor, Sentimen & WTP -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="p-5 rounded-2xl border space-y-2" style="background:#FAF8F5; border-color:#E5E0D8;">
                            <span class="text-[10px] font-bold uppercase tracking-wider text-stone-500 block">SKOR KELAYAKAN PASAR</span>
                            <div class="font-serif font-extrabold text-3xl" style="color:#C89D54;">{{ $aiAnalysis['skor_potensi'] ?? 85 }}/100</div>
                            <p class="text-xs text-stone-500">Tingkat penerimaan target market</p>
                        </div>

                        <div class="p-5 rounded-2xl border space-y-2" style="background:#FAF8F5; border-color:#E5E0D8;">
                            <span class="text-[10px] font-bold uppercase tracking-wider text-stone-500 block">MINAT KONSUMEN</span>
                            <div class="font-serif font-extrabold text-2xl text-emerald-800">{{ $aiAnalysis['analisis_sentimen']['tingkat_minat'] ?? 'Tinggi' }}</div>
                            <p class="text-xs text-stone-500">Persentase positif: {{ $aiAnalysis['analisis_sentimen']['persentase_positif'] ?? 80 }}%</p>
                        </div>

                        <div class="p-5 rounded-2xl border space-y-2" style="background:#FAF8F5; border-color:#E5E0D8;">
                            <span class="text-[10px] font-bold uppercase tracking-wider text-stone-500 block">SWEET SPOT HARGA (WTP)</span>
                            <div class="font-serif font-extrabold text-2xl text-ink">{{ $aiAnalysis['strategi_harga_wtp']['sweet_spot_harga'] ?? '-' }}</div>
                            <p class="text-xs text-stone-500">Rentang: {{ $aiAnalysis['strategi_harga_wtp']['rentang_harga_optimal'] ?? '-' }}</p>
                        </div>
                    </div>

                    <!-- Rekomendasi Produk & Strategi Pemasaran -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-2">
                        @if(!empty($aiAnalysis['rekomendasi_produk']))
                            <div class="card p-5 space-y-3">
                                <div class="flex items-center gap-2">
                                    <span class="p-1.5 rounded-lg bg-blue-50 text-blue-700 text-sm">📦</span>
                                    <h3 class="font-bold text-sm text-ink">Rekomendasi Produk & Varian</h3>
                                </div>
                                <ul class="space-y-2 text-xs text-stone-700">
                                    @foreach($aiAnalysis['rekomendasi_produk'] as $item)
                                        <li class="flex items-start gap-2 p-2.5 rounded-xl border bg-stone-50/60 border-stone-200">
                                            <span class="text-emerald-600 font-bold shrink-0">✓</span>
                                            <span>{{ is_array($item) ? ($item['poin'] ?? json_encode($item)) : $item }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @if(!empty($aiAnalysis['strategi_pemasaran']))
                            <div class="card p-5 space-y-3">
                                <div class="flex items-center gap-2">
                                    <span class="p-1.5 rounded-lg bg-purple-50 text-purple-700 text-sm">📣</span>
                                    <h3 class="font-bold text-sm text-ink">Strategi Pemasaran & Kanal</h3>
                                </div>
                                <ul class="space-y-2 text-xs text-stone-700">
                                    @foreach($aiAnalysis['strategi_pemasaran'] as $item)
                                        <li class="flex items-start gap-2 p-2.5 rounded-xl border bg-stone-50/60 border-stone-200">
                                            <span class="text-purple-600 font-bold shrink-0">✓</span>
                                            <span>{{ is_array($item) ? ($item['poin'] ?? json_encode($item)) : $item }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>

                    <!-- Action Plan -->
                    @if(!empty($aiAnalysis['action_plan']))
                        <div class="card p-5 space-y-3">
                            <div class="flex items-center gap-2">
                                <span class="p-1.5 rounded-lg bg-amber-50 text-amber-700 text-sm">🎯</span>
                                <h3 class="font-bold text-sm text-ink">Rencana Aksi Eksekusi Bisnis (Action Plan)</h3>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
                                @foreach($aiAnalysis['action_plan'] as $idx => $step)
                                    <div class="p-3.5 rounded-xl border space-y-1.5" style="background:#FAF8F5; border-color:#E5E0D8;">
                                        <span class="text-[10px] font-bold text-amber-800 uppercase tracking-wider block">Langkah {{ $idx + 1 }}</span>
                                        <p class="text-xs font-semibold text-ink leading-snug">{{ is_array($step) ? ($step['tahap'] ?? json_encode($step)) : $step }}</p>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            @else
                <div class="card p-12 text-center space-y-3 border-dashed">
                    <span class="text-4xl block">✨</span>
                    <h3 class="font-serif font-bold text-lg text-ink">Analisa AI Belum Tersedia</h3>
                    <p class="text-xs text-stone-500 max-w-md mx-auto">
                        Pengelola survei belum memproses dokumen rekomendasi intelijen pasar dengan Gemini AI untuk survei ini.
                    </p>
                </div>
            @endif
        </div>

        <!-- ============================================================= -->
        <!-- VIEW MODE 3: JAWABAN PER PERTANYAAN                          -->
        <!-- ============================================================= -->
        <div x-show="activeView === 'QUESTIONS'" x-transition class="space-y-6">
            @foreach($analytics as $index => $item)
                @php
                    $q = $item['question'];
                    $totalResp = $item['total_responses'];
                @endphp
                <div class="card p-6 space-y-4">
                    <div class="flex items-start justify-between gap-4 pb-3 border-b" style="border-color:#E5E0D8;">
                        <div>
                            <span class="text-[10px] font-bold uppercase tracking-wider" style="color:#C89D54;">Pertanyaan {{ $index + 1 }}</span>
                            <h3 class="font-bold text-base text-ink">{{ $q->teks_pertanyaan }}</h3>
                            <span class="text-[10px] uppercase font-bold px-2 py-0.5 rounded-md border mt-1 inline-block" :class="getTypeBadgeClass('{{ $q->tipe_pertanyaan }}')">
                                {{ $q->tipe_pertanyaan }}
                            </span>
                        </div>
                        <span class="text-xs font-bold text-stone-600">{{ $totalResp }} Respon</span>
                    </div>

                    @if(in_array($q->tipe_pertanyaan, ['SINGLE_CHOICE', 'MULTIPLE_CHOICE', 'LIKERT']))
                        <div class="space-y-2">
                            @foreach($item['counts'] ?? [] as $opt => $cnt)
                                @php $pct = $totalResp > 0 ? round(($cnt / $totalResp) * 100, 1) : 0; @endphp
                                <div class="p-3 rounded-xl border flex items-center justify-between text-xs" style="background:#FAF8F5; border-color:#E5E0D8;">
                                    <span class="font-bold text-ink">{{ $opt }}</span>
                                    <span class="font-semibold text-stone-700">{{ $cnt }} responden ({{ $pct }}%)</span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="space-y-2 max-h-64 overflow-y-auto pr-1">
                            @forelse($item['responses'] ?? [] as $r)
                                <div class="p-3 rounded-xl border text-xs" style="background:#FAF8F5; border-color:#E5E0D8;">
                                    {{ $r }}
                                </div>
                            @empty
                                <p class="text-xs text-stone-400 italic">Belum ada respon terkumpul.</p>
                            @endforelse
                        </div>
                    @endif
                </div>
            @endforeach
        </div>

        <!-- ============================================================= -->
        <!-- VIEW MODE 4: DETAIL JAWABAN INDIVIDUAL                       -->
        <!-- ============================================================= -->
        <div x-show="activeView === 'INDIVIDUAL'" x-transition class="space-y-4">
            <div class="card p-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div>
                    <h3 class="font-bold text-sm text-ink">Daftar Pengisian Jawaban</h3>
                    <p class="text-xs text-stone-500">Telusuri seluruh respon individual responden</p>
                </div>
                <input type="text" x-model="searchNisn" placeholder="Cari NISN..."
                       class="px-3 py-2 border rounded-xl text-xs font-medium text-ink focus:outline-none focus:ring-2"
                       style="background:#FAF8F5; border-color:#E5E0D8;">
            </div>

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
                                    <td class="p-4 text-right">
                                        <button type="button" @click="openDetail('{{ $resp->id }}', '{{ $resp->nisn }}', {{ json_encode($resp->answers) }})"
                                                class="px-3 py-1.5 rounded-lg border font-bold text-[11px] transition cursor-pointer"
                                                style="background:#FAF8F5; border-color:#E5E0D8; color:#1C1917;">
                                            Lihat Jawaban
                                        </button>
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

    </main>

    <!-- ── Modal Detail Individual ────────────────────────────────────── -->
    <div x-show="detailOpen" x-transition.opacity class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/35" style="display:none;">
        <div @click.away="detailOpen = false" class="card w-full max-w-2xl p-6 space-y-4 max-h-[85vh] overflow-y-auto" style="background:#FFFFFF;">
            <div class="flex items-center justify-between pb-3 border-b" style="border-color:#E5E0D8;">
                <div>
                    <span class="text-[10px] font-bold uppercase tracking-widest block" style="color:#C89D54;">DETAIL JAWABAN INDIVIDUAL</span>
                    <h3 class="font-bold text-lg text-ink">Responden NISN: <span x-text="detailNisn"></span></h3>
                </div>
                <button type="button" @click="detailOpen = false" class="p-2 text-stone-400 hover:text-stone-700 cursor-pointer">✕</button>
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
                <button type="button" @click="detailOpen = false" class="px-4 py-2 font-bold text-xs rounded-xl border cursor-pointer" style="background:#1C1917; color:#FFFFFF;">Tutup</button>
            </div>
        </div>
    </div>

    <!-- ── Public Footer ──────────────────────────────────────────────── -->
    <footer class="mt-12 py-6 border-t border-surface-divider bg-white text-center">
        <p class="text-xs text-stone-500 font-medium">
            Platform Riset Pasar & Intelijen Bisnis &copy; {{ date('Y') }} SMKN 2 Indramayu. Ditenagai oleh Google Gemini AI.
        </p>
    </footer>

</div>

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
                            labels: { boxWidth: 12, padding: 14, font: { family: 'Plus Jakarta Sans', size: 11, weight: '600' }, color: '#44403C' }
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
                btn.className = 'px-2.5 py-1 rounded text-[11px] font-bold transition flex items-center gap-1 bg-stone-900 text-white shadow-xs cursor-pointer';
            } else {
                btn.className = 'px-2.5 py-1 rounded text-[11px] font-bold transition flex items-center gap-1 text-stone-600 hover:text-stone-900 cursor-pointer';
            }
        });
    }
}

document.addEventListener('DOMContentLoaded', () => {
    setTimeout(() => initAllCharts(), 150);
});

function publicAnalyticsApp() {
    return {
        activeView: '{{ request("tab", "SUMMARY") }}',
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

</body>
</html>
