@extends('layouts.admin')
@section('title', 'Builder Survey — {{ $survey->judul }}')

@section('head')
<style>
    .question-card { transition: all 0.2s ease; }
    .question-card.newly-added { animation: popIn 0.35s cubic-bezier(0.34,1.56,0.64,1) both; }
    @keyframes popIn { 0%{transform:scale(0.92) translateY(8px);opacity:0} 70%{transform:scale(1.02)} 100%{transform:scale(1);opacity:1} }
    .card { background: white; border: 1px solid #EBE6DE; border-radius: 1rem; }
</style>
@endsection

@section('content')
<div x-data="builderApp()" class="pb-10">

    <!-- ── Sticky Save Bar ─────────────────────────────────────────────── -->
    <div class="sticky top-0 z-30 border-b shadow-xs -mx-4 sm:-mx-6 px-4 sm:px-6 py-3.5 mb-6 flex items-center justify-between gap-3 flex-wrap"
         style="background:#FFFFFF; border-color:#E5E0D8;">
        <div class="flex items-center gap-3 min-w-0">
            <a href="{{ route('admin.surveys.index') }}"
               @click.prevent="confirmBack()"
               class="p-2 rounded-xl border transition" style="background:#FAF8F5; border-color:#E5E0D8; color:#1C1917;"
               onmouseover="this.style.background='#F4F0E8';"
               onmouseout="this.style.background='#FAF8F5';"
               title="Kembali ke Daftar Survey">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <div class="min-w-0">
                <div class="text-[10px] font-sans uppercase tracking-widest font-bold" style="color:#C89D54;">SURVEY BUILDER</div>
                <div class="text-ink text-sm font-bold truncate max-w-xs sm:max-w-md" x-text="judul || 'Survey Baru (Tanpa Judul)'"></div>
            </div>
        </div>
        <div class="flex items-center gap-2 shrink-0">
            <!-- Auto Save Indicator Badge -->
            <span class="text-[10px] font-semibold px-2.5 py-1 rounded-full flex items-center gap-1.5 transition border"
                  :style="isDirty
                        ? 'background:#FFFBEB; border-color:#FCD34D; color:#92400E;'
                        : 'background:#F8F4EC; border-color:#E5E0D8; color:#6E675F;'">
                <span class="w-1.5 h-1.5 rounded-full shrink-0"
                      :class="isDirty ? 'animate-pulse' : ''"
                      :style="isDirty ? 'background:#F59E0B;' : 'background:#10B981;'"></span>
                <span x-text="autoSaveStatusText"></span>
            </span>

            <!-- Status badge -->
            <span x-text="status"
                  :style="status === 'PUBLISHED'
                        ? 'background:#EDFAF2; border:1px solid #A3D9BA; color:#1A5C38;'
                        : (status === 'DRAFT'
                            ? 'background:#FAF8F5; border:1px solid #E5E0D8; color:#6E675F;'
                            : 'background:#FDECEA; border:1px solid #F5B7B1; color:#C0392B;')"
                  class="text-[10px] font-bold px-2.5 py-1 rounded-full uppercase tracking-wider"></span>

            <!-- Google Form Accepting Responses Toggle Switch -->
            <div class="flex items-center gap-2 px-3 py-1 rounded-xl border bg-stone-50 border-stone-200">
                <span class="text-[11px] font-bold text-stone-800" x-text="acceptingResponses ? '🟢 Menerima Tanggapan' : '🔴 Tanggapan Ditutup'"></span>
                <button type="button" @click="toggleAcceptingResponses()"
                        class="relative inline-flex h-5 w-9 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none"
                        :class="acceptingResponses ? 'bg-emerald-500' : 'bg-stone-300'">
                    <span class="pointer-events-none inline-block h-4 w-4 transform rounded-full bg-white shadow-sm ring-0 transition duration-200 ease-in-out"
                          :class="acceptingResponses ? 'translate-x-4' : 'translate-x-0'"></span>
                </button>
            </div>

            <!-- Settings Drawer Button -->
            <button type="button" @click="showSettingsModal = true"
                    class="px-3 py-2 rounded-xl border border-stone-300 bg-white text-stone-800 font-bold text-xs hover:bg-stone-50 transition flex items-center gap-1.5 shadow-2xs">
                <span>⚙️ Opsi Survey</span>
            </button>

            <button @click="saveSurvey('DRAFT')" :disabled="saving"
                    class="px-4 py-2 text-xs font-bold rounded-xl border transition disabled:opacity-50 flex items-center gap-1.5"
                    style="background:#FAF8F5; border-color:#E5E0D8; color:#1C1917;"
                    onmouseover="this.style.background='#F4F0E8';"
                    onmouseout="this.style.background='#FAF8F5';">
                <svg class="w-3.5 h-3.5" style="color:#C89D54;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
                <span x-text="saving ? 'Menyimpan...' : 'Simpan Draft'"></span>
            </button>
            <button @click="saveSurvey('PUBLISHED')" :disabled="saving"
                    class="px-4 py-2 text-xs font-bold rounded-xl transition disabled:opacity-50 shadow-xs flex items-center gap-1.5"
                    style="background:#1C1917; color:#FFFFFF;"
                    onmouseover="this.style.background='#2C2723';"
                    onmouseout="this.style.background='#1C1917';">
                <svg class="w-3.5 h-3.5" style="color:#C89D54;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span>Save</span>
            </button>
        </div>
    </div>

    <!-- ── Google Form Options Modal / Drawer ─────────────────────────── -->
    <div x-show="showSettingsModal" x-cloak x-transition class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/25">
        <div class="bg-white rounded-3xl border border-stone-300 max-w-lg w-full p-6 sm:p-8 space-y-6 shadow-xl">
            <div class="flex items-center justify-between border-b pb-4 border-stone-200">
                <div class="flex items-center gap-2">
                    <span class="text-xl">⚙️</span>
                    <h3 class="font-serif font-bold text-lg text-stone-900">Pengaturan Opsi Survey (Google Form Style)</h3>
                </div>
                <button type="button" @click="showSettingsModal = false" class="text-xs font-bold text-stone-400 hover:text-stone-800">✕ Tutup</button>
            </div>

            <div class="space-y-5 text-xs text-stone-800">

                <!-- 1. Menerima Tanggapan Toggle -->
                <div class="flex items-center justify-between p-4 rounded-2xl border border-stone-200 bg-stone-50/80">
                    <div class="space-y-0.5">
                        <div class="font-bold text-stone-900">Status Menerima Tanggapan</div>
                        <div class="text-[11px] text-stone-500">Buka atau tutup pengisian survey secara manual kapan saja.</div>
                    </div>
                    <button type="button" @click="acceptingResponses = !acceptingResponses; markDirty();"
                            class="relative inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none"
                            :class="acceptingResponses ? 'bg-emerald-500' : 'bg-stone-300'">
                        <span class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow-sm ring-0 transition duration-200 ease-in-out"
                              :class="acceptingResponses ? 'translate-x-5' : 'translate-x-0'"></span>
                    </button>
                </div>

                <!-- 2. Limit 1 Response Toggle -->
                <div class="flex items-center justify-between p-4 rounded-2xl border border-stone-200 bg-stone-50/80">
                    <div class="space-y-0.5">
                        <div class="font-bold text-stone-900">Batasi 1x Tanggapan Per Responden</div>
                        <div class="text-[11px] text-stone-500">Jika diaktifkan, setiap NISN & perangkat browser hanya boleh mengisi 1 kali.</div>
                    </div>
                    <button type="button" @click="limitOneResponse = !limitOneResponse; markDirty();"
                            class="relative inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none"
                            :class="limitOneResponse ? 'bg-amber-500' : 'bg-stone-300'">
                        <span class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow-sm ring-0 transition duration-200 ease-in-out"
                              :class="limitOneResponse ? 'translate-x-5' : 'translate-x-0'"></span>
                    </button>
                </div>

                <!-- 3. Scheduled Auto Close (GMT+7 / WIB) -->
                <div class="space-y-2 p-4 rounded-2xl border border-stone-200 bg-stone-50/80">
                    <label class="font-bold text-stone-900 block">Jadwal Penutupan Otomatis (GMT+7 / WIB)</label>
                    <p class="text-[11px] text-stone-500">Pilih tanggal dan waktu di mana survey ini akan ditutup secara otomatis.</p>
                    <input type="datetime-local" x-model="closedAt" @change="markDirty()"
                           class="w-full px-4 py-3 rounded-xl border border-stone-300 bg-white font-mono text-xs text-stone-900 focus:outline-none focus:ring-2 focus:ring-amber-500">
                </div>

                <!-- 4. Custom Closed Message -->
                <div class="space-y-2 p-4 rounded-2xl border border-stone-200 bg-stone-50/80">
                    <label class="font-bold text-stone-900 block">Pesan Penutupan Kustom</label>
                    <p class="text-[11px] text-stone-500">Pesan yang ditampilkan kepada responden jika survey sudah ditutup.</p>
                    <textarea x-model="customClosedMessage" @input="markDirty()" rows="3"
                              placeholder="Contoh: Survey ini telah ditutup. Terima kasih atas partisipasi Anda!"
                              class="w-full px-4 py-3 rounded-xl border border-stone-300 bg-white text-xs text-stone-900 focus:outline-none focus:ring-2 focus:ring-amber-500"></textarea>
                </div>

            </div>

            <div class="pt-2 flex justify-end">
                <button type="button" @click="saveSurvey(status); showSettingsModal = false;"
                        class="px-5 py-2.5 rounded-xl bg-stone-900 text-white font-bold text-xs shadow-xs hover:bg-stone-800 transition">
                    Simpan Pengaturan
                </button>
            </div>
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
         class="fixed bottom-6 right-6 z-50 text-white px-5 py-3 rounded-xl shadow-lg font-semibold text-sm flex items-center gap-2"
         style="background:#1C1917; display:none;" x-text="toastMessage">
    </div>

    <!-- ── Exit / Save Draft Modal ──────────────────────────────────────── -->
    <div x-show="showExitModal"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="fixed inset-0 top-0 left-0 w-screen h-screen bg-black/25 z-[100] flex items-center justify-center p-4"
         style="display:none;">

        <div @click.away="showExitModal = false"
             class="bg-white rounded-2xl border shadow-xl max-w-md w-full p-6 space-y-5"
             style="background:#FFFFFF; border-color:#E5E0D8;">

            <div class="flex items-center gap-3 border-b pb-4" style="border-color:#EBE6DE;">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0" style="background:#FFFBEB; border:1px solid #FCD34D;">
                    <svg class="w-5 h-5" style="color:#D97706;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="font-bold text-base text-ink">Simpan Perubahan Draft?</h3>
                    <p class="text-xs text-ink-subtle mt-0.5" style="color:#6E675F;">Anda akan keluar dari Builder Survey.</p>
                </div>
            </div>

            <p class="text-xs leading-relaxed" style="color:#6E675F;">
                Pilih apakah Anda ingin <strong>Menyimpan sebagai Draft</strong> agar dapat dilanjutkan nanti, atau <strong>Membuang & Menghapus</strong> survey ini dari daftar.
            </p>

            <!-- Modal Action Buttons -->
            <div class="space-y-2 pt-2">
                <!-- Save Draft & Exit -->
                <button @click="saveAndExit()" :disabled="saving"
                        class="w-full py-3 px-4 text-xs font-bold rounded-xl text-white transition flex items-center justify-center gap-2"
                        style="background:#1C1917;"
                        onmouseover="this.style.background='#2C2723';"
                        onmouseout="this.style.background='#1C1917';">
                    <svg class="w-4 h-4" style="color:#C89D54;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
                    <span>Simpan Draft & Keluar</span>
                </button>

                <!-- Discard & Delete Draft -->
                <button @click="discardAndExit()" :disabled="saving"
                        class="w-full py-3 px-4 text-xs font-bold rounded-xl border transition flex items-center justify-center gap-2"
                        style="background:#FAF8F5; border-color:#F5B7B1; color:#C0392B;"
                        onmouseover="this.style.background='#FDECEA';"
                        onmouseout="this.style.background='#FAF8F5';">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    <span>Jangan Simpan (Buang & Hapus Survey)</span>
                </button>

                <!-- Cancel / Continue Editing -->
                <button @click="showExitModal = false"
                        class="w-full py-2 px-4 text-xs font-semibold text-center transition"
                        style="color:#A19A91;">
                    Batal (Lanjutkan Mengedit)
                </button>
            </div>
        </div>
    </div>

    <!-- ── 2-Panel Layout ─────────────────────────────────────────────── -->
    <div class="grid grid-cols-1 xl:grid-cols-12 gap-6">

        <!-- LEFT: Editor Panel ──────────────────────────────────────── -->
        <div class="xl:col-span-7 space-y-5">

            <!-- Survey Metadata -->
            <div class="card p-5 sm:p-6 space-y-4">
                <h2 class="font-bold text-lg text-ink border-b pb-3" style="border-color:#EBE6DE;">Informasi Survey</h2>

                <div class="space-y-3">
                    <div>
                        <label class="block text-[10px] font-sans uppercase tracking-widest font-bold mb-1" style="color:#6E675F;">Judul Survey *</label>
                        <input type="text" x-model="judul" @input="markDirty()" placeholder="Misal: Riset Pasar Produk Kewirausahaan Siswa"
                               class="w-full px-4 py-3 border rounded-xl text-sm font-semibold text-ink transition focus:outline-none focus:ring-2"
                               style="background:#FAF8F5; border-color:#E5E0D8;">
                    </div>
                    <div>
                        <label class="block text-[10px] font-sans uppercase tracking-widest font-bold mb-1" style="color:#6E675F;">Deskripsi / Konteks</label>
                        <textarea x-model="deskripsi" @input="markDirty()" rows="2" placeholder="Jelaskan tujuan survey ini kepada responden..."
                                  class="w-full px-4 py-3 border rounded-xl text-sm text-ink resize-none transition focus:outline-none focus:ring-2"
                                  style="background:#FAF8F5; border-color:#E5E0D8;"></textarea>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-[10px] font-sans uppercase tracking-widest font-bold mb-1" style="color:#6E675F;">Tanggal Mulai</label>
                            <input type="date" x-model="tanggalMulai" @change="markDirty()"
                                   class="w-full px-4 py-2.5 border rounded-xl text-sm text-ink focus:outline-none focus:ring-2"
                                   style="background:#FAF8F5; border-color:#E5E0D8;">
                        </div>
                        <div>
                            <label class="block text-[10px] font-sans uppercase tracking-widest font-bold mb-1" style="color:#6E675F;">Tanggal Selesai</label>
                            <input type="date" x-model="tanggalSelesai" @change="markDirty()"
                                   class="w-full px-4 py-2.5 border rounded-xl text-sm text-ink focus:outline-none focus:ring-2"
                                   style="background:#FAF8F5; border-color:#E5E0D8;">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Questions List -->
            <div class="space-y-4">
                <template x-for="(q, idx) in questions" :key="q.id || ('temp_' + idx)">
                    <div class="card question-card transition-all duration-200"
                         draggable="true"
                         @dragstart="onDragStart(idx, $event)"
                         @dragover="onDragOver(idx, $event)"
                         @drop="onDrop(idx, $event)"
                         @dragend="onDragEnd()"
                         :class="{
                             'newly-added ring-2 ring-amber-500': newlyAddedIdx === idx,
                             'opacity-40 border-dashed border-amber-500 scale-[0.99]': draggedIdx === idx
                         }">

                        <!-- Question Card Header -->
                        <div class="flex items-center gap-3 p-4 border-b" style="border-color:#EBE6DE;">
                            <!-- Drag Handle -->
                            <div class="cursor-grab active:cursor-grabbing p-1 rounded hover:bg-surface-subtle transition shrink-0 flex items-center justify-center"
                                 title="Geser (drag & drop) untuk memindahkan urutan">
                                <svg class="w-4 h-4" style="color:#A19A91;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"/>
                                </svg>
                            </div>

                            <span class="w-8 h-8 rounded-lg font-bold text-xs flex items-center justify-center shrink-0"
                                  style="background:#1C1917; color:#C89D54;"
                                  x-text="idx + 1"></span>

                            <!-- Type Select (No Emojis / No Icons) -->
                            <select x-model="q.tipe_pertanyaan" @change="changeType(q, $event.target.value)"
                                    class="flex-1 text-xs font-bold border rounded-lg px-3 py-2 transition focus:outline-none focus:ring-2"
                                    style="background:#FAF8F5; border-color:#E5E0D8; color:#1C1917;">
                                <option value="SINGLE_CHOICE">Pilihan Tunggal (Single Choice)</option>
                                <option value="MULTIPLE_CHOICE">Pilihan Ganda (Multiple Choice)</option>
                                <option value="LIKERT">Skala Likert 1–5</option>
                                <option value="SHORT_TEXT">Teks Singkat</option>
                                <option value="LONG_TEXT">Teks Panjang (Esai)</option>
                                <option value="NUMBER">Nominal Angka (Harga)</option>
                                <option value="IMAGE_UPLOAD">Unggah Gambar / Foto</option>
                                <option value="DATE">Tanggal</option>
                            </select>

                            <!-- Move Controls -->
                            <div class="flex gap-1">
                                <button @click="moveUp(idx)" :disabled="idx === 0"
                                        class="p-1.5 rounded-lg border transition disabled:opacity-30"
                                        style="background:#FAF8F5; border-color:#E5E0D8; color:#6E675F;">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/></svg>
                                </button>
                                <button @click="moveDown(idx)" :disabled="idx === questions.length - 1"
                                        class="p-1.5 rounded-lg border transition disabled:opacity-30"
                                        style="background:#FAF8F5; border-color:#E5E0D8; color:#6E675F;">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                </button>
                                <button @click="removeQuestion(idx)"
                                        class="p-1.5 rounded-lg transition" style="color:#C0392B;">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            </div>
                        </div>

                        <!-- Question Body -->
                        <div class="p-4 space-y-3">
                            <!-- Question Text & Required Checkbox -->
                            <div class="flex items-start gap-2">
                                <textarea x-model="q.teks_pertanyaan" @input="markDirty()" rows="2"
                                          :placeholder="'Teks Pertanyaan #' + (idx + 1) + '...'"
                                          class="flex-1 px-3 py-2.5 border rounded-xl text-sm text-ink resize-none transition focus:outline-none focus:ring-2"
                                          style="background:#FAF8F5; border-color:#E5E0D8;"></textarea>
                                <label class="flex items-center gap-1.5 mt-2 shrink-0 cursor-pointer">
                                    <input type="checkbox" x-model="q.wajib_diisi" @change="markDirty()"
                                           class="w-4 h-4 rounded border-gray-300 cursor-pointer" style="accent-color:#C89D54;">
                                    <span class="text-[10px] font-bold text-ink-muted whitespace-nowrap">Wajib</span>
                                </label>
                            </div>

                            <!-- Question Description / Helper Text -->
                            <div>
                                <label class="block text-[10px] font-sans uppercase tracking-widest font-bold mb-1" style="color:#6E675F;">
                                    Deskripsi / Penjelasan Tambahan Pertanyaan (Opsional)
                                </label>
                                <input type="text" x-model="q.deskripsi_pertanyaan" @input="markDirty()"
                                       placeholder="Misal: Berikan masukan yang jujur mengenai rasa dan kemasan produk..."
                                       class="w-full px-3 py-2 border rounded-lg text-xs text-ink transition focus:outline-none focus:ring-2"
                                       style="background:#FAF8F5; border-color:#E5E0D8;">
                            </div>

                            <!-- Options for SINGLE/MULTIPLE_CHOICE -->
                            <template x-if="q.tipe_pertanyaan === 'SINGLE_CHOICE' || q.tipe_pertanyaan === 'MULTIPLE_CHOICE'">
                                <div class="space-y-2 pt-1">
                                    <div class="text-[10px] font-sans uppercase tracking-widest font-bold" style="color:#6E675F;">OPSI JAWABAN</div>
                                    <template x-for="(opt, optIdx) in q.opsi_jawaban" :key="optIdx">
                                        <div class="flex items-center gap-2">
                                            <span class="text-xs font-bold w-5 text-center" style="color:#6E675F;" x-text="optIdx + 1"></span>
                                            <input type="text" x-model="q.opsi_jawaban[optIdx]" @input="markDirty()"
                                                   :placeholder="'Opsi ' + (optIdx + 1)"
                                                   class="flex-1 px-3 py-2 border rounded-lg text-xs transition focus:outline-none focus:ring-2"
                                                   style="background:#FAF8F5; border-color:#E5E0D8; color:#1C1917;">
                                            <button @click="q.opsi_jawaban.splice(optIdx, 1); markDirty();"
                                                    x-show="q.opsi_jawaban.length > 2"
                                                    class="p-1.5 rounded-lg transition" style="color:#C0392B;">
                                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                            </button>
                                        </div>
                                    </template>
                                    <button @click="q.opsi_jawaban.push('Opsi ' + (q.opsi_jawaban.length + 1)); markDirty();"
                                            class="flex items-center gap-1.5 text-xs font-bold transition mt-1"
                                            style="color:#C89D54;">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                        Tambah Opsi
                                    </button>
                                </div>
                            </template>

                            <!-- LIKERT Info -->
                            <template x-if="q.tipe_pertanyaan === 'LIKERT'">
                                <div class="text-xs border rounded-lg px-3 py-2 flex items-center gap-2"
                                     style="background:#FAF8F5; border-color:#E5E0D8; color:#6E675F;">
                                    <svg class="w-4 h-4 shrink-0" style="color:#C89D54;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                                    <span>Skala 1 (Sangat Tidak Setuju) hingga 5 (Sangat Setuju) — otomatis disediakan.</span>
                                </div>
                            </template>

                            <!-- NUMBER Info -->
                            <template x-if="q.tipe_pertanyaan === 'NUMBER'">
                                <div class="text-xs border rounded-lg px-3 py-2 flex items-center gap-2"
                                     style="background:#FAF8F5; border-color:#E5E0D8; color:#6E675F;">
                                    <span class="font-bold text-xs" style="color:#C89D54;">Rp</span>
                                    <span>Siswa akan mengisi nilai nominal angka (Rp) — cocok untuk estimasi harga (WTP).</span>
                                </div>
                            </template>

                            <!-- IMAGE_UPLOAD Info -->
                            <template x-if="q.tipe_pertanyaan === 'IMAGE_UPLOAD'">
                                <div class="text-xs border rounded-lg px-3 py-2 flex items-center gap-2"
                                     style="background:#FAF8F5; border-color:#E5E0D8; color:#6E675F;">
                                    <svg class="w-4 h-4 shrink-0" style="color:#C89D54;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    <span>Siswa akan mengunggah berkas foto/gambar (JPG/PNG, maks. 5MB).</span>
                                </div>
                            </template>

                            <!-- DATE Info -->
                            <template x-if="q.tipe_pertanyaan === 'DATE'">
                                <div class="text-xs border rounded-lg px-3 py-2 flex items-center gap-2"
                                     style="background:#FAF8F5; border-color:#E5E0D8; color:#6E675F;">
                                    <svg class="w-4 h-4 shrink-0" style="color:#C89D54;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    <span>Siswa akan memilih tanggal dari kalender.</span>
                                </div>
                            </template>
                        </div>
                    </div>
                </template>

                <!-- Add Question Button -->
                <button @click="addQuestion()"
                        class="w-full py-4 border-2 border-dashed rounded-2xl text-xs font-bold transition-all flex items-center justify-center gap-2"
                        style="background:#FAF8F5; border-color:#E5E0D8; color:#1C1917;"
                        onmouseover="this.style.background='#F4F0E8';this.style.borderColor='#C89D54';"
                        onmouseout="this.style.background='#FAF8F5';this.style.borderColor='#E5E0D8';">
                    <svg class="w-5 h-5" style="color:#C89D54;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    <span>Tambah Pertanyaan Baru</span>
                </button>
            </div>
        </div>

        <!-- RIGHT: Live Preview Panel (High Contrast Professional Card) ────── -->
        <div class="xl:col-span-5">
            <div class="sticky top-24">
                <div class="card p-5 space-y-4 shadow-sm" style="background:#FFFFFF; border-color:#E5E0D8;">
                    <div class="flex items-center justify-between pb-3 border-b" style="border-color:#EBE6DE;">
                        <div class="text-[10px] font-sans uppercase tracking-widest font-bold" style="color:#C89D54;">LIVE PREVIEW — TAMPILAN SISWA</div>
                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-full border" style="background:#FAF8F5; border-color:#E5E0D8; color:#1C1917;">Interactive</span>
                    </div>

                    <!-- Preview empty -->
                    <template x-if="questions.length === 0">
                        <div class="text-center py-12" style="color:#A19A91;">
                            <div class="text-4xl mb-2">📋</div>
                            <p class="text-xs font-medium">Tambah pertanyaan untuk melihat preview siswa.</p>
                        </div>
                    </template>

                    <!-- Preview wizard -->
                    <template x-if="questions.length > 0">
                        <div class="space-y-4">
                            <!-- Progress Bar -->
                            <div class="rounded-full h-2 overflow-hidden" style="background:#F4F0E8;">
                                <div class="h-full rounded-full transition-all duration-300"
                                     style="background:#C89D54;"
                                     :style="'width:' + (previewStep === 0 ? 0 : Math.round((previewStep / questions.length) * 100)) + '%'"></div>
                            </div>

                            <!-- Step 0: NISN -->
                            <template x-if="previewStep === 0">
                                <div class="rounded-xl border p-4 space-y-3" style="background:#FAF8F5; border-color:#E5E0D8;">
                                    <div class="text-[10px] font-sans uppercase tracking-widest font-bold" style="color:#C89D54;">LANGKAH AWAL</div>
                                    <p class="text-sm font-bold text-ink">Identitas Responden Siswa</p>
                                    <div class="rounded-lg px-3 py-2.5 text-xs border" style="background:#FFFFFF; border-color:#E5E0D8; color:#A19A91;">Masukkan 10-digit NISN...</div>
                                </div>
                            </template>

                            <!-- Step 1..N Question Preview -->
                            <template x-if="previewStep > 0 && previewStep <= questions.length">
                                <div class="rounded-xl border p-4 space-y-3" style="background:#FAF8F5; border-color:#E5E0D8;">
                                    <div class="flex items-center justify-between">
                                        <span class="text-[10px] font-bold px-2.5 py-0.5 rounded border"
                                              style="background:#F8F4EC; border-color:#E5E0D8; color:#1C1917;"
                                              x-text="questions[previewStep-1].tipe_pertanyaan"></span>
                                        <span class="text-xs font-bold tabular-nums" style="color:#6E675F;"
                                              x-text="String(previewStep).padStart(2,'0') + ' / ' + String(questions.length).padStart(2,'0')"></span>
                                    </div>

                                     <div class="space-y-1">
                                         <div class="flex items-center gap-2 flex-wrap">
                                             <p class="text-sm font-bold leading-snug text-ink"
                                                x-text="questions[previewStep-1].teks_pertanyaan || '(Teks pertanyaan belum diisi)'"></p>
                                             <span x-show="questions[previewStep-1].wajib_diisi"
                                                   class="text-[10px] font-bold px-2 py-0.5 rounded-full border uppercase tracking-wider shrink-0"
                                                   style="background:#FDECEA; border-color:#F5B7B1; color:#C0392B;">* Wajib Diisi</span>
                                         </div>
                                         <p x-show="questions[previewStep-1].deskripsi_pertanyaan"
                                            class="text-xs leading-relaxed" style="color:#6E675F;"
                                            x-text="questions[previewStep-1].deskripsi_pertanyaan"></p>
                                     </div>

                                    <!-- SINGLE_CHOICE -->
                                    <template x-if="questions[previewStep-1].tipe_pertanyaan === 'SINGLE_CHOICE'">
                                        <div class="space-y-2">
                                            <template x-for="opt in questions[previewStep-1].opsi_jawaban" :key="opt">
                                                <div class="flex items-center gap-2 p-2.5 rounded-lg border text-xs font-medium cursor-pointer transition"
                                                     style="background:#FFFFFF; border-color:#E5E0D8; color:#1C1917;" x-text="opt"></div>
                                            </template>
                                        </div>
                                    </template>

                                    <!-- MULTIPLE_CHOICE -->
                                    <template x-if="questions[previewStep-1].tipe_pertanyaan === 'MULTIPLE_CHOICE'">
                                        <div class="space-y-2">
                                            <template x-for="opt in questions[previewStep-1].opsi_jawaban" :key="opt">
                                                <div class="flex items-center gap-2.5 p-2.5 rounded-lg border text-xs font-medium cursor-pointer transition"
                                                     style="background:#FFFFFF; border-color:#E5E0D8; color:#1C1917;">
                                                    <div class="w-4 h-4 rounded border shrink-0" style="border-color:#C89D54;"></div>
                                                    <span x-text="opt"></span>
                                                </div>
                                            </template>
                                        </div>
                                    </template>

                                    <!-- LIKERT -->
                                    <template x-if="questions[previewStep-1].tipe_pertanyaan === 'LIKERT'">
                                        <div class="grid grid-cols-5 gap-1.5">
                                            <template x-for="n in ['1','2','3','4','5']" :key="n">
                                                <div class="aspect-square flex flex-col items-center justify-center rounded-lg border text-xs font-bold cursor-pointer transition"
                                                     style="background:#FFFFFF; border-color:#E5E0D8; color:#1C1917;" x-text="n"></div>
                                            </template>
                                        </div>
                                    </template>

                                    <!-- SHORT_TEXT -->
                                    <template x-if="questions[previewStep-1].tipe_pertanyaan === 'SHORT_TEXT'">
                                        <div class="rounded-lg px-3 py-2.5 text-xs border" style="background:#FFFFFF; border-color:#E5E0D8; color:#A19A91;">Ketik jawaban singkat...</div>
                                    </template>

                                    <!-- LONG_TEXT -->
                                    <template x-if="questions[previewStep-1].tipe_pertanyaan === 'LONG_TEXT'">
                                        <div class="rounded-lg px-3 py-5 text-xs border" style="background:#FFFFFF; border-color:#E5E0D8; color:#A19A91;">Ketik masukan / penjelasan...</div>
                                    </template>

                                    <!-- NUMBER -->
                                    <template x-if="questions[previewStep-1].tipe_pertanyaan === 'NUMBER'">
                                        <div class="rounded-lg px-3 py-2.5 text-xs border flex items-center gap-2" style="background:#FFFFFF; border-color:#E5E0D8; color:#A19A91;">
                                            <span class="font-bold" style="color:#C89D54;">Rp</span> <span>Nominal angka harga...</span>
                                        </div>
                                    </template>

                                    <!-- IMAGE_UPLOAD -->
                                    <template x-if="questions[previewStep-1].tipe_pertanyaan === 'IMAGE_UPLOAD'">
                                        <div class="border-2 border-dashed rounded-lg p-4 text-center text-xs font-medium" style="border-color:#E5E0D8; color:#6E675F;">
                                            Pilih Berkas Foto / Gambar...
                                        </div>
                                    </template>

                                    <!-- DATE -->
                                    <template x-if="questions[previewStep-1].tipe_pertanyaan === 'DATE'">
                                        <div class="rounded-lg px-3 py-2.5 text-xs border" style="background:#FFFFFF; border-color:#E5E0D8; color:#A19A91;">Pilih tanggal...</div>
                                    </template>
                                </div>
                            </template>

                            <!-- Preview Nav -->
                            <div class="flex justify-between gap-2 pt-1">
                                <button @click="previewStep = Math.max(0, previewStep - 1)"
                                        class="px-4 py-2 border rounded-xl text-xs font-bold transition"
                                        style="background:#FAF8F5; border-color:#E5E0D8; color:#1C1917;">
                                    ← Kembali
                                </button>
                                <span class="text-xs font-semibold self-center tabular-nums" style="color:#6E675F;" x-text="previewStep + ' / ' + questions.length"></span>
                                <button @click="previewStep = Math.min(questions.length, previewStep + 1)"
                                        class="px-4 py-2 text-xs font-bold rounded-xl transition shadow-xs"
                                        style="background:#1C1917; color:#FFFFFF;">
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
        judul:                @json($survey->judul),
        deskripsi:            @json($survey->deskripsi ?? ''),
        status:               @json($survey->status),
        acceptingResponses:   @json((bool)$survey->accepting_responses),
        closedAt:             @json($survey->closed_at?->format('Y-m-d\TH:i') ?? ''),
        customClosedMessage:  @json($survey->custom_closed_message ?? ''),
        limitOneResponse:     @json((bool)$survey->limit_one_response),
        tanggalMulai:         @json($survey->tanggal_mulai?->format('Y-m-d') ?? ''),
        tanggalSelesai:       @json($survey->tanggal_selesai?->format('Y-m-d') ?? ''),
        questions:            @json($formattedQuestions),
        previewStep: 0,
        saving: false,
        toastMessage: '',
        newlyAddedIdx: null,
        draggedIdx: null,

        // Settings Modal & Exit modal & Auto Save state
        showSettingsModal: false,
        showExitModal: false,
        isDirty: false,
        autoSaving: false,
        autoSaveStatusText: 'Semua perubahan tersimpan',
        _autoSaveTimer: null,

        init() {
            // Alert user before tab close or browser refresh if unsaved changes exist
            window.addEventListener('beforeunload', (e) => {
                if (this.isDirty && !this.saving && !this.autoSaving) {
                    e.preventDefault();
                    e.returnValue = 'Anda memiliki perubahan survey yang belum disimpan. Yakin ingin keluar?';
                    return e.returnValue;
                }
            });

            // Watch fields for changes
            this.$watch('judul', () => this.markDirty());
            this.$watch('deskripsi', () => this.markDirty());
            this.$watch('tanggalMulai', () => this.markDirty());
            this.$watch('tanggalSelesai', () => this.markDirty());
            this.$watch('questions', () => this.markDirty(), { deep: true });
        },

        markDirty(immediate = false) {
            this.isDirty = true;
            this.autoSaveStatusText = 'Menunggu menyimpan...';
            clearTimeout(this._autoSaveTimer);

            if (immediate) {
                this.triggerAutoSave();
            } else {
                this._autoSaveTimer = setTimeout(() => {
                    this.triggerAutoSave();
                }, 800);
            }
        },

        async toggleAcceptingResponses() {
            this.acceptingResponses = !this.acceptingResponses;
            this.markDirty(true);
            try {
                const res = await fetch("{{ route('admin.surveys.toggle-acceptance', $survey->id, false) }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });
                const json = await res.json();
                if (json.success) {
                    this.showToast(json.message);
                }
            } catch (e) {
                console.error('Toggle error:', e);
            }
        },

        async triggerAutoSave() {
            if (this.saving || this.autoSaving) return;
            this.autoSaving = true;
            this.autoSaveStatusText = 'Menyimpan otomatis...';

            const payload = {
                judul:                 this.judul || 'Survey Tanpa Judul',
                deskripsi:             this.deskripsi,
                status:                this.status,
                accepting_responses:   this.acceptingResponses,
                closed_at:             this.closedAt ? this.closedAt : null,
                custom_closed_message: this.customClosedMessage ? this.customClosedMessage : null,
                limit_one_response:    this.limitOneResponse,
                tanggal_mulai:         this.tanggalMulai ? this.tanggalMulai : null,
                tanggal_selesai:       this.tanggalSelesai ? this.tanggalSelesai : null,
                questions:             this.questions,
            };

            try {
                const res = await fetch("{{ route('admin.surveys.update', $survey->id, false) }}", {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify(payload)
                });

                const json = await res.json().catch(() => ({}));

                if (res.ok && json.success) {
                    this.isDirty = false;
                    if (json.questions && Array.isArray(json.questions)) {
                        json.questions.forEach((savedQ, i) => {
                            if (this.questions[i]) {
                                this.questions[i].id = savedQ.id;
                            }
                        });
                    }
                    const nowStr = new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
                    this.autoSaveStatusText = 'Tersimpan otomatis (' + nowStr + ')';
                } else {
                    console.warn('Auto-save response error:', json);
                    this.autoSaveStatusText = 'Gagal menyimpan: ' + (json.message || 'Coba lagi');
                }
            } catch (e) {
                console.error('Auto-save connection error:', e);
                this.autoSaveStatusText = 'Gagal koneksi auto-save';
            } finally {
                this.autoSaving = false;
            }
        },

        confirmBack() {
            if (this.isDirty || (!this.judul && this.status === 'DRAFT')) {
                this.showExitModal = true;
            } else {
                window.location.href = "{{ route('admin.surveys.index', [], false) }}";
            }
        },

        async saveAndExit() {
            this.showExitModal = false;
            await this.saveSurvey('DRAFT');
            this.isDirty = false;
            window.location.href = "{{ route('admin.surveys.index', [], false) }}";
        },

        async discardAndExit() {
            this.showExitModal = false;
            this.isDirty = false; // Disable beforeunload trigger
            try {
                await fetch("{{ route('admin.surveys.destroy', $survey->id, false) }}", {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });
            } catch (e) {
                console.error('Error discarding draft:', e);
            }
            window.location.href = "{{ route('admin.surveys.index', [], false) }}";
        },

        showToast(msg) {
            this.toastMessage = msg;
            clearTimeout(this._toastTimer);
            this._toastTimer = setTimeout(() => this.toastMessage = '', 2500);
        },

        onDragStart(idx, e) {
            this.draggedIdx = idx;
            if (e.dataTransfer) {
                e.dataTransfer.effectAllowed = 'move';
                e.dataTransfer.setData('text/plain', idx);
            }
        },

        onDragOver(idx, e) {
            e.preventDefault();
            if (e.dataTransfer) {
                e.dataTransfer.dropEffect = 'move';
            }
        },

        onDrop(targetIdx, e) {
            e.preventDefault();
            if (this.draggedIdx === null || this.draggedIdx === targetIdx) return;
            const movedItem = this.questions.splice(this.draggedIdx, 1)[0];
            this.questions.splice(targetIdx, 0, movedItem);
            this.questions = [...this.questions];
            this.showToast('Pertanyaan #' + (this.draggedIdx + 1) + ' dipindah ke posisi #' + (targetIdx + 1));
            this.draggedIdx = null;
            this.markDirty(true);
        },

        onDragEnd() {
            this.draggedIdx = null;
        },

        getTypeBadgeClass(type) {
            const styles = {
                'SINGLE_CHOICE':   'bg-blue-500/20 text-blue-300 border border-blue-400/30 font-bold',
                'MULTIPLE_CHOICE': 'bg-purple-500/20 text-purple-300 border border-purple-400/30 font-bold',
                'LIKERT':          'bg-amber-500/20 text-amber-300 border border-amber-400/30 font-bold',
                'NUMBER':          'bg-emerald-500/20 text-emerald-300 border border-emerald-400/30 font-bold',
                'SHORT_TEXT':      'bg-sky-500/20 text-sky-300 border border-sky-400/30 font-bold',
                'LONG_TEXT':       'bg-teal-500/20 text-teal-300 border border-teal-400/30 font-bold',
                'IMAGE_UPLOAD':    'bg-rose-500/20 text-rose-300 border border-rose-400/30 font-bold',
                'DATE':            'bg-indigo-500/20 text-indigo-300 border border-indigo-400/30 font-bold'
            };
            return styles[type] || 'bg-white/10 text-white/80 border border-white/20 font-bold';
        },

        addQuestion() {
            this.questions.push({
                id: null,
                tipe_pertanyaan: 'SINGLE_CHOICE',
                teks_pertanyaan: '',
                deskripsi_pertanyaan: '',
                opsi_jawaban: ['Opsi 1', 'Opsi 2', 'Opsi 3'],
                wajib_diisi: true,
                urutan: this.questions.length + 1
            });
            this.questions = [...this.questions];
            this.newlyAddedIdx = this.questions.length - 1;
            this.showToast('Pertanyaan #' + this.questions.length + ' ditambahkan');
            setTimeout(() => this.newlyAddedIdx = null, 1500);
            this.markDirty(true);
        },

        removeQuestion(idx) {
            this.questions.splice(idx, 1);
            this.questions = [...this.questions];
            this.showToast('Pertanyaan berhasil dihapus');
            this.markDirty(true);
        },

        moveUp(idx) {
            if (idx === 0) return;
            [this.questions[idx - 1], this.questions[idx]] = [this.questions[idx], this.questions[idx - 1]];
            this.questions = [...this.questions];
            this.showToast('Pertanyaan dipindah ke atas');
            this.markDirty(true);
        },

        moveDown(idx) {
            if (idx === this.questions.length - 1) return;
            [this.questions[idx + 1], this.questions[idx]] = [this.questions[idx], this.questions[idx + 1]];
            this.questions = [...this.questions];
            this.showToast('Pertanyaan dipindah ke bawah');
            this.markDirty(true);
        },

        changeType(q, newType) {
            q.tipe_pertanyaan = newType;
            if ((newType === 'SINGLE_CHOICE' || newType === 'MULTIPLE_CHOICE') && (!q.opsi_jawaban || q.opsi_jawaban.length === 0)) {
                q.opsi_jawaban = ['Opsi 1', 'Opsi 2', 'Opsi 3'];
            }
            this.markDirty(true);
        },

        async saveSurvey(targetStatus) {
            this.saving = true;
            clearTimeout(this._autoSaveTimer);
            const payload = {
                judul:                 this.judul || 'Survey Tanpa Judul',
                deskripsi:             this.deskripsi,
                status:                targetStatus || this.status,
                accepting_responses:   this.acceptingResponses,
                closed_at:             this.closedAt ? this.closedAt : null,
                custom_closed_message: this.customClosedMessage ? this.customClosedMessage : null,
                limit_one_response:    this.limitOneResponse,
                tanggal_mulai:         this.tanggalMulai ? this.tanggalMulai : null,
                tanggal_selesai:       this.tanggalSelesai ? this.tanggalSelesai : null,
                questions:             this.questions,
            };

            try {
                const res = await fetch("{{ route('admin.surveys.update', $survey->id, false) }}", {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify(payload)
                });

                const json = await res.json().catch(() => ({}));

                if (res.ok && json.success) {
                    this.status = targetStatus || this.status;
                    this.isDirty = false;
                    if (json.questions && Array.isArray(json.questions)) {
                        json.questions.forEach((savedQ, i) => {
                            if (this.questions[i]) {
                                this.questions[i].id = savedQ.id;
                            }
                        });
                    }
                    const verb = targetStatus === 'PUBLISHED' ? 'Survey berhasil dipublikasikan' : 'Survey berhasil disimpan sebagai Draft';
                    this.autoSaveStatusText = 'Tersimpan';
                    this.showToast(verb);
                } else {
                    this.showToast('Gagal menyimpan: ' + (json.message || 'Coba lagi'));
                }
            } catch (e) {
                this.showToast('Terjadi kesalahan jaringan');
            } finally {
                this.saving = false;
            }
        }
    }
}
</script>
@endsection
