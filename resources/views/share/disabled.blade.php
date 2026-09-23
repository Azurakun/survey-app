<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tautan Tidak Aktif — {{ $survey->judul ?? 'Survey Riset Pasar' }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #FAF8F5;
            color: #1C1917;
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4 sm:p-6">
    <div class="max-w-md w-full bg-white border border-stone-200 rounded-3xl p-8 text-center space-y-6 shadow-xl">
        <div class="w-16 h-16 rounded-2xl mx-auto flex items-center justify-center text-3xl"
             style="background:#FAF6EE; border:1px solid #E5E0D8; color:#C89D54;">
            🔒
        </div>
        <div class="space-y-2">
            <span class="inline-block px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-stone-100 text-stone-600 border border-stone-200">
                Akses Publik Dinonaktifkan
            </span>
            <h1 class="text-xl sm:text-2xl font-bold text-stone-900 leading-snug">
                Tautan Hasil Survei Ditutup
            </h1>
            <p class="text-xs sm:text-sm text-stone-600 leading-relaxed">
                Pemilik survei saat ini menonaktifkan pembagian hasil analitik untuk survei:
                <br>
                <strong class="text-stone-900 block mt-1">"{{ $survey->judul }}"</strong>
            </p>
        </div>

        <div class="p-4 rounded-2xl bg-stone-50 border border-stone-200 text-xs text-stone-500 text-left space-y-1.5">
            <p class="font-bold text-stone-700">Catatan:</p>
            <p>Jika Anda adalah pengelola atau memerlukan akses ke laporan ini, silakan hubungi pemilik survei untuk mengaktifkan kembali tautan publik.</p>
        </div>

        <div class="pt-2 flex flex-col gap-2">
            <a href="{{ route('admin.login') }}"
               class="w-full py-3 rounded-xl text-xs font-bold text-white transition shadow-sm"
               style="background:#1C1917;">
                Masuk ke Portal Pengelola →
            </a>
        </div>
    </div>
</body>
</html>
