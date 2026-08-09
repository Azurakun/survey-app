@extends('layouts.admin')
@section('title', 'Profil & Akun — Platform Survey Riset Pasar')

@section('content')
<div class="max-w-5xl mx-auto space-y-6 pb-8">

    <!-- ── Header Banner ───────────────────────────────────────────────── -->
    <div class="card p-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="text-[10px] font-sans uppercase tracking-widest font-bold mb-1" style="color:#C89D54;">PENGATURAN AKUN PENGELOLA</div>
            <h1 class="font-serif font-bold text-2xl md:text-3xl text-ink">Profil &amp; Akun Admin</h1>
            <p class="text-xs mt-1" style="color:#6E675F;">Kelola informasi profil diri, alamat email resmi, serta keamanan kata sandi akun Anda.</p>
        </div>
        <div class="px-3 py-1.5 rounded-lg border text-xs font-semibold shrink-0 flex items-center gap-2" style="background:#F8F4EC; border-color:#E5E0D8; color:#1C1917;">
            <span class="w-2 h-2 rounded-full" style="background:#C89D54;"></span>
            <span>Status Akun: Aktif</span>
        </div>
    </div>

    @if(session('success'))
        <div class="p-4 rounded-xl border text-xs font-semibold flex items-center gap-2.5 animate-pop-in"
             style="background:#EDFAF2; border-color:#A3D9BA; color:#1A5C38;">
            <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if($errors->any())
        <div class="p-4 rounded-xl border text-xs font-semibold flex items-start gap-2.5 animate-pop-in"
             style="background:#FDECEA; border-color:#F5B7B1; color:#922B21;">
            <svg class="w-4 h-4 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span>{{ $errors->first() }}</span>
        </div>
    @endif

    <!-- ── Balanced Split Panel Layout (4 cols : 8 cols) ───────────────── -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

        <!-- Left Panel: Profile Summary Card & Account Info -->
        <div class="lg:col-span-4 space-y-5">
            <div class="card p-6 text-center space-y-4">
                <div class="relative w-20 h-20 mx-auto rounded-2xl flex items-center justify-center font-serif font-bold text-3xl shadow-sm"
                     style="background:#1C1917; color:#C89D54;">
                    {{ Str::upper(Str::substr($user->name, 0, 1)) }}
                    <div class="absolute -bottom-1 -right-1 w-6 h-6 rounded-full border-2 border-white flex items-center justify-center text-[10px]" style="background:#C89D54; color:#1C1917;">
                        ✓
                    </div>
                </div>

                <div>
                    <h2 class="font-serif font-bold text-lg text-ink">{{ $user->name }}</h2>
                    <p class="text-xs mt-0.5" style="color:#6E675F;">{{ $user->email }}</p>
                </div>

                <div class="pt-3 border-t flex flex-wrap items-center justify-center gap-2" style="border-color:#EBE6DE;">
                    <span class="text-[10px] font-bold px-2.5 py-1 rounded-full uppercase tracking-wider border"
                          style="background:#F8F4EC; border-color:#E5E0D8; color:#C89D54;">
                        🔑 Administrator
                    </span>
                    <span class="text-[10px] font-bold px-2.5 py-1 rounded-full uppercase tracking-wider border"
                          style="background:#FAF8F5; border-color:#E5E0D8; color:#6E675F;">
                        Pengelola Platform
                    </span>
                </div>
            </div>

            <!-- Security Notice Card -->
            <div class="card p-5 space-y-3" style="background:#F8F4EC; border-color:#E5E0D8;">
                <div class="flex items-center gap-2 text-xs font-bold" style="color:#1C1917;">
                    <svg class="w-4 h-4" style="color:#C89D54;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    <span>Keamanan Akun</span>
                </div>
                <p class="text-[11px] leading-relaxed" style="color:#6E675F;">
                    Pastikan kata sandi Anda terdiri dari minimal 8 karakter dan mengombinasikan huruf serta angka untuk menjaga integritas data riset pasar.
                </p>
            </div>
        </div>

        <!-- Right Panel: Profile & Account Edit Form -->
        <div class="lg:col-span-8">
            <form action="{{ route('admin.profile.update') }}" method="POST" class="card p-6 space-y-6">
                @csrf

                <div>
                    <div class="text-[10px] font-bold uppercase tracking-widest mb-1" style="color:#C89D54;">INFORMASI PROFIL</div>
                    <h3 class="font-serif font-bold text-lg text-ink">Identitas Pengelola</h3>
                    <p class="text-xs mt-0.5" style="color:#6E675F;">Perbarui nama resmi dan alamat email akun admin Anda.</p>
                </div>

                <!-- Name Input -->
                <div>
                    <label for="name" class="block text-[10px] font-bold uppercase tracking-widest mb-1.5" style="color:#6E675F;">
                        Nama Lengkap Pengelola
                    </label>
                    <input id="name" type="text" name="name" required
                           value="{{ old('name', $user->name) }}"
                           placeholder="Contoh: Drs. Ahmad Fauzi, M.Pd."
                           class="w-full px-4 py-3 border rounded-xl text-sm font-medium text-ink transition focus:outline-none focus:ring-2"
                           style="background:#FAF8F5; border-color:#E5E0D8;">
                </div>

                <!-- Email Input -->
                <div>
                    <label for="email" class="block text-[10px] font-bold uppercase tracking-widest mb-1.5" style="color:#6E675F;">
                        Alamat Email Admin
                    </label>
                    <input id="email" type="email" name="email" required
                           value="{{ old('email', $user->email) }}"
                           placeholder="admin@smkn2indramayu.sch.id"
                           class="w-full px-4 py-3 border rounded-xl text-sm font-medium text-ink transition focus:outline-none focus:ring-2"
                           style="background:#FAF8F5; border-color:#E5E0D8;">
                </div>

                <!-- Password Section -->
                <div class="pt-5 border-t space-y-4" style="border-color:#EBE6DE;">
                    <div>
                        <div class="text-[10px] font-bold uppercase tracking-widest mb-1" style="color:#C89D54;">KEAMANAN AKUN</div>
                        <h3 class="font-serif font-bold text-lg text-ink">Ubah Kata Sandi</h3>
                        <p class="text-xs mt-0.5" style="color:#6E675F;">Biarkan kosong jika Anda tidak ingin mengubah kata sandi saat ini.</p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="password" class="block text-[10px] font-bold uppercase tracking-widest mb-1.5" style="color:#6E675F;">
                                Kata Sandi Baru
                            </label>
                            <input id="password" type="password" name="password" placeholder="••••••••"
                                   class="w-full px-4 py-3 border rounded-xl text-sm font-medium text-ink transition focus:outline-none focus:ring-2"
                                   style="background:#FAF8F5; border-color:#E5E0D8;">
                        </div>

                        <div>
                            <label for="password_confirmation" class="block text-[10px] font-bold uppercase tracking-widest mb-1.5" style="color:#6E675F;">
                                Konfirmasi Kata Sandi
                            </label>
                            <input id="password_confirmation" type="password" name="password_confirmation" placeholder="••••••••"
                                   class="w-full px-4 py-3 border rounded-xl text-sm font-medium text-ink transition focus:outline-none focus:ring-2"
                                   style="background:#FAF8F5; border-color:#E5E0D8;">
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="pt-4 border-t flex justify-end" style="border-color:#EBE6DE;">
                    <button type="submit"
                            class="px-6 py-3.5 text-xs font-bold rounded-xl transition-all shadow-sm active:scale-95 flex items-center gap-2"
                            style="background:#1C1917; color:#FFFFFF;"
                            onmouseover="this.style.background='#2C2723'"
                            onmouseout="this.style.background='#1C1917'">
                        <svg class="w-4 h-4" style="color:#C89D54;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span>Simpan Perubahan Profil &amp; Akun</span>
                    </button>
                </div>
            </form>
        </div>

    </div>
</div>
@endsection
