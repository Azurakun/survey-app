<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin — Platform Survey Riset Pasar</title>
    <meta name="description" content="Platform Survey Riset Pasar Kewirausahaan">
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
                    surface: { DEFAULT:'#FFFFFF', base:'#FAF8F5', border:'#E5E0D8' },
                },
                fontFamily: {
                    sans: ['Plus Jakarta Sans','Inter','system-ui','sans-serif'],
                }
            }}
        }
    </script>
    <style>
        body { font-family:'Plus Jakarta Sans','Inter',system-ui,sans-serif; background-color:#FAF8F5; color:#1C1917; }
        
        .bg-dots-pattern {
            background-image: radial-gradient(#E5E0D8 1.5px, transparent 1.5px);
            background-size: 32px 32px;
        }

        @keyframes fadeUp { from{opacity:0;transform:translateY(16px)} to{opacity:1;transform:translateY(0)} }
        .animate-fade-up { animation:fadeUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    </style>
</head>
<body class="min-h-screen bg-dots-pattern flex items-center justify-center p-4 sm:p-6 md:p-10 relative overflow-hidden">

    <!-- Ambient Subtle Background Glow -->
    <div class="absolute -top-32 -left-32 w-[600px] h-[600px] rounded-full opacity-40 blur-3xl pointer-events-none" style="background:#FAF6EE;"></div>
    <div class="absolute -bottom-32 -right-32 w-[600px] h-[600px] rounded-full opacity-40 blur-3xl pointer-events-none" style="background:#FAF6EE;"></div>

    <!-- Main Container: Clean 2 Column Split (Left Simple Text, Right Clean White Card) -->
    <div class="max-w-5xl w-full grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-center relative z-10">

        <!-- ── LEFT SIDE: Simple Welcome Text ────────────────────────────── -->
        <div class="lg:col-span-6 space-y-6 text-left animate-fade-up">
            <!-- Branding Badge -->
            <div class="inline-flex items-center gap-2.5 px-4 py-2 rounded-full text-xs font-bold border"
                 style="background:#FFFFFF; border-color:#E5E0D8; color:#C89D54;">
                <div class="w-6 h-6 rounded-lg flex items-center justify-center text-[10px] font-bold" style="background:#1C1917; color:#C89D54;">RP</div>
                <span>PLATFORM SURVEY RISET PASAR</span>
            </div>

            <!-- Big Simple Title -->
            <h1 class="font-bold text-4xl sm:text-5xl lg:text-6xl text-ink tracking-tight leading-[1.1]">
                Selamat Datang!
            </h1>

            <p class="text-sm sm:text-base leading-relaxed max-w-md" style="color:#6E675F;">
                Platform survey terintegrasi untuk merevolusi cara riset pasar produk kewirausahaan siswa diajarkan dan dipraktikkan di sekolah.
            </p>

            <!-- Simple Checkmarks -->
            <div class="flex items-center gap-6 pt-2 text-xs font-bold text-ink">
                <span class="flex items-center gap-1.5">
                    <span style="color:#C89D54;">✓</span> Mudah Digunakan
                </span>
                <span class="flex items-center gap-1.5">
                    <span style="color:#C89D54;">✓</span> Realtime Analytics
                </span>
                <span class="flex items-center gap-1.5">
                    <span style="color:#C89D54;">✓</span> Validasi NISN
                </span>
            </div>
        </div>

        <!-- ── RIGHT SIDE: Clean White Login Card ────────────────────────── -->
        <div class="lg:col-span-6 flex justify-center lg:justify-end animate-fade-up">
            <div class="bg-white rounded-3xl border shadow-xl p-8 sm:p-10 w-full max-w-md space-y-6"
                 style="background:#FFFFFF; border-color:#E5E0D8;">

                <!-- Header / Logo -->
                <div class="text-center space-y-2">
                    <div class="w-12 h-12 rounded-2xl mx-auto flex items-center justify-center text-xs font-bold shadow-2xs"
                         style="background:#1C1917; color:#C89D54;">
                        RP
                    </div>
                    <h2 class="font-bold text-xl text-ink">Login Dashboard</h2>
                    <p class="text-xs" style="color:#6E675F;">Masukkan kredensial Anda untuk mengakses dashboard</p>
                </div>

                <!-- Error Alert -->
                @if ($errors->any())
                    <div class="p-3.5 rounded-2xl border text-xs font-semibold flex items-start gap-2.5 animate-pop-in"
                         style="background:#FDECEA; border-color:#F5B7B1; color:#922B21;">
                        <svg class="w-4 h-4 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>{{ $errors->first() }}</span>
                    </div>
                @endif

                <!-- Login Form -->
                <form method="POST" action="{{ route('admin.login.post') }}" class="space-y-4">
                    @csrf

                    <div>
                        <label for="email" class="block text-[10px] font-bold uppercase tracking-widest mb-1.5" style="color:#6E675F;">
                            Email Admin
                        </label>
                        <input id="email" type="email" name="email"
                               value="{{ old('email') }}"
                               required autocomplete="email" autofocus
                               placeholder="admin@domain.sch.id"
                               class="w-full px-4 py-3.5 rounded-2xl border text-xs font-medium text-ink transition focus:outline-none focus:ring-2"
                               style="background:#FAF8F5; border-color:#E5E0D8;">
                    </div>

                    <div>
                        <label for="password" class="block text-[10px] font-bold uppercase tracking-widest mb-1.5" style="color:#6E675F;">
                            Kata Sandi
                        </label>
                        <input id="password" type="password" name="password"
                               required autocomplete="current-password"
                               placeholder="Masukkan Kata Sandi"
                               class="w-full px-4 py-3.5 rounded-2xl border text-xs font-medium text-ink transition focus:outline-none focus:ring-2"
                               style="background:#FAF8F5; border-color:#E5E0D8;">
                    </div>

                    <div class="flex items-center justify-between text-xs pt-1">
                        <label for="remember" class="flex items-center gap-2 cursor-pointer select-none font-medium" style="color:#6E675F;">
                            <input id="remember" type="checkbox" name="remember"
                                   class="w-4 h-4 rounded cursor-pointer border"
                                   style="accent-color:#C89D54; border-color:#E5E0D8;">
                            <span>Ingat Saya</span>
                        </label>
                    </div>

                    <button type="submit"
                            class="w-full py-4 text-xs font-bold rounded-2xl transition shadow-md hover:shadow-lg flex items-center justify-center gap-2"
                            style="background:#1C1917; color:#FFFFFF;"
                            onmouseover="this.style.background='#2C2723'"
                            onmouseout="this.style.background='#1C1917'">
                        <span>Masuk</span>
                        <svg class="w-4 h-4" style="color:#C89D54;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </button>
                </form>

                <!-- Footer Note -->
                <div class="text-center text-[11px] pt-2" style="color:#A19A91;">
                    Platform Survey Riset Pasar &middot; {{ date('Y') }}
                </div>

            </div>
        </div>

    </div>

</body>
</html>
