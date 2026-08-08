@extends('layouts.admin')
@section('title', 'Builder Survey — {{ $survey->judul }}')

@section('head')
<style>
    .question-card { transition: all 0.2s ease; }
    .question-card.newly-added { animation: popIn 0.35s cubic-bezier(0.34,1.56,0.64,1) both; }
    @keyframes popIn { 0%{transform:scale(0.92) translateY(8px);opacity:0} 70%{transform:scale(1.02)} 100%{transform:scale(1);opacity:1} }
</style>
@endsection

@section('content')
<div x-data="builderApp()" class="pb-10">

    <!-- ── Sticky Save Bar ─────────────────────────────────────────────── -->
    <div class="sticky top-0 z-30 bg-neutral-dark/95 backdrop-blur border-b border-white/10 -mx-4 sm:-mx-6 px-4 sm:px-6 py-3 mb-6 flex items-center justify-between gap-3 flex-wrap">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.surveys.index') }}"
               class="p-2 rounded-lg hover:bg-white/10 text-white/70 hover:text-white transition">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <div>
                <div class="text-[9px] font-sans uppercase tracking-widest text-mango/70 font-semibold">SURVEY BUILDER</div>
                <div class="font-serif text-white text-sm font-bold truncate max-w-xs" x-text="judul"></div>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <!-- Status badge -->
            <span x-text="status"
                  :class="status === 'PUBLISHED' ? 'bg-leaf-tint text-leaf' : status === 'DRAFT' ? 'bg-white/10 text-white/60' : 'bg-terra-tint text-terra'"
                  class="text-[10px] font-bold px-2.5 py-1 rounded-full uppercase tracking-wider"></span>

            <button @click="saveSurvey('DRAFT')" :disabled="saving"
                    class="px-4 py-2 text-xs font-bold bg-white/10 hover:bg-white/20 text-white rounded-xl transition disabled:opacity-50 flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
                <span x-text="saving ? 'Menyimpan...' : 'Simpan Draft'"></span>
            </button>
            <button @click="saveSurvey('PUBLISHED')" :disabled="saving"
                    class="px-4 py-2 text-xs font-bold bg-mango hover:bg-mango-dark text-neutral-dark rounded-xl transition disabled:opacity-50 shadow-sm">
                🚀 Publikasikan
            </button>
        </div>
    </div>

    <!-- ── Toast Notification ──────────────────────────────────────────── -->
    <div x-show="toastMessage"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed bottom-6 right-6 z-50 bg-leaf text-white px-5 py-3 rounded-xl shadow-lg font-semibold text-sm"
         style="display:none;" x-text="toastMessage">
    </div>

    <!-- ── 2-Panel Layout ─────────────────────────────────────────────── -->
    <div class="grid grid-cols-1 xl:grid-cols-12 gap-6">

        <!-- LEFT: Editor Panel ──────────────────────────────────────── -->
        <div class="xl:col-span-7 space-y-5">

            <!-- Survey Metadata -->
            <div class="bg-white rounded-2xl border border-neutral-border p-5 sm:p-6 space-y-4">
                <h2 class="font-serif font-bold text-lg text-neutral-dark border-b border-neutral-border pb-3">Informasi Survey</h2>

                <div class="space-y-3">
                    <div>
                        <label class="block text-[10px] font-sans uppercase tracking-widest font-semibold text-neutral-muted mb-1">Judul Survey *</label>
                        <input type="text" x-model="judul" placeholder="Misal: Riset Pasar Produk Kewirausahaan Siswa"
                               class="w-full px-4 py-3 border border-neutral-border rounded-xl text-sm font-semibold text-neutral-dark
                                      bg-neutral-bg/50 focus:ring-2 focus:ring-mango focus:outline-none transition">
                    </div>
                    <div>
                        <label class="block text-[10px] font-sans uppercase tracking-widest font-semibold text-neutral-muted mb-1">Deskripsi / Konteks</label>
                        <textarea x-model="deskripsi" rows="2" placeholder="Jelaskan tujuan survey ini kepada responden..."
                                  class="w-full px-4 py-3 border border-neutral-border rounded-xl text-sm text-neutral-dark
                                         bg-neutral-bg/50 focus:ring-2 focus:ring-mango focus:outline-none resize-none transition"></textarea>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-[10px] font-sans uppercase tracking-widest font-semibold text-neutral-muted mb-1">Tanggal Mulai</label>
                            <input type="date" x-model="tanggalMulai"
                                   class="w-full px-4 py-2.5 border border-neutral-border rounded-xl text-sm text-neutral-dark
                                          bg-neutral-bg/50 focus:ring-2 focus:ring-mango focus:outline-none">
                        </div>
                        <div>
                            <label class="block text-[10px] font-sans uppercase tracking-widest font-semibold text-neutral-muted mb-1">Tanggal Selesai</label>
                            <input type="date" x-model="tanggalSelesai"
                                   class="w-full px-4 py-2.5 border border-neutral-border rounded-xl text-sm text-neutral-dark
                                          bg-neutral-bg/50 focus:ring-2 focus:ring-mango focus:outline-none">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Questions List -->
            <div class="space-y-4">
                <template x-for="(q, idx) in questions" :key="idx">
                    <div class="bg-white rounded-2xl border border-neutral-border hover:border-mango/40 transition-all duration-200 question-card"
                         :class="newlyAddedIdx === idx ? 'newly-added ring-2 ring-mango' : ''">

                        <!-- Question Card Header -->
                        <div class="flex items-center gap-3 p-4 border-b border-neutral-border">
                            <span class="w-8 h-8 rounded-lg bg-mango text-neutral-dark font-serif font-bold text-sm flex items-center justify-center shrink-0"
                                  x-text="idx + 1"></span>

                            <!-- Type Select -->
                            <select x-model="q.tipe_pertanyaan" @change="changeType(q, $event.target.value)"
                                    class="flex-1 text-xs font-bold border border-neutral-border rounded-lg px-3 py-2
                                           bg-neutral-bg focus:ring-2 focus:ring-mango focus:outline-none transition text-neutral-dark">
                                <option value="SINGLE_CHOICE">⭕ Pilihan Tunggal (Single Choice)</option>
                                <option value="MULTIPLE_CHOICE">☑️ Pilihan Ganda (Multiple Choice)</option>
                                <option value="LIKERT">⭐ Skala Likert 1–5</option>
                                <option value="SHORT_TEXT">✏️ Teks Singkat</option>
                                <option value="LONG_TEXT">📝 Teks Panjang (Esai)</option>
                                <option value="NUMBER">🔢 Nominal Angka (Harga)</option>
                                <option value="IMAGE_UPLOAD">🖼️ Unggah Gambar / Foto</option>
                                <option value="DATE">📅 Tanggal</option>
                            </select>

                            <!-- Move Controls -->
                            <div class="flex gap-1">
                                <button @click="moveUp(idx)" :disabled="idx === 0"
                                        class="p-1.5 rounded-lg hover:bg-neutral-bg text-neutral-muted hover:text-neutral-dark disabled:opacity-30 transition">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/></svg>
                                </button>
                                <button @click="moveDown(idx)" :disabled="idx === questions.length - 1"
                                        class="p-1.5 rounded-lg hover:bg-neutral-bg text-neutral-muted hover:text-neutral-dark disabled:opacity-30 transition">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                </button>
                                <button @click="removeQuestion(idx)"
                                        class="p-1.5 rounded-lg hover:bg-terra-tint text-neutral-muted hover:text-terra transition">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            </div>
                        </div>

                        <!-- Question Body -->
                        <div class="p-4 space-y-3">
                            <!-- Question Text -->
                            <div class="flex items-start gap-2">
                                <textarea x-model="q.teks_pertanyaan" rows="2"
                                          :placeholder="'Pertanyaan #' + (idx + 1) + '...'"
                                          class="flex-1 px-3 py-2.5 border border-neutral-border rounded-xl text-sm text-neutral-dark
                                                 bg-neutral-bg/40 focus:ring-2 focus:ring-mango focus:outline-none resize-none"></textarea>
                                <label class="flex items-center gap-1.5 mt-2 shrink-0 cursor-pointer">
                                    <input type="checkbox" x-model="q.wajib_diisi"
                                           class="w-4 h-4 rounded text-mango border-neutral-border focus:ring-mango cursor-pointer">
                                    <span class="text-[10px] font-semibold text-neutral-muted whitespace-nowrap">Wajib</span>
                                </label>
                            </div>

                            <!-- Options for SINGLE/MULTIPLE_CHOICE -->
                            <template x-if="q.tipe_pertanyaan === 'SINGLE_CHOICE' || q.tipe_pertanyaan === 'MULTIPLE_CHOICE'">
                                <div class="space-y-2 pt-1">
                                    <div class="text-[10px] font-sans uppercase tracking-widest font-semibold text-neutral-muted">OPSI JAWABAN</div>
                                    <template x-for="(opt, optIdx) in q.opsi_jawaban" :key="optIdx">
                                        <div class="flex items-center gap-2">
                                            <span class="text-xs text-neutral-muted font-bold w-5 text-center" x-text="optIdx + 1"></span>
                                            <input type="text" x-model="q.opsi_jawaban[optIdx]"
                                                   :placeholder="'Opsi ' + (optIdx + 1)"
                                                   class="flex-1 px-3 py-2 border border-neutral-border rounded-lg text-xs
                                                          bg-neutral-bg/40 focus:ring-2 focus:ring-mango focus:outline-none">
                                            <button @click="q.opsi_jawaban.splice(optIdx, 1)"
                                                    x-show="q.opsi_jawaban.length > 2"
                                                    class="p-1.5 text-neutral-muted hover:text-terra rounded-lg transition">
                                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                            </button>
                                        </div>
                                    </template>
                                    <button @click="q.opsi_jawaban.push('')"
                                            class="flex items-center gap-1.5 text-xs font-semibold text-mango hover:text-mango-dark transition">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                        Tambah Opsi
                                    </button>
                                </div>
                            </template>

                            <!-- LIKERT Info -->
                            <template x-if="q.tipe_pertanyaan === 'LIKERT'">
                                <div class="text-xs text-neutral-muted bg-neutral-bg/60 rounded-lg px-3 py-2 flex items-center gap-2">
                                    <span class="text-mango">⭐</span>
                                    Skala 1 (Sangat Tidak Setuju) hingga 5 (Sangat Setuju) — otomatis disediakan.
                                </div>
                            </template>

                            <!-- NUMBER Info -->
                            <template x-if="q.tipe_pertanyaan === 'NUMBER'">
                                <div class="text-xs text-neutral-muted bg-neutral-bg/60 rounded-lg px-3 py-2 flex items-center gap-2">
                                    <span>🔢</span>
                                    Siswa akan mengisi nilai nominal (Rp) — cocok untuk pertanyaan harga (WTP).
                                </div>
                            </template>

                            <!-- IMAGE_UPLOAD Info -->
                            <template x-if="q.tipe_pertanyaan === 'IMAGE_UPLOAD'">
                                <div class="text-xs text-neutral-muted bg-neutral-bg/60 rounded-lg px-3 py-2 flex items-center gap-2">
                                    <span>🖼️</span>
                                    Siswa akan mengunggah foto (JPG/PNG, maks. 5MB).
                                </div>
                            </template>
                        </div>
                    </div>
                </template>

                <!-- Add Question Button -->
                <button @click="addQuestion()"
                        class="w-full py-4 border-2 border-dashed border-neutral-border rounded-2xl text-xs font-bold text-neutral-muted
                               hover:border-mango hover:text-mango hover:bg-mango-tint/30 transition-all flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Tambah Pertanyaan Baru
                </button>
            </div>
        </div>

        <!-- RIGHT: Live Preview Panel ──────────────────────────────── -->
        <div class="xl:col-span-5">
            <div class="sticky top-24">
                <div class="bg-neutral-dark rounded-2xl border border-white/10 p-4 text-white">
                    <div class="text-[9px] font-sans uppercase tracking-widest text-mango/70 font-semibold mb-3">LIVE PREVIEW — TAMPILAN SISWA</div>

                    <!-- Preview empty -->
                    <template x-if="questions.length === 0">
                        <div class="text-center py-10 text-white/30">
                            <div class="text-3xl mb-2">📋</div>
                            <p class="text-xs">Tambah pertanyaan untuk melihat preview.</p>
                        </div>
                    </template>

                    <!-- Preview wizard -->
                    <template x-if="questions.length > 0">
                        <div class="space-y-3">
                            <!-- Progress Bar -->
                            <div class="bg-white/10 rounded-full h-1.5">
                                <div class="bg-mango h-full rounded-full transition-all duration-300"
                                     :style="'width:' + (previewStep === 0 ? 0 : Math.round((previewStep / questions.length) * 100)) + '%'"></div>
                            </div>

                            <!-- Step 0: NISN -->
                            <template x-if="previewStep === 0">
                                <div class="bg-white/5 rounded-xl border border-white/10 p-4 space-y-3">
                                    <div class="text-[10px] font-sans uppercase tracking-widest text-mango/70 font-semibold">LANGKAH AWAL</div>
                                    <p class="text-xs font-bold">Identitas Responden Siswa</p>
                                    <div class="bg-white/5 rounded-lg px-3 py-2.5 text-xs text-white/40">Masukkan 10-digit NISN...</div>
                                </div>
                            </template>

                            <!-- Step 1..N Question Preview -->
                            <template x-if="previewStep > 0 && previewStep <= questions.length">
                                <div class="bg-white/5 rounded-xl border border-white/10 p-4 space-y-3">
                                    <div class="flex items-center justify-between">
                                        <span class="text-[10px] font-bold bg-white/10 px-2 py-0.5 rounded"
                                              x-text="questions[previewStep-1].tipe_pertanyaan"></span>
                                        <span class="text-xs text-white/60 tabular-nums"
                                              x-text="String(previewStep).padStart(2,'0') + ' / ' + String(questions.length).padStart(2,'0')"></span>
                                    </div>

                                    <p class="text-xs font-bold leading-relaxed"
                                       x-text="questions[previewStep-1].teks_pertanyaan || '(Teks pertanyaan belum diisi)'"></p>

                                    <!-- SINGLE_CHOICE -->
                                    <template x-if="questions[previewStep-1].tipe_pertanyaan === 'SINGLE_CHOICE'">
                                        <div class="space-y-1.5">
                                            <template x-for="opt in questions[previewStep-1].opsi_jawaban" :key="opt">
                                                <div class="flex items-center gap-2 p-2 rounded-lg bg-white/5 border border-white/10 text-xs cursor-pointer hover:border-mango/50 transition" x-text="opt"></div>
                                            </template>
                                        </div>
                                    </template>

                                    <!-- MULTIPLE_CHOICE -->
                                    <template x-if="questions[previewStep-1].tipe_pertanyaan === 'MULTIPLE_CHOICE'">
                                        <div class="space-y-1.5">
                                            <template x-for="opt in questions[previewStep-1].opsi_jawaban" :key="opt">
                                                <div class="flex items-center gap-2 p-2 rounded-lg bg-white/5 border border-white/10 text-xs cursor-pointer hover:border-mango/50 transition">
                                                    <div class="w-3 h-3 rounded border border-white/30 shrink-0"></div>
                                                    <span x-text="opt"></span>
                                                </div>
                                            </template>
                                        </div>
                                    </template>

                                    <!-- LIKERT -->
                                    <template x-if="questions[previewStep-1].tipe_pertanyaan === 'LIKERT'">
                                        <div class="grid grid-cols-5 gap-1.5">
                                            <template x-for="n in ['1','2','3','4','5']" :key="n">
                                                <div class="aspect-square flex flex-col items-center justify-center rounded-lg bg-white/5 border border-white/10 text-xs font-bold hover:bg-mango/20 hover:border-mango cursor-pointer transition" x-text="n"></div>
                                            </template>
                                        </div>
                                    </template>

                                    <!-- SHORT_TEXT -->
                                    <template x-if="questions[previewStep-1].tipe_pertanyaan === 'SHORT_TEXT'">
                                        <div class="bg-white/5 rounded-lg px-3 py-2.5 text-xs text-white/30">Ketik jawaban singkat...</div>
                                    </template>

                                    <!-- LONG_TEXT -->
                                    <template x-if="questions[previewStep-1].tipe_pertanyaan === 'LONG_TEXT'">
                                        <div class="bg-white/5 rounded-lg px-3 py-5 text-xs text-white/30">Ketik masukan / penjelasan...</div>
                                    </template>

                                    <!-- NUMBER -->
                                    <template x-if="questions[previewStep-1].tipe_pertanyaan === 'NUMBER'">
                                        <div class="bg-white/5 rounded-lg px-3 py-2.5 text-xs text-white/40 flex items-center gap-2">
                                            <span class="text-mango font-bold">Rp</span> <span>Nominal angka harga...</span>
                                        </div>
                                    </template>

                                    <!-- IMAGE_UPLOAD -->
                                    <template x-if="questions[previewStep-1].tipe_pertanyaan === 'IMAGE_UPLOAD'">
                                        <div class="border-2 border-dashed border-white/20 rounded-lg p-4 text-center text-xs text-white/40">
                                            🖼️ Pilih Berkas Foto / Gambar...
                                        </div>
                                    </template>

                                    <!-- DATE -->
                                    <template x-if="questions[previewStep-1].tipe_pertanyaan === 'DATE'">
                                        <div class="bg-white/5 rounded-lg px-3 py-2.5 text-xs text-white/40">📅 Pilih tanggal...</div>
                                    </template>
                                </div>
                            </template>

                            <!-- Preview Nav -->
                            <div class="flex justify-between gap-2 pt-1">
                                <button @click="previewStep = Math.max(0, previewStep - 1)"
                                        class="px-4 py-2 bg-white/10 hover:bg-white/20 text-white rounded-xl text-xs font-bold transition">
                                    ← Kembali
                                </button>
                                <span class="text-[10px] text-white/40 self-center tabular-nums" x-text="previewStep + ' / ' + questions.length"></span>
                                <button @click="previewStep = Math.min(questions.length, previewStep + 1)"
                                        class="px-4 py-2 bg-mango hover:bg-mango-dark text-neutral-dark rounded-xl text-xs font-bold transition">
                                    Lanjut →
                                </button>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@section('scripts')
<script>
function builderApp() {
    return {
        judul:          @json($survey->judul),
        deskripsi:      @json($survey->deskripsi ?? ''),
        status:         @json($survey->status),
        tanggalMulai:   @json($survey->tanggal_mulai?->format('Y-m-d') ?? ''),
        tanggalSelesai: @json($survey->tanggal_selesai?->format('Y-m-d') ?? ''),
        questions:      @json($formattedQuestions),
        previewStep: 0,
        saving: false,
        toastMessage: '',
        newlyAddedIdx: null,

        showToast(msg) {
            this.toastMessage = msg;
            clearTimeout(this._toastTimer);
            this._toastTimer = setTimeout(() => this.toastMessage = '', 2500);
        },

        addQuestion() {
            this.questions.push({
                id: null,
                tipe_pertanyaan: 'SINGLE_CHOICE',
                teks_pertanyaan: '',
                opsi_jawaban: ['Opsi 1', 'Opsi 2', 'Opsi 3'],
                wajib_diisi: true,
                urutan: this.questions.length + 1
            });
            this.newlyAddedIdx = this.questions.length - 1;
            this.showToast('✨ Pertanyaan #' + this.questions.length + ' ditambahkan!');
            setTimeout(() => this.newlyAddedIdx = null, 1500);
        },

        removeQuestion(idx) {
            this.questions.splice(idx, 1);
            this.showToast('🗑️ Pertanyaan dihapus.');
        },

        moveUp(idx) {
            if (idx === 0) return;
            [this.questions[idx - 1], this.questions[idx]] = [this.questions[idx], this.questions[idx - 1]];
            this.questions = [...this.questions];
            this.showToast('⬆️ Pertanyaan dipindah ke atas.');
        },

        moveDown(idx) {
            if (idx === this.questions.length - 1) return;
            [this.questions[idx + 1], this.questions[idx]] = [this.questions[idx], this.questions[idx + 1]];
            this.questions = [...this.questions];
            this.showToast('⬇️ Pertanyaan dipindah ke bawah.');
        },

        changeType(q, newType) {
            q.tipe_pertanyaan = newType;
            if ((newType === 'SINGLE_CHOICE' || newType === 'MULTIPLE_CHOICE') && (!q.opsi_jawaban || q.opsi_jawaban.length === 0)) {
                q.opsi_jawaban = ['Opsi 1', 'Opsi 2', 'Opsi 3'];
            }
        },

        async saveSurvey(targetStatus) {
            this.saving = true;
            const payload = {
                judul:            this.judul,
                deskripsi:        this.deskripsi,
                status:           targetStatus || this.status,
                tanggal_mulai:    this.tanggalMulai,
                tanggal_selesai:  this.tanggalSelesai,
                questions:        this.questions,
            };

            try {
                const res = await fetch("{{ route('admin.surveys.update', $survey->id) }}", {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify(payload)
                });

                if (res.ok) {
                    this.status = targetStatus || this.status;
                    const verb = targetStatus === 'PUBLISHED' ? '🚀 Survey berhasil dipublikasikan!' : '💾 Survey berhasil disimpan sebagai Draft.';
                    this.showToast(verb);
                } else {
                    const json = await res.json().catch(() => ({}));
                    this.showToast('❌ Gagal menyimpan: ' + (json.message || 'Coba lagi.'));
                }
            } catch (e) {
                this.showToast('❌ Terjadi kesalahan jaringan.');
            } finally {
                this.saving = false;
            }
        }
    }
}
</script>
@endsection
