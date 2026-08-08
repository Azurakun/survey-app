<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $survey->judul }} — Survey Riset Pasar SMKN 2 Indramayu</title>
    <meta name="description" content="{{ $survey->deskripsi ?? 'Survey Riset Pasar Kewirausahaan SMKN 2 Indramayu' }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,600;9..144,700;9..144,800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: { extend: {
                colors: {
                    mango:   { DEFAULT:'#E8A33D', dark:'#C7842A', tint:'#FDF3E3' },
                    leaf:    { DEFAULT:'#2F5233', dark:'#213A25', tint:'#E6EFE7', light:'#4A7A4E' },
                    terra:   { DEFAULT:'#D9622B', dark:'#B44A1A', tint:'#FAEAE3' },
                    neutral: { dark:'#2B2118', medium:'#5A4A3A', muted:'#8A7A6A', bg:'#FBF6EE', border:'#EDE2D0' },
                },
                fontFamily: {
                    serif: ['Fraunces','Georgia','serif'],
                    sans:  ['Plus Jakarta Sans','system-ui','sans-serif'],
                }
            }}
        }
    </script>
    <style>
        body { font-family:'Plus Jakarta Sans',system-ui,sans-serif; background-color:#FBF6EE; color:#2B2118; }
        .font-serif { font-family:'Fraunces',Georgia,serif !important; }
        @keyframes popSuccess {
            0%   { transform:scale(0.5); opacity:0; }
            60%  { transform:scale(1.15); opacity:1; }
            100% { transform:scale(1); opacity:1; }
        }
        @keyframes slideIn {
            0%   { transform:translateX(24px); opacity:0; }
            100% { transform:translateX(0); opacity:1; }
        }
        .animate-pop { animation:popSuccess 0.5s cubic-bezier(0.34,1.56,0.64,1) forwards; }
        .animate-slide { animation:slideIn 0.3s ease-out forwards; }
    </style>
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js" defer></script>
</head>
<body class="bg-neutral-bg min-h-screen py-8 px-4">

<div x-data="studentWizardApp()" class="max-w-[600px] mx-auto space-y-5">

    <!-- ── Header Card ─────────────────────────────────────────────────── -->
    <div class="bg-white rounded-2xl border border-neutral-border shadow-sm p-6 text-center">
        <div class="flex items-center justify-center gap-2.5 mb-3">
            <div class="w-9 h-9 rounded-xl bg-leaf flex items-center justify-center font-serif font-bold text-mango text-xs shadow">
                SMK
            </div>
            <span class="text-[11px] font-sans font-bold text-terra uppercase tracking-widest">SMKN 2 INDRAMAYU</span>
        </div>
        <h1 class="font-serif font-bold text-xl sm:text-2xl text-neutral-dark leading-tight">
            {{ $survey->judul }}
        </h1>
        @if($survey->deskripsi)
            <p class="text-xs text-neutral-muted mt-2 leading-relaxed max-w-md mx-auto">{{ $survey->deskripsi }}</p>
        @endif

        <!-- Progress Bar -->
        <div class="mt-5 pt-4 border-t border-neutral-border">
            <div class="flex justify-between items-center text-[11px] font-semibold text-neutral-dark mb-2">
                <span x-text="step === 0 ? '🏁 IDENTITAS SISWA' : (isSubmitted ? '✅ SELESAI' : '⚡ LANGKAH ' + step + ' DARI ' + totalQuestions)"></span>
                <span class="tabular-nums font-bold text-mango" x-text="progressPercent + '%'"></span>
            </div>
            <div class="w-full bg-neutral-border h-2.5 rounded-full overflow-hidden">
                <div class="bg-mango h-full transition-all duration-500 ease-out rounded-full"
                     :style="'width:' + progressPercent + '%'"></div>
            </div>
        </div>
    </div>

    <!-- ── Global Error ─────────────────────────────────────────────────── -->
    <template x-if="errorMessage">
        <div class="p-4 rounded-xl bg-terra-tint border border-terra/30 text-terra-dark text-xs font-semibold flex items-start gap-2.5 animate-slide">
            <svg class="w-4 h-4 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span x-text="errorMessage"></span>
        </div>
    </template>

    <!-- ── STEP 0: Input NISN ───────────────────────────────────────────── -->
    <template x-if="step === 0">
        <div class="bg-white p-6 rounded-2xl border-2 border-leaf shadow-sm space-y-5 animate-slide">
            <div class="flex items-center justify-between border-b border-neutral-border pb-3">
                <span class="text-[10px] font-sans uppercase tracking-widest text-terra font-bold">LANGKAH AWAL</span>
                <span class="text-xs font-semibold text-neutral-muted">00 / <span x-text="totalQuestions"></span></span>
            </div>

            <div class="space-y-1.5">
                <h2 class="font-serif font-bold text-xl text-neutral-dark">Identitas Responden Siswa</h2>
                <p class="text-xs text-neutral-muted leading-relaxed">
                    Masukkan Nomor Induk Siswa Nasional (NISN) Anda untuk memulai survey. Setiap siswa hanya dapat mengisi satu kali.
                </p>
            </div>

            <div class="space-y-2">
                <label class="block text-[10px] font-sans uppercase tracking-widest font-bold text-neutral-dark">
                    NISN Siswa (10 digit)
                </label>
                <input type="text" x-model="nisn" inputmode="numeric" maxlength="20"
                       placeholder="Contoh: 0012345678"
                       @keyup.enter="startSurvey()"
                       class="w-full px-4 py-3 bg-neutral-bg/60 border border-neutral-border rounded-xl text-base
                              font-bold text-neutral-dark tracking-wider text-center
                              focus:ring-2 focus:ring-mango focus:outline-none transition">
            </div>

            <button @click="startSurvey()"
                    class="w-full py-3.5 bg-leaf hover:bg-leaf-dark text-white font-bold text-sm rounded-xl
                           shadow-md transition-all duration-200 flex items-center justify-center gap-2 active:scale-[0.98]">
                <span>Mulai Isi Survey</span>
                <svg class="w-5 h-5 text-mango" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
            </button>
        </div>
    </template>

    <!-- ── STEP 1..N: Wizard Questions ─────────────────────────────────── -->
    <template x-if="step > 0 && !isSubmitted">
        <div class="bg-white p-6 rounded-2xl border border-neutral-border shadow-sm space-y-5">
            <template x-for="(q, qIdx) in questions" :key="q.id">
                <div x-show="step === (qIdx + 1)"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-x-3"
                     x-transition:enter-end="opacity-100 translate-x-0"
                     class="space-y-4">

                    <!-- Question Header -->
                    <div class="flex items-center justify-between border-b border-neutral-border pb-3">
                        <span class="text-[10px] font-bold bg-neutral-dark text-white px-2.5 py-1 rounded-lg uppercase tracking-widest"
                              x-text="q.tipe_pertanyaan"></span>
                        <span class="font-sans text-sm font-bold text-neutral-muted tabular-nums">
                            <span class="text-mango" x-text="String(qIdx+1).padStart(2,'0')"></span>
                            / <span x-text="String(totalQuestions).padStart(2,'0')"></span>
                        </span>
                    </div>

                    <!-- Question Text -->
                    <div class="flex items-start gap-3">
                        <span class="w-7 h-7 rounded-lg bg-mango/20 text-mango-dark font-bold text-xs flex items-center justify-center shrink-0 mt-0.5"
                              x-text="qIdx + 1"></span>
                        <div>
                            <p class="text-sm font-bold text-neutral-dark leading-relaxed" x-text="q.teks_pertanyaan"></p>
                            <span x-show="q.wajib_diisi" class="text-[10px] text-terra font-semibold">* Wajib diisi</span>
                        </div>
                    </div>

                    <!-- SINGLE_CHOICE -->
                    <template x-if="q.tipe_pertanyaan === 'SINGLE_CHOICE'">
                        <div class="space-y-2.5">
                            <template x-for="opt in q.parsed_options" :key="opt">
                                <label :class="answers[q.id] === opt
                                        ? 'bg-mango-tint border-mango shadow-sm scale-[1.01]'
                                        : 'bg-neutral-bg/50 border-neutral-border hover:bg-neutral-bg'"
                                       class="flex items-center gap-3 p-3.5 rounded-xl border text-sm text-neutral-dark cursor-pointer transition-all duration-150">
                                    <input type="radio" :name="'q_' + q.id" :value="opt" x-model="answers[q.id]"
                                           class="w-4 h-4 text-mango focus:ring-mango border-neutral-border">
                                    <span x-text="opt" class="font-medium"></span>
                                </label>
                            </template>
                        </div>
                    </template>

                    <!-- MULTIPLE_CHOICE -->
                    <template x-if="q.tipe_pertanyaan === 'MULTIPLE_CHOICE'">
                        <div class="space-y-2.5">
                            <template x-for="opt in q.parsed_options" :key="opt">
                                <label :class="isCheckboxChecked(q.id, opt)
                                        ? 'bg-mango-tint border-mango shadow-sm scale-[1.01]'
                                        : 'bg-neutral-bg/50 border-neutral-border hover:bg-neutral-bg'"
                                       class="flex items-center gap-3 p-3.5 rounded-xl border text-sm text-neutral-dark cursor-pointer transition-all duration-150">
                                    <input type="checkbox" :value="opt"
                                           @change="toggleCheckbox(q.id, opt)"
                                           :checked="isCheckboxChecked(q.id, opt)"
                                           class="w-4 h-4 rounded text-mango focus:ring-mango border-neutral-border">
                                    <span x-text="opt" class="font-medium"></span>
                                </label>
                            </template>
                        </div>
                    </template>

                    <!-- LIKERT 1-5 -->
                    <template x-if="q.tipe_pertanyaan === 'LIKERT'">
                        <div class="space-y-3">
                            <div class="grid grid-cols-5 gap-2">
                                <template x-for="num in ['1','2','3','4','5']" :key="num">
                                    <button type="button" @click="answers[q.id] = num"
                                            :class="answers[q.id] === num
                                                    ? 'bg-neutral-dark text-white border-neutral-dark shadow-lg scale-105'
                                                    : 'bg-neutral-bg border-neutral-border text-neutral-dark hover:bg-neutral-border'"
                                            class="py-4 rounded-xl border font-bold text-lg transition-all duration-150 flex flex-col items-center">
                                        <span x-text="num"></span>
                                    </button>
                                </template>
                            </div>
                            <div class="flex justify-between text-[10px] font-semibold text-neutral-muted px-1">
                                <span>1 · Sangat Tidak Setuju</span>
                                <span>5 · Sangat Setuju</span>
                            </div>
                        </div>
                    </template>

                    <!-- SHORT_TEXT -->
                    <template x-if="q.tipe_pertanyaan === 'SHORT_TEXT'">
                        <input type="text" x-model="answers[q.id]"
                               placeholder="Ketik jawaban singkat Anda..."
                               class="w-full px-4 py-3 bg-neutral-bg/60 border border-neutral-border rounded-xl text-sm text-neutral-dark focus:ring-2 focus:ring-mango focus:outline-none transition">
                    </template>

                    <!-- LONG_TEXT -->
                    <template x-if="q.tipe_pertanyaan === 'LONG_TEXT'">
                        <textarea x-model="answers[q.id]" rows="4"
                                  placeholder="Ketik masukan / penjelasan lengkap Anda..."
                                  class="w-full px-4 py-3 bg-neutral-bg/60 border border-neutral-border rounded-xl text-sm text-neutral-dark focus:ring-2 focus:ring-mango focus:outline-none resize-none transition"></textarea>
                    </template>

                    <!-- NUMBER -->
                    <template x-if="q.tipe_pertanyaan === 'NUMBER'">
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-sm font-bold text-mango-dark">Rp</span>
                            <input type="number" x-model="answers[q.id]" min="0"
                                   placeholder="Masukkan estimasi harga..."
                                   class="w-full pl-12 pr-4 py-3 bg-neutral-bg/60 border border-neutral-border rounded-xl text-sm font-bold text-neutral-dark focus:ring-2 focus:ring-mango focus:outline-none transition">
                        </div>
                    </template>

                    <!-- IMAGE_UPLOAD -->
                    <template x-if="q.tipe_pertanyaan === 'IMAGE_UPLOAD'">
                        <div class="space-y-3">
                            <label class="block w-full border-2 border-dashed border-neutral-border hover:border-mango
                                         p-6 text-center rounded-xl bg-neutral-bg/30 cursor-pointer transition-all">
                                <svg class="w-9 h-9 text-mango mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                <span class="text-xs font-bold text-neutral-dark block">Pilih Berkas Foto / Gambar Produk</span>
                                <span class="text-[10px] font-sans text-neutral-muted mt-1 block">FORMAT: JPG, JPEG, PNG · MAKS 5MB</span>
                                <input type="file" accept="image/*" @change="handleFileUpload(q.id, $event)" class="hidden">
                            </label>
                            <template x-if="filePreviews[q.id]">
                                <div class="p-3 bg-white border border-neutral-border rounded-xl flex items-center gap-3 animate-slide">
                                    <img :src="filePreviews[q.id]" class="w-14 h-14 object-cover rounded-lg border border-neutral-border">
                                    <div class="flex-1 min-w-0">
                                        <span class="text-xs font-bold text-leaf block">✓ Foto dipilih</span>
                                        <span class="text-[11px] text-neutral-muted truncate block" x-text="files[q.id] ? files[q.id].name : ''"></span>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </template>

                    <!-- DATE -->
                    <template x-if="q.tipe_pertanyaan === 'DATE'">
                        <input type="date" x-model="answers[q.id]"
                               class="w-full px-4 py-3 bg-neutral-bg/60 border border-neutral-border rounded-xl text-sm font-bold text-neutral-dark focus:ring-2 focus:ring-mango focus:outline-none transition">
                    </template>
                </div>
            </template>

            <!-- Navigation -->
            <div class="flex items-center justify-between pt-4 border-t border-neutral-border">
                <button type="button" @click="prevStep()"
                        class="px-4 py-2.5 bg-neutral-bg hover:bg-neutral-border text-neutral-dark text-xs font-bold rounded-xl transition flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    Kembali
                </button>

                <template x-if="step < totalQuestions">
                    <button type="button" @click="nextStep()"
                            class="px-5 py-2.5 bg-mango hover:bg-mango-dark text-neutral-dark text-xs font-bold rounded-xl shadow-sm transition flex items-center gap-1.5 active:scale-95">
                        Lanjut
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </button>
                </template>

                <template x-if="step === totalQuestions">
                    <button type="button" @click="submitSurvey()" :disabled="submitting"
                            class="px-6 py-2.5 bg-leaf hover:bg-leaf-dark text-white text-xs font-bold rounded-xl shadow-md transition flex items-center gap-1.5 disabled:opacity-50 active:scale-95">
                        <span x-text="submitting ? 'Mengirim...' : 'Kirim Jawaban'"></span>
                        <svg class="w-4 h-4 text-mango" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </button>
                </template>
            </div>
        </div>
    </template>

    <!-- ── SUCCESS SCREEN ──────────────────────────────────────────────── -->
    <template x-if="isSubmitted">
        <div class="bg-white rounded-2xl border-2 border-mango p-8 text-center shadow-lg space-y-4 animate-pop">
            <div class="w-20 h-20 rounded-full bg-mango flex items-center justify-center mx-auto shadow-lg">
                <svg class="w-10 h-10 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
            </div>
            <h2 class="font-serif font-bold text-2xl text-neutral-dark">Jawaban Berhasil Disimpan!</h2>
            <p class="text-xs text-neutral-muted leading-relaxed max-w-sm mx-auto">
                Terima kasih atas partisipasi Anda dalam survey riset pasar Kewirausahaan SMKN 2 Indramayu.
                Kontribusi Anda sangat berharga bagi inovasi produk siswa!
            </p>
            <div class="px-4 py-3 bg-neutral-bg rounded-xl border border-neutral-border text-xs text-neutral-dark font-semibold">
                NISN Anda: <strong class="text-mango font-bold tracking-wider" x-text="nisn"></strong>
            </div>
            <button @click="window.location.reload()"
                    class="w-full py-3.5 bg-leaf hover:bg-leaf-dark text-white font-bold text-sm rounded-xl shadow-md transition">
                Selesai
            </button>
        </div>
    </template>

</div>

<script>
function studentWizardApp() {
    return {
        nisn: '',
        step: 0,
        questions: @json($formattedQuestions),
        answers: {},
        files: {},
        filePreviews: {},
        submitting: false,
        isSubmitted: false,
        errorMessage: '',

        get totalQuestions() { return this.questions.length; },

        get progressPercent() {
            if (this.isSubmitted) return 100;
            if (this.totalQuestions === 0) return 0;
            return Math.round((this.step / this.totalQuestions) * 100);
        },

        startSurvey() {
            this.errorMessage = '';
            const n = this.nisn.trim();
            if (!n || n.length < 5) {
                this.errorMessage = 'Harap masukkan NISN yang valid (minimal 5 digit).';
                return;
            }
            this.step = 1;
        },

        isCheckboxChecked(qId, opt) {
            return Array.isArray(this.answers[qId]) && this.answers[qId].includes(opt);
        },

        toggleCheckbox(qId, opt) {
            if (!Array.isArray(this.answers[qId])) this.answers[qId] = [];
            const idx = this.answers[qId].indexOf(opt);
            if (idx > -1) this.answers[qId].splice(idx, 1);
            else this.answers[qId].push(opt);
        },

        handleFileUpload(qId, event) {
            const file = event.target.files[0];
            if (file) {
                this.files[qId] = file;
                this.filePreviews[qId] = URL.createObjectURL(file);
            }
        },

        validateCurrentStep() {
            this.errorMessage = '';
            if (this.step === 0) return true;
            const q = this.questions[this.step - 1];
            if (q && q.wajib_diisi) {
                const hasText = this.answers[q.id] !== undefined && this.answers[q.id] !== ''
                    && (!Array.isArray(this.answers[q.id]) || this.answers[q.id].length > 0);
                const hasFile = this.files[q.id] !== undefined;
                if (!hasText && !hasFile) {
                    this.errorMessage = `Pertanyaan "${q.teks_pertanyaan}" wajib diisi.`;
                    return false;
                }
            }
            return true;
        },

        nextStep() {
            if (this.validateCurrentStep() && this.step < this.totalQuestions) this.step++;
        },

        prevStep() {
            this.errorMessage = '';
            if (this.step > 0) this.step--;
        },

        async submitSurvey() {
            if (!this.validateCurrentStep()) return;
            this.submitting = true;
            this.errorMessage = '';

            const formData = new FormData();
            formData.append('nisn', this.nisn.trim());

            Object.keys(this.answers).forEach(qId => {
                const val = this.answers[qId];
                if (Array.isArray(val)) {
                    val.forEach(item => formData.append(`answers[${qId}][]`, item));
                } else {
                    formData.append(`answers[${qId}]`, val);
                }
            });

            Object.keys(this.files).forEach(qId => {
                formData.append(`answers[${qId}]`, this.files[qId]);
            });

            try {
                const res = await fetch("{{ route('student.survey.submit', $survey->id) }}", {
                    method: 'POST',
                    headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: formData
                });
                const json = await res.json();
                if (!res.ok) {
                    this.errorMessage = json.error || 'Gagal mengirim jawaban. Coba lagi.';
                    return;
                }
                this.isSubmitted = true;
            } catch(e) {
                this.errorMessage = 'Terjadi kesalahan jaringan. Periksa koneksi internet Anda.';
            } finally {
                this.submitting = false;
            }
        }
    }
}
</script>
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>
</html>
