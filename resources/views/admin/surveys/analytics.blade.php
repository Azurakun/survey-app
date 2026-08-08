@extends('layouts.admin')
@section('title', 'Analitik — {{ $survey->judul }}')

@section('content')
<div x-data="analyticsApp()" class="space-y-6 pb-10">

    <!-- ── Page Header ─────────────────────────────────────────────────── -->
    <div class="bg-white rounded-2xl border border-neutral-border p-5 sm:p-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-start gap-4">
            <a href="{{ route('admin.surveys.index') }}"
               class="mt-0.5 p-2 rounded-lg hover:bg-neutral-bg text-neutral-muted hover:text-neutral-dark transition">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <div>
                <div class="text-[10px] font-sans uppercase tracking-widest text-terra font-semibold mb-1">ANALITIK SURVEY</div>
                <h1 class="font-serif font-bold text-xl md:text-2xl text-neutral-dark leading-tight">{{ $survey->judul }}</h1>
                <div class="flex items-center gap-2 mt-2">
                    @if($survey->status === 'PUBLISHED')
                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-leaf-tint text-leaf uppercase">● Published</span>
                    @elseif($survey->status === 'DRAFT')
                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-neutral-bg border border-neutral-border text-neutral-muted uppercase">○ Draft</span>
                    @else
                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-terra-tint text-terra uppercase">✕ Closed</span>
                    @endif
                    <span class="text-xs text-neutral-muted">{{ $totalRespondents }} responden</span>
                </div>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.surveys.export', $survey->id) }}"
               class="inline-flex items-center gap-2 px-4 py-2.5 bg-leaf text-white font-bold text-xs rounded-xl hover:bg-leaf-dark transition shadow-sm">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                Ekspor XLSX
            </a>
            <a href="{{ route('admin.surveys.builder', $survey->id) }}"
               class="inline-flex items-center gap-2 px-4 py-2.5 bg-neutral-bg border border-neutral-border text-neutral-dark font-bold text-xs rounded-xl hover:bg-neutral-border transition">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                Builder
            </a>
        </div>
    </div>

    <!-- ── Date Filter ──────────────────────────────────────────────────── -->
    <form method="GET" action="{{ route('admin.surveys.analytics', $survey->id) }}"
          class="bg-white rounded-2xl border border-neutral-border p-4 flex flex-wrap items-end gap-4">
        <div>
            <label class="block text-[10px] font-sans uppercase tracking-widest font-semibold text-neutral-muted mb-1">Dari Tanggal</label>
            <input type="date" name="start_date" value="{{ request('start_date') }}"
                   class="px-3 py-2 border border-neutral-border rounded-lg text-sm text-neutral-dark bg-neutral-bg/50 focus:ring-2 focus:ring-mango focus:outline-none">
        </div>
        <div>
            <label class="block text-[10px] font-sans uppercase tracking-widest font-semibold text-neutral-muted mb-1">Sampai Tanggal</label>
            <input type="date" name="end_date" value="{{ request('end_date') }}"
                   class="px-3 py-2 border border-neutral-border rounded-lg text-sm text-neutral-dark bg-neutral-bg/50 focus:ring-2 focus:ring-mango focus:outline-none">
        </div>
        <button type="submit"
                class="px-4 py-2 bg-mango hover:bg-mango-dark text-neutral-dark font-bold text-xs rounded-lg transition">
            Filter Data
        </button>
        @if(request('start_date') || request('end_date'))
            <a href="{{ route('admin.surveys.analytics', $survey->id) }}"
               class="px-4 py-2 bg-neutral-bg border border-neutral-border text-neutral-muted font-semibold text-xs rounded-lg hover:bg-neutral-border transition">
                Reset Filter
            </a>
        @endif
    </form>

    <!-- ── Per-Question Analytics Charts ─────────────────────────────── -->
    @if(count($analytics) > 0)
        @foreach($analytics as $aIdx => $item)
        @php
            $q = $item['question'];
            $chartId = 'chart_' . $aIdx;
        @endphp
        <div class="bg-white rounded-2xl border border-neutral-border p-5 sm:p-6 space-y-4">

            <!-- Question Header -->
            <div class="flex items-start gap-3 border-b border-neutral-border pb-4">
                <span class="w-8 h-8 rounded-lg bg-mango text-neutral-dark font-serif font-bold text-sm flex items-center justify-center shrink-0">
                    {{ $aIdx + 1 }}
                </span>
                <div class="flex-1 min-w-0">
                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-neutral-bg border border-neutral-border text-neutral-muted uppercase tracking-wider">
                        {{ $q->tipe_pertanyaan }}
                    </span>
                    <h3 class="font-serif font-bold text-base text-neutral-dark mt-1 leading-snug">{{ $q->teks_pertanyaan }}</h3>
                    <p class="text-xs text-neutral-muted mt-0.5">{{ $item['total_responses'] }} jawaban terkumpul</p>
                </div>
            </div>

            {{-- SINGLE_CHOICE & MULTIPLE_CHOICE: Donut + Bar --}}
            @if(in_array($q->tipe_pertanyaan, ['SINGLE_CHOICE', 'MULTIPLE_CHOICE']))
                @php $counts = $item['counts']; $total = array_sum($counts); @endphp
                @if($total > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-center">
                        <div class="relative h-56">
                            <canvas id="{{ $chartId }}_donut"></canvas>
                        </div>
                        <div class="space-y-2">
                            @php $colors = ['#E8A33D','#2F5233','#D9622B','#7A9E7E','#F2C879','#8C4A2F','#5A7A5E','#C7842A']; $ci = 0; @endphp
                            @foreach($counts as $opt => $cnt)
                                @php $pct = $total > 0 ? round($cnt / $total * 100) : 0; @endphp
                                <div class="space-y-1">
                                    <div class="flex justify-between text-xs font-semibold">
                                        <span class="text-neutral-dark truncate max-w-[65%]">{{ $opt }}</span>
                                        <span class="text-neutral-muted tabular-nums">{{ $cnt }} ({{ $pct }}%)</span>
                                    </div>
                                    <div class="w-full bg-neutral-bg rounded-full h-2">
                                        <div class="h-2 rounded-full transition-all duration-500"
                                             style="width:{{ $pct }}%; background:{{ $colors[$ci % count($colors)] }}"></div>
                                    </div>
                                </div>
                                @php $ci++; @endphp
                            @endforeach
                        </div>
                    </div>
                    <script>
                        document.addEventListener('DOMContentLoaded', () => {
                            const ctx{{ $aIdx }} = document.getElementById('{{ $chartId }}_donut');
                            if (ctx{{ $aIdx }}) {
                                new Chart(ctx{{ $aIdx }}, {
                                    type: 'doughnut',
                                    data: {
                                        labels: @json(array_keys($counts)),
                                        datasets: [{ data: @json(array_values($counts)), backgroundColor: ['#E8A33D','#2F5233','#D9622B','#7A9E7E','#F2C879','#8C4A2F'], borderWidth: 2, borderColor: '#fff' }]
                                    },
                                    options: { responsive:true, maintainAspectRatio:false, plugins:{ legend:{ position:'bottom', labels:{ font:{ family:'Plus Jakarta Sans', size:11 }, boxWidth:12, padding:12 } } } }
                                });
                            }
                        });
                    </script>
                @else
                    <p class="text-xs text-neutral-muted py-4 text-center">Belum ada jawaban untuk pertanyaan ini.</p>
                @endif

            {{-- LIKERT: Horizontal bar + avg card --}}
            @elseif($q->tipe_pertanyaan === 'LIKERT')
                @php $counts = $item['counts']; $total = array_sum($counts); @endphp
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-center">
                    <div class="md:col-span-2 relative h-48">
                        <canvas id="{{ $chartId }}_likert"></canvas>
                    </div>
                    <div class="text-center bg-neutral-bg rounded-2xl border border-neutral-border p-5">
                        <div class="text-[10px] font-sans uppercase tracking-widest text-neutral-muted font-semibold mb-2">RATA-RATA SKOR</div>
                        <div class="font-serif text-5xl font-bold text-mango">{{ $item['average'] }}</div>
                        <div class="text-xs text-neutral-muted mt-1">dari skala 1–5</div>
                    </div>
                </div>
                <script>
                    document.addEventListener('DOMContentLoaded', () => {
                        const ctx{{ $aIdx }} = document.getElementById('{{ $chartId }}_likert');
                        if(ctx{{ $aIdx }}) {
                            new Chart(ctx{{ $aIdx }}, {
                                type: 'bar',
                                data: {
                                    labels: ['1 ⭐','2 ⭐⭐','3 ⭐⭐⭐','4 ⭐⭐⭐⭐','5 ⭐⭐⭐⭐⭐'],
                                    datasets: [{ label:'Jumlah', data: @json(array_values($counts)), backgroundColor:['#D9622B','#E8A33D','#F2C879','#7A9E7E','#2F5233'], borderRadius:8, borderSkipped:false }]
                                },
                                options: { indexAxis:'y', responsive:true, maintainAspectRatio:false, plugins:{ legend:{ display:false } }, scales:{ x:{ grid:{ color:'#EDE2D0' }, ticks:{ font:{ family:'Plus Jakarta Sans', size:11 } } }, y:{ grid:{ display:false }, ticks:{ font:{ family:'Plus Jakarta Sans', size:11 } } } } }
                            });
                        }
                    });
                </script>

            {{-- NUMBER: 4 stat boxes --}}
            @elseif($q->tipe_pertanyaan === 'NUMBER')
                @php $s = $item['stats']; @endphp
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    @foreach(['count' => ['Jumlah Jawaban', ''], 'average' => ['Rata-rata', 'Rp'], 'min' => ['Terendah', 'Rp'], 'max' => ['Tertinggi', 'Rp']] as $key => [$label, $prefix])
                        <div class="bg-neutral-bg rounded-xl border border-neutral-border p-4 text-center">
                            <div class="text-[10px] font-sans uppercase tracking-widest text-neutral-muted font-semibold mb-2">{{ $label }}</div>
                            <div class="font-serif font-bold text-xl text-neutral-dark">
                                {{ $prefix }}{{ number_format($s[$key], 0, ',', '.') }}
                            </div>
                        </div>
                    @endforeach
                </div>

            {{-- IMAGE_UPLOAD: Grid thumbnails --}}
            @elseif($q->tipe_pertanyaan === 'IMAGE_UPLOAD')
                @php $images = $item['responses']; @endphp
                @if(count($images) > 0)
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3">
                        @foreach($images as $imgUrl)
                            <a href="{{ $imgUrl }}" target="_blank"
                               class="block rounded-xl overflow-hidden border-2 border-neutral-border hover:border-mango transition-all aspect-square group">
                                <img src="{{ $imgUrl }}" alt="Foto responden"
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            </a>
                        @endforeach
                    </div>
                @else
                    <p class="text-xs text-neutral-muted py-4 text-center">Belum ada foto yang diunggah.</p>
                @endif

            {{-- SHORT_TEXT / LONG_TEXT / DATE: Scrollable list --}}
            @else
                @php $responses = $item['responses']; @endphp
                @if(count($responses) > 0)
                    <div class="max-h-60 overflow-y-auto space-y-2 pr-1">
                        @foreach($responses as $ri => $resp)
                            <div class="flex items-start gap-3 p-3 bg-neutral-bg/70 rounded-lg border border-neutral-border text-xs">
                                <span class="text-[10px] font-bold text-neutral-muted w-5 shrink-0 tabular-nums">{{ $ri + 1 }}</span>
                                <p class="text-neutral-dark leading-relaxed">{{ $resp }}</p>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-xs text-neutral-muted py-4 text-center">Belum ada jawaban teks.</p>
                @endif
            @endif

        </div>
        @endforeach
    @else
        <div class="text-center py-10 bg-white rounded-2xl border border-neutral-border">
            <div class="text-4xl mb-2">📊</div>
            <p class="text-sm font-medium text-neutral-muted">Survey belum memiliki pertanyaan.</p>
        </div>
    @endif

    <!-- ── Respondents Table ────────────────────────────────────────────── -->
    <div class="bg-white rounded-2xl border border-neutral-border p-5 sm:p-6 space-y-4">
        <div class="flex items-center justify-between flex-wrap gap-3 border-b border-neutral-border pb-4">
            <div>
                <div class="text-[10px] font-sans uppercase tracking-widest text-terra font-semibold mb-0.5">DATA RESPONDEN</div>
                <h2 class="font-serif font-bold text-lg text-neutral-dark">Daftar Jawaban Siswa</h2>
            </div>
            <!-- Live Search NISN -->
            <div class="relative">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-neutral-muted" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input type="text" x-model="searchNisn" placeholder="Cari NISN..."
                       class="pl-9 pr-4 py-2 border border-neutral-border rounded-xl text-sm text-neutral-dark bg-neutral-bg/50 focus:ring-2 focus:ring-mango focus:outline-none w-48">
            </div>
        </div>

        @if($respondents->isEmpty())
            <div class="text-center py-10 bg-neutral-bg/50 rounded-xl border border-dashed border-neutral-border">
                <div class="text-4xl mb-2">👥</div>
                <p class="text-xs font-medium text-neutral-muted">Belum ada responden yang mengisi survey ini.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="bg-neutral-bg border-b border-neutral-border text-neutral-muted font-semibold uppercase tracking-wider text-[10px]">
                            <th class="px-4 py-3 rounded-l-lg">No</th>
                            <th class="px-4 py-3">NISN Siswa</th>
                            <th class="px-4 py-3">Waktu Pengisian</th>
                            <th class="px-4 py-3 rounded-r-lg text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-border">
                        @foreach($respondents as $ri => $respondent)
                        <tr x-show="!searchNisn || '{{ $respondent->nisn }}'.includes(searchNisn)"
                            class="hover:bg-neutral-bg/50 transition">
                            <td class="px-4 py-3.5 text-neutral-muted tabular-nums">{{ $ri + 1 }}</td>
                            <td class="px-4 py-3.5 font-bold font-mono text-neutral-dark tracking-wide text-sm">{{ $respondent->nisn }}</td>
                            <td class="px-4 py-3.5 text-neutral-muted">
                                {{ $respondent->submitted_at?->format('d M Y, H:i') ?? $respondent->created_at->format('d M Y, H:i') }}
                            </td>
                            <td class="px-4 py-3.5 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button @click="openDetail({{ $respondent->id ? "'" . $respondent->id . "'" : 'null' }}, '{{ $respondent->nisn }}', {{ $respondent->answers->map(fn($a) => ['question' => $a->question?->teks_pertanyaan, 'type' => $a->question?->tipe_pertanyaan, 'jawaban' => $a->jawaban])->toJson() }})"
                                            class="px-3 py-1.5 bg-mango-tint text-mango-dark font-semibold text-[11px] rounded-lg hover:bg-mango hover:text-neutral-dark transition">
                                        Detail
                                    </button>
                                    <form method="POST" action="{{ route('admin.respondents.destroy', $respondent->id) }}"
                                          onsubmit="return confirm('Hapus data responden NISN {{ $respondent->nisn }}? Tindakan ini tidak dapat dibatalkan.')">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                                class="px-3 py-1.5 bg-terra-tint text-terra font-semibold text-[11px] rounded-lg hover:bg-terra hover:text-white transition">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <!-- ── Detail Modal ──────────────────────────────────────────────────── -->
    <div x-show="detailOpen"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click.self="detailOpen = false"
         class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4"
         style="display:none;">

        <div x-show="detailOpen"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-90"
             x-transition:enter-end="opacity-100 scale-100"
             class="bg-white rounded-2xl border border-neutral-border shadow-xl max-w-lg w-full max-h-[80vh] flex flex-col">

            <!-- Modal Header -->
            <div class="flex items-center justify-between p-5 border-b border-neutral-border shrink-0">
                <div>
                    <div class="text-[10px] font-sans uppercase tracking-widest text-terra font-semibold">DETAIL JAWABAN</div>
                    <h3 class="font-serif font-bold text-lg text-neutral-dark">
                        NISN: <span x-text="detailNisn" class="text-mango font-mono"></span>
                    </h3>
                </div>
                <button @click="detailOpen = false"
                        class="p-2 rounded-lg hover:bg-neutral-bg text-neutral-muted transition">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="p-5 space-y-3 overflow-y-auto flex-1">
                <template x-for="(ans, idx) in detailAnswers" :key="idx">
                    <div class="p-3.5 rounded-xl bg-neutral-bg/60 border border-neutral-border space-y-1.5">
                        <div class="flex items-center gap-2">
                            <span class="text-[10px] font-bold px-2 py-0.5 rounded bg-white border border-neutral-border text-neutral-muted uppercase tracking-wider"
                                  x-text="ans.type"></span>
                            <span class="text-[9px] text-neutral-muted font-semibold" x-text="'#' + (idx+1)"></span>
                        </div>
                        <p class="text-xs font-semibold text-neutral-dark" x-text="ans.question"></p>
                        <template x-if="ans.type === 'IMAGE_UPLOAD'">
                            <a :href="ans.jawaban" target="_blank"
                               class="block">
                                <img :src="ans.jawaban" class="w-full max-h-40 object-cover rounded-lg border border-neutral-border">
                            </a>
                        </template>
                        <template x-if="ans.type !== 'IMAGE_UPLOAD'">
                            <p class="text-xs text-neutral-dark bg-white rounded-lg px-3 py-2 border border-neutral-border" x-text="ans.jawaban || '–'"></p>
                        </template>
                    </div>
                </template>
                <template x-if="detailAnswers.length === 0">
                    <p class="text-center text-xs text-neutral-muted py-6">Tidak ada jawaban tersimpan untuk responden ini.</p>
                </template>
            </div>
        </div>
    </div>

</div>
@endsection

@section('scripts')
<script>
function analyticsApp() {
    return {
        searchNisn: '',
        detailOpen: false,
        detailNisn: '',
        detailAnswers: [],

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
