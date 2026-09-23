<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $survey->judul }} — Survey Riset Pasar</title>
    <meta name="description" content="{{ $survey->deskripsi ?? 'Survey Riset Pasar Kewirausahaan' }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        amber: { 50:'#FFFBEB', 100:'#FEF3C7', 400:'#FBBF24', 500:'#F59E0B', 800:'#92400E', 900:'#78350F' },
                        stone: { 50:'#FAFAF9', 100:'#F5F5F4', 200:'#E7E5E4', 300:'#D6D3D1', 400:'#A8A29E', 600:'#57534E', 800:'#292524', 900:'#1C1917' }
                    },
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'system-ui', 'sans-serif'],
                        serif: ['Plus Jakarta Sans', 'system-ui', 'sans-serif']
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Plus Jakarta Sans', system-ui, sans-serif; background-color: #FAF8F5; color: #1C1917; }
        [x-cloak] { display: none !important; }
        @keyframes popSuccess {
            0%   { transform: scale(0.6); opacity: 0; }
            60%  { transform: scale(1.08); opacity: 1; }
            100% { transform: scale(1); opacity: 1; }
        }
        .animate-pop { animation: popSuccess 0.4s cubic-bezier(0.34, 1.56, 0.64, 1) forwards; }
    </style>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-stone-50 min-h-screen py-6 px-4 sm:py-12">

<div x-data="studentWizardApp()" x-cloak class="max-w-3xl mx-auto space-y-6">

    <!-- ── Header Bar ──────────────────────────────────────────────────── -->
    <header class="flex items-center justify-between p-4 sm:p-5 rounded-2xl bg-white border border-stone-200 shadow-xs">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center font-bold text-sm shrink-0 shadow-2xs"
                 style="background:#1C1917; color:#C89D54;">
                RP
            </div>
            <div>
                <div class="text-[9px] font-mono uppercase tracking-widest font-bold text-amber-800">PLATFORM SURVEY</div>
                <div class="font-serif text-sm font-bold text-stone-900 leading-tight">Riset Pasar Kewirausahaan</div>
            </div>
        </div>
        <div class="flex items-center gap-2.5">
            <!-- Google Form style Autosave Indicator -->
            <template x-if="!isClosed && !isAlreadySubmitted && !isSubmitted && (step > 0 || (nisn && nisn.trim().length > 0))">
                <div class="flex items-center gap-1.5 px-3 py-1.5 rounded-full text-[11px] transition-all duration-200 border"
                     :class="saveStatus === 'saving' ? 'bg-amber-50 text-amber-800 border-amber-200' : 'bg-stone-50 text-stone-600 border-stone-200'">
                    <template x-if="saveStatus === 'saving'">
                        <span class="flex items-center gap-1.5 font-medium">
                            <span class="inline-block w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                            <span>Menyimpan draf...</span>
                        </span>
                    </template>
                    <template x-if="saveStatus === 'saved'">
                        <span class="flex items-center gap-1.5 font-medium text-stone-600" title="Draf otomatis tersimpan di perangkat ini">
                            <span class="text-emerald-600 text-xs font-bold">☁️✓</span>
                            <span>Draf tersimpan</span>
                            <span class="text-[10px] text-stone-400 font-mono" x-show="lastSavedTime" x-text="lastSavedTime"></span>
                        </span>
                    </template>
                </div>
            </template>
            <div class="text-xs font-semibold px-3 py-1.5 rounded-full border border-stone-200 bg-stone-50 text-stone-700 hidden sm:inline-block">
                <span>Responden Siswa</span>
            </div>
        </div>
    </header>

    <!-- ── Restored Draft Notification Banner ──────────────────────────── -->
    <template x-if="restoredFromDraft && !isClosed && !isAlreadySubmitted && !isSubmitted">
        <div class="p-4 rounded-2xl border text-xs bg-amber-50/90 border-amber-200 text-amber-900 flex items-center justify-between gap-3 shadow-xs">
            <div class="flex items-center gap-2.5">
                <span class="text-base shrink-0">📋</span>
                <div>
                    <span class="font-bold">Draf Ditemukan:</span> Progres jawaban Anda sebelumnya telah dipulihkan secara otomatis.
                </div>
            </div>
            <button type="button" @click="resetDraftConfirm()" class="px-3 py-1.5 rounded-lg bg-white border border-amber-300 hover:bg-amber-100 text-amber-900 text-[11px] font-bold transition shrink-0 cursor-pointer shadow-2xs">
                Kosongkan & Mulai Ulang
            </button>
        </div>
    </template>

    <!-- ── Error Message Banner ────────────────────────────────────────── -->
    <template x-if="errorMessage">
        <div class="p-4 rounded-xl border text-xs font-semibold flex items-center gap-3 bg-red-50 border-red-200 text-red-800 shadow-xs">
            <span class="text-base shrink-0">⚠️</span>
            <span x-text="errorMessage"></span>
        </div>
    </template>

    <!-- ── CLOSED SURVEY SCREEN ────────────────────────────────────────── -->
    <template x-if="isClosed">
        <div class="bg-white p-8 sm:p-12 rounded-3xl border border-stone-200 text-center space-y-6 shadow-sm">
            <div class="w-20 h-20 rounded-full bg-stone-100 border-2 border-stone-300 text-stone-600 text-4xl mx-auto flex items-center justify-center">
                🔒
            </div>
            <div class="space-y-2 max-w-md mx-auto">
                <span class="inline-block px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider border border-red-200 bg-red-50 text-red-800 mb-2">
                    🔴 SURVEY DITUTUP
                </span>
                <h2 class="font-serif font-bold text-2xl text-stone-900">{{ $survey->judul }}</h2>
                <p class="text-xs sm:text-sm text-stone-600 leading-relaxed">
                    {{ $closedMessage }}
                </p>
            </div>
        </div>
    </template>

    <!-- ── ALREADY SUBMITTED SCREEN ──────────────────────────────────────── -->
    <template x-if="!isClosed && isAlreadySubmitted && !isSubmitted">
        <div class="bg-white p-8 sm:p-12 rounded-3xl border border-stone-200 text-center space-y-6 shadow-sm">
            <div class="w-20 h-20 rounded-full bg-amber-50 border-2 border-amber-300 text-amber-800 text-4xl mx-auto flex items-center justify-center">
                ✓
            </div>
            <div class="space-y-2 max-w-md mx-auto">
                <span class="inline-block px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider border border-amber-200 bg-amber-50 text-amber-900 mb-2">
                    ✅ SUDAH MENANGGAPI
                </span>
                <h2 class="font-serif font-bold text-2xl text-stone-900">Anda Sudah Mengisi Survey Ini</h2>
                <p class="text-xs sm:text-sm text-stone-600 leading-relaxed">
                    Tanggapan Anda telah tercatat sebelumnya. Pembatasan 1x pengisian diaktifkan untuk survey <strong>"{{ $survey->judul }}"</strong>.
                </p>
            </div>
        </div>
    </template>

    <!-- ── SUCCESS SCREEN (WHEN SURVEY SUBMITTED) ──────────────────────── -->
    <template x-if="!isClosed && !isAlreadySubmitted && isSubmitted">
        <div class="bg-white p-8 sm:p-12 rounded-3xl border border-stone-200 text-center space-y-6 shadow-sm animate-pop">
            <div class="w-20 h-20 rounded-full bg-emerald-100 border-2 border-emerald-300 text-emerald-700 text-4xl mx-auto flex items-center justify-center shadow-xs">
                ✓
            </div>
            <div class="space-y-2 max-w-md mx-auto">
                <h2 class="font-serif font-bold text-2xl text-stone-900">Jawaban Berhasil Terkirim!</h2>
                <p class="text-xs text-stone-600 leading-relaxed">
                    Terima kasih atas partisipasi Anda dalam survey <strong>"{{ $survey->judul }}"</strong>. Masukan Anda sangat berharga untuk riset pasar dan pengembangan produk kewirausahaan.
                </p>
            </div>
        </div>
    </template>

    <!-- ── SURVEY WIZARD CONTAINER (IF NOT CLOSED & NOT SUBMITTED) ──────── -->
    <template x-if="!isClosed && !isAlreadySubmitted && !isSubmitted">
        <div class="space-y-6">

            <!-- STEP 0: NISN VERIFICATION CARD -->
            <template x-if="step === 0">
                <div class="bg-white p-6 sm:p-10 rounded-3xl border border-stone-200 shadow-sm space-y-6">
                    <div class="space-y-3">
                        <span class="inline-block px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider border border-amber-200 bg-amber-50 text-amber-900">
                            📋 SURVEY AKTIF
                        </span>
                        <h1 class="font-serif font-bold text-2xl sm:text-3xl text-stone-900 leading-tight">
                            {{ $survey->judul }}
                        </h1>
                        @if($survey->deskripsi)
                            <p class="text-xs sm:text-sm text-stone-600 leading-relaxed">{{ $survey->deskripsi }}</p>
                        @endif
                    </div>

                    <div class="p-5 rounded-2xl border border-stone-200 bg-stone-50 space-y-2 text-xs text-stone-600">
                        <div class="font-bold text-stone-900 flex items-center gap-2">
                            <span>📌</span>
                            <span>Panduan Pengisian Responden:</span>
                        </div>
                        <ul class="space-y-1.5 pl-5 list-disc">
                            <li>Masukkan NISN aktif Anda untuk verifikasi identitas responden.</li>
                            <li>Jawab setiap pertanyaan dengan sejujurnya sesuai preferensi Anda.</li>
                            <li>Setiap responden hanya dapat berpartisipasi 1 kali.</li>
                        </ul>
                    </div>

                    <!-- NISN Form Input -->
                    <div class="space-y-3 pt-2">
                        <label class="block text-xs font-bold uppercase tracking-wider text-stone-800">
                            Masukkan NISN Siswa (10 Digit)
                        </label>
                        <input type="text" x-model="nisn" inputmode="numeric" maxlength="20"
                               placeholder="Contoh: 0012345678"
                               @input="triggerAutosave()"
                               @keyup.enter="startSurvey()"
                               class="w-full px-5 py-4 rounded-xl text-lg font-bold tracking-wider text-center border border-stone-300 bg-stone-50 text-stone-900 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:bg-white transition">
                    </div>

                    <button @click="startSurvey()"
                            class="w-full py-4 rounded-xl text-white font-bold text-sm shadow-xs transition flex items-center justify-center gap-2"
                            style="background:#1C1917;">
                        <span>Mulai Pengisian Survey</span>
                        <span class="text-amber-400">→</span>
                    </button>
                </div>
            </template>

            <!-- STEP 1..N: QUESTION STEPS -->
            <template x-if="step > 0">
                <div class="bg-white p-6 sm:p-10 rounded-3xl border border-stone-200 shadow-sm space-y-8">

                    <!-- Progress Bar & Step Header -->
                    <div class="space-y-3 border-b border-stone-200 pb-5">
                        <div class="flex items-center justify-between text-xs font-bold">
                            <span class="text-stone-500 uppercase tracking-wider"
                                  x-text="'Pertanyaan ' + step + ' dari ' + totalQuestions"></span>
                            <span class="text-amber-800 font-mono" x-text="progressPercent + '%'"></span>
                        </div>
                        <div class="w-full h-2.5 rounded-full bg-stone-100 overflow-hidden">
                            <div class="h-full bg-amber-500 transition-all duration-300 rounded-full"
                                 :style="'width:' + progressPercent + '%'"></div>
                        </div>
                    </div>

                    <!-- Loop Questions -->
                    <template x-for="(q, qIdx) in questions" :key="q.id">
                        <div x-show="step === (qIdx + 1)" class="space-y-6">

                            <!-- Question Title & Description -->
                            <div class="space-y-2">
                                <div class="flex items-center gap-2">
                                    <span class="px-2.5 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-stone-100 border border-stone-200 text-stone-700"
                                          x-text="q.tipe_pertanyaan"></span>
                                    <template x-if="q.wajib_diisi">
                                        <span class="text-[10px] font-bold text-red-600 uppercase tracking-wider">* Wajib</span>
                                    </template>
                                </div>

                                <h2 class="font-serif font-bold text-xl sm:text-2xl text-stone-900 leading-snug">
                                    <span class="text-amber-800 font-mono mr-1" x-text="(qIdx + 1) + '.'"></span>
                                    <span x-text="q.teks_pertanyaan"></span>
                                </h2>

                                <template x-if="q.deskripsi_pertanyaan">
                                    <p class="text-xs text-stone-500 leading-relaxed" x-text="q.deskripsi_pertanyaan"></p>
                                </template>
                            </div>

                            <!-- INPUT TYPE 1: SINGLE CHOICE (RADIO CARDS) -->
                            <template x-if="q.tipe_pertanyaan === 'SINGLE_CHOICE'">
                                <div class="space-y-3 pt-2">
                                    <template x-for="(opt, oIdx) in getOptions(q.opsi_jawaban)" :key="oIdx">
                                        <label class="p-4 rounded-xl border border-stone-200 bg-stone-50/50 hover:bg-stone-50 hover:border-stone-400 flex items-center gap-3 cursor-pointer transition"
                                               :class="answers[q.id] === opt ? 'border-amber-500 bg-amber-50/60 ring-2 ring-amber-500/20' : ''">
                                            <input type="radio" :name="'q_' + q.id" :value="opt"
                                                   x-model="answers[q.id]" @change="triggerAutosave()" class="w-4 h-4 text-amber-600 focus:ring-amber-500">
                                            <span class="text-xs font-semibold text-stone-800" x-text="opt"></span>
                                        </label>
                                    </template>
                                </div>
                            </template>

                            <!-- INPUT TYPE 2: MULTIPLE CHOICE (CHECKBOX CARDS) -->
                            <template x-if="q.tipe_pertanyaan === 'MULTIPLE_CHOICE'">
                                <div class="space-y-3 pt-2">
                                    <template x-for="(opt, oIdx) in getOptions(q.opsi_jawaban)" :key="oIdx">
                                        <label class="p-4 rounded-xl border border-stone-200 bg-stone-50/50 hover:bg-stone-50 hover:border-stone-400 flex items-center gap-3 cursor-pointer transition"
                                               :class="isOptionChecked(q.id, opt) ? 'border-amber-500 bg-amber-50/60 ring-2 ring-amber-500/20' : ''">
                                            <input type="checkbox" :value="opt"
                                                   @change="toggleMultipleChoice(q.id, opt)"
                                                   :checked="isOptionChecked(q.id, opt)"
                                                   class="w-4 h-4 rounded text-amber-600 focus:ring-amber-500">
                                            <span class="text-xs font-semibold text-stone-800" x-text="opt"></span>
                                        </label>
                                    </template>
                                </div>
                            </template>

                            <!-- INPUT TYPE 3: LIKERT SCALE (1 to 5 BUTTONS) -->
                            <template x-if="q.tipe_pertanyaan === 'LIKERT'">
                                <div class="space-y-4 pt-2">
                                    <div class="grid grid-cols-5 gap-2 sm:gap-3">
                                        <template x-for="val in [1, 2, 3, 4, 5]" :key="val">
                                            <button type="button" @click="answers[q.id] = val; triggerAutosave()"
                                                    class="py-4 rounded-2xl border text-center transition font-bold text-sm sm:text-base cursor-pointer"
                                                    :class="answers[q.id] == val ? 'bg-amber-500 border-amber-600 text-white shadow-xs scale-105' : 'bg-stone-50 border-stone-200 text-stone-800 hover:bg-stone-100'">
                                                <div x-text="val"></div>
                                                <div class="text-[9px] font-normal uppercase opacity-75 mt-0.5"
                                                     x-text="val === 1 ? 'Sangat Buruk' : (val === 3 ? 'Netral' : (val === 5 ? 'Sangat Baik' : ''))"></div>
                                            </button>
                                        </template>
                                    </div>
                                    <div class="flex justify-between text-[10px] font-bold text-stone-400 uppercase tracking-wider px-1">
                                        <span>1 = Sangat Tidak Setuju / Buruk</span>
                                        <span>5 = Sangat Setuju / Baik</span>
                                    </div>
                                </div>
                            </template>

                            <!-- INPUT TYPE 4: NUMBER INPUT (WTP / HARGA) -->
                            <template x-if="q.tipe_pertanyaan === 'NUMBER'">
                                <div class="space-y-3 pt-2">
                                    <div class="relative">
                                        <span class="absolute left-4 top-1/2 -translate-y-1/2 font-bold text-sm text-stone-400">Rp</span>
                                        <input type="number" x-model="answers[q.id]" @input="triggerAutosave()" placeholder="Contoh: 25000"
                                               class="w-full pl-12 pr-4 py-4 rounded-xl text-base font-bold border border-stone-300 bg-stone-50 text-stone-900 focus:outline-none focus:ring-2 focus:ring-amber-500 transition">
                                    </div>
                                    <p class="text-[11px] text-stone-500">Masukkan nominal angka harga dalam Rupiah.</p>
                                </div>
                            </template>

                            <!-- INPUT TYPE 5: SHORT TEXT -->
                            <template x-if="q.tipe_pertanyaan === 'SHORT_TEXT'">
                                <div class="space-y-3 pt-2">
                                    <input type="text" x-model="answers[q.id]" @input="triggerAutosave()" placeholder="Ketik jawaban Anda..."
                                           class="w-full px-5 py-4 rounded-xl text-sm font-semibold border border-stone-300 bg-stone-50 text-stone-900 focus:outline-none focus:ring-2 focus:ring-amber-500 transition">
                                </div>
                            </template>

                            <!-- INPUT TYPE 6: LONG TEXT -->
                            <template x-if="q.tipe_pertanyaan === 'LONG_TEXT'">
                                <div class="space-y-3 pt-2">
                                    <textarea x-model="answers[q.id]" @input="triggerAutosave()" rows="4" placeholder="Ketik ulasan atau saran lengkap Anda di sini..."
                                              class="w-full px-5 py-4 rounded-xl text-sm font-semibold border border-stone-300 bg-stone-50 text-stone-900 focus:outline-none focus:ring-2 focus:ring-amber-500 transition"></textarea>
                                </div>
                            </template>

                            <!-- INPUT TYPE 7: DATE INPUT -->
                            <template x-if="q.tipe_pertanyaan === 'DATE' || q.tipe_pertanyaan === 'DATE_INPUT'">
                                <div class="space-y-3 pt-2">
                                    <input type="date" x-model="answers[q.id]" @change="triggerAutosave()"
                                           class="w-full px-5 py-4 rounded-xl text-sm font-bold border border-stone-300 bg-stone-50 text-stone-900 focus:outline-none focus:ring-2 focus:ring-amber-500 transition">
                                    <p class="text-[11px] text-stone-500">Pilih tanggal dari kalender.</p>
                                </div>
                            </template>

                            <!-- INPUT TYPE 8 & 9: IMAGE UPLOAD & FILE UPLOAD -->
                            <template x-if="q.tipe_pertanyaan === 'IMAGE_UPLOAD' || q.tipe_pertanyaan === 'FILE_UPLOAD'">
                                <div class="space-y-3 pt-2">
                                    <div class="p-6 rounded-2xl border-2 border-dashed border-stone-300 bg-stone-50 text-center space-y-3 hover:border-amber-500 transition cursor-pointer relative">
                                        <input type="file"
                                               :accept="q.tipe_pertanyaan === 'IMAGE_UPLOAD' ? 'image/*' : '*'"
                                               @change="handleFileChange($event, q.id, q.tipe_pertanyaan)"
                                               class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                                        <div class="w-12 h-12 rounded-xl bg-stone-200 text-stone-700 text-xl mx-auto flex items-center justify-center">
                                            <span x-text="q.tipe_pertanyaan === 'IMAGE_UPLOAD' ? '🖼️' : '📁'"></span>
                                        </div>
                                        <div class="space-y-1">
                                            <div class="text-xs font-bold text-stone-800" x-text="files[q.id] ? files[q.id].name : (q.tipe_pertanyaan === 'IMAGE_UPLOAD' ? 'Klik atau Tarik Foto Ke Sini' : 'Klik atau Tarik File Ke Sini')"></div>
                                            <div class="text-[10px] text-stone-500" x-text="files[q.id] ? (Math.round(files[q.id].size / 1024) + ' KB') : (q.tipe_pertanyaan === 'IMAGE_UPLOAD' ? 'Format gambar yang didukung: JPG, PNG, WEBP (Maks 5MB)' : 'Semua format file didukung (Maks 10MB)')"></div>
                                        </div>
                                    </div>
                                    <template x-if="files[q.id]">
                                        <div class="p-3.5 rounded-xl border border-emerald-300 bg-emerald-50 text-xs font-semibold text-emerald-900 flex items-center justify-between gap-2 shadow-2xs">
                                            <div class="flex items-center gap-2 truncate">
                                                <span class="text-emerald-600">✓ File Berhasil Dipilih:</span>
                                                <span class="truncate font-bold" x-text="files[q.id].name"></span>
                                            </div>
                                            <button type="button" @click="removeFile(q.id)" class="px-3 py-1.5 text-[11px] font-bold text-red-700 bg-red-100 hover:bg-red-200 border border-red-200 rounded-lg transition shrink-0 flex items-center gap-1.5 cursor-pointer">
                                                <span>🗑️</span>
                                                <span>Hapus File</span>
                                            </button>
                                        </div>
                                    </template>
                                </div>
                            </template>

                        </div>
                    </template>

                    <!-- Wizard Navigation Control Buttons -->
                    <div class="flex items-center justify-between border-t border-stone-200 pt-6">
                        <button type="button" @click="prevStep()"
                                class="px-5 py-3 rounded-xl border border-stone-300 font-bold text-xs text-stone-700 bg-white hover:bg-stone-50 transition">
                            ← Kembali
                        </button>

                        <template x-if="step < totalQuestions">
                            <button type="button" @click="nextStep()"
                                    class="px-6 py-3 rounded-xl font-bold text-xs text-white shadow-xs transition flex items-center gap-2"
                                    style="background:#1C1917;">
                                <span>Pertanyaan Selanjutnya</span>
                                <span class="text-amber-400">→</span>
                            </button>
                        </template>

                        <template x-if="step === totalQuestions">
                            <button type="button" @click="submitSurvey()" :disabled="submitting"
                                    class="px-7 py-3.5 rounded-xl font-bold text-xs text-stone-900 bg-amber-400 hover:bg-amber-500 shadow-xs transition flex items-center gap-2 disabled:opacity-50">
                                <span x-text="submitting ? 'Mengirim Jawaban...' : 'Kirim Seluruh Jawaban'">Kirim Seluruh Jawaban</span>
                                <span>🚀</span>
                            </button>
                        </template>
                    </div>

                </div>
            </template>

        </div>
    </template>

</div>

<script>
function studentWizardApp() {
    return {
        surveyId:           @json($survey->id),
        isClosed:           @json($isClosed),
        closedMessage:      @json($closedMessage),
        isAlreadySubmitted: @json($isAlreadySubmitted),
        limitOneResponse:   @json((bool)$survey->limit_one_response),
        nisn: '',
        step: 0,
        questions: @json($survey->questions),
        answers: {},
        files: {},
        errorMessage: '',
        submitting: false,
        isSubmitted: false,

        // Autosave (Google Form style) properties
        saveStatus: '', // '' | 'saving' | 'saved'
        lastSavedTime: '',
        restoredFromDraft: false,
        _saveTimer: null,

        init() {
            if (this.limitOneResponse) {
                const storedNisn = localStorage.getItem('survey_submitted_' + this.surveyId);
                if (storedNisn) {
                    this.isAlreadySubmitted = true;
                    return;
                }
            }

            this.loadDraft();

            // Set up reactive watchers for autosave
            this.$watch('nisn', () => this.triggerAutosave());
            this.$watch('step', () => this.triggerAutosave());
            this.$watch('answers', () => this.triggerAutosave());
        },

        loadDraft() {
            try {
                const raw = localStorage.getItem('survey_draft_' + this.surveyId);
                if (!raw) return;

                const draft = JSON.parse(raw);
                if (draft && typeof draft === 'object') {
                    if (draft.nisn) this.nisn = draft.nisn;
                    if (draft.answers && typeof draft.answers === 'object') {
                        this.answers = Object.assign({}, draft.answers);
                    }
                    if (typeof draft.step === 'number' && draft.step >= 0 && draft.step <= this.totalQuestions) {
                        this.step = draft.step;
                    }
                    if (draft.savedAt) {
                        this.lastSavedTime = draft.savedAt;
                        this.saveStatus = 'saved';
                    }

                    const hasAnswers = Object.keys(this.answers).some(k => {
                        const val = this.answers[k];
                        return val !== undefined && val !== '' && (!Array.isArray(val) || val.length > 0);
                    });
                    if (hasAnswers || (this.nisn && this.nisn.trim().length > 0)) {
                        this.restoredFromDraft = true;
                    }
                }
            } catch (e) {
                console.warn('Gagal memuat draf survey:', e);
            }
        },

        triggerAutosave() {
            if (this.isClosed || this.isAlreadySubmitted || this.isSubmitted) return;

            this.saveStatus = 'saving';
            clearTimeout(this._saveTimer);

            this._saveTimer = setTimeout(() => {
                try {
                    const now = new Date();
                    const hours = String(now.getHours()).padStart(2, '0');
                    const mins = String(now.getMinutes()).padStart(2, '0');
                    const timeStr = `${hours}:${mins}`;

                    const draft = {
                        nisn: this.nisn,
                        answers: this.answers,
                        step: this.step,
                        savedAt: timeStr
                    };

                    localStorage.setItem('survey_draft_' + this.surveyId, JSON.stringify(draft));
                    this.saveStatus = 'saved';
                    this.lastSavedTime = timeStr;
                } catch (e) {
                    console.warn('Autosave error:', e);
                }
            }, 350);
        },

        clearDraft() {
            try {
                localStorage.removeItem('survey_draft_' + this.surveyId);
            } catch (e) {}
            this.saveStatus = '';
            this.lastSavedTime = '';
            this.restoredFromDraft = false;
        },

        resetDraftConfirm() {
            if (confirm('Apakah Anda yakin ingin mengosongkan draf dan mengulang pengisian survey dari awal?')) {
                this.clearDraft();
                this.answers = {};
                this.files = {};
                this.nisn = '';
                this.step = 0;
                this.errorMessage = '';
            }
        },

        get totalQuestions() {
            return this.questions ? this.questions.length : 0;
        },

        get progressPercent() {
            if (this.step === 0) return 0;
            if (this.totalQuestions === 0) return 100;
            return Math.round((this.step / this.totalQuestions) * 100);
        },

        getOptions(raw) {
            if (!raw) return [];
            if (Array.isArray(raw)) return raw;
            try {
                const parsed = JSON.parse(raw);
                if (Array.isArray(parsed)) return parsed;
            } catch (e) {}
            return String(raw).split(',').map(s => s.trim()).filter(Boolean);
        },

        isOptionChecked(qId, val) {
            const arr = this.answers[qId] || [];
            return Array.isArray(arr) && arr.includes(val);
        },

        toggleMultipleChoice(qId, val) {
            if (!Array.isArray(this.answers[qId])) {
                this.answers[qId] = [];
            }
            const idx = this.answers[qId].indexOf(val);
            if (idx > -1) {
                this.answers[qId].splice(idx, 1);
            } else {
                this.answers[qId].push(val);
            }
            this.answers = Object.assign({}, this.answers);
            this.triggerAutosave();
        },

        handleFileChange(event, qId, type) {
            this.errorMessage = '';
            const file = event.target.files[0];
            if (!file) return;

            const maxMb = type === 'IMAGE_UPLOAD' ? 5 : 10;
            if (file.size > maxMb * 1024 * 1024) {
                const sizeMb = (file.size / (1024 * 1024)).toFixed(1);
                this.errorMessage = `Ukuran file "${file.name}" (${sizeMb} MB) melebihi batas maksimum ${maxMb} MB.`;
                event.target.value = '';
                return;
            }

            if (type === 'IMAGE_UPLOAD' && !file.type.startsWith('image/')) {
                this.errorMessage = `File "${file.name}" bukan gambar yang valid. Harap pilih file JPG, PNG, atau WEBP.`;
                event.target.value = '';
                return;
            }

            this.files[qId] = file;
            this.triggerAutosave();
        },

        removeFile(qId) {
            delete this.files[qId];
            this.files = Object.assign({}, this.files);
            this.triggerAutosave();
        },

        startSurvey() {
            this.errorMessage = '';
            if (!this.nisn.trim()) {
                this.errorMessage = 'Silakan masukkan NISN 10-digit Anda terlebih dahulu.';
                return;
            }
            if (this.totalQuestions === 0) {
                this.errorMessage = 'Survey ini belum memiliki pertanyaan.';
                return;
            }
            this.step = 1;
            this.triggerAutosave();
        },

        validateCurrentStep() {
            this.errorMessage = '';
            if (this.step === 0) return true;
            const q = this.questions[this.step - 1];
            if (q && q.wajib_diisi) {
                const ans = this.answers[q.id];
                const hasText = ans !== undefined && ans !== '' && (!Array.isArray(ans) || ans.length > 0);
                const hasFile = this.files[q.id] !== undefined;
                if (!hasText && !hasFile) {
                    this.errorMessage = `Pertanyaan "${q.teks_pertanyaan}" wajib diisi.`;
                    return false;
                }
            }
            return true;
        },

        nextStep() {
            if (this.validateCurrentStep() && this.step < this.totalQuestions) {
                this.step++;
                this.triggerAutosave();
            }
        },

        prevStep() {
            this.errorMessage = '';
            if (this.step > 0) {
                this.step--;
                this.triggerAutosave();
            }
        },

        async submitSurvey() {
            if (!this.validateCurrentStep()) return;
            this.submitting = true;
            this.errorMessage = '';

            const formData = new FormData();
            formData.append('nisn', this.nisn.trim());

            Object.keys(this.answers).forEach(qId => {
                // If a file is uploaded for this question, don't append text answer
                if (this.files[qId]) return;

                const val = this.answers[qId];
                if (Array.isArray(val)) {
                    val.forEach(item => formData.append(`answers[${qId}][]`, item));
                } else if (val !== undefined && val !== null && val !== '') {
                    formData.append(`answers[${qId}]`, val);
                }
            });

            Object.keys(this.files).forEach(qId => {
                if (this.files[qId]) {
                    formData.append(`answers[${qId}]`, this.files[qId]);
                }
            });

            try {
                const res = await fetch("{{ route('student.survey.submit', $survey->id, false) }}", {
                    method: 'POST',
                    headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: formData
                });

                let json = {};
                try {
                    json = await res.json();
                } catch (jsonErr) {}

                if (!res.ok) {
                    if (res.status === 413) {
                        this.errorMessage = 'Ukuran file gambar terlalu besar untuk diproses server. Harap hapus atau pilih file lebih kecil.';
                    } else if (json.error) {
                        this.errorMessage = json.error;
                    } else if (json.message) {
                        this.errorMessage = json.message;
                    } else if (json.errors) {
                        this.errorMessage = Object.values(json.errors).flat().join(', ');
                    } else {
                        this.errorMessage = 'Gagal mengirim jawaban. Silakan periksa kembali server/koneksi.';
                    }
                    return;
                }

                if (this.limitOneResponse) {
                    localStorage.setItem('survey_submitted_' + this.surveyId, this.nisn.trim());
                }
                this.clearDraft();
                this.isSubmitted = true;
            } catch(e) {
                this.errorMessage = 'Terjadi kesalahan jaringan. Periksa koneksi internet Anda.';
            } finally {
                this.submitting = false;
            }
        }
    };
}
</script>
</body>
</html>
