<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin — Survey Riset Pasar SMKN 2 Indramayu</title>
    <meta name="description" content="Login untuk pengelola/guru Survey Riset Pasar Kewirausahaan SMKN 2 Indramayu">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,600;0,9..144,700;0,9..144,800;1,9..144,700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: { extend: {
                colors: {
                    accent:  { DEFAULT:'#C9933A', light:'#E8B86D', tint:'#FBF4E8' },
                    danger:  { DEFAULT:'#C0392B', tint:'#FDECEA' },
                    ink:     { DEFAULT:'#1A1714', muted:'#6B6458', subtle:'#A09488' },
                    surface: { DEFAULT:'#FFFFFF', base:'#F5F2EC', subtle:'#EDE8DF', border:'#DDD7CC', divider:'#EAE5DC' },
                },
                fontFamily: {
                    serif: ['Fraunces','Georgia','serif'],
                    sans:  ['Plus Jakarta Sans','system-ui','sans-serif'],
                }
            }}
        }
    </script>
    <style>
        body { font-family:'Plus Jakarta Sans',system-ui,sans-serif; background:#F5F2EC; color:#1A1714; }
        .font-serif { font-family:'Fraunces',Georgia,serif !important; }
        @keyframes fadeUp { from{opacity:0;transform:translateY(20px)} to{opacity:1;transform:translateY(0)} }
        @keyframes fadeLeft { from{opacity:0;transform:translateX(-20px)} to{opacity:1;transform:translateX(0)} }
        .animate-fade-up   { animation:fadeUp  0.55s ease-out forwards; }
        .animate-fade-left { animation:fadeLeft 0.55s ease-out forwards; }
        .d1 { animation-delay:0.05s; opacity:0; }
        .d2 { animation-delay:0.15s; opacity:0; }
        .d3 { animation-delay:0.25s; opacity:0; }
        .d4 { animation-delay:0.35s; opacity:0; }

        .feature-item { display:flex; align-items:flex-start; gap:12px; padding:14px 0; border-bottom:1px solid rgba(255,255,255,0.1); }
        .feature-item:last-child { border-bottom: none; }
    </style>
</head>
<body class="min-h-screen flex items-stretch">

    {{-- ── SPLIT LAYOUT ─────────────────────────────────────────────────── --}}
    <div class="flex w-full min-h-screen">

        {{-- ── LEFT PANEL: Branding & Feature Highlights ──────────────────── --}}
        <div class="hidden lg:flex lg:w-[54%] xl:w-[58%] flex-col justify-between p-12 relative overflow-hidden"
             style="background:#1A1714;">

            {{-- Background texture --}}
            <div class="absolute inset-0 pointer-events-none opacity-5"
                 style="background-image:radial-gradient(circle at 20% 80%, #C9933A 0%, transparent 60%), radial-gradient(circle at 80% 20%, #A09488 0%, transparent 60%);"></div>
            <div class="absolute bottom-0 right-0 w-72 h-72 rounded-full pointer-events-none"
                 style="background:radial-gradient(circle, #C9933A22 0%, transparent 70%); transform:translate(30%,30%);"></div>

            {{-- Top: Logo + Nav label --}}
            <div class="relative z-10 animate-fade-left d1">
                <div class="flex items-center gap-3 mb-10">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center font-serif font-bold text-sm"
                         style="background:#C9933A; color:#1A1714;">
                        SMK
                    </div>
                    <div>
                        <div class="text-[9px] font-sans font-bold tracking-widest uppercase" style="color:#A09488;">SMKN 2 INDRAMAYU</div>
                        <div class="font-serif text-sm font-bold text-white leading-none">Kewirausahaan</div>
                    </div>
                </div>

                {{-- Headline --}}
                <div class="mb-10">
                    <div class="text-[10px] font-sans font-bold tracking-widest uppercase mb-3" style="color:#C9933A;">PLATFORM RISET PASAR</div>
                    <h1 class="font-serif font-bold text-4xl xl:text-5xl leading-[1.15] text-white mb-4">
                        Kelola Survey<br>Riset Pasar<br><em style="color:#C9933A;">Siswa Anda</em>
                    </h1>
                    <p class="text-sm leading-relaxed max-w-md" style="color:#A09488;">
                        Satu platform untuk membuat survey, mengumpulkan data riset pasar dari siswa,
                        dan menganalisis hasilnya secara visual — dirancang khusus untuk program Kewirausahaan SMKN 2 Indramayu.
                    </p>
                </div>

                {{-- Feature List --}}
                <div class="space-y-0">
                    @foreach([
                        ['📋', 'Survey Builder Dinamis', '8 tipe pertanyaan, live preview, reorder bebas'],
                        ['👥', 'Validasi NISN Otomatis', 'Mencegah duplikasi data responden siswa'],
                        ['📊', 'Analitik Visual Real-time', 'Chart.js: donut, bar, KPI pasar, skor WTP'],
                        ['📥', 'Ekspor Data Excel', 'Download satu lembar data mentah siap analisis'],
                    ] as [$icon, $title, $desc])
                    <div class="feature-item animate-fade-left d2">
                        <div class="w-9 h-9 rounded-lg flex items-center justify-center text-base shrink-0"
                             style="background:rgba(201,147,58,0.15);">{{ $icon }}</div>
                        <div>
                            <div class="text-xs font-bold text-white">{{ $title }}</div>
                            <div class="text-xs mt-0.5" style="color:#7A7066;">{{ $desc }}</div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Bottom: testimonial / stats --}}
            <div class="relative z-10 animate-fade-left d4">
                <div class="flex items-center gap-6 pt-8 border-t" style="border-color:rgba(255,255,255,0.1);">
                    @foreach(['8' => 'Tipe Pertanyaan', '∞' => 'Responden', '100%' => 'Berbasis Web'] as $val => $label)
                    <div>
                        <div class="font-serif text-2xl font-bold" style="color:#C9933A;">{{ $val }}</div>
                        <div class="text-[10px]" style="color:#7A7066;">{{ $label }}</div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- ── RIGHT PANEL: Login Form ─────────────────────────────────────── --}}
        <div class="flex-1 flex flex-col items-center justify-center p-6 sm:p-10 lg:p-14"
             style="background:#F5F2EC;">

            {{-- Mobile branding (lg: hidden) --}}
            <div class="lg:hidden flex items-center gap-3 mb-8 self-start">
                <div class="w-9 h-9 rounded-lg flex items-center justify-center font-serif font-bold text-sm"
                     style="background:#1A1714; color:#C9933A;">SMK</div>
                <div>
                    <div class="text-[9px] font-bold tracking-widest uppercase" style="color:#A09488;">SMKN 2 INDRAMAYU</div>
                    <div class="font-serif text-sm font-bold text-ink leading-none">Riset Pasar Kewirausahaan</div>
                </div>
            </div>

            <div class="w-full max-w-sm animate-fade-up d1">

                {{-- Form Header --}}
                <div class="mb-8">
                    <div class="text-[10px] font-sans font-bold tracking-widest uppercase mb-2" style="color:#C9933A;">
                        AKSES ADMIN
                    </div>
                    <h2 class="font-serif font-bold text-2xl xl:text-3xl text-ink leading-tight">
                        Selamat Datang,<br>Guru / Pengelola
                    </h2>
                    <p class="text-xs mt-2" style="color:#A09488;">
                        Masukkan kredensial akun admin Anda untuk melanjutkan.
                    </p>
                </div>

                {{-- Error Alert --}}
                @if ($errors->any())
                    <div class="mb-5 p-3.5 rounded-xl border text-xs font-semibold flex items-start gap-2.5"
                         style="background:#FDECEA; border-color:#F5B7B1; color:#922B21;">
                        <svg class="w-4 h-4 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>{{ $errors->first() }}</span>
                    </div>
                @endif

                {{-- Login Form --}}
                <form method="POST" action="{{ route('admin.login.post') }}" class="space-y-4">
                    @csrf

                    <div>
                        <label for="email" class="block text-[10px] font-bold uppercase tracking-widest mb-1.5" style="color:#6B6458;">
                            Email Admin
                        </label>
                        <input id="email" type="email" name="email"
                               value="{{ old('email') }}"
                               required autocomplete="email" autofocus
                               placeholder="admin@smkn2indramayu.sch.id"
                               class="w-full px-4 py-3 rounded-xl border text-sm text-ink font-medium
                                      placeholder-ink-subtle transition focus:outline-none focus:ring-2"
                               style="background:#FFFFFF; border-color:#DDD7CC; focus-ring-color:#C9933A;">
                    </div>

                    <div>
                        <label for="password" class="block text-[10px] font-bold uppercase tracking-widest mb-1.5" style="color:#6B6458;">
                            Kata Sandi
                        </label>
                        <input id="password" type="password" name="password"
                               required autocomplete="current-password"
                               placeholder="••••••••"
                               class="w-full px-4 py-3 rounded-xl border text-sm text-ink font-medium
                                      placeholder-ink-subtle transition focus:outline-none focus:ring-2"
                               style="background:#FFFFFF; border-color:#DDD7CC;">
                    </div>

                    <div class="flex items-center gap-2.5 pt-1">
                        <input id="remember" type="checkbox" name="remember"
                               class="w-4 h-4 rounded cursor-pointer"
                               style="accent-color:#C9933A;">
                        <label for="remember" class="text-xs cursor-pointer select-none" style="color:#6B6458;">
                            Ingat saya di perangkat ini
                        </label>
                    </div>

                    <button type="submit"
                            class="w-full py-3.5 text-sm font-bold rounded-xl transition-all duration-200 shadow-sm
                                   active:scale-[0.98] hover:shadow-md mt-2"
                            style="background:#1A1714; color:#FFFFFF;"
                            onmouseover="this.style.background='#2B2118'"
                            onmouseout="this.style.background='#1A1714'">
                        Masuk ke Dashboard Admin
                    </button>
                </form>

                {{-- Footer --}}
                <div class="mt-8 pt-6 border-t" style="border-color:#DDD7CC;">
                    <p class="text-center text-xs" style="color:#A09488;">
                        Survey Riset Pasar · Kewirausahaan SMKN 2 Indramayu · {{ date('Y') }}
                    </p>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
