<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Survey Tidak Tersedia — SMKN 2 Indramayu</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,700&family=Plus+Jakarta+Sans:wght@400;600&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: { extend: {
                colors: {
                    mango: { DEFAULT:'#E8A33D', tint:'#FDF3E3' },
                    leaf:  { DEFAULT:'#2F5233', tint:'#E6EFE7' },
                    terra: { DEFAULT:'#D9622B', tint:'#FAEAE3' },
                    neutral: { dark:'#2B2118', bg:'#FBF6EE', border:'#EDE2D0', muted:'#8A7A6A' },
                },
                fontFamily: { serif:['Fraunces','Georgia','serif'], sans:['Plus Jakarta Sans','system-ui','sans-serif'] }
            }}
        }
    </script>
    <style>body{font-family:'Plus Jakarta Sans',system-ui,sans-serif;background:#FBF6EE;}</style>
</head>
<body class="min-h-screen bg-neutral-bg flex items-center justify-center px-4 py-12">
    <div class="max-w-md w-full text-center space-y-6">
        <div class="w-20 h-20 rounded-2xl bg-terra-tint flex items-center justify-center mx-auto border-2 border-terra/30">
            <span class="text-3xl">🔒</span>
        </div>
        <div>
            <div class="text-[10px] font-sans uppercase tracking-widest text-terra font-semibold mb-2">SURVEY TIDAK TERSEDIA</div>
            <h1 style="font-family:'Fraunces',serif;" class="font-bold text-2xl text-neutral-dark mb-3">
                {{ $survey->judul }}
            </h1>
            <p class="text-sm text-neutral-muted leading-relaxed">{{ $message }}</p>
        </div>
        <div class="p-4 bg-white rounded-xl border border-neutral-border text-xs text-neutral-muted">
            <p>Jika Anda yakin survey ini seharusnya aktif, hubungi guru/wali kelas Anda.</p>
        </div>
        <p class="text-xs text-neutral-muted">Survey Riset Pasar Kewirausahaan &middot; SMKN 2 Indramayu &middot; {{ date('Y') }}</p>
    </div>
</body>
</html>
