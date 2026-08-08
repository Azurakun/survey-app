<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin — Survey Riset Pasar SMKN 2 Indramayu')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Platform Survey Riset Pasar Kewirausahaan SMKN 2 Indramayu">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,600;0,9..144,700;0,9..144,800;1,9..144,600&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        // Accent — amber/gold (dipakai tipis, bukan dominan)
                        accent: {
                            DEFAULT: '#C9933A',
                            light:   '#E8B86D',
                            tint:    '#FBF4E8',
                        },
                        // Danger
                        danger: {
                            DEFAULT: '#C0392B',
                            tint:    '#FDECEA',
                        },
                        // Neutral greyscale berbasis krem
                        ink: {
                            DEFAULT: '#1A1714',   // teks utama
                            muted:   '#6B6458',   // teks sekunder
                            subtle:  '#A09488',   // placeholder
                        },
                        surface: {
                            DEFAULT: '#FFFFFF',   // card, sidebar
                            raised:  '#FFFFFF',   // modal, dropdown
                            base:    '#F5F2EC',   // background halaman
                            subtle:  '#EDE8DF',   // hover, alt row
                            border:  '#DDD7CC',   // border
                            divider: '#EAE5DC',   // divider halus
                        },
                        // Tetap ada untuk chart colors
                        chart: {
                            1: '#C9933A', 2: '#8B5E3C', 3: '#4A7A5E',
                            4: '#6B8FAB', 5: '#A0846A', 6: '#7A6E8A',
                        }
                    },
                    fontFamily: {
                        serif: ['Fraunces', 'Georgia', 'serif'],
                        sans:  ['Plus Jakarta Sans', 'system-ui', 'sans-serif'],
                    },
                }
            }
        }
    </script>

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js"></script>

    <style>
        * { box-sizing: border-box; }
        body {
            font-family: 'Plus Jakarta Sans', system-ui, sans-serif;
            background-color: #F5F2EC;
            color: #1A1714;
        }
        .font-serif { font-family: 'Fraunces', Georgia, serif !important; }

        /* Scrollbar */
        ::-webkit-scrollbar { width: 5px; height: 5px; }
        ::-webkit-scrollbar-track { background: #EDE8DF; }
        ::-webkit-scrollbar-thumb { background: #C9C0B0; border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: #C9933A; }

        /* Sidebar active state */
        .nav-item {
            display: flex; align-items: center; gap: 10px;
            padding: 9px 12px; border-radius: 8px;
            font-size: 12px; font-weight: 600;
            color: #6B6458; transition: all 0.15s ease;
            text-decoration: none;
        }
        .nav-item:hover { background: #EDE8DF; color: #1A1714; }
        .nav-item.active {
            background: #FBF4E8;
            color: #1A1714;
            border-left: 3px solid #C9933A;
            padding-left: 9px;
        }
        .nav-item.active svg { color: #C9933A; }

        /* Animations */
        @keyframes popIn {
            0%   { transform: scale(0.93) translateY(8px); opacity: 0; }
            65%  { transform: scale(1.02); opacity: 1; }
            100% { transform: scale(1); opacity: 1; }
        }
        @keyframes fadeInUp {
            0%   { transform: translateY(14px); opacity: 0; }
            100% { transform: translateY(0); opacity: 1; }
        }
        @keyframes stagger1 { 0%,8%  { opacity:0; transform:translateY(18px);} 100%{opacity:1;transform:translateY(0);} }
        @keyframes stagger2 { 0%,16% { opacity:0; transform:translateY(18px);} 100%{opacity:1;transform:translateY(0);} }
        @keyframes stagger3 { 0%,24% { opacity:0; transform:translateY(18px);} 100%{opacity:1;transform:translateY(0);} }
        @keyframes stagger4 { 0%,32% { opacity:0; transform:translateY(18px);} 100%{opacity:1;transform:translateY(0);} }

        .animate-pop-in  { animation: popIn 0.35s cubic-bezier(0.34,1.56,0.64,1) forwards; }
        .animate-fade-up { animation: fadeInUp 0.4s ease-out forwards; }
        .kpi-card-1 { animation: stagger1 0.65s ease-out both; }
        .kpi-card-2 { animation: stagger2 0.75s ease-out both; }
        .kpi-card-3 { animation: stagger3 0.85s ease-out both; }
        .kpi-card-4 { animation: stagger4 0.95s ease-out both; }

        /* Sidebar */
        .sidebar {
            width: 240px; min-width: 240px;
            background: #FFFFFF;
            border-right: 1px solid #DDD7CC;
            display: flex; flex-direction: column;
            height: 100vh; position: sticky; top: 0;
            overflow: hidden;
        }

        /* Page card */
        .card {
            background: #FFFFFF;
            border: 1px solid #DDD7CC;
            border-radius: 12px;
        }
    </style>

    @yield('head')
</head>
<body class="min-h-screen antialiased" style="background:#F5F2EC;">
<div x-data="{ sidebarOpen: false }" class="min-h-screen flex">

    <!-- ── Mobile Overlay ─────────────────────────────────────────────── -->
    <div x-show="sidebarOpen"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="sidebarOpen = false"
         class="fixed inset-0 bg-black/30 z-40 lg:hidden"
         style="display:none;">
    </div>

    <!-- ── Sidebar ────────────────────────────────────────────────────── -->
    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
           class="sidebar fixed top-0 bottom-0 left-0 z-50 transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:z-auto">

        <!-- Logo / Branding -->
        <div class="px-5 py-4 border-b border-surface-divider flex items-center gap-3 shrink-0">
            <div class="w-9 h-9 rounded-lg flex items-center justify-center shrink-0"
                 style="background:#1A1714;">
                <span class="font-serif font-bold text-xs" style="color:#C9933A;">SMK</span>
            </div>
            <div class="min-w-0">
                <div class="text-[9px] font-sans uppercase tracking-widest font-bold truncate" style="color:#A09488;">SMKN 2 INDRAMAYU</div>
                <div class="font-serif text-sm font-bold leading-tight text-ink">Riset Pasar</div>
            </div>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 px-3 py-4 space-y-0.5 overflow-y-auto">
            <p class="text-[9px] font-sans uppercase tracking-widest font-bold px-3 pt-1 pb-2" style="color:#A09488;">MENU UTAMA</p>

            <a href="{{ route('admin.dashboard') }}"
               class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                <span>Dashboard</span>
            </a>

            <a href="{{ route('admin.surveys.index') }}"
               class="nav-item {{ request()->routeIs('admin.surveys.*') ? 'active' : '' }}">
                <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                <span>Manajemen Survey</span>
            </a>

            <a href="{{ route('admin.surveys.create') }}"
               class="nav-item">
                <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 4v16m8-8H4"/></svg>
                <span>Buat Survey Baru</span>
            </a>
        </nav>

        <!-- Bottom Section: Profil + Logout -->
        <div class="px-3 pb-4 shrink-0 space-y-1 border-t border-surface-divider pt-3">

            <!-- User Identity -->
            <div class="px-3 py-2.5 rounded-lg mb-1" style="background:#F5F2EC;">
                <div class="text-[11px] font-bold truncate text-ink">{{ Auth::user()->name ?? 'Guru Pengelola' }}</div>
                <div class="text-[10px] truncate" style="color:#A09488;">{{ Auth::user()->email ?? '' }}</div>
            </div>

            <!-- Profil & Akun (di atas tombol Keluar) -->
            <a href="{{ route('admin.profile.show') }}"
               class="nav-item {{ request()->routeIs('admin.profile.*') ? 'active' : '' }}">
                <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                <span>Profil &amp; Akun</span>
            </a>

            <!-- Logout -->
            <form action="{{ route('admin.logout') }}" method="POST">
                @csrf
                <button type="submit"
                        class="w-full flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs font-semibold transition-all
                               text-danger hover:bg-danger-tint"
                        style="color:#C0392B;">
                    <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    <span>Keluar</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- ── Main Content ───────────────────────────────────────────────── -->
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">

        <!-- Mobile Top Bar -->
        <header class="lg:hidden px-4 py-3 flex items-center justify-between sticky top-0 z-30 bg-white border-b border-surface-border">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center font-serif font-bold text-xs"
                     style="background:#1A1714; color:#C9933A;">
                    SMK
                </div>
                <span class="font-serif font-bold text-sm text-ink">Riset Pasar SMKN 2</span>
            </div>
            <button @click="sidebarOpen = !sidebarOpen"
                    class="p-2 rounded-lg hover:bg-surface-subtle transition text-ink-muted">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
        </header>

        <!-- Page Content -->
        <main class="flex-1 p-5 sm:p-7 overflow-auto">

            @if(session('success'))
                <div class="mb-5 p-3.5 rounded-xl border text-xs font-semibold flex items-center gap-2.5 animate-pop-in"
                     style="background:#EDFAF2; border-color:#A3D9BA; color:#1A5C38;">
                    <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-5 p-3.5 rounded-xl border text-xs font-semibold flex items-center gap-2.5 animate-pop-in"
                     style="background:#FDECEA; border-color:#F5B7B1; color:#922B21;">
                    <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @yield('content')
        </main>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => lucide.createIcons());
</script>
@yield('scripts')
</body>
</html>
