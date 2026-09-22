@extends('layouts.admin')
@section('title', 'Laporan Analisa AI — ' . $document->judul)

@section('content')
@php
    $initialDbHistory = $document->chat_history ?? [];
    if (empty($initialDbHistory)) {
        $judulClean = str_replace(['"', "'"], '', $document->judul);
        $fileTypeClean = strtoupper($document->file_type);
        $initialDbHistory = [
            [
                'role' => 'assistant',
                'text' => "Halo! Saya **Dr. Arjuna Pratama**, Senior Business Intelligence Consultant.

Dokumen **{$judulClean}** ({$fileTypeClean}) telah **terlampir sebagai konteks percakapan**.

*Tips:* Anda bisa **memblok/menyeleksi teks** di halaman ini untuk langsung menanyakannya ke AI!",
                'timestamp' => now()->format('H:i')
            ]
        ];
    }
@endphp

<script>
window.documentChatData = function() {
    return {
        activeTab: 'AI_REPORT',
        confirmDeleteModal: false,
        confirmRegenerateModal: false,
        regenerating: false,
        chatDrawerOpen: false,
        userQuestion: '',
        sendingChat: false,
        selectedTextSnippet: '',
        tooltipPos: { top: '0px', left: '0px', show: false },
        chatMessages: @json($initialDbHistory),

        init() {
            document.addEventListener('selectionchange', () => {
                let sel = window.getSelection();
                let txt = sel ? sel.toString().trim() : '';
                if (txt.length > 3) {
                    this.selectedTextSnippet = txt;
                    try {
                        let range = sel.getRangeAt(0);
                        let rect = range.getBoundingClientRect();
                        this.tooltipPos = {
                            top: (rect.top + window.scrollY - 40) + 'px',
                            left: Math.max(10, (rect.left + window.scrollX + (rect.width / 2) - 80)) + 'px',
                            show: true
                        };
                    } catch (e) {
                        this.tooltipPos.show = false;
                    }
                } else {
                    this.tooltipPos.show = false;
                }
            });
        },

        renderFormattedMarkdown(text) {
            if (!text) return '';
            let html = text;
            html = html.replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;");

            html = html.replace(/^### (.*$)/gim, '<h3 class="font-serif font-bold text-sm text-stone-900 mt-2 mb-1 border-b pb-1 border-stone-200">$1</h3>');
            html = html.replace(/^## (.*$)/gim, '<h2 class="font-serif font-bold text-base text-stone-900 mt-2 mb-1">$1</h2>');

            html = html.replace(/^&gt; (.*$)/gim, '<blockquote class="border-l-4 border-amber-500 pl-3 py-1.5 bg-amber-50/70 text-stone-900 my-2 rounded-r-lg italic">$1</blockquote>');
            html = html.replace(/^> (.*$)/gim, '<blockquote class="border-l-4 border-amber-500 pl-3 py-1.5 bg-amber-50/70 text-stone-900 my-2 rounded-r-lg italic">$1</blockquote>');

            html = html.replace(/\*\*\*(.*?)\*\*\*/g, '<strong><em>$1</em></strong>');
            html = html.replace(/\*\*(.*?)\*\*/g, '<strong class="font-bold text-stone-900">$1</strong>');
            html = html.replace(/\*(.*?)\*/g, '<em class="italic text-stone-700">$1</em>');

            html = html.replace(/^- (.*$)/gim, '<li class="ml-4 list-disc text-stone-800 my-0.5">$1</li>');
            html = html.replace(/^\d+\. (.*$)/gim, '<li class="ml-4 list-decimal text-stone-800 my-0.5">$1</li>');

            html = html.replace(/\n/g, '<br>');
            return html;
        },

        askAboutSelectedText() {
            if (!this.selectedTextSnippet) return;
            let txt = this.selectedTextSnippet;
            this.userQuestion = 'Mengenai bagian teks berikut:\n> "' + txt + '"\n\nTolong berikan analisis & rekomendasi instruksi lebih lanjut mengenai poin di atas.';
            this.tooltipPos.show = false;
            this.chatDrawerOpen = true;
            if (window.getSelection) window.getSelection().removeAllRanges();
            this.$nextTick(() => {
                if (this.$refs.chatInput) this.$refs.chatInput.focus();
            });
        },

        sendQuestion(customQ = null) {
            let q = customQ || this.userQuestion;
            if (!q || !q.trim() || this.sendingChat) return;

            this.chatMessages.push({ role: 'user', text: q, timestamp: new Date().toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'}) });
            this.userQuestion = '';
            this.sendingChat = true;
            this.chatDrawerOpen = true;

            fetch('{{ route('admin.document-analytics.chat', $document->id) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ question: q })
            })
            .then(res => res.json())
            .then(data => {
                this.sendingChat = false;
                if (data.success && data.reply) {
                    if (data.history && data.history.length > 0) {
                        this.chatMessages = data.history;
                    } else {
                        this.chatMessages.push({ role: 'assistant', text: data.reply, timestamp: new Date().toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'}) });
                    }
                } else {
                    this.chatMessages.push({ role: 'assistant', text: '⚠️ Maaf, terjadi kendala saat memproses pertanyaan AI: ' + (data.message || 'Error API'), timestamp: new Date().toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'}) });
                }
                this.$nextTick(() => {
                    let box = this.$refs.chatBox;
                    if (box) box.scrollTop = box.scrollHeight;
                });
            })
            .catch(err => {
                this.sendingChat = false;
                this.chatMessages.push({ role: 'assistant', text: '⚠️ Gagal terhubung ke server AI: ' + err.message });
            });
        },

        rewindChat() {
            if (this.chatMessages.length <= 1 || this.sendingChat) return;

            fetch('{{ route('admin.document-analytics.chat.rewind', $document->id) }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    this.chatMessages = data.history && data.history.length > 0 ? data.history : [
                        {
                            role: 'assistant',
                            text: 'Obrolan telah di-rewind. Silakan ajukan pertanyaan baru!',
                            timestamp: new Date().toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})
                        }
                    ];
                }
            });
        },

        regenerateLastResponse() {
            if (this.sendingChat) return;
            let lastUserQuestion = null;
            for (let i = this.chatMessages.length - 1; i >= 0; i--) {
                if (this.chatMessages[i].role === 'user') {
                    lastUserQuestion = this.chatMessages[i].text;
                    break;
                }
            }
            if (!lastUserQuestion) return;

            this.rewindChat();
            this.$nextTick(() => {
                this.sendQuestion(lastUserQuestion);
            });
        },

        clearChatHistory() {
            if (!confirm('Apakah Anda yakin ingin menghapus seluruh histori percakapan AI untuk dokumen ini?')) return;

            fetch('{{ route('admin.document-analytics.chat.clear', $document->id) }}', {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    this.chatMessages = [
                        {
                            role: 'assistant',
                            text: 'Histori obrolan telah dibersihkan. Silakan ajukan pertanyaan baru!',
                            timestamp: new Date().toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})
                        }
                    ];
                }
            });
        },

        copyText(text) {
            navigator.clipboard.writeText(text);
            alert('Teks berhasil disalin ke clipboard!');
        }
    };
};
</script>

<div x-data="documentChatData()" class="space-y-6 pb-16 relative">

    <!-- ── Floating Text Selection Tooltip Badge ────────────────────────── -->
    <div x-show="tooltipPos.show" x-cloak x-transition
         :style="'position: absolute; top: ' + tooltipPos.top + '; left: ' + tooltipPos.left + '; z-index: 45;'"
         class="animate-pop-in">
        <button type="button" @click="askAboutSelectedText()"
                class="px-3.5 py-1.5 rounded-full text-xs font-bold text-white shadow-xl flex items-center gap-1.5 cursor-pointer transition transform hover:scale-105"
                style="background:#1C1917; border: 1.5px solid #C89D54;">
            <span>💬 Tanya Teks Ini ke AI</span>
        </button>
    </div>

    <!-- ── Alert Flash Messages ─────────────────────────────────────────── -->
    @if (session('success'))
        <div class="p-4 rounded-xl border flex items-center justify-between animate-fade-up"
             style="background:#EDFAF2; border-color:#A3E6CD; color:#1A5C38;">
            <div class="flex items-center gap-2.5 text-xs font-semibold">
                <span class="text-base">✅</span>
                <span>{{ session('success') }}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-xs font-bold opacity-60 hover:opacity-100">✕</button>
        </div>
    @endif

    <!-- ── Top Header Navigation ─────────────────────────────────────────── -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.document-analytics.index') }}"
               class="p-2.5 rounded-xl border transition flex items-center justify-center shrink-0"
               style="background:#FAF8F5; border-color:#E5E0D8; color:#1C1917;"
               onmouseover="this.style.background='#F4F0E8';"
               onmouseout="this.style.background='#FAF8F5';">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            </a>
            <div>
                <div class="flex items-center gap-2 flex-wrap">
                    <span class="px-2.5 py-0.5 rounded-md text-[10px] font-bold uppercase tracking-wider border flex items-center gap-1.5"
                          style="background:#FFFBEB; border-color:#FDE68A; color:#92400E;">
                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-ping"></span>
                        🚧 DALAM TAHAP PENGEMBANGAN
                    </span>
                    <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider border"
                          style="background:#FAF6EE; border-color:#E5E0D8; color:#C89D54;">
                        {{ strtoupper($document->file_type) }}
                    </span>
                    <span class="text-xs text-stone-400">• {{ $document->created_at->format('d M Y, H:i') }} WIB</span>
                </div>
                <h1 class="font-serif font-bold text-xl sm:text-2xl text-ink mt-0.5 line-clamp-1">
                    {{ $document->judul }}
                </h1>
            </div>
        </div>

        <div class="flex items-center gap-2 self-start sm:self-auto">
            <!-- Right AI Drawer Trigger Button -->
            <button type="button" @click="chatDrawerOpen = true"
                    class="px-3.5 py-2 rounded-xl border text-xs font-bold transition flex items-center gap-1.5 cursor-pointer shadow-xs"
                    style="background:#FAF6EE; border-color:#E5E0D8; color:#C89D54;"
                    onmouseover="this.style.background='#F4EBD9';"
                    onmouseout="this.style.background='#FAF6EE';">
                <span>💬 Tanya AI Konsultan</span>
            </button>

            <!-- Regenerate AI Button -->
            <button type="button" @click="confirmRegenerateModal = true"
                    class="px-3.5 py-2 rounded-xl border text-xs font-bold transition flex items-center gap-1.5 cursor-pointer"
                    style="background:#FAF8F5; border-color:#E5E0D8; color:#1C1917;"
                    onmouseover="this.style.background='#F4F0E8';"
                    onmouseout="this.style.background='#FAF8F5';">
                <svg class="w-3.5 h-3.5" style="color:#C89D54;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                <span>Regenerate AI</span>
            </button>

            <!-- Print / PDF -->
            <button onclick="window.print()"
                    class="px-3.5 py-2 rounded-xl border text-xs font-bold transition flex items-center gap-1.5 cursor-pointer"
                    style="background:#FAF8F5; border-color:#E5E0D8; color:#1C1917;"
                    onmouseover="this.style.background='#F4F0E8';"
                    onmouseout="this.style.background='#FAF8F5';">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                <span>Cetak / PDF</span>
            </button>

            <!-- Delete Button -->
            <button type="button" @click="confirmDeleteModal = true"
                    class="px-3 py-2 rounded-xl border text-xs font-bold transition flex items-center justify-center cursor-pointer"
                    style="background:#FDECEA; border-color:#F5B7B1; color:#922B21;"
                    title="Hapus Dokumen">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
            </button>
        </div>
    </div>

    <!-- ── Key Business Growth Metrics Bar ───────────────────────────────── -->
    @php
        $ai = $aiAnalysis;
        $skor = $ai['skor_potensi'] ?? 0;
        $growth = $ai['growth_likelihood'] ?? 'Potensial (Good Growth)';
        $sentimen = $ai['analisis_sentimen'] ?? [];
        $wtp = $ai['strategi_harga_wtp'] ?? [];
    @endphp
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- 1. Skor Growth Likelihood -->
        <div class="card p-5 space-y-2 border-l-4" style="border-left-color:#C89D54;">
            <div class="text-[10px] font-sans uppercase tracking-widest font-bold text-stone-400">GROWTH LIKELIHOOD SKOR</div>
            <div class="flex items-baseline justify-between">
                <div class="font-serif font-extrabold text-2xl text-ink">{{ $skor }}%</div>
                <span class="text-xs font-bold px-2 py-0.5 rounded-full" style="background:#FAF6EE; color:#C89D54;">
                    {{ $growth }}
                </span>
            </div>
            <p class="text-[11px] text-stone-500 line-clamp-1">Evaluasi kelayakan pertumbuhan usaha awal di pasar.</p>
        </div>

        <!-- 2. Sentimen & Minat Konsumen -->
        <div class="card p-5 space-y-2 border-l-4" style="border-left-color:#10B981;">
            <div class="text-[10px] font-sans uppercase tracking-widest font-bold text-stone-400">SENTIMEN & DAYA TARIK</div>
            <div class="flex items-baseline justify-between">
                <div class="font-serif font-extrabold text-2xl text-emerald-700">{{ $sentimen['persentase_positif'] ?? 85 }}%</div>
                <span class="text-xs font-bold text-stone-700">{{ $sentimen['tingkat_minat'] ?? 'Tinggi' }}</span>
            </div>
            <p class="text-[11px] text-stone-500 line-clamp-1">Penerimaan pasar & daya tarik ide produk.</p>
        </div>

        <!-- 3. Rekomendasi WTP Harga -->
        <div class="card p-5 space-y-2 border-l-4" style="border-left-color:#3B82F6;">
            <div class="text-[10px] font-sans uppercase tracking-widest font-bold text-stone-400">ESTIMASI TARIF WTP</div>
            <div class="font-serif font-bold text-sm text-stone-900 line-clamp-1">
                {{ $wtp['sweet_spot_harga'] ?? 'WTP Terukur' }}
            </div>
            <p class="text-[11px] text-stone-500 line-clamp-1">Proposisi harga manis berdasarkan daya beli.</p>
        </div>

        <!-- 4. Info Dokumen Sumber -->
        <div class="card p-5 space-y-2 border-l-4" style="border-left-color:#8B5CF6;">
            <div class="text-[10px] font-sans uppercase tracking-widest font-bold text-stone-400">INFO BERKAS DEPO</div>
            <div class="text-xs font-bold text-stone-800 truncate">{{ $document->nama_file_asli }}</div>
            <p class="text-[11px] text-stone-500">{{ $document->file_size }} • {{ $document->total_baris_halaman }} baris/slide</p>
        </div>
    </div>

    <!-- ── Tab Navigation ────────────────────────────────────────────────── -->
    <div class="border-b border-stone-200 flex items-center gap-4 text-xs font-bold overflow-x-auto">
        <button @click="activeTab = 'AI_REPORT'"
                :class="activeTab === 'AI_REPORT' ? 'border-amber-600 text-stone-900 border-b-2 pb-3 font-extrabold whitespace-nowrap' : 'text-stone-500 pb-3 hover:text-stone-800 whitespace-nowrap'">
            ✨ Laporan Intelijen AI & Growth Likelihood
        </button>
        <button @click="activeTab = 'DOCUMENT_PREVIEW'"
                :class="activeTab === 'DOCUMENT_PREVIEW' ? 'border-amber-600 text-stone-900 border-b-2 pb-3 font-extrabold whitespace-nowrap' : 'text-stone-500 pb-3 hover:text-stone-800 whitespace-nowrap'">
            📄 Preview Teks & Data Dokumen
        </button>
    </div>

    <!-- ── Tab 1: AI Report ──────────────────────────────────────────────── -->
    <div x-show="activeTab === 'AI_REPORT'" class="space-y-6">

        <!-- ── Data Usability & Feasibility Verification Card ─────────────── -->
        @php
            $val = $aiAnalysis['validasi_dokumen'] ?? [];
            $isValidData = $val['is_valid_for_analysis'] ?? true;
            $pctLayak = $val['persentase_kelayakan_data'] ?? 90;
            $pctTidakLayak = $val['persentase_ketidaklayakan_data'] ?? 10;
            $statusKelayakan = $val['status_kelayakan'] ?? 'Sangat Layak (Data Kuantitatif & Keuangan Terdeteksi)';
            $catatanValidasi = $val['catatan_kelayakan'] ?? 'Dokumen ini memiliki data kuantitatif yang valid untuk acuan analisis bisnis.';
            $elemenDitemukan = $val['elemen_ditemukan'] ?? ['Data Harga/Tarif (Rp)', 'Struktur Data Responden/Tabel'];
            $elemenKurang = $val['elemen_kurang'] ?? ['Laporan Keuangan Bulanan Lengkap (Proyeksi 12 Bulan)'];
        @endphp

        <div class="card p-6 space-y-4 border-2" style="border-color:{{ $isValidData ? '#A3E6CD' : '#F5B7B1' }}; background:{{ $isValidData ? '#FAFDFA' : '#FFFDFD' }};">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b pb-4 border-stone-200">
                <div class="flex items-center gap-2.5">
                    <span class="text-2xl">{{ $isValidData ? '✅' : '⚠️' }}</span>
                    <div>
                        <div class="text-[10px] font-sans uppercase tracking-widest font-bold text-stone-400">HASIL VERIFIKASI KELAYAKAN DATA</div>
                        <h3 class="font-serif font-bold text-lg text-ink">Evaluasi Kelayakan Dokumen Sebagai Acuan Bisnis</h3>
                    </div>
                </div>

                <span class="px-3 py-1 rounded-full text-xs font-bold border self-start sm:self-auto flex items-center gap-1.5"
                      style="background:{{ $isValidData ? '#EDFAF2' : '#FDECEA' }}; border-color:{{ $isValidData ? '#A3E6CD' : '#F5B7B1' }}; color:{{ $isValidData ? '#1A5C38' : '#922B21' }};">
                    <span class="w-2 h-2 rounded-full {{ $isValidData ? 'bg-emerald-500' : 'bg-red-500' }}"></span>
                    <span>{{ $statusKelayakan }}</span>
                </span>
            </div>

            <!-- Feasibility Progress Bars -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 pt-1">
                <div class="space-y-1.5">
                    <div class="flex justify-between text-xs font-bold">
                        <span class="text-emerald-900">📊 Persentase Kelayakan Data (Acuan Valid)</span>
                        <span class="text-emerald-700">{{ $pctLayak }}%</span>
                    </div>
                    <div class="w-full bg-stone-200 rounded-full h-3 overflow-hidden">
                        <div class="bg-emerald-500 h-3 rounded-full transition-all duration-500" style="width: {{ $pctLayak }}%"></div>
                    </div>
                </div>

                <div class="space-y-1.5">
                    <div class="flex justify-between text-xs font-bold">
                        <span class="{{ $isValidData ? 'text-amber-900' : 'text-red-900' }}">⚠️ Defisit Data / Ketidaklayakan</span>
                        <span class="{{ $isValidData ? 'text-amber-700' : 'text-red-700' }}">{{ $pctTidakLayak }}%</span>
                    </div>
                    <div class="w-full bg-stone-200 rounded-full h-3 overflow-hidden">
                        <div class="{{ $isValidData ? 'bg-amber-400' : 'bg-red-500' }} h-3 rounded-full transition-all duration-500" style="width: {{ $pctTidakLayak }}%"></div>
                    </div>
                </div>
            </div>

            <!-- Catatan Validasi AI Alert Box -->
            <div class="p-4 rounded-xl border text-xs leading-relaxed font-medium"
                 style="background:{{ $isValidData ? '#EDFAF2' : '#FDECEA' }}; border-color:{{ $isValidData ? '#A3E6CD' : '#F5B7B1' }}; color:{{ $isValidData ? '#1A5C38' : '#922B21' }};">
                <strong>Catatan AI Evaluator:</strong> {{ $catatanValidasi }}
            </div>

            <!-- Expert Minimum Requirements Checklist Section -->
            @php
                $syaratPakar = $val['syarat_minimum_pakar'] ?? [];
                $kriteriaTerpenuhi = $syaratPakar['kriteria_terpenuhi'] ?? ['Definisi Produk & Value Proposition', 'Segmentasi Target Pasar'];
                $kriteriaBelum = $syaratPakar['kriteria_belum_terpenuhi'] ?? ['Data Nominal Harga (Rp)'];
                $skorKriteria = $syaratPakar['skor_kriteria'] ?? '3 dari 5 kriteria pakar terpenuhi';
            @endphp
            <div class="p-4 rounded-xl border space-y-2.5 bg-stone-50 border-stone-200 text-xs">
                <div class="flex items-center justify-between font-bold text-stone-900 border-b pb-2 border-stone-200">
                    <span class="flex items-center gap-1.5">🎯 Scan Syarat Minimum Pakar Business Intelligence</span>
                    <span class="px-2 py-0.5 rounded text-[10px] bg-stone-200 text-stone-800 font-mono">{{ $skorKriteria }}</span>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-1">
                    <div class="space-y-1">
                        <div class="font-bold text-emerald-900">✓ Kriteria Pakar Terpenuhi:</div>
                        <ul class="space-y-1">
                            @foreach ($kriteriaTerpenuhi as $kt)
                                <li class="flex items-center gap-1.5 text-stone-800 font-medium">
                                    <span class="text-emerald-600 font-bold">✓</span>
                                    <span>{{ $kt }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="space-y-1">
                        <div class="font-bold text-amber-900">⚠️ Kriteria Yang Perlu Dilengkapi:</div>
                        <ul class="space-y-1">
                            @foreach ($kriteriaBelum as $kb)
                                <li class="flex items-center gap-1.5 text-stone-700">
                                    <span class="text-amber-600 font-bold">!</span>
                                    <span>{{ $kb }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Detected & Missing Elements -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-1 text-xs">
                <div class="space-y-1.5">
                    <div class="font-bold text-emerald-900">✓ Elemen Data Terdeteksi:</div>
                    <div class="flex flex-wrap gap-1.5">
                        @foreach ($elemenDitemukan as $el)
                            <span class="px-2.5 py-1 rounded-md text-[11px] font-semibold border bg-emerald-50 border-emerald-200 text-emerald-800">
                                {{ $el }}
                            </span>
                        @endforeach
                    </div>
                </div>

                <div class="space-y-1.5">
                    <div class="font-bold text-amber-900">⚠️ Elemen Data Yang Perlu Dilengkapi:</div>
                    <div class="flex flex-wrap gap-1.5">
                        @foreach ($elemenKurang as $el)
                            <span class="px-2.5 py-1 rounded-md text-[11px] font-semibold border bg-amber-50 border-amber-200 text-amber-900">
                                {{ $el }}
                            </span>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Executive Summary & Market Fit Card -->
        <div class="card p-6 space-y-4">
            <div class="flex items-center gap-2 border-b pb-3 border-stone-200">
                <span class="text-xl">📋</span>
                <h3 class="font-serif font-bold text-lg text-ink">Ringkasan Eksekutif & Kelayakan Pasar Riil</h3>
            </div>
            <p class="text-xs text-stone-800 leading-relaxed font-medium whitespace-pre-line text-justify">
                {{ $aiAnalysis['ringkasan_eksekutif'] ?? 'Ringkasan belum tersedia.' }}
            </p>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 pt-2">
                <div class="p-4 rounded-xl border border-stone-200 bg-stone-50 space-y-1.5">
                    <div class="text-[10px] font-bold uppercase tracking-wider text-amber-700">POTENSI PASAR RIIL</div>
                    <p class="text-xs text-stone-800 leading-relaxed font-medium">
                        {{ $aiAnalysis['analisis_pasar_riil']['potensi_pasar'] ?? '-' }}
                    </p>
                </div>

                <div class="p-4 rounded-xl border border-stone-200 bg-stone-50 space-y-1.5">
                    <div class="text-[10px] font-bold uppercase tracking-wider text-emerald-700">PRODUCT-MARKET FIT</div>
                    <p class="text-xs text-stone-800 leading-relaxed font-medium">
                        {{ $aiAnalysis['analisis_pasar_riil']['kesesuaian_produk'] ?? '-' }}
                    </p>
                </div>

                <div class="p-4 rounded-xl border border-stone-200 bg-stone-50 space-y-1.5">
                    <div class="text-[10px] font-bold uppercase tracking-wider text-blue-700">TARGET SEGMEN PASAR</div>
                    <p class="text-xs text-stone-800 leading-relaxed font-medium">
                        {{ $aiAnalysis['analisis_pasar_riil']['target_segmen'] ?? '-' }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Target Market Persona & WTP Pricing -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Target Persona -->
            <div class="card p-6 space-y-4">
                <div class="flex items-center gap-2 border-b pb-3 border-stone-200">
                    <span class="text-xl">🎯</span>
                    <h3 class="font-serif font-bold text-lg text-ink">Profil Target Konsumen (Persona)</h3>
                </div>
                <div class="space-y-3 text-xs">
                    <div class="p-3.5 rounded-xl border border-stone-200 bg-stone-50/60">
                        <span class="font-bold text-stone-900 block mb-0.5">Demografi Utama:</span>
                        <span class="text-stone-700 leading-relaxed">{{ $aiAnalysis['analisis_target_pasar']['demografi_utama'] ?? '-' }}</span>
                    </div>
                    <div class="p-3.5 rounded-xl border border-stone-200 bg-stone-50/60">
                        <span class="font-bold text-stone-900 block mb-0.5">Masalah Utama (Pain Points):</span>
                        <span class="text-stone-700 leading-relaxed">{{ $aiAnalysis['analisis_target_pasar']['kebutuhan_utama'] ?? '-' }}</span>
                    </div>
                    <div class="p-3.5 rounded-xl border border-stone-200 bg-stone-50/60">
                        <span class="font-bold text-stone-900 block mb-0.5">Perilaku Transaksi:</span>
                        <span class="text-stone-700 leading-relaxed">{{ $aiAnalysis['analisis_target_pasar']['perilaku_pembelian'] ?? '-' }}</span>
                    </div>
                </div>
            </div>

            <!-- Pricing & WTP Strategy -->
            <div class="card p-6 space-y-4">
                <div class="flex items-center gap-2 border-b pb-3 border-stone-200">
                    <span class="text-xl">💰</span>
                    <h3 class="font-serif font-bold text-lg text-ink">Strategi Penetapan Harga & Profit Margin</h3>
                </div>
                <div class="space-y-4">
                    <div class="p-4 rounded-xl border border-amber-200 bg-amber-50/50 space-y-1">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-amber-800">REKOMENDASI HARGA (SWEET SPOT)</span>
                        <div class="font-serif font-extrabold text-xl text-amber-900">
                            {{ $wtp['sweet_spot_harga'] ?? 'Disesuaikan dengan WTP' }}
                        </div>
                    </div>
                    <div class="p-4 rounded-xl border border-stone-200 bg-stone-50 space-y-1 text-xs">
                        <span class="font-bold text-stone-900 block">Panduan Penetapan HPP & Margin:</span>
                        <p class="text-stone-700 leading-relaxed">
                            {{ $wtp['rekomendasi_margin'] ?? 'Jaga margin kotor minimal 35% di atas HPP.' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Product & Marketing Recommendations -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Product Recs -->
            <div class="card p-6 space-y-4">
                <div class="flex items-center gap-2 border-b pb-3 border-stone-200">
                    <span class="text-xl">💡</span>
                    <h3 class="font-serif font-bold text-lg text-ink">Rekomendasi Pengembangan Produk</h3>
                </div>
                <ul class="space-y-2.5 text-xs text-stone-800">
                    @foreach ($aiAnalysis['rekomendasi_produk'] ?? [] as $rec)
                        <li class="flex items-start gap-2.5 p-3 rounded-xl border border-stone-200 bg-stone-50/50">
                            <span class="text-amber-600 font-bold shrink-0">✦</span>
                            <span class="leading-relaxed">{{ $rec }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>

            <!-- Marketing Strategy -->
            <div class="card p-6 space-y-4">
                <div class="flex items-center gap-2 border-b pb-3 border-stone-200">
                    <span class="text-xl">📢</span>
                    <h3 class="font-serif font-bold text-lg text-ink">Strategi Pemasaran & Akuisisi</h3>
                </div>
                <ul class="space-y-2.5 text-xs text-stone-800">
                    @foreach ($aiAnalysis['strategi_pemasaran'] ?? [] as $mkt)
                        <li class="flex items-start gap-2.5 p-3 rounded-xl border border-stone-200 bg-stone-50/50">
                            <span class="text-emerald-600 font-bold shrink-0">✔</span>
                            <span class="leading-relaxed">{{ $mkt }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>

        <!-- Risk & Mitigations -->
        @if (!empty($aiAnalysis['risiko_dan_mitigasi']))
            <div class="card p-6 space-y-4">
                <div class="flex items-center gap-2 border-b pb-3 border-stone-200">
                    <span class="text-xl">🛡️</span>
                    <h3 class="font-serif font-bold text-lg text-ink">Identifikasi Risiko & Langkah Mitigasi Usaha</h3>
                </div>
                <div class="space-y-3">
                    @foreach ($aiAnalysis['risiko_dan_mitigasi'] as $rm)
                        <div class="p-4 rounded-xl border border-stone-200 bg-stone-50/50 text-xs text-stone-800 leading-relaxed">
                            ⚠️ {{ $rm }}
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Action Plan -->
        <div class="card p-6 space-y-4">
            <div class="flex items-center gap-2 border-b pb-3 border-stone-200">
                <span class="text-xl">📅</span>
                <h3 class="font-serif font-bold text-lg text-ink">Action Plan Eksekusi & Scaling Usaha</h3>
            </div>
            <div class="space-y-3">
                @foreach ($aiAnalysis['action_plan'] ?? [] as $idx => $step)
                    <div class="flex items-start gap-3 p-3.5 rounded-xl border border-stone-200 bg-stone-50/50">
                        <div class="w-7 h-7 rounded-lg flex items-center justify-center font-bold text-xs shrink-0"
                             style="background:#1C1917; color:#C89D54;">
                            {{ $idx + 1 }}
                        </div>
                        <p class="text-xs text-stone-800 leading-relaxed pt-1">{{ $step }}</p>
                    </div>
                @endforeach
            </div>
        </div>

    </div>

    <!-- ── Tab 2: Document Preview ───────────────────────────────────────── -->
    <div x-show="activeTab === 'DOCUMENT_PREVIEW'" class="space-y-6" style="display:none;">
        <div class="card p-6 space-y-4">
            <div class="flex items-center justify-between border-b pb-3 border-stone-200">
                <div class="flex items-center gap-2">
                    <span class="text-xl">📖</span>
                    <h3 class="font-serif font-bold text-lg text-ink">Preview Isi Teks & Data Dokumen</h3>
                </div>
                <span class="text-xs text-stone-500">{{ $document->file_size }} • {{ $document->total_baris_halaman }} baris/slide</span>
            </div>

            @if (!empty($document->preview_data))
                <div class="space-y-2">
                    <div class="text-xs font-bold text-stone-700">Tabel Sampel Raw Data / Header:</div>
                    <div class="overflow-x-auto rounded-xl border border-stone-200">
                        <table class="w-full text-left text-xs">
                            <thead class="bg-stone-100 text-stone-700">
                                <tr>
                                    @foreach ($document->headers ?? [] as $head)
                                        <th class="p-3 border-b border-stone-200 font-bold whitespace-nowrap">{{ $head }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-stone-200">
                                @foreach (array_slice($document->preview_data, 0, 15) as $row)
                                    <tr class="hover:bg-stone-50">
                                        @if (is_array($row))
                                            @foreach ($row as $cell)
                                                <td class="p-3 text-stone-800 truncate max-w-xs">{{ (string)$cell }}</td>
                                            @endforeach
                                        @else
                                            <td colspan="10" class="p-3 text-stone-800">{{ (string)$row }}</td>
                                        @endif
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            <div class="space-y-2 pt-4">
                <div class="text-xs font-bold text-stone-700">Teks Lengkap Terurai (Extracted Content):</div>
                <div class="p-4 rounded-xl border border-stone-200 bg-stone-50 max-h-96 overflow-y-auto font-mono text-[11px] text-stone-800 whitespace-pre-wrap leading-relaxed">
                    {{ $document->extracted_text ?: 'Tidak ada teks tambahan.' }}
                </div>
            </div>
        </div>
    </div>

    <!-- ── Right-Side Slide-Over AI Chatbot Panel Drawer ────────────────── -->
    <!-- Drawer Overlay Backdrop -->
    <div x-show="chatDrawerOpen" x-cloak x-transition.opacity
         @click="chatDrawerOpen = false"
         class="fixed inset-0 z-50 bg-black/25"></div>

    <!-- Slide-over Drawer -->
    <div x-show="chatDrawerOpen" x-cloak
         x-transition:enter="transition ease-out duration-300 transform"
         x-transition:enter-start="translate-x-full"
         x-transition:enter-end="translate-x-0"
         x-transition:leave="transition ease-in duration-200 transform"
         x-transition:leave-start="translate-x-0"
         x-transition:leave-end="translate-x-full"
         class="fixed inset-y-0 right-0 z-50 w-full max-w-lg bg-white shadow-2xl border-l border-stone-300 flex flex-col justify-between">
        
        <!-- Drawer Header -->
        <div class="p-5 border-b border-stone-200 flex items-center justify-between shrink-0" style="background:#FAF8F5;">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl flex items-center justify-center text-xl shrink-0" style="background:#1C1917; color:#C89D54;">
                    🤖
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <h3 class="font-serif font-bold text-base text-stone-900">Dr. Arjuna Pratama</h3>
                        <span class="px-2 py-0.5 rounded text-[9px] font-bold bg-amber-100 text-amber-900 border border-amber-300">AI CONSULTANT</span>
                    </div>
                    <p class="text-[11px] text-stone-500">Tanya AI &amp; Rekomendasi Lanjutan</p>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <!-- Clear History Button -->
                <button type="button" @click="clearChatHistory()"
                        class="p-2 rounded-lg text-stone-400 hover:text-red-600 hover:bg-red-50 transition text-xs font-bold"
                        title="Hapus Histori Percakapan">
                    🗑️
                </button>

                <!-- Close Button -->
                <button type="button" @click="chatDrawerOpen = false"
                        class="w-8 h-8 rounded-full flex items-center justify-center text-stone-400 hover:text-stone-800 hover:bg-stone-100 transition text-base font-bold">
                    ✕
                </button>
            </div>
        </div>

        <!-- Attached Context & Quick Actions -->
        <div class="px-5 py-3 border-b border-stone-200 bg-stone-50 space-y-2 shrink-0">
            <!-- Attached Pill -->
            <div class="text-[11px] font-bold text-stone-700 flex items-center gap-1.5 truncate">
                <span>📎 Terlampir:</span>
                <span class="truncate text-amber-900 font-extrabold">"{{ $document->judul }}"</span>
                <span class="text-[9px] px-1.5 py-0.5 rounded bg-white border text-stone-600 font-mono">{{ strtoupper($document->file_type) }}</span>
            </div>

            <!-- Quick Action Chips -->
            <div class="flex flex-wrap gap-1.5">
                <button type="button" @click="sendQuestion('Bagaimana rekomendasi strategi harga & WTP yang tepat untuk produk ini?')"
                        class="px-2.5 py-1 rounded-lg border text-[11px] font-semibold bg-white hover:bg-stone-100 border-stone-200 text-stone-800 transition cursor-pointer">
                    💡 Strategi Harga
                </button>
                <button type="button" @click="sendQuestion('Tolong buatkan draf pesan promosi WhatsApp dan Instagram berdasarkan dokumen ini.')"
                        class="px-2.5 py-1 rounded-lg border text-[11px] font-semibold bg-white hover:bg-stone-100 border-stone-200 text-stone-800 transition cursor-pointer">
                    📢 Promosi WA/IG
                </button>
                <button type="button" @click="sendQuestion('Sebutkan 3 risiko utama operasional usaha ini beserta langkah mitigasinya.')"
                        class="px-2.5 py-1 rounded-lg border text-[11px] font-semibold bg-white hover:bg-stone-100 border-stone-200 text-stone-800 transition cursor-pointer">
                    🛡️ Mitigasi Risiko
                </button>
                <button type="button" @click="sendQuestion('Buatkan jadwal eksekusi Action Plan 30 hari pertama untuk meluncurkan bisnis ini.')"
                        class="px-2.5 py-1 rounded-lg border text-[11px] font-semibold bg-white hover:bg-stone-100 border-stone-200 text-stone-800 transition cursor-pointer">
                    📅 Action Plan
                </button>
            </div>
        </div>

        <!-- Chat Messages Box (Scrollable) -->
        <div x-ref="chatBox" class="flex-1 p-5 overflow-y-auto space-y-5 bg-stone-50/50">
            <template x-for="(msg, index) in chatMessages" :key="index">
                <div class="flex gap-3" :class="msg.role === 'user' ? 'justify-end' : 'justify-start'">
                    <div x-show="msg.role === 'assistant'" class="w-8 h-8 rounded-xl flex items-center justify-center font-bold text-xs shrink-0 mt-1" style="background:#1C1917; color:#C89D54;">
                        🤖
                    </div>

                    <div class="max-w-[88%] space-y-2">
                        <!-- Response Bubble Card -->
                        <div class="rounded-2xl p-4 text-xs leading-relaxed shadow-xs"
                             :class="msg.role === 'user' ? 'bg-stone-900 text-white rounded-br-none' : 'bg-white border border-stone-200 text-stone-900 rounded-bl-none'">
                            
                            <div class="font-sans text-xs leading-relaxed space-y-1.5" x-html="renderFormattedMarkdown(msg.text)"></div>

                            <div class="mt-2 text-[10px] opacity-60 font-medium text-right" x-text="msg.timestamp || 'AI Consultant'"></div>
                        </div>

                        <!-- Action Buttons OUTSIDE Response Box (Text Only, Rounded Pill, Smooth Hover) -->
                        <div x-show="msg.role === 'assistant'" class="flex items-center gap-2 pt-0.5">
                            <!-- Salin Button -->
                            <button type="button" @click="copyText(msg.text)"
                                    class="px-3 py-1 rounded-full border text-[11px] font-bold text-stone-700 bg-stone-100 hover:bg-stone-200 border-stone-300 transition-all cursor-pointer shadow-2xs">
                                Salin
                            </button>

                            <!-- Regenerate Button (Only on latest assistant message) -->
                            <template x-if="index === chatMessages.length - 1">
                                <button type="button" @click="regenerateLastResponse()"
                                        class="px-3 py-1 rounded-full border text-[11px] font-bold text-emerald-800 bg-emerald-50 hover:bg-emerald-100 border-emerald-300 transition-all cursor-pointer shadow-2xs">
                                    Regenerate
                                </button>
                            </template>

                            <!-- Rewind Button (Only on latest assistant message) -->
                            <template x-if="index === chatMessages.length - 1 && chatMessages.length > 1">
                                <button type="button" @click="rewindChat()"
                                        class="px-3 py-1 rounded-full border text-[11px] font-bold text-amber-900 bg-amber-50 hover:bg-amber-100 border-amber-300 transition-all cursor-pointer shadow-2xs">
                                    Rewind
                                </button>
                            </template>
                        </div>
                    </div>

                    <div x-show="msg.role === 'user'" class="w-8 h-8 rounded-xl bg-stone-800 text-amber-400 flex items-center justify-center font-bold text-xs shrink-0 mt-1">
                        👤
                    </div>
                </div>
            </template>

            <!-- Typing Indicator -->
            <div x-show="sendingChat" class="flex gap-3 items-center text-xs text-stone-500">
                <div class="w-8 h-8 rounded-xl flex items-center justify-center font-bold text-xs shrink-0" style="background:#1C1917; color:#C89D54;">
                    🤖
                </div>
                <div class="bg-white border border-stone-200 rounded-2xl px-4 py-3 shadow-xs flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-amber-500 animate-ping"></span>
                    <span class="font-semibold text-stone-700">Dr. Arjuna Pratama sedang menyusun jawaban...</span>
                </div>
            </div>
        </div>

        <!-- Disclaimer Banner -->
        <div class="px-4 py-2 bg-amber-50/90 border-t border-amber-200 text-[10px] text-amber-900 leading-tight flex items-center gap-2 shrink-0">
            <span class="text-xs">⚠️</span>
            <span><strong>Peringatan Referensi:</strong> Hasil analisis &amp; rekomendasi AI ini bersifat sebagai <em>referensi awal riset pasar</em> dan tidak dapat dijadikan acuan/jawaban mutlak tanpa validasi eksekusi lapangan.</span>
        </div>

        <!-- Chat Input Form Footer -->
        <div class="p-4 border-t border-stone-200 bg-white shrink-0">
            <form @submit.prevent="sendQuestion()" class="flex flex-col gap-2">
                <textarea x-ref="chatInput" x-model="userQuestion" rows="2"
                          placeholder="Tanyakan atau instruksikan AI... (bisa blok teks di layar lalu klik 'Tanya Teks Ini')"
                          class="w-full p-3 rounded-xl border text-xs font-medium text-stone-900 focus:outline-none focus:ring-2 focus:ring-amber-500/30 resize-none"
                          style="background:#FAF8F5; border-color:#E5E0D8;"
                          @keydown.enter.prevent="sendQuestion()"></textarea>

                <div class="flex items-center justify-between">
                    <span class="text-[10px] text-stone-400">Tekan Enter untuk mengirim</span>
                    <button type="submit" :disabled="sendingChat"
                            class="px-5 py-2 rounded-xl text-xs font-bold text-white shadow-xs flex items-center gap-1.5 cursor-pointer disabled:opacity-50 transition"
                            style="background:#1C1917;">
                        <span>Kirim</span>
                        <svg class="w-3.5 h-3.5" style="color:#C89D54;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ── Modal: Konfirmasi Hapus Data? (Regenerate AI) ────────────────── -->
    <div x-show="confirmRegenerateModal" x-cloak x-transition
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/25">
        <div class="bg-white rounded-3xl border border-stone-300 max-w-md w-full p-6 space-y-5 shadow-2xl animate-pop-in">
            <div class="flex items-center gap-3 text-amber-600">
                <span class="text-3xl">🔄</span>
                <div>
                    <h3 class="font-serif font-bold text-lg text-stone-900">Konfirmasi Hapus Data?</h3>
                    <p class="text-xs text-stone-500">Regenerasi Laporan AI Dokumen</p>
                </div>
            </div>

            <div class="p-4 rounded-2xl border border-amber-200 bg-amber-50/60 text-xs text-amber-900 leading-relaxed">
                Hasil analisa AI yang saat ini tersimpan di database akan <strong>dihapus</strong> dan digantikan dengan analisis AI yang baru. Apakah Anda yakin ingin melanjutkan?
            </div>

            <div class="flex items-center justify-end gap-3 pt-2">
                <button type="button" @click="confirmRegenerateModal = false"
                        class="px-4 py-2 rounded-xl text-xs font-bold text-stone-600 hover:bg-stone-100">
                    Batal
                </button>

                <form action="{{ route('admin.document-analytics.regenerate', $document->id) }}" method="POST">
                    @csrf
                    <button type="submit" @click="regenerating = true"
                            class="px-5 py-2.5 rounded-xl text-xs font-bold text-white shadow-sm flex items-center gap-2 cursor-pointer"
                            style="background:#1C1917;">
                        <span x-text="regenerating ? 'Memproses AI...' : 'Ya, Hapus Data & Regenerate'"></span>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- ── Modal: Konfirmasi Hapus Dokumen ──────────────────────────────── -->
    <div x-show="confirmDeleteModal" x-cloak x-transition
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/25">
        <div class="bg-white rounded-3xl border border-stone-300 max-w-md w-full p-6 space-y-5 shadow-2xl animate-pop-in">
            <div class="flex items-center gap-3 text-red-600">
                <span class="text-3xl">⚠️</span>
                <div>
                    <h3 class="font-serif font-bold text-lg text-stone-900">Konfirmasi Hapus Dokumen</h3>
                    <p class="text-xs text-stone-500">Hapus Permanen Dokumen & AI Report</p>
                </div>
            </div>

            <p class="text-xs text-stone-600 leading-relaxed">
                Apakah Anda yakin ingin menghapus dokumen <strong>"{{ $document->judul }}"</strong>? Seluruh file dan laporan AI tersimpan di database akan dihapus permanen.
            </p>

            <div class="flex items-center justify-end gap-3 pt-2">
                <button type="button" @click="confirmDeleteModal = false"
                        class="px-4 py-2 rounded-xl text-xs font-bold text-stone-600 hover:bg-stone-100">
                    Batal
                </button>

                <form action="{{ route('admin.document-analytics.destroy', $document->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="px-5 py-2.5 rounded-xl text-xs font-bold text-white shadow-sm cursor-pointer"
                            style="background:#C0392B;">
                        Hapus Permanen
                    </button>
                </form>
            </div>
        </div>
    </div>

</div>
@endsection
