<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Survey Riset Pasar Kewirausahaan — SMKN 2 Indramayu</title>
    <meta name="description" content="Platform survey riset pasar digital untuk program Kewirausahaan SMKN 2 Indramayu. Buat survey, kumpulkan data siswa, dan analisis hasil dalam satu platform terintegrasi.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,700;0,9..144,800;1,9..144,600&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: { extend: {
                colors: {
                    mango:   { DEFAULT:'#E8A33D', dark:'#C7842A', tint:'#FDF3E3', light:'#F5C878' },
                    leaf:    { DEFAULT:'#2F5233', dark:'#213A25', light:'#4A7A4E', tint:'#E6EFE7' },
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
        @keyframes float { 0%,100%{transform:translateY(0)} 50%{transform:translateY(-10px)} }
        @keyframes fadeUp { 0%{opacity:0;transform:translateY(24px)} 100%{opacity:1;transform:translateY(0)} }
        @keyframes fadeLeft { 0%{opacity:0;transform:translateX(-24px)} 100%{opacity:1;transform:translateX(0)} }
        .animate-float { animation:float 4s ease-in-out infinite; }
        .animate-fade-up { animation:fadeUp 0.6s ease-out forwards; }
        .animate-fade-left { animation:fadeLeft 0.6s ease-out forwards; }
        .delay-100 { animation-delay:0.1s; opacity:0; }
        .delay-200 { animation-delay:0.2s; opacity:0; }
        .delay-300 { animation-delay:0.3s; opacity:0; }
    </style>
</head>
<body class="min-h-screen">

    <!-- ── Navigation ──────────────────────────────────────────────────── -->
    <nav class="border-b border-neutral-border bg-white/80 backdrop-blur sticky top-0 z-20">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 py-3.5 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-leaf flex items-center justify-center font-serif font-bold text-mango text-xs shadow">
                    SMK
                </div>
                <div>
                    <div class="text-[9px] font-sans uppercase tracking-widest text-terra font-semibold">SMKN 2 INDRAMAYU</div>
                    <div class="font-serif text-sm font-bold text-neutral-dark leading-none">Riset Pasar Kewirausahaan</div>
                </div>
            </div>
            <a href="{{ route('admin.login') }}"
               class="px-4 py-2 bg-leaf hover:bg-leaf-dark text-white text-xs font-bold rounded-xl transition shadow-sm">
                Login Admin →
            </a>
        </div>
    </nav>

    <!-- ── Hero Section ─────────────────────────────────────────────────── -->
    <section class="max-w-6xl mx-auto px-4 sm:px-6 py-16 md:py-24 grid grid-cols-1 md:grid-cols-2 gap-12 items-center">

        <!-- Left Text -->
        <div class="space-y-6">
            <div class="inline-flex items-center gap-2 bg-mango-tint border border-mango/30 text-mango-dark text-xs font-bold px-3.5 py-1.5 rounded-full animate-fade-left">
                🥭 Program Kewirausahaan SMKN 2 Indramayu
            </div>
            <h1 class="font-serif font-bold text-4xl md:text-5xl text-neutral-dark leading-tight animate-fade-up delay-100">
                Platform Riset<br>
                <span class="text-mango">Pasar Digital</span><br>
                Siswa Kewirausahaan
            </h1>
            <p class="text-neutral-muted leading-relaxed max-w-md animate-fade-up delay-200">
                Sistem survey riset pasar terintegrasi untuk program Kewirausahaan SMKN 2 Indramayu.
                Dari pembuatan survey, pengumpulan data responden, hingga analisis hasil — semua dalam satu platform.
            </p>
            <div class="flex flex-wrap gap-3 animate-fade-up delay-300">
                <a href="{{ route('admin.login') }}"
                   class="px-6 py-3.5 bg-mango hover:bg-mango-dark text-neutral-dark font-bold text-sm rounded-xl transition shadow-sm hover:shadow-md">
                    Masuk sebagai Admin / Guru
                </a>
                <a href="#fitur"
                   class="px-6 py-3.5 bg-white border border-neutral-border text-neutral-dark font-bold text-sm rounded-xl transition hover:bg-neutral-bg">
                    Pelajari Fitur ↓
                </a>
            </div>

            <!-- Stats -->
            <div class="flex items-center gap-6 pt-2 animate-fade-up delay-300">
                <div>
                    <div class="font-serif text-2xl font-bold text-neutral-dark">8</div>
                    <div class="text-xs text-neutral-muted">Tipe Pertanyaan</div>
                </div>
                <div class="w-px h-10 bg-neutral-border"></div>
                <div>
                    <div class="font-serif text-2xl font-bold text-neutral-dark">100%</div>
                    <div class="text-xs text-neutral-muted">Berbasis Web</div>
                </div>
                <div class="w-px h-10 bg-neutral-border"></div>
                <div>
                    <div class="font-serif text-2xl font-bold text-neutral-dark">∞</div>
                    <div class="text-xs text-neutral-muted">Responden</div>
                </div>
            </div>
        </div>

        <!-- Right Illustration -->
        <div class="flex justify-center animate-float">
            <div class="relative w-72 h-72 md:w-80 md:h-80">
                <!-- Background circles -->
                <div class="absolute inset-0 rounded-full bg-mango-tint border-2 border-mango/20"></div>
                <div class="absolute inset-8 rounded-full bg-leaf-tint border-2 border-leaf/20"></div>
                <!-- Center icon -->
                <div class="absolute inset-0 flex items-center justify-center">
                    <div class="w-32 h-32 rounded-3xl bg-leaf shadow-xl flex items-center justify-center">
                        <span class="font-serif font-bold text-5xl text-mango">📊</span>
                    </div>
                </div>
                <!-- Floating badges -->
                <div class="absolute top-4 -right-4 bg-white rounded-xl border border-neutral-border px-3 py-2 shadow-sm text-xs font-bold text-neutral-dark">
                    ⭐ Likert Scale
                </div>
                <div class="absolute bottom-8 -left-6 bg-white rounded-xl border border-neutral-border px-3 py-2 shadow-sm text-xs font-bold text-neutral-dark">
                    📝 Riset Pasar
                </div>
                <div class="absolute -bottom-2 right-8 bg-mango rounded-xl px-3 py-2 shadow-sm text-xs font-bold text-neutral-dark">
                    ✓ NISN Validated
                </div>
            </div>
        </div>
    </section>

    <!-- ── Feature Cards ─────────────────────────────────────────────────── -->
    <section id="fitur" class="bg-neutral-dark py-16 md:py-20">
        <div class="max-w-6xl mx-auto px-4 sm:px-6">
            <div class="text-center mb-12">
                <div class="text-[10px] font-sans uppercase tracking-widest text-mango font-semibold mb-3">FITUR UTAMA</div>
                <h2 class="font-serif font-bold text-3xl md:text-4xl text-white">Semua yang Anda Butuhkan</h2>
                <p class="text-neutral-muted mt-3 max-w-xl mx-auto">
                    Dirancang khusus untuk kebutuhan riset pasar produk kewirausahaan siswa SMKN 2 Indramayu.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- F1 -->
                <div class="bg-white/5 border border-white/10 rounded-2xl p-6 space-y-4 hover:bg-white/10 transition">
                    <div class="w-12 h-12 rounded-xl bg-mango flex items-center justify-center text-xl">📋</div>
                    <h3 class="font-serif font-bold text-lg text-white">Buat Survey Dinamis</h3>
                    <p class="text-sm text-neutral-muted leading-relaxed">
                        Builder intuitif dengan 8 tipe pertanyaan: pilihan ganda, Likert, teks, angka, foto, tanggal, dan lainnya.
                        Preview real-time saat membangun survey.
                    </p>
                    <ul class="space-y-1.5 text-xs text-neutral-muted">
                        <li class="flex items-center gap-2"><span class="text-mango">✓</span> 8 Tipe pertanyaan fleksibel</li>
                        <li class="flex items-center gap-2"><span class="text-mango">✓</span> Live preview siswa</li>
                        <li class="flex items-center gap-2"><span class="text-mango">✓</span> Drag & reorder pertanyaan</li>
                    </ul>
                </div>

                <!-- F2 -->
                <div class="bg-white/5 border border-white/10 rounded-2xl p-6 space-y-4 hover:bg-white/10 transition">
                    <div class="w-12 h-12 rounded-xl bg-leaf flex items-center justify-center text-xl">👥</div>
                    <h3 class="font-serif font-bold text-lg text-white">Kumpulkan Data Siswa</h3>
                    <p class="text-sm text-neutral-muted leading-relaxed">
                        Wizard 1-pertanyaan-per-langkah yang mudah digunakan siswa. Validasi NISN otomatis mencegah data duplikat.
                    </p>
                    <ul class="space-y-1.5 text-xs text-neutral-muted">
                        <li class="flex items-center gap-2"><span class="text-leaf">✓</span> Verifikasi NISN unik</li>
                        <li class="flex items-center gap-2"><span class="text-leaf">✓</span> Upload foto produk</li>
                        <li class="flex items-center gap-2"><span class="text-leaf">✓</span> Responsif di HP & komputer</li>
                    </ul>
                </div>

                <!-- F3 -->
                <div class="bg-white/5 border border-white/10 rounded-2xl p-6 space-y-4 hover:bg-white/10 transition">
                    <div class="w-12 h-12 rounded-xl bg-terra flex items-center justify-center text-xl">📈</div>
                    <h3 class="font-serif font-bold text-lg text-white">Analisis Hasil Riset</h3>
                    <p class="text-sm text-neutral-muted leading-relaxed">
                        Dashboard analitik visual dengan chart interaktif, KPI pasar, indeks minat, dan estimasi harga (WTP) otomatis.
                    </p>
                    <ul class="space-y-1.5 text-xs text-neutral-muted">
                        <li class="flex items-center gap-2"><span class="text-terra">✓</span> Chart donut & bar per pertanyaan</li>
                        <li class="flex items-center gap-2"><span class="text-terra">✓</span> Market Demand Index</li>
                        <li class="flex items-center gap-2"><span class="text-terra">✓</span> Ekspor data ke Excel (.xlsx)</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- ── CTA Footer ────────────────────────────────────────────────────── -->
    <section class="max-w-6xl mx-auto px-4 sm:px-6 py-16 text-center space-y-6">
        <h2 class="font-serif font-bold text-3xl text-neutral-dark">Siap Memulai Riset Pasar?</h2>
        <p class="text-neutral-muted max-w-md mx-auto">Masuk sebagai admin/guru untuk membuat survey riset pasar pertama Anda.</p>
        <a href="{{ route('admin.login') }}"
           class="inline-flex items-center gap-2 px-8 py-4 bg-mango hover:bg-mango-dark text-neutral-dark font-bold text-sm rounded-xl transition shadow-md hover:shadow-lg">
            Login ke Dashboard Admin
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
        </a>
    </section>

    <!-- Footer -->
    <footer class="border-t border-neutral-border py-6 text-center text-xs text-neutral-muted">
        <p>Survey Riset Pasar Kewirausahaan &middot; SMKN 2 Indramayu &middot; {{ date('Y') }}</p>
    </footer>

</body>
</html>
