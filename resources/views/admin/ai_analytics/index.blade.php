@extends('layouts.admin')
@section('title', 'Pilih Survey — Analisa Pasar AI')

@section('content')
<div class="space-y-6 pb-8">

    <!-- ── Header Banner ───────────────────────────────────────────────── -->
    <div class="card p-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="px-2.5 py-0.5 rounded-md text-[10px] font-bold uppercase tracking-wider border"
                      style="background:#FAF6EE; border-color:#E5E0D8; color:#C89D54;">
                    ✨ PUSAT ANALISA SURVEY
                </span>
            </div>
            <h1 class="font-serif font-bold text-2xl text-ink">Analisa & Intelijen Survey Riset</h1>
            <p class="text-xs mt-1" style="color:#6E675F;">
                Pilih survey di bawah ini untuk mengakses <strong>Analitik Data Mentah/Grafik</strong> atau <strong>Analisa AI & Dokumen Eksekutif</strong>.
            </p>
        </div>

        <a href="{{ route('admin.surveys.index') }}"
           class="px-4 py-2.5 rounded-xl border text-xs font-bold transition flex items-center gap-1.5 self-start md:self-auto"
           style="background:#FAF8F5; border-color:#E5E0D8; color:#1C1917;">
            <span>Kembali ke Manajemen Survey</span>
        </a>
    </div>

    <!-- ── Filter & Search Bar ───────────────────────────────────────────── -->
    <div class="card p-4 flex flex-col sm:flex-row items-center justify-between gap-3">
        <form method="GET" action="{{ route('admin.ai-analytics.index') }}" class="w-full flex flex-col sm:flex-row gap-3">
            <!-- Search -->
            <div class="relative flex-1">
                <svg class="w-4 h-4 absolute left-3.5 top-1/2 -translate-y-1/2" style="color:#A19A91;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Cari berdasarkan judul survey atau topik..."
                       class="w-full pl-10 pr-4 py-2.5 rounded-xl border text-xs font-medium text-ink focus:outline-none focus:ring-2"
                       style="background:#FAF8F5; border-color:#E5E0D8;">
            </div>

            <!-- Status Filter -->
            <select name="status" onchange="this.form.submit()"
                    class="px-4 py-2.5 rounded-xl border text-xs font-semibold text-ink focus:outline-none focus:ring-2"
                    style="background:#FAF8F5; border-color:#E5E0D8;">
                <option value="">Semua Status</option>
                <option value="PUBLISHED" {{ request('status') === 'PUBLISHED' ? 'selected' : '' }}>Published (Aktif)</option>
                <option value="DRAFT" {{ request('status') === 'DRAFT' ? 'selected' : '' }}>Draft</option>
                <option value="CLOSED" {{ request('status') === 'CLOSED' ? 'selected' : '' }}>Selesai (Closed)</option>
            </select>
        </form>
    </div>

    <!-- ── Survey Cards Grid ─────────────────────────────────────────────── -->
    @if ($surveys->isEmpty())
        <div class="card p-12 text-center space-y-4">
            <div class="w-14 h-14 rounded-2xl mx-auto flex items-center justify-center text-2xl border"
                 style="background:#FAF6EE; border-color:#E5E0D8;">📊</div>
            <h3 class="font-serif font-bold text-lg text-ink">Belum Ada Survey Ditemukan</h3>
            <p class="text-xs max-w-sm mx-auto" style="color:#6E675F;">
                Tidak ada survey yang sesuai dengan pencarian Anda. Silakan buat survey riset baru atau ubah filter pencarian.
            </p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($surveys as $survey)
                <div class="card p-6 flex flex-col justify-between space-y-5 transition-all duration-200 hover:-translate-y-1 hover:shadow-md"
                     style="border-color:#E5E0D8;">

                    <div class="space-y-3">
                        <!-- Status Badge -->
                        <div class="flex items-center justify-between">
                            @if ($survey->status === 'PUBLISHED')
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider border flex items-center gap-1.5"
                                      style="background:#EDFAF2; border-color:#A3E6CD; color:#1A5C38;">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                    <span>Published</span>
                                </span>
                            @elseif ($survey->status === 'DRAFT')
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider border flex items-center gap-1.5"
                                      style="background:#FAF8F5; border-color:#E5E0D8; color:#6E675F;">
                                    <span>Draft</span>
                                </span>
                            @else
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider border"
                                      style="background:#FDECEA; border-color:#F5B7B1; color:#922B21;">
                                    <span>Closed</span>
                                </span>
                            @endif

                            <span class="text-[11px] font-semibold" style="color:#A19A91;">
                                {{ $survey->respondents_count }} Responden
                            </span>
                        </div>

                        <!-- Title & Description -->
                        <div>
                            <h3 class="font-serif font-bold text-base text-ink line-clamp-2 leading-snug">
                                {{ $survey->judul }}
                            </h3>
                            <p class="text-xs mt-1.5 line-clamp-3 leading-relaxed" style="color:#6E675F;">
                                {{ $survey->deskripsi ?? 'Tidak ada deskripsi survey.' }}
                            </p>
                        </div>
                    </div>

                    <!-- Meta Details & Action Buttons -->
                    <div class="pt-4 border-t space-y-4" style="border-color:#EBE6DE;">
                        <div class="flex items-center justify-between text-xs" style="color:#6E675F;">
                            <span>{{ $survey->questions_count }} Pertanyaan</span>
                            <span>{{ $survey->created_at->format('d M Y') }}</span>
                        </div>

                        <div>
                            <a href="{{ route('admin.surveys.analytics', $survey->id) }}"
                               class="w-full py-2.5 px-4 rounded-xl font-bold text-xs shadow-xs transition-all duration-200 flex items-center justify-center gap-2"
                               style="background:#1C1917; color:#FFFFFF;"
                               onmouseover="this.style.background='#2C2723'"
                               onmouseout="this.style.background='#1C1917'">
                                <svg class="w-3.5 h-3.5" style="color:#C89D54;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                                <span>Lihat Analitik & Intelijen AI</span>
                                <span style="color:#C89D54;">→</span>
                            </a>
                        </div>
                    </div>

                </div>
            @endforeach
        </div>

        <div class="pt-4">
            {{ $surveys->links() }}
        </div>
    @endif

</div>
@endsection
