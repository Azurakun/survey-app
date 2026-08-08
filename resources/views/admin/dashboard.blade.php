@extends('layouts.admin')
@section('title', 'Dashboard — Survey Riset Pasar SMKN 2 Indramayu')

@section('content')
<div class="space-y-6 pb-6">

    <!-- ── Page Header ─────────────────────────────────────────────────── -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="text-[10px] font-sans uppercase tracking-widest font-bold mb-1" style="color:#A09488;">MARKET ANALYTICS FRAMEWORK</div>
            <h1 class="font-serif font-bold text-2xl xl:text-3xl text-ink leading-tight">Dashboard Analisis Pasar</h1>
            <p class="text-xs mt-1 max-w-xl" style="color:#6B6458;">
                Intelijen riset pasar terpadu — indeks minat, daya beli (WTP), dan skor kepuasan produk kewirausahaan.
            </p>
        </div>
        <a href="{{ route('admin.surveys.create') }}"
           class="inline-flex items-center gap-2 px-5 py-2.5 text-xs font-bold rounded-xl transition shrink-0 active:scale-95"
           style="background:#1A1714; color:#FFFFFF;"
           onmouseover="this.style.background='#2B2118'" onmouseout="this.style.background='#1A1714'">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Buat Survey Baru
        </a>
    </div>

    <!-- ── KPI Cards (Staggered Animation) ────────────────────────────── -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

        <!-- 01 Demand Index -->
        <div class="kpi-card-1 card p-5">
            <div class="flex items-center justify-between mb-3">
                <div class="text-[9px] font-bold uppercase tracking-widest" style="color:#A09488;">INDEKS MINAT</div>
                <div class="w-7 h-7 rounded-lg flex items-center justify-center" style="background:#FBF4E8;">
                    <svg class="w-3.5 h-3.5" style="color:#C9933A;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                </div>
            </div>
            <div class="font-serif text-3xl font-bold text-ink tabular-nums">{{ $marketDemandIndex }}%</div>
            <div class="flex items-center gap-1.5 mt-2">
                <div class="w-1.5 h-1.5 rounded-full" style="background:#4A7A5E;"></div>
                <span class="text-[10px] font-medium" style="color:#6B6458;">Sentimen Konsumen Positif</span>
            </div>
        </div>

        <!-- 02 Avg WTP -->
        <div class="kpi-card-2 card p-5">
            <div class="flex items-center justify-between mb-3">
                <div class="text-[9px] font-bold uppercase tracking-widest" style="color:#A09488;">RATA-RATA WTP</div>
                <div class="w-7 h-7 rounded-lg flex items-center justify-center" style="background:#F5F2EC; border:1px solid #DDD7CC;">
                    <span class="text-xs font-bold" style="color:#6B6458;">Rp</span>
                </div>
            </div>
            <div class="font-serif text-xl font-bold text-ink tabular-nums">
                Rp {{ number_format($avgPriceWTP, 0, ',', '.') }}
            </div>
            <div class="text-[10px] font-medium mt-2 tabular-nums" style="color:#6B6458;">
                Rp {{ number_format($minPriceWTP, 0, ',', '.') }} – {{ number_format($maxPriceWTP, 0, ',', '.') }}
            </div>
        </div>

        <!-- 03 Satisfaction -->
        <div class="kpi-card-3 card p-5">
            <div class="flex items-center justify-between mb-3">
                <div class="text-[9px] font-bold uppercase tracking-widest" style="color:#A09488;">SKOR KEPUASAN</div>
                <div class="w-7 h-7 rounded-lg flex items-center justify-center" style="background:#FBF4E8;">
                    <svg class="w-3.5 h-3.5" style="color:#C9933A;" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                </div>
            </div>
            <div class="font-serif text-3xl font-bold text-ink tabular-nums">
                {{ $avgSatisfactionScore }}
                <span class="text-sm font-sans" style="color:#A09488;">/ 5.0</span>
            </div>
            <div class="text-[10px] font-medium mt-2" style="color:#6B6458;">Skala Likert rata-rata</div>
        </div>

        <!-- 04 Total Respondents -->
        <div class="kpi-card-4 card p-5">
            <div class="flex items-center justify-between mb-3">
                <div class="text-[9px] font-bold uppercase tracking-widest" style="color:#A09488;">TOTAL SAMPEL</div>
                <div class="w-7 h-7 rounded-lg flex items-center justify-center" style="background:#F5F2EC; border:1px solid #DDD7CC;">
                    <svg class="w-3.5 h-3.5" style="color:#6B6458;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
            </div>
            <div class="font-serif text-3xl font-bold text-ink tabular-nums">{{ $totalRespondents }}</div>
            <div class="text-[10px] font-medium mt-2" style="color:#6B6458;">Siswa Terverifikasi NISN</div>
        </div>
    </div>

    <!-- ── Charts & Market Synthesis ──────────────────────────────────── -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-5">

        <!-- Sentiment Chart -->
        <div class="lg:col-span-7 card p-5 sm:p-6 space-y-4">
            <div class="flex items-center justify-between pb-4 border-b" style="border-color:#EAE5DC;">
                <div>
                    <div class="text-[9px] font-bold uppercase tracking-widest mb-0.5" style="color:#A09488;">INTELIJEN PASAR</div>
                    <h2 class="font-serif font-bold text-lg text-ink">Distribusi Sentimen Konsumen</h2>
                </div>
                <span class="text-[10px] font-bold px-2.5 py-1 rounded-full border" style="background:#F5F2EC; border-color:#DDD7CC; color:#A09488;">REAL-TIME</span>
            </div>

            @php $totalSent = array_sum($orderedSentiment); @endphp
            @if($totalSent > 0)
                <div class="relative h-52">
                    <canvas id="sentimentChart"></canvas>
                </div>
            @else
                <div class="text-center py-10" style="color:#A09488;">
                    <div class="text-4xl mb-2">📊</div>
                    <p class="text-xs font-medium">Belum ada data responden untuk ditampilkan.</p>
                </div>
            @endif
        </div>

        <!-- Recommendation Card -->
        <div class="lg:col-span-5 card p-5 sm:p-6 space-y-4 flex flex-col" style="background:#1A1714; border-color:#2B2118;">
            <div class="text-[9px] font-bold uppercase tracking-widest" style="color:#C9933A;">REKOMENDASI STRATEGI</div>
            <h2 class="font-serif font-bold text-xl text-white leading-tight">Rekomendasi Produk Kewirausahaan</h2>
            <div class="space-y-3 text-xs border-t pt-4 flex-1" style="border-color:rgba(255,255,255,0.1); color:rgba(255,255,255,0.6);">
                <div class="flex items-start gap-3">
                    <span class="w-6 h-6 rounded-md font-bold text-xs flex items-center justify-center shrink-0 mt-0.5"
                          style="background:#C9933A; color:#1A1714;">1</span>
                    <p><strong class="text-white">Indeks Minat:</strong> Mencapai <strong style="color:#C9933A;">{{ $marketDemandIndex }}%</strong>. Produk memiliki traksi yang {{ $marketDemandIndex >= 60 ? 'kuat' : 'perlu ditingkatkan' }}.</p>
                </div>
                <div class="flex items-start gap-3">
                    <span class="w-6 h-6 rounded-md font-bold text-xs flex items-center justify-center shrink-0 mt-0.5"
                          style="background:rgba(201,147,58,0.2); color:#C9933A;">2</span>
                    <p><strong class="text-white">Pricing Sweet Spot:</strong> Rata-rata WTP konsumen <strong style="color:#C9933A;">Rp {{ number_format($avgPriceWTP, 0, ',', '.') }}</strong>/unit.</p>
                </div>
                <div class="flex items-start gap-3">
                    <span class="w-6 h-6 rounded-md font-bold text-xs flex items-center justify-center shrink-0 mt-0.5"
                          style="background:rgba(201,147,58,0.2); color:#C9933A;">3</span>
                    <p><strong class="text-white">Kepuasan:</strong> Skor rata-rata <strong style="color:#C9933A;">{{ $avgSatisfactionScore }} / 5.0</strong> dari skala Likert.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- ── Survey Performance Table ────────────────────────────────────── -->
    <div class="card p-5 sm:p-6 space-y-4">
        <div class="flex items-center justify-between pb-4 border-b" style="border-color:#EAE5DC;">
            <div>
                <div class="text-[9px] font-bold uppercase tracking-widest mb-0.5" style="color:#A09488;">PERFORMA PROYEK</div>
                <h2 class="font-serif font-bold text-lg text-ink">Kinerja Survey Riset Pasar</h2>
            </div>
            <a href="{{ route('admin.surveys.index') }}"
               class="text-xs font-semibold transition" style="color:#C9933A;"
               onmouseover="this.style.color='#8B5E3C'" onmouseout="this.style.color='#C9933A'">
                Lihat Semua →
            </a>
        </div>

        @if($surveys->isEmpty())
            <div class="text-center py-14 rounded-xl border-2 border-dashed" style="border-color:#DDD7CC; background:#F5F2EC;">
                <div class="text-5xl mb-3">📋</div>
                <h3 class="font-serif font-bold text-lg text-ink">Belum Ada Survey</h3>
                <p class="text-xs mt-1 mb-5" style="color:#6B6458;">Mulai buat survey pertama untuk mengumpulkan data riset pasar siswa.</p>
                <a href="{{ route('admin.surveys.create') }}"
                   class="inline-flex items-center gap-2 px-4 py-2 text-xs font-bold rounded-xl transition"
                   style="background:#1A1714; color:#FFFFFF;">
                    Buat Survey Pertama
                </a>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="border-b text-[10px] font-bold uppercase tracking-wider" style="border-color:#EAE5DC; color:#A09488;">
                            <th class="pb-3 px-1">Survey</th>
                            <th class="pb-3 px-1">Status</th>
                            <th class="pb-3 px-1">Pertanyaan</th>
                            <th class="pb-3 px-1">Responden</th>
                            <th class="pb-3 px-1 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y" style="border-color:#EAE5DC;">
                        @foreach($surveys as $survey)
                        <tr class="hover:bg-surface-subtle transition" style="--tw-bg-opacity:1;">
                            <td class="py-3 px-1">
                                <div class="font-serif font-bold text-sm text-ink">{{ Str::limit($survey->judul, 42) }}</div>
                                <div class="text-[11px] mt-0.5 line-clamp-1" style="color:#A09488;">{{ $survey->deskripsi ?? '–' }}</div>
                            </td>
                            <td class="py-3 px-1">
                                @if($survey->status === 'PUBLISHED')
                                    <span class="text-[10px] font-bold px-2 py-1 rounded-full" style="background:#EDFAF2; color:#1A5C38;">Published</span>
                                @elseif($survey->status === 'DRAFT')
                                    <span class="text-[10px] font-bold px-2 py-1 rounded-full border" style="background:#F5F2EC; color:#A09488; border-color:#DDD7CC;">Draft</span>
                                @else
                                    <span class="text-[10px] font-bold px-2 py-1 rounded-full" style="background:#FDECEA; color:#922B21;">Closed</span>
                                @endif
                            </td>
                            <td class="py-3 px-1 font-semibold text-ink tabular-nums">{{ $survey->questions_count }}</td>
                            <td class="py-3 px-1 font-bold tabular-nums" style="color:#C9933A;">{{ $survey->respondents_count }}</td>
                            <td class="py-3 px-1 text-right">
                                <a href="{{ route('admin.surveys.analytics', $survey->id) }}"
                                   class="inline-flex items-center gap-1.5 px-3 py-1.5 text-[11px] font-bold rounded-lg border transition"
                                   style="background:#F5F2EC; border-color:#DDD7CC; color:#6B6458;"
                                   onmouseover="this.style.background='#1A1714';this.style.color='#FFF';this.style.borderColor='#1A1714'"
                                   onmouseout="this.style.background='#F5F2EC';this.style.color='#6B6458';this.style.borderColor='#DDD7CC'">
                                    Analitik →
                                </a>
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

@section('scripts')
@if(array_sum($orderedSentiment) > 0)
<script>
    const sentimentData   = @json(array_values($orderedSentiment));
    const sentimentLabels = @json(array_keys($orderedSentiment));
    const barColors       = ['#C9933A','#4A7A5E','#8B5E3C','#6B8FAB','#A0846A'];

    const ctx = document.getElementById('sentimentChart');
    if (ctx) {
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: sentimentLabels,
                datasets: [{
                    label: 'Jumlah Respons',
                    data: sentimentData,
                    backgroundColor: barColors,
                    borderRadius: 6,
                    borderSkipped: false,
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: { callbacks: { label: c => ` ${c.parsed.x} respons` } }
                },
                scales: {
                    x: { grid: { color: '#EAE5DC' }, ticks: { font: { family: 'Plus Jakarta Sans', size: 11 }, color: '#6B6458' } },
                    y: { grid: { display: false }, ticks: { font: { family: 'Plus Jakarta Sans', size: 11 }, color: '#1A1714' } }
                }
            }
        });
    }
</script>
@endif
@endsection
