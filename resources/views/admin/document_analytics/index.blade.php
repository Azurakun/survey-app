@extends('layouts.admin')
@section('title', 'Analisa Dokumen Bisnis & Excel AI')

@section('content')
<div x-data="{ uploadModalOpen: false, deleteModalOpen: false, deleteDocId: '', deleteDocTitle: '', selectedFileName: '', selectedFileSize: '' }" class="space-y-6 pb-8">

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

    @if (session('error'))
        <div class="p-4 rounded-xl border flex items-center justify-between animate-fade-up"
             style="background:#FDECEA; border-color:#F5B7B1; color:#922B21;">
            <div class="flex items-center gap-2.5 text-xs font-semibold">
                <span class="text-base">⚠️</span>
                <span>{{ session('error') }}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-xs font-bold opacity-60 hover:opacity-100">✕</button>
        </div>
    @endif

    <!-- ── Header Banner ───────────────────────────────────────────────── -->
    <div class="card p-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="font-serif font-bold text-2xl text-ink">Analisa Dokumen Bisnis & Excel AI</h1>
            <p class="text-xs mt-1" style="color:#6E675F;">
                Unggah <strong>PDF Laporan, PPTX Proposal, DOCX Rencana Usaha, atau Excel Responden (Google Form)</strong> untuk menganalisis potensi pertumbuhan usaha & kelayakan pasar riil.
            </p>
        </div>

        <button @click="uploadModalOpen = true; selectedFileName = ''; selectedFileSize = '';"
                class="px-5 py-2.5 rounded-xl border text-xs font-bold transition-all shadow-xs flex items-center gap-2 self-start md:self-auto cursor-pointer"
                style="background:#1C1917; color:#FFFFFF; border-color:#1C1917;"
                onmouseover="this.style.background='#2C2723';"
                onmouseout="this.style.background='#1C1917';">
            <svg class="w-4 h-4" style="color:#C89D54;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
            </svg>
            <span>+ Unggah Dokumen Baru</span>
        </button>
    </div>

    <!-- ── Info Banner: Dalam Tahap Pengembangan ────────────────────────── -->
    <div class="p-4 rounded-xl border flex items-start gap-3.5 animate-fade-up"
         style="background:#FFFBEB; border-color:#FDE68A; color:#78350F;">
        <span class="text-xl shrink-0">🚧</span>
        <div class="text-xs space-y-1">
            <div class="font-bold flex items-center gap-2">
                <span>Modul Analisa Dokumen Bisnis Masih Dalam Tahap Pengembangan</span>
                <span class="px-2 py-0.5 rounded text-[9px] font-extrabold uppercase bg-amber-200 text-amber-900">BETA / IN DEVELOPMENT</span>
            </div>
            <p class="text-amber-800 leading-relaxed">
                Fitur ini sedang aktif dikembangkan. Anda sudah dapat mengunggah dan menguji analisis AI untuk dokumen PDF, PPTX, DOCX, dan Excel, namun pembaruan fitur dan peningkatan akurasi sistem terus dilakukan secara berkala.
            </p>
        </div>
    </div>

    <!-- ── Filter & Search Bar ───────────────────────────────────────────── -->
    <div class="card p-4 flex flex-col sm:flex-row items-center justify-between gap-3">
        <form method="GET" action="{{ route('admin.document-analytics.index') }}" class="w-full flex flex-col sm:flex-row gap-3">
            <!-- Search -->
            <div class="relative flex-1">
                <svg class="w-4 h-4 absolute left-3.5 top-1/2 -translate-y-1/2" style="color:#A19A91;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Cari judul dokumen atau nama file..."
                       class="w-full pl-10 pr-4 py-2.5 rounded-xl border text-xs font-medium text-ink focus:outline-none focus:ring-2"
                       style="background:#FAF8F5; border-color:#E5E0D8;">
            </div>

            <!-- Type Filter -->
            <select name="type" onchange="this.form.submit()"
                    class="px-4 py-2.5 rounded-xl border text-xs font-semibold text-ink focus:outline-none focus:ring-2"
                    style="background:#FAF8F5; border-color:#E5E0D8;">
                <option value="">Semua Format Dokumen</option>
                <option value="pdf" {{ request('type') === 'pdf' ? 'selected' : '' }}>📄 PDF Report</option>
                <option value="pptx" {{ request('type') === 'pptx' ? 'selected' : '' }}>📊 PPTX Presentation</option>
                <option value="docx" {{ request('type') === 'docx' ? 'selected' : '' }}>📝 DOCX Document</option>
                <option value="xlsx" {{ request('type') === 'xlsx' ? 'selected' : '' }}>📊 Excel / CSV Data</option>
                <option value="txt" {{ request('type') === 'txt' ? 'selected' : '' }}>📄 Text File</option>
            </select>
        </form>
    </div>

    <!-- ── Document Cards Grid ───────────────────────────────────────────── -->
    @if ($documents->isEmpty())
        <div class="card p-12 text-center space-y-4">
            <div class="w-16 h-16 rounded-2xl mx-auto flex items-center justify-center text-3xl border"
                 style="background:#FAF6EE; border-color:#E5E0D8;">📁</div>
            <h3 class="font-serif font-bold text-lg text-ink">Belum Ada Dokumen Bisnis Diterbitkan</h3>
            <p class="text-xs max-w-md mx-auto" style="color:#6E675F;">
                Unggah dokumen proposal usaha (PPTX/PDF), laporan studi kelayakan (DOCX), atau data responden ekspor Google Form (Excel/CSV) Anda untuk mendapatkan analisa kelayakan AI terperinci.
            </p>
            <button @click="uploadModalOpen = true; selectedFileName = ''; selectedFileSize = '';"
                    class="px-5 py-2.5 rounded-xl text-xs font-bold transition inline-flex items-center gap-2 border cursor-pointer"
                    style="background:#1C1917; color:#FFFFFF; border-color:#1C1917;">
                <span>+ Unggah Dokumen Pertama</span>
            </button>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($documents as $doc)
                @php
                    $ai = $doc->ai_analysis ?? [];
                    $skor = $ai['skor_potensi'] ?? 0;
                    $type = strtoupper($doc->file_type);
                @endphp
                <div class="card p-6 flex flex-col justify-between space-y-5 transition-all duration-200 hover:-translate-y-1 hover:shadow-md"
                     style="border-color:#E5E0D8;">

                    <div class="space-y-3">
                        <!-- Format Badge & Growth Likelihood Badge -->
                        <div class="flex items-center justify-between gap-2">
                            <span class="px-2.5 py-1 rounded-lg text-[10px] font-bold uppercase tracking-wider border flex items-center gap-1"
                                  style="background:#FAF6EE; border-color:#E5E0D8; color:#C89D54;">
                                @if (in_array($doc->file_type, ['xlsx','xls','csv'])) 📊 EXCEL @elseif ($doc->file_type === 'pdf') 📄 PDF @elseif (in_array($doc->file_type, ['pptx','ppt'])) 📊 PPTX @else 📝 {{ $type }} @endif
                            </span>

                            @if ($skor > 0)
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold border"
                                      style="background:{{ $skor >= 80 ? '#EDFAF2' : '#FFFBEB' }}; border-color:{{ $skor >= 80 ? '#A3E6CD' : '#FDE68A' }}; color:{{ $skor >= 80 ? '#1A5C38' : '#92400E' }};">
                                    ✨ Potential: {{ $skor }}%
                                </span>
                            @endif
                        </div>

                        <!-- Title & File Name -->
                        <div>
                            <h3 class="font-serif font-bold text-base text-ink line-clamp-2 leading-snug">
                                {{ $doc->judul }}
                            </h3>
                            <div class="text-[11px] mt-1 flex items-center gap-1.5" style="color:#A19A91;">
                                <svg class="w-3.5 h-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                                <span class="truncate">{{ $doc->nama_file_asli }}</span>
                                <span class="shrink-0">({{ $doc->file_size }})</span>
                            </div>
                        </div>

                        <!-- Summary Snippet -->
                        <p class="text-xs line-clamp-3 leading-relaxed" style="color:#6E675F;">
                            {{ $ai['ringkasan_eksekutif'] ?? 'Dokumen tersimpan di database. Siap diakses dan dianalisis.' }}
                        </p>
                    </div>

                    <!-- Footer Info & Actions -->
                    <div class="pt-4 border-t flex items-center justify-between" style="border-color:#EBE6DE;">
                        <div class="text-[10px]" style="color:#A19A91;">
                            Tersimpan: {{ $doc->created_at->format('d M Y') }}
                        </div>

                        <div class="flex items-center gap-2">
                            <a href="{{ route('admin.document-analytics.show', $doc->id) }}"
                               class="px-3 py-1.5 rounded-lg text-xs font-bold border transition flex items-center gap-1 cursor-pointer"
                               style="background:#FAF8F5; border-color:#E5E0D8; color:#1C1917;"
                               onmouseover="this.style.background='#F4F0E8';"
                               onmouseout="this.style.background='#FAF8F5';">
                                <span>Lihat Laporan AI</span>
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </a>

                            <button type="button"
                                    @click="deleteModalOpen = true; deleteDocId = '{{ $doc->id }}'; deleteDocTitle = '{{ addslashes($doc->judul) }}';"
                                    class="px-2.5 py-1.5 rounded-lg text-xs font-bold border transition flex items-center gap-1 cursor-pointer"
                                    style="background:#FDECEA; border-color:#F5B7B1; color:#922B21;"
                                    onmouseover="this.style.background='#FADBD8';"
                                    onmouseout="this.style.background='#FDECEA';"
                                    title="Hapus Dokumen">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                                <span>Hapus</span>
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $documents->links() }}
        </div>
    @endif

    <!-- ── Upload Document Modal ─────────────────────────────────────────── -->
    <div x-show="uploadModalOpen" x-cloak x-transition
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/25">
        
        <!-- Modal Card -->
        <div @click.away="uploadModalOpen = false"
             class="bg-white rounded-3xl border border-stone-300 max-w-lg w-full p-6 sm:p-8 space-y-5 shadow-2xl animate-pop-in relative">
            
            <!-- Header -->
            <div class="flex items-center justify-between border-b pb-4 border-stone-200">
                <div class="flex items-center gap-2.5">
                    <div class="w-9 h-9 rounded-xl flex items-center justify-center text-lg shrink-0" style="background:#FAF6EE; border:1px solid #E5E0D8;">
                        📁
                    </div>
                    <div>
                        <h3 class="font-serif font-bold text-base text-stone-900">Unggah Dokumen Bisnis / Excel</h3>
                        <p class="text-[11px] text-stone-500">Pilih berkas untuk dianalisis oleh Gemini AI</p>
                    </div>
                </div>
                <button type="button" @click="uploadModalOpen = false"
                        class="w-7 h-7 rounded-full flex items-center justify-center text-stone-400 hover:text-stone-800 hover:bg-stone-100 transition text-sm font-bold">
                    ✕
                </button>
            </div>

            <!-- Upload Form -->
            <form action="{{ route('admin.document-analytics.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf

                <!-- Judul Input -->
                <div>
                    <label class="block text-xs font-bold text-stone-700 mb-1.5">
                        Judul / Topik Riset Bisnis <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="judul" required
                           placeholder="Contoh: Proposal Usaha Servis Komputer / Hasil Survey Google Form"
                           class="w-full px-3.5 py-2.5 rounded-xl border text-xs font-medium focus:outline-none focus:ring-2 focus:ring-amber-500/30"
                           style="background:#FAF8F5; border-color:#E5E0D8;">
                </div>

                <!-- Custom Styled Drag & Drop Area -->
                <div>
                    <label class="block text-xs font-bold text-stone-700 mb-1.5">
                        File Dokumen (PDF, PPTX, DOCX, XLSX, CSV, TXT) <span class="text-red-500">*</span>
                    </label>

                    <div class="relative p-6 border-2 border-dashed rounded-2xl text-center space-y-3 transition-colors cursor-pointer"
                         style="background:#FAF8F5; border-color:#D5CEC4;"
                         @click="$refs.fileInput.click()">

                        <input type="file" x-ref="fileInput" name="document" required
                               accept=".pdf,.pptx,.ppt,.docx,.doc,.xlsx,.xls,.csv,.txt,.md"
                               class="hidden"
                               @change="
                                   let f = $event.target.files[0];
                                   if(f) {
                                       selectedFileName = f.name;
                                       selectedFileSize = f.size >= 1048576 ? (f.size / 1048576).toFixed(1) + ' MB' : (f.size / 1024).toFixed(0) + ' KB';
                                   }
                               ">

                        <div x-show="!selectedFileName" class="space-y-2">
                            <div class="w-12 h-12 rounded-2xl mx-auto flex items-center justify-center text-2xl border"
                                 style="background:#FFFFFF; border-color:#E5E0D8;">📄</div>
                            <div>
                                <span class="text-xs font-bold text-stone-900 block">Klik atau Seret Berkas Ke Sini</span>
                                <span class="text-[10px] text-stone-500 block mt-0.5">Mendukung .pdf, .pptx, .docx, .xlsx, .csv, .txt (Maks 15MB)</span>
                            </div>

                            <!-- Format Pills -->
                            <div class="flex items-center justify-center gap-1.5 pt-1">
                                <span class="px-2 py-0.5 rounded text-[9px] font-bold border bg-white text-stone-600">PDF</span>
                                <span class="px-2 py-0.5 rounded text-[9px] font-bold border bg-white text-stone-600">PPTX</span>
                                <span class="px-2 py-0.5 rounded text-[9px] font-bold border bg-white text-stone-600">DOCX</span>
                                <span class="px-2 py-0.5 rounded text-[9px] font-bold border bg-white text-stone-600">EXCEL</span>
                                <span class="px-2 py-0.5 rounded text-[9px] font-bold border bg-white text-stone-600">TXT</span>
                            </div>
                        </div>

                        <!-- Selected File Preview Pill -->
                        <div x-show="selectedFileName" x-cloak class="p-3 rounded-xl border bg-emerald-50/80 border-emerald-200 text-left flex items-center justify-between">
                            <div class="flex items-center gap-2.5 min-w-0">
                                <span class="text-lg">✅</span>
                                <div class="min-w-0">
                                    <div class="text-xs font-bold text-emerald-900 truncate" x-text="selectedFileName"></div>
                                    <div class="text-[10px] text-emerald-700" x-text="selectedFileSize"></div>
                                </div>
                            </div>
                            <span class="text-[10px] font-bold px-2 py-1 rounded bg-emerald-200 text-emerald-800 shrink-0">Siap Diunggah</span>
                        </div>
                    </div>
                </div>

                <!-- Footer Buttons -->
                <div class="pt-2 flex items-center justify-end gap-2.5">
                    <button type="button" @click="uploadModalOpen = false"
                            class="px-4 py-2.5 rounded-xl text-xs font-bold text-stone-600 hover:bg-stone-100 transition">
                        Batal
                    </button>
                    <button type="submit"
                            class="px-5 py-2.5 rounded-xl text-xs font-bold text-white shadow-xs flex items-center gap-1.5 cursor-pointer transition"
                            style="background:#1C1917;"
                            onmouseover="this.style.background='#2C2723';"
                            onmouseout="this.style.background='#1C1917';">
                        <span>Proses & Analisis AI</span>
                        <svg class="w-4 h-4" style="color:#C89D54;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ── Clean Floating Modal: Konfirmasi Hapus Data? ────────────────────── -->
    <div x-show="deleteModalOpen" x-cloak x-transition
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/25">
        <div @click.away="deleteModalOpen = false"
             class="bg-white rounded-3xl border border-stone-300 max-w-md w-full p-6 space-y-5 shadow-2xl animate-pop-in relative">
            
            <div class="flex items-center gap-3 text-red-600 border-b pb-4 border-stone-200">
                <div class="w-10 h-10 rounded-2xl flex items-center justify-center text-xl shrink-0 bg-red-50 border border-red-200">
                    🗑️
                </div>
                <div>
                    <h3 class="font-serif font-bold text-lg text-stone-900">Konfirmasi Hapus Data?</h3>
                    <p class="text-xs text-stone-500">Hapus Permanen Dokumen & Laporan AI</p>
                </div>
            </div>

            <div class="p-4 rounded-2xl border border-red-200 bg-red-50/60 text-xs text-red-900 leading-relaxed">
                Apakah Anda yakin ingin menghapus dokumen <strong x-text="'&quot;' + deleteDocTitle + '&quot;'"></strong>? Seluruh berkas dan laporan AI tersimpan di database akan <strong>dihapus permanen</strong>.
            </div>

            <div class="flex items-center justify-end gap-2.5 pt-1">
                <button type="button" @click="deleteModalOpen = false"
                        class="px-4 py-2.5 rounded-xl text-xs font-bold text-stone-600 hover:bg-stone-100 transition">
                    Batal
                </button>

                <form :action="'{{ url('/admin/document-analytics') }}/' + deleteDocId" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="px-5 py-2.5 rounded-xl text-xs font-bold text-white shadow-xs cursor-pointer transition flex items-center gap-1.5"
                            style="background:#C0392B;">
                        <span>Ya, Hapus Permanen</span>
                    </button>
                </form>
            </div>
        </div>
    </div>

</div>
@endsection
