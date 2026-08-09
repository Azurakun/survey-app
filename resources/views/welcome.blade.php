<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Survey Riset Pasar Kewirausahaan — SMKN 2 Indramayu</title>
    <meta name="description" content="Platform survey riset pasar digital terintegrasi untuk program Kewirausahaan SMKN 2 Indramayu. Buat survey, kumpulkan data siswa, dan analisis hasil secara real-time.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: { extend: {
                colors: {
                    accent:  { DEFAULT:'#C89D54', light:'#D9AB55', dark:'#8C6B30', tint:'#FAF6EE' },
                    ink:     { DEFAULT:'#1C1917', muted:'#6E675F', subtle:'#A19A91' },
                    surface: { DEFAULT:'#FFFFFF', base:'#FFFFFF', subtle:'#FAF8F5', border:'#E5E0D8', divider:'#EBE6DE' },
                },
                fontFamily: {
                    sans: ['Plus Jakarta Sans','Inter','system-ui','sans-serif'],
                }
            }}
        }
    </script>
    <style>
        body { font-family:'Plus Jakarta Sans','Inter',system-ui,sans-serif; background-color:#FFFFFF; color:#1C1917; }
        
        /* Background Grid Overlay */
        .bg-grid-pattern {
            background-image: radial-gradient(#E5E0D8 1.2px, transparent 1.2px);
            background-size: 28px 28px;
        }

        /* Animations */
        @keyframes floatSlow {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-12px) rotate(1deg); }
        }
        @keyframes floatReverse {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(10px) rotate(-1.5deg); }
        }
        @keyframes pulseGlow {
            0%, 100% { opacity: 0.4; transform: scale(1); }
            50% { opacity: 0.7; transform: scale(1.05); }
        }
        @keyframes shimmer {
            100% { transform: translateX(100%); }
        }
        @keyframes fadeUp {
            0% { opacity: 0; transform: translateY(20px); }
            100% { opacity: 1; transform: translateY(0); }
        }

        .animate-float-slow { animation: floatSlow 5s ease-in-out infinite; }
        .animate-float-reverse { animation: floatReverse 6s ease-in-out infinite; }
        .animate-pulse-glow { animation: pulseGlow 4s ease-in-out infinite; }
        .animate-fade-up { animation: fadeUp 0.7s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        
        .delay-1 { animation-delay: 0.1s; opacity: 0; }
        .delay-2 { animation-delay: 0.2s; opacity: 0; }
        .delay-3 { animation-delay: 0.3s; opacity: 0; }
        .delay-4 { animation-delay: 0.4s; opacity: 0; }

        /* Glassmorphism Cards */
        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(12px);
            border: 1px solid #E5E0D8;
        }
        .glass-card-hover {
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .glass-card-hover:hover {
            transform: translateY(-6px);
            border-color: #C89D54;
            box-shadow: 0 20px 30px -10px rgba(28, 25, 23, 0.08);
        }
    </style>
</head>
<body class="min-h-screen relative overflow-x-hidden bg-white text-ink">

    <!-- Ambient Glow Top Background -->
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[1000px] h-[500px] bg-gradient-to-b from-[#FAF6EE] via-[#FFFFFF] to-transparent opacity-70 pointer-events-none -z-10 rounded-full blur-3xl"></div>

    <!-- ── Header Navigation ───────────────────────────────────────────── -->
    <header class="sticky top-0 z-40 w-full bg-white/90 backdrop-blur-md border-b border-surface-border transition-all">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 py-3.5 flex items-center justify-between">
            <a href="#" class="flex items-center gap-3 group">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center font-bold text-xs shadow-xs transition group-hover:scale-105"
                     style="background:#1C1917; color:#C89D54;">
                    SMK
                </div>
                <div>
                    <div class="text-[9px] font-sans uppercase tracking-widest font-bold" style="color:#C89D54;">SMKN 2 INDRAMAYU</div>
                    <div class="text-sm font-bold text-ink leading-none group-hover:text-accent transition">Riset Pasar Kewirausahaan</div>
                </div>
            </a>

            <!-- Nav Links -->
            <div class="hidden md:flex items-center gap-8 text-xs font-semibold" style="color:#6E675F;">
                <a href="#fitur" class="hover:text-ink transition">Fitur Utama</a>
                <a href="#tipe-pertanyaan" class="hover:text-ink transition">Tipe Pertanyaan</a>
                <a href="#statistik" class="hover:text-ink transition">Statistik Platform</a>
            </div>

            <!-- Login Admin Button -->
            <a href="{{ route('admin.login') }}"
               class="px-4 py-2.5 text-xs font-bold rounded-xl transition shadow-xs flex items-center gap-2 group"
               style="background:#1C1917; color:#FFFFFF;"
               onmouseover="this.style.background='#2C2723';"
               onmouseout="this.style.background='#1C1917';">
                <span>Login Admin / Guru</span>
                <svg class="w-3.5 h-3.5 transition transform group-hover:translate-x-1" style="color:#C89D54;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </a>
        </div>
    </header>

    <!-- ── Hero Section ─────────────────────────────────────────────────── -->
    <section class="relative max-w-6xl mx-auto px-4 sm:px-6 pt-16 pb-20 md:pt-24 md:pb-28 grid grid-cols-1 lg:grid-cols-12 gap-12 items-center bg-grid-pattern">

        <!-- Left Column (Text & CTAs) -->
        <div class="lg:col-span-7 space-y-6 text-left">
            <div class="inline-flex items-center gap-2 border text-xs font-bold px-4 py-2 rounded-full shadow-2xs animate-fade-up delay-1"
                 style="background:#FAF6EE; border-color:#E5E0D8; color:#C89D54;">
                <span class="w-2 h-2 rounded-full animate-ping" style="background:#C89D54;"></span>
                <span>Program Kewirausahaan SMKN 2 Indramayu</span>
            </div>

            <h1 class="font-bold text-4xl sm:text-5xl lg:text-6xl text-ink leading-[1.12] tracking-tight animate-fade-up delay-2">
                Platform Riset<br>
                <span class="relative inline-block text-transparent bg-clip-text" style="background-image: linear-gradient(135deg, #1C1917 0%, #C89D54 100%);">
                    Pasar Digital
                </span><br>
                Siswa Kewirausahaan
            </h1>

            <p class="text-sm sm:text-base leading-relaxed max-w-xl animate-fade-up delay-3" style="color:#6E675F;">
                Sistem survey terintegrasi untuk mendukung riset produk siswa.
                Rancang survey interaktif, kumpulkan tanggapan responden secara real-time, dan peroleh analisis minat pasar secara otomatis.
            </p>

            <!-- CTA Buttons -->
            <div class="flex flex-wrap items-center gap-3 pt-2 animate-fade-up delay-4">
                <a href="{{ route('admin.login') }}"
                   class="px-7 py-4 text-white font-bold text-sm rounded-xl transition shadow-md hover:shadow-lg flex items-center gap-2.5 group"
                   style="background:#1C1917;"
                   onmouseover="this.style.background='#2C2723';"
                   onmouseout="this.style.background='#1C1917';">
                    <span>Masuk Dashboard Admin</span>
                    <svg class="w-4 h-4 transition transform group-hover:translate-x-1" style="color:#C89D54;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </a>
                <a href="#fitur"
                   class="px-6 py-4 bg-white border text-ink font-bold text-sm rounded-xl transition hover:bg-surface-subtle shadow-2xs"
                   style="border-color:#E5E0D8;">
                    Pelajari Fitur ↓
                </a>
            </div>

            <!-- Stats Bar -->
            <div id="statistik" class="grid grid-cols-3 gap-6 pt-6 border-t border-surface-divider max-w-lg animate-fade-up delay-4">
                <div>
                    <div class="text-2xl sm:text-3xl font-bold text-ink tracking-tight">8 Tipe</div>
                    <div class="text-xs font-medium mt-0.5" style="color:#6E675F;">Pertanyaan Fleksibel</div>
                </div>
                <div>
                    <div class="text-2xl sm:text-3xl font-bold text-ink tracking-tight">100%</div>
                    <div class="text-xs font-medium mt-0.5" style="color:#6E675F;">Berbasis Web</div>
                </div>
                <div>
                    <div class="text-2xl sm:text-3xl font-bold text-ink tracking-tight">Realtime</div>
                    <div class="text-xs font-medium mt-0.5" style="color:#6E675F;">Analisis & Chart</div>
                </div>
            </div>
        </div>

        <!-- Right Column (Animated Visual Graphic) -->
        <div class="lg:col-span-5 flex justify-center lg:justify-end relative">
            <!-- Pulsing Background Circle -->
            <div class="absolute inset-0 m-auto w-72 h-72 rounded-full animate-pulse-glow -z-10" style="background:#FAF6EE;"></div>

            <!-- Animated Interactive Visual Container -->
            <div class="relative w-80 h-80 sm:w-96 sm:h-96 flex items-center justify-center">

                <!-- Outer Decorative Rings -->
                <div class="absolute inset-0 rounded-full border border-dashed transition duration-700" style="border-color:#E5E0D8;"></div>
                <div class="absolute inset-6 rounded-full border" style="background:#FFFFFF; border-color:#E5E0D8;"></div>

                <!-- Center App Badge Icon -->
                <div class="w-36 h-36 rounded-3xl shadow-xl border flex flex-col items-center justify-center gap-2 animate-float-slow z-10"
                     style="background:#1C1917; border-color:#2C2723;">
                    <div class="w-12 h-12 rounded-2xl flex items-center justify-center text-2xl shadow-inner" style="background:#FAF6EE; color:#C89D54;">
                        📊
                    </div>
                    <span class="text-xs font-bold text-white tracking-wide">Riset Pasar</span>
                    <span class="text-[9px] uppercase tracking-widest font-bold" style="color:#C89D54;">SMKN 2 Indramayu</span>
                </div>

                <!-- Floating Badge 1: Top Right -->
                <div class="absolute top-2 -right-2 glass-card rounded-2xl px-4 py-3 shadow-md flex items-center gap-2.5 animate-float-slow"
                     style="animation-delay: 0.5s;">
                    <div class="w-7 h-7 rounded-lg flex items-center justify-center text-xs font-bold" style="background:#EDFAF2; color:#1A5C38;">⭐</div>
                    <div>
                        <div class="text-xs font-bold text-ink">Skala Likert (1-5)</div>
                        <div class="text-[10px]" style="color:#A19A91;">Evaluasi Persepsi Siswa</div>
                    </div>
                </div>

                <!-- Floating Badge 2: Bottom Left -->
                <div class="absolute bottom-6 -left-4 glass-card rounded-2xl px-4 py-3 shadow-md flex items-center gap-2.5 animate-float-reverse"
                     style="animation-delay: 1s;">
                    <div class="w-7 h-7 rounded-lg flex items-center justify-center text-xs font-bold" style="background:#FDECEA; color:#922B21;">✓</div>
                    <div>
                        <div class="text-xs font-bold text-ink">Validasi NISN</div>
                        <div class="text-[10px]" style="color:#A19A91;">Cegah Duplikasi Respon</div>
                    </div>
                </div>

                <!-- Floating Badge 3: Bottom Right -->
                <div class="absolute -bottom-2 right-4 glass-card rounded-2xl px-4 py-2.5 shadow-md flex items-center gap-2 animate-float-slow"
                     style="animation-delay: 1.5s;">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-ping"></span>
                    <span class="text-xs font-bold text-ink">Live Analytics Dashboard</span>
                </div>

            </div>
        </div>

    </section>

    <!-- ── Feature Cards Section ────────────────────────────────────────── -->
    <section id="fitur" class="py-20 border-t border-b bg-white" style="border-color:#E5E0D8;">
        <div class="max-w-6xl mx-auto px-4 sm:px-6">

            <!-- Section Title -->
            <div class="text-center max-w-2xl mx-auto space-y-3 mb-16">
                <div class="text-[10px] font-sans uppercase tracking-widest font-bold" style="color:#C89D54;">FITUR UTAMA PLATFORM</div>
                <h2 class="font-bold text-3xl sm:text-4xl text-ink tracking-tight">Semua yang Anda Butuhkan untuk Riset</h2>
                <p class="text-xs sm:text-sm leading-relaxed" style="color:#6E675F;">
                    Dirancang khusus untuk mendukung kegiatan praktek bisnis & kewirausahaan siswa SMKN 2 Indramayu dari tahap perencanaan hingga evaluasi pasar.
                </p>
            </div>

            <!-- 3 Columns Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

                <!-- Feature 1 -->
                <div class="glass-card glass-card-hover rounded-2xl p-7 space-y-5">
                    <div class="w-14 h-14 rounded-2xl flex items-center justify-center text-2xl border shadow-2xs" style="background:#FAF6EE; border-color:#E5E0D8; color:#1C1917;">
                        📋
                    </div>
                    <div class="space-y-2">
                        <h3 class="font-bold text-lg text-ink">Buat Survey Dinamis</h3>
                        <p class="text-xs leading-relaxed" style="color:#6E675F;">
                            Builder interaktif dengan 8 pilihan tipe pertanyaan fleksibel, mulai dari skala Likert hingga upload foto produk.
                        </p>
                    </div>
                    <ul class="space-y-2 text-xs border-t pt-4" style="border-color:#EBE6DE; color:#6E675F;">
                        <li class="flex items-center gap-2"><span style="color:#C89D54;" class="font-bold">✓</span> Builder 8 Tipe Pertanyaan</li>
                        <li class="flex items-center gap-2"><span style="color:#C89D54;" class="font-bold">✓</span> Live Preview Responden</li>
                        <li class="flex items-center gap-2"><span style="color:#C89D54;" class="font-bold">✓</span> Drag & Drop Susunan Soal</li>
                    </ul>
                </div>

                <!-- Feature 2 -->
                <div class="glass-card glass-card-hover rounded-2xl p-7 space-y-5">
                    <div class="w-14 h-14 rounded-2xl flex items-center justify-center text-2xl border shadow-2xs" style="background:#FAF6EE; border-color:#E5E0D8; color:#1C1917;">
                        👥
                    </div>
                    <div class="space-y-2">
                        <h3 class="font-bold text-lg text-ink">Pengumpulan Data Siswa</h3>
                        <p class="text-xs leading-relaxed" style="color:#6E675F;">
                            Tampilan survey wizard 1-soal-per-langkah yang ramah ponsel. Validasi NISN otomatis menjamin keakuratan responden.
                        </p>
                    </div>
                    <ul class="space-y-2 text-xs border-t pt-4" style="border-color:#EBE6DE; color:#6E675F;">
                        <li class="flex items-center gap-2"><span style="color:#C89D54;" class="font-bold">✓</span> Verifikasi NISN Unik</li>
                        <li class="flex items-center gap-2"><span style="color:#C89D54;" class="font-bold">✓</span> Antarmuka Wizard Responsif</li>
                        <li class="flex items-center gap-2"><span style="color:#C89D54;" class="font-bold">✓</span> Pengunggahan Bukti Foto</li>
                    </ul>
                </div>

                <!-- Feature 3 -->
                <div class="glass-card glass-card-hover rounded-2xl p-7 space-y-5">
                    <div class="w-14 h-14 rounded-2xl flex items-center justify-center text-2xl border shadow-2xs" style="background:#FAF6EE; border-color:#E5E0D8; color:#1C1917;">
                        📈
                    </div>
                    <div class="space-y-2">
                        <h3 class="font-bold text-lg text-ink">Analisis & Ekspor Data</h3>
                        <p class="text-xs leading-relaxed" style="color:#6E675F;">
                            Dashboard analitik visual otomatis menyajikan chart donut, statistik angka, hingga opsi ekspor laporan ke Excel.
                        </p>
                    </div>
                    <ul class="space-y-2 text-xs border-t pt-4" style="border-color:#EBE6DE; color:#6E675F;">
                        <li class="flex items-center gap-2"><span style="color:#C89D54;" class="font-bold">✓</span> Chart Donut & Bar Per Soal</li>
                        <li class="flex items-center gap-2"><span style="color:#C89D54;" class="font-bold">✓</span> Rekap Jawaban Per Responden</li>
                        <li class="flex items-center gap-2"><span style="color:#C89D54;" class="font-bold">✓</span> Ekspor File Spreadsheet (.XLSX)</li>
                    </ul>
                </div>

            </div>

        </div>
    </section>

    <!-- ── Supported Question Types Section ─────────────────────────────── -->
    <section id="tipe-pertanyaan" class="py-20 bg-white">
        <div class="max-w-6xl mx-auto px-4 sm:px-6">

            <div class="text-center max-w-2xl mx-auto space-y-3 mb-14">
                <div class="text-[10px] font-sans uppercase tracking-widest font-bold" style="color:#C89D54;">FLEKSIBILITAS INSTRUMEN</div>
                <h2 class="font-bold text-3xl text-ink tracking-tight">8 Tipe Pertanyaan Terintegrasi</h2>
                <p class="text-xs sm:text-sm" style="color:#6E675F;">Rancang berbagai jenis survey riset produk dengan pilihan instrumen lengkap.</p>
            </div>

            <!-- Question Badges Grid -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">

                <div class="p-4 rounded-xl border flex items-center gap-3 transition hover:border-accent" style="background:#FAF8F5; border-color:#E5E0D8;">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center font-bold text-xs" style="background:#FFFFFF; color:#C89D54; border:1px solid #E5E0D8;">1</div>
                    <div>
                        <div class="text-xs font-bold text-ink">Single Choice</div>
                        <div class="text-[10px]" style="color:#A19A91;">Pilihan Tunggal</div>
                    </div>
                </div>

                <div class="p-4 rounded-xl border flex items-center gap-3 transition hover:border-accent" style="background:#FAF8F5; border-color:#E5E0D8;">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center font-bold text-xs" style="background:#FFFFFF; color:#C89D54; border:1px solid #E5E0D8;">2</div>
                    <div>
                        <div class="text-xs font-bold text-ink">Multiple Choice</div>
                        <div class="text-[10px]" style="color:#A19A91;">Pilih Banyak</div>
                    </div>
                </div>

                <div class="p-4 rounded-xl border flex items-center gap-3 transition hover:border-accent" style="background:#FAF8F5; border-color:#E5E0D8;">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center font-bold text-xs" style="background:#FFFFFF; color:#C89D54; border:1px solid #E5E0D8;">3</div>
                    <div>
                        <div class="text-xs font-bold text-ink">Skala Likert (1-5)</div>
                        <div class="text-[10px]" style="color:#A19A91;">Tingkat Kepuasan</div>
                    </div>
                </div>

                <div class="p-4 rounded-xl border flex items-center gap-3 transition hover:border-accent" style="background:#FAF8F5; border-color:#E5E0D8;">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center font-bold text-xs" style="background:#FFFFFF; color:#C89D54; border:1px solid #E5E0D8;">4</div>
                    <div>
                        <div class="text-xs font-bold text-ink">Angka / Nominal</div>
                        <div class="text-[10px]" style="color:#A19A91;">Estimasi Harga</div>
                    </div>
                </div>

                <div class="p-4 rounded-xl border flex items-center gap-3 transition hover:border-accent" style="background:#FAF8F5; border-color:#E5E0D8;">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center font-bold text-xs" style="background:#FFFFFF; color:#C89D54; border:1px solid #E5E0D8;">5</div>
                    <div>
                        <div class="text-xs font-bold text-ink">Teks Pendek</div>
                        <div class="text-[10px]" style="color:#A19A91;">Jawaban Singkat</div>
                    </div>
                </div>

                <div class="p-4 rounded-xl border flex items-center gap-3 transition hover:border-accent" style="background:#FAF8F5; border-color:#E5E0D8;">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center font-bold text-xs" style="background:#FFFFFF; color:#C89D54; border:1px solid #E5E0D8;">6</div>
                    <div>
                        <div class="text-xs font-bold text-ink">Teks Panjang</div>
                        <div class="text-[10px]" style="color:#A19A91;">Saran & Masukan</div>
                    </div>
                </div>

                <div class="p-4 rounded-xl border flex items-center gap-3 transition hover:border-accent" style="background:#FAF8F5; border-color:#E5E0D8;">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center font-bold text-xs" style="background:#FFFFFF; color:#C89D54; border:1px solid #E5E0D8;">7</div>
                    <div>
                        <div class="text-xs font-bold text-ink">Upload Foto</div>
                        <div class="text-[10px]" style="color:#A19A91;">Dokumentasi Produk</div>
                    </div>
                </div>

                <div class="p-4 rounded-xl border flex items-center gap-3 transition hover:border-accent" style="background:#FAF8F5; border-color:#E5E0D8;">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center font-bold text-xs" style="background:#FFFFFF; color:#C89D54; border:1px solid #E5E0D8;">8</div>
                    <div>
                        <div class="text-xs font-bold text-ink">Tanggal</div>
                        <div class="text-[10px]" style="color:#A19A91;">Penjadwalan</div>
                    </div>
                </div>

            </div>

        </div>
    </section>

    <!-- ── Call to Action Banner Card ────────────────────────────────────── -->
    <section class="max-w-6xl mx-auto px-4 sm:px-6 py-16">
        <div class="relative rounded-3xl p-8 sm:p-12 text-center text-white overflow-hidden shadow-2xl border"
             style="background:#1C1917; border-color:#2C2723;">
            
            <!-- Ambient Glow -->
            <div class="absolute -top-24 -right-24 w-72 h-72 rounded-full opacity-20 blur-2xl" style="background:#C89D54;"></div>
            <div class="absolute -bottom-24 -left-24 w-72 h-72 rounded-full opacity-20 blur-2xl" style="background:#C89D54;"></div>

            <div class="relative z-10 max-w-xl mx-auto space-y-6">
                <div class="w-12 h-12 rounded-2xl mx-auto flex items-center justify-center text-xl shadow-inner border"
                     style="background:#FAF6EE; border-color:#E5E0D8; color:#C89D54;">
                    🚀
                </div>
                <h2 class="font-bold text-3xl sm:text-4xl text-white tracking-tight">Siap Memulai Riset Pasar?</h2>
                <p class="text-xs sm:text-sm leading-relaxed" style="color:#A19A91;">
                    Masuk sebagai admin atau guru untuk merancang survey pertama Anda dan mulai mengumpulkan tanggapan dari siswa.
                </p>
                <div class="pt-2">
                    <a href="{{ route('admin.login') }}"
                       class="inline-flex items-center gap-2.5 px-8 py-4 text-white font-bold text-sm rounded-xl transition shadow-lg hover:shadow-xl group"
                       style="background:#C89D54;"
                       onmouseover="this.style.background='#D9AB55';"
                       onmouseout="this.style.background='#C89D54';">
                        <span>Masuk ke Dashboard Admin</span>
                        <svg class="w-4 h-4 transition transform group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </a>
                </div>
            </div>

        </div>
    </section>

    <!-- ── Footer ────────────────────────────────────────────────────────── -->
    <footer class="border-t py-8 text-center text-xs bg-white" style="border-color:#E5E0D8; color:#A19A91;">
        <div class="max-w-6xl mx-auto px-4 flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-2">
                <div class="w-6 h-6 rounded-md flex items-center justify-center text-[10px] font-bold" style="background:#1C1917; color:#C89D54;">SMK</div>
                <span class="font-bold text-ink">SMKN 2 Indramayu</span>
            </div>
            <p>Survey Riset Pasar Kewirausahaan Siswa &middot; {{ date('Y') }}</p>
        </div>
    </footer>

</body>
</html>
