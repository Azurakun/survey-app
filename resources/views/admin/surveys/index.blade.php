@extends('layouts.admin')
@section('title', 'Manajemen Survey — Survey Riset Pasar SMKN 2 Indramayu')

@section('content')
<div x-data="surveyIndex()" class="space-y-6 pb-8">

    <!-- ── Page Header ──────────────────────────────────────────────────── -->
    <div class="bg-white rounded-2xl border border-neutral-border p-5 sm:p-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="text-[10px] font-sans uppercase tracking-widest text-terra font-semibold mb-1">PENGELOLAAN PROYEK</div>
            <h1 class="font-serif font-bold text-2xl md:text-3xl text-neutral-dark">Manajemen Survey</h1>
            <p class="text-xs text-neutral-muted mt-1">Buat, kelola, dan publikasikan survey riset pasar untuk program kewirausahaan.</p>
        </div>
        <a href="{{ route('admin.surveys.create') }}"
           class="inline-flex items-center gap-2 px-5 py-3 bg-mango hover:bg-mango-dark text-neutral-dark
                  font-bold text-xs rounded-xl transition shadow-sm shrink-0 active:scale-95">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Buat Survey Baru
        </a>
    </div>

    <!-- ── Filter Tabs ───────────────────────────────────────────────────── -->
    <div class="bg-white rounded-2xl border border-neutral-border p-2 flex flex-wrap gap-1">
        @foreach(['ALL' => 'Semua', 'PUBLISHED' => 'Published', 'DRAFT' => 'Draft', 'CLOSED' => 'Closed'] as $key => $label)
            <button @click="activeTab = '{{ $key }}'"
                    :class="activeTab === '{{ $key }}'
                        ? 'bg-leaf text-white shadow-sm'
                        : 'text-neutral-muted hover:bg-neutral-bg'"
                    class="px-4 py-2 rounded-xl text-xs font-bold transition-all duration-200">
                {{ $label }}
                @php $count = $key === 'ALL' ? $surveys->count() : $surveys->where('status', $key)->count(); @endphp
                <span :class="activeTab === '{{ $key }}' ? 'bg-white/20 text-white' : 'bg-neutral-border text-neutral-muted'"
                      class="ml-1.5 text-[10px] px-1.5 py-0.5 rounded-full font-bold">{{ $count }}</span>
            </button>
        @endforeach
    </div>

    <!-- ── Toast Clipboard ───────────────────────────────────────────────── -->
    <div x-show="toast"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed bottom-6 right-6 z-50 bg-leaf text-white px-5 py-3 rounded-xl shadow-lg font-semibold text-sm flex items-center gap-2"
         style="display:none;">
        <svg class="w-4 h-4 text-mango" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
        Link survey berhasil disalin!
    </div>

    <!-- ── Survey Grid ───────────────────────────────────────────────────── -->
    @if($surveys->isEmpty())
        <div class="text-center py-16 bg-white rounded-2xl border border-dashed border-neutral-border">
            <div class="text-6xl mb-4">📋</div>
            <h2 class="font-serif font-bold text-xl text-neutral-dark">Belum Ada Survey</h2>
            <p class="text-xs text-neutral-muted mt-2 mb-6">Klik tombol di atas untuk membuat survey riset pasar pertama Anda.</p>
            <a href="{{ route('admin.surveys.create') }}"
               class="inline-flex items-center gap-2 px-5 py-2.5 bg-mango text-neutral-dark text-sm font-bold rounded-xl hover:bg-mango-dark transition">
                Buat Survey Pertama
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
            @foreach($surveys as $survey)
            @php
                $periodStatus = $survey->period_status;
            @endphp
            <div x-show="activeTab === 'ALL' || activeTab === '{{ $survey->status }}'"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 class="bg-white rounded-2xl border border-neutral-border hover:border-mango/50 hover:shadow-md transition-all duration-200 flex flex-col">

                <!-- Card Header -->
                <div class="p-5 border-b border-neutral-border flex items-start justify-between gap-3">
                    <div class="flex-1 min-w-0">
                        <!-- Status Badge -->
                        <div class="flex items-center gap-2 mb-2 flex-wrap">
                            @if($survey->status === 'PUBLISHED')
                                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-leaf-tint text-leaf uppercase tracking-wider">● Published</span>
                            @elseif($survey->status === 'DRAFT')
                                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-neutral-bg border border-neutral-border text-neutral-muted uppercase tracking-wider">○ Draft</span>
                            @else
                                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-terra-tint text-terra uppercase tracking-wider">✕ Closed</span>
                            @endif

                            {{-- F6: Period Status Indicator --}}
                            @if($periodStatus === 'AKTIF')
                                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-mango-tint text-mango-dark uppercase tracking-wider">⏳ Aktif</span>
                            @elseif($periodStatus === 'BELUM_MULAI')
                                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-neutral-bg border border-neutral-border text-neutral-muted uppercase tracking-wider">⚠️ Belum Mulai</span>
                            @elseif($periodStatus === 'BERAKHIR')
                                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-terra-tint text-terra uppercase tracking-wider">🔒 Berakhir</span>
                            @endif
                        </div>
                        <h2 class="font-serif font-bold text-base text-neutral-dark leading-snug line-clamp-2">{{ $survey->judul }}</h2>
                    </div>
                </div>

                <!-- Card Body -->
                <div class="p-5 flex-1 space-y-3">
                    @if($survey->deskripsi)
                        <p class="text-xs text-neutral-muted leading-relaxed line-clamp-2">{{ $survey->deskripsi }}</p>
                    @endif

                    <!-- Stats Row -->
                    <div class="flex items-center gap-4 text-xs font-semibold">
                        <span class="flex items-center gap-1.5 text-neutral-muted">
                            <svg class="w-3.5 h-3.5 text-leaf" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            {{ $survey->questions_count }} Pertanyaan
                        </span>
                        <span class="flex items-center gap-1.5 text-mango-dark font-bold">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            {{ $survey->respondents_count }} Responden
                        </span>
                    </div>

                    <!-- Dates -->
                    @if($survey->tanggal_mulai || $survey->tanggal_selesai)
                        <div class="flex items-center gap-1.5 text-[11px] text-neutral-muted bg-neutral-bg/60 rounded-lg px-3 py-1.5">
                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            {{ $survey->tanggal_mulai?->format('d M Y') ?? '–' }} — {{ $survey->tanggal_selesai?->format('d M Y') ?? '–' }}
                        </div>
                    @endif

                    <!-- Share Link -->
                    @if($survey->status === 'PUBLISHED')
                        <div class="flex items-center gap-2 bg-neutral-bg rounded-xl border border-neutral-border p-2.5">
                            <span class="text-[10px] font-sans text-neutral-muted truncate flex-1 select-all">
                                {{ route('student.survey', $survey->id) }}
                            </span>
                            <button @click="copyLink('{{ route('student.survey', $survey->id) }}')"
                                    class="shrink-0 px-2.5 py-1 text-[10px] font-bold bg-mango/20 text-mango-dark rounded-lg hover:bg-mango hover:text-neutral-dark transition">
                                Salin
                            </button>
                        </div>
                    @endif
                </div>

                <!-- Card Footer Actions -->
                <div class="px-5 py-3.5 border-t border-neutral-border flex items-center justify-between gap-2">
                    <div class="flex items-center gap-2">
                        <a href="{{ route('admin.surveys.builder', $survey->id) }}"
                           class="inline-flex items-center gap-1.5 px-3 py-1.5 text-[11px] font-bold
                                  bg-neutral-bg border border-neutral-border text-neutral-dark rounded-lg hover:bg-neutral-border transition">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            Builder
                        </a>
                        <a href="{{ route('admin.surveys.analytics', $survey->id) }}"
                           class="inline-flex items-center gap-1.5 px-3 py-1.5 text-[11px] font-bold
                                  bg-leaf-tint text-leaf rounded-lg hover:bg-leaf hover:text-white transition">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                            Analitik
                        </a>
                    </div>
                    <!-- Delete -->
                    <form method="POST" action="{{ route('admin.surveys.destroy', $survey->id) }}"
                          onsubmit="return confirm('Hapus survey \'{{ addslashes($survey->judul) }}\' beserta seluruh data responden? Tindakan ini tidak dapat dibatalkan.')">
                        @csrf @method('DELETE')
                        <button type="submit"
                                class="p-1.5 text-terra hover:bg-terra-tint rounded-lg transition">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
    @endif

</div>
@endsection

@section('scripts')
<script>
function surveyIndex() {
    return {
        activeTab: 'ALL',
        toast: false,

        copyLink(url) {
            navigator.clipboard.writeText(url).then(() => {
                this.toast = true;
                setTimeout(() => this.toast = false, 2500);
            });
        }
    }
}
</script>
@endsection
