<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin — Survey Riset Pasar')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Platform Survey Riset Pasar Kewirausahaan">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        accent: {
                            DEFAULT: '#C89D54',
                            light:   '#D9AB55',
                            tint:    '#F8F4EC',
                        },
                        danger: {
                            DEFAULT: '#C0392B',
                            tint:    '#FDECEA',
                        },
                        ink: {
                            DEFAULT: '#1C1917',
                            muted:   '#6E675F',
                            subtle:  '#A19A91',
                        },
                        surface: {
                            DEFAULT: '#FFFFFF',
                            raised:  '#FFFFFF',
                            base:    '#FAF8F5',
                            subtle:  '#F4F0E8',
                            border:  '#E5E0D8',
                            divider: '#EBE6DE',
                        },
                        chart: {
                            1: '#C89D54', 2: '#A37A3E', 3: '#786858',
                            4: '#5B86A6', 5: '#938474', 6: '#685D75',
                        }
                    },
                    fontFamily: {
                        serif: ['Plus Jakarta Sans', 'Inter', 'system-ui', 'sans-serif'],
                        sans:  ['Plus Jakarta Sans', 'Inter', 'system-ui', 'sans-serif'],
                    },
                }
            }
        }
    </script>

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }
        * { box-sizing: border-box; }
        body {
            font-family: 'Plus Jakarta Sans', 'Inter', system-ui, sans-serif;
            background-color: #FAF8F5;
            color: #1C1917;
            margin: 0;
            padding: 0;
        }
        .font-serif {
            font-family: 'Plus Jakarta Sans', 'Inter', system-ui, sans-serif !important;
            letter-spacing: -0.02em;
        }

        /* Scrollbar */
        ::-webkit-scrollbar { width: 5px; height: 5px; }
        ::-webkit-scrollbar-track { background: #F4F0E8; }
        ::-webkit-scrollbar-thumb { background: #D5CEC4; border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: #C89D54; }

        /* Sidebar active state */
        .nav-item {
            display: flex; align-items: center; gap: 10px;
            padding: 9px 12px; border-radius: 8px;
            font-size: 12px; font-weight: 600;
            color: #6E675F; transition: all 0.15s ease;
            text-decoration: none;
        }
        .nav-item:hover { background: #F4F0E8; color: #1C1917; }
        .nav-item.active {
            background: #F8F4EC;
            color: #1C1917;
            border-left: 3px solid #C89D54;
            padding-left: 9px;
        }
        .nav-item.active svg { color: #C89D54; }

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
            width: 250px; min-width: 250px;
            background: #FFFFFF;
            border-right: 1px solid #E5E0D8;
            display: flex; flex-direction: column;
            height: 100vh; position: sticky; top: 0;
            overflow: hidden;
        }

        /* Page card */
        .card {
            background: #FFFFFF;
            border: 1px solid #E5E0D8;
            border-radius: 12px;
        }

        /* Profile Menu Item Active State */
        .profile-menu-card {
            display: flex; align-items: center; gap: 10px;
            padding: 10px 12px; border-radius: 10px;
            background: #FAF8F5; border: 1px solid #E5E0D8;
            transition: all 0.2s ease; text-decoration: none;
        }
        .profile-menu-card:hover {
            background: #F8F4EC; border-color: #C89D54;
            transform: translateY(-1px);
        }
        .profile-menu-card.active {
            background: #F8F4EC; border-color: #C89D54;
            box-shadow: 0 2px 4px rgba(200, 157, 84, 0.1);
        }
    </style>

    @yield('head')
</head>
<body class="min-h-screen antialiased" style="background:#FAF8F5;">
<div x-data="{ sidebarOpen: false }" class="min-h-screen flex items-start">

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
           class="sidebar fixed top-0 bottom-0 left-0 z-50 transition-transform duration-300 ease-in-out lg:translate-x-0 lg:sticky lg:top-0 lg:h-screen lg:z-30 shrink-0">

        <!-- Logo / Branding -->
        <div class="px-5 py-4 border-b border-surface-divider flex items-center gap-3 shrink-0">
            <div class="w-9 h-9 rounded-lg flex items-center justify-center shrink-0"
                 style="background:#1C1917;">
                <span class="font-serif font-bold text-xs" style="color:#C89D54;">RP</span>
            </div>
            <div class="min-w-0">
                <div class="text-[9px] font-sans uppercase tracking-widest font-bold truncate" style="color:#A19A91;">PLATFORM SURVEY</div>
                <div class="font-serif text-sm font-bold leading-tight text-ink">Riset Pasar</div>
            </div>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 px-3 py-4 space-y-0.5 overflow-y-auto">
            <p class="text-[9px] font-sans uppercase tracking-widest font-bold px-3 pt-1 pb-2" style="color:#A19A91;">MENU UTAMA</p>

            <a href="{{ route('admin.dashboard') }}"
               class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                <span>Dashboard</span>
            </a>

            <a href="{{ route('admin.surveys.index') }}"
               class="nav-item {{ (request()->routeIs('admin.surveys.*') && !request()->routeIs('admin.surveys.analytics')) ? 'active' : '' }}">
                <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                <span>Manajemen Survey</span>
            </a>

            <a href="{{ route('admin.ai-analytics.index') }}"
               class="nav-item {{ (request()->routeIs('admin.ai-analytics.*') || request()->routeIs('admin.surveys.analytics')) ? 'active' : '' }}">
                <svg class="w-4 h-4 shrink-0" style="color:#C89D54;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                <span>Analisa AI Survey</span>
                <span class="ml-auto text-[9px] font-bold px-1.5 py-0.5 rounded-md border" style="background:#FAF6EE; border-color:#E5E0D8; color:#C89D54;">AI</span>
            </a>

            <a href="{{ route('admin.document-analytics.index') }}"
               class="nav-item {{ request()->routeIs('admin.document-analytics.*') ? 'active' : '' }}">
                <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                <span>Analisa Dokumen Bisnis</span>
                <span class="ml-auto text-[9px] font-bold px-1.5 py-0.5 rounded-md border" style="background:#FFFBEB; border-color:#FDE68A; color:#92400E;" title="Fitur Dalam Tahap Pengembangan">DEV</span>
            </a>
        </nav>

        <!-- Bottom Section: Profil & Akun Menu (Menyatukan info akun + link profil tepat diatas tombol Keluar) -->
        <div class="px-3 pb-4 shrink-0 space-y-2 border-t border-surface-divider pt-3">
            <p class="text-[9px] font-sans uppercase tracking-widest font-bold px-1 text-ink-subtle" style="color:#A19A91;">AKUN PENGELOLA</p>

            <!-- Unified Profile & Account Menu Card -->
            <a href="{{ route('admin.profile.show') }}"
               class="profile-menu-card {{ request()->routeIs('admin.profile.*') ? 'active' : '' }}">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center font-serif font-bold text-xs shrink-0"
                     style="background:#1C1917; color:#C89D54;">
                    {{ Str::upper(Str::substr(Auth::user()->name ?? 'A', 0, 1)) }}
                </div>
                <div class="min-w-0 flex-1">
                    <div class="text-[11px] font-bold truncate text-ink">{{ Auth::user()->name ?? 'Guru Pengelola' }}</div>
                    <div class="text-[10px] truncate" style="color:#A19A91;">{{ Auth::user()->email ?? 'admin@domain.sch.id' }}</div>
                </div>
                <svg class="w-4 h-4 shrink-0 transition-transform group-hover:translate-x-0.5" style="color:#C89D54;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>

            <!-- Logout Button -->
            <form action="{{ route('admin.logout') }}" method="POST">
                @csrf
                <button type="submit"
                        class="w-full flex items-center justify-center gap-2 px-3 py-2 rounded-lg text-xs font-semibold transition-all border border-danger/20 hover:bg-danger-tint"
                        style="color:#C0392B; background:#FAF8F5;">
                    <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    <span>Keluar dari Akun</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- ── Main Content ───────────────────────────────────────────────── -->
    <div class="flex-1 flex flex-col min-w-0">

        <!-- Mobile Top Bar -->
        <header class="lg:hidden px-4 py-3 flex items-center justify-between sticky top-0 z-30 bg-white border-b border-surface-border">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center font-serif font-bold text-xs"
                     style="background:#1C1917; color:#C89D54;">
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
        <main class="flex-1 p-5 sm:p-7">

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
