@extends('layouts.admin')
@section('title', 'Profil & Keamanan — Survey Riset Pasar SMKN 2 Indramayu')

@section('content')
<div class="max-w-xl mx-auto space-y-6 pb-8">

    <div class="bg-white rounded-2xl border border-neutral-border p-5 sm:p-6">
        <div class="text-[10px] font-sans uppercase tracking-widest text-terra font-semibold mb-1">AKUN ADMIN</div>
        <h1 class="font-serif font-bold text-2xl text-neutral-dark">Profil &amp; Keamanan</h1>
        <p class="text-xs text-neutral-muted mt-1">Perbarui nama, email, dan kata sandi akun pengelola survey.</p>
    </div>

    @if(session('success'))
        <div class="p-4 rounded-xl bg-leaf-tint border border-leaf/30 text-leaf-dark text-xs font-semibold flex items-center gap-2">
            <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="p-4 rounded-xl bg-terra-tint border border-terra/30 text-terra-dark text-xs font-semibold flex items-center gap-2">
            <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ $errors->first() }}
        </div>
    @endif

    <form action="{{ route('admin.profile.update') }}" method="POST"
          class="bg-white rounded-2xl border border-neutral-border p-5 sm:p-6 space-y-5">
        @csrf

        <!-- Avatar Placeholder -->
        <div class="flex items-center gap-4 pb-4 border-b border-neutral-border">
            <div class="w-16 h-16 rounded-2xl bg-leaf flex items-center justify-center font-serif font-bold text-2xl text-mango shadow">
                {{ Str::upper(Str::substr($user->name, 0, 1)) }}
            </div>
            <div>
                <div class="font-serif font-bold text-lg text-neutral-dark">{{ $user->name }}</div>
                <div class="text-xs text-neutral-muted">{{ $user->email }}</div>
            </div>
        </div>

        <!-- Name -->
        <div>
            <label for="name" class="block text-[10px] font-sans uppercase tracking-widest font-semibold text-neutral-muted mb-1.5">
                Nama Lengkap
            </label>
            <input id="name" type="text" name="name" required
                   value="{{ old('name', $user->name) }}"
                   class="w-full px-4 py-3 border border-neutral-border rounded-xl text-sm font-semibold text-neutral-dark
                          bg-neutral-bg/50 focus:ring-2 focus:ring-mango focus:outline-none transition">
        </div>

        <!-- Email -->
        <div>
            <label for="email" class="block text-[10px] font-sans uppercase tracking-widest font-semibold text-neutral-muted mb-1.5">
                Email Admin
            </label>
            <input id="email" type="email" name="email" required
                   value="{{ old('email', $user->email) }}"
                   class="w-full px-4 py-3 border border-neutral-border rounded-xl text-sm font-semibold text-neutral-dark
                          bg-neutral-bg/50 focus:ring-2 focus:ring-mango focus:outline-none transition">
        </div>

        <!-- Password Section -->
        <div class="pt-4 border-t border-neutral-border space-y-4">
            <div class="text-[10px] font-sans uppercase tracking-widest text-mango font-semibold">
                Ubah Kata Sandi (Kosongkan jika tidak diubah)
            </div>
            <div>
                <label for="password" class="block text-[10px] font-sans uppercase tracking-widest font-semibold text-neutral-muted mb-1.5">
                    Kata Sandi Baru
                </label>
                <input id="password" type="password" name="password" placeholder="••••••••"
                       class="w-full px-4 py-3 border border-neutral-border rounded-xl text-sm text-neutral-dark
                              bg-neutral-bg/50 focus:ring-2 focus:ring-mango focus:outline-none transition">
            </div>
            <div>
                <label for="password_confirmation" class="block text-[10px] font-sans uppercase tracking-widest font-semibold text-neutral-muted mb-1.5">
                    Konfirmasi Kata Sandi
                </label>
                <input id="password_confirmation" type="password" name="password_confirmation" placeholder="••••••••"
                       class="w-full px-4 py-3 border border-neutral-border rounded-xl text-sm text-neutral-dark
                              bg-neutral-bg/50 focus:ring-2 focus:ring-mango focus:outline-none transition">
            </div>
        </div>

        <div class="pt-2 flex justify-end">
            <button type="submit"
                    class="px-6 py-3 bg-mango hover:bg-mango-dark text-neutral-dark font-bold text-xs rounded-xl transition shadow-sm flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection
