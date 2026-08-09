<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Survey Tidak Tersedia — Platform Survey Riset Pasar</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: { extend: {
                colors: {
                    accent:  { DEFAULT:'#C89D54', light:'#D9AB55', tint:'#F8F4EC' },
                    danger:  { DEFAULT:'#C0392B', tint:'#FDECEA' },
                    ink:     { DEFAULT:'#1C1917', muted:'#6E675F', subtle:'#A19A91' },
                    surface: { DEFAULT:'#FFFFFF', base:'#FAF8F5', subtle:'#F4F0E8', border:'#E5E0D8', divider:'#EBE6DE' },
                },
                fontFamily: { serif:['Plus Jakarta Sans','Inter','system-ui','sans-serif'], sans:['Plus Jakarta Sans','Inter','system-ui','sans-serif'] }
            }}
        }
    </script>
    <style>body{font-family:'Plus Jakarta Sans','Inter',system-ui,sans-serif;background:#FAF8F5;color:#1C1917;}</style>
</head>
<body class="min-h-screen flex items-center justify-center px-4 py-12" style="background:#FAF8F5;">
    <div class="max-w-md w-full text-center space-y-6">
        <div class="w-20 h-20 rounded-2xl flex items-center justify-center mx-auto border-2 shadow-sm"
             style="background:#FDECEA; border-color:#F5B7B1;">
            <span class="text-3xl">🔒</span>
        </div>
        <div>
            <div class="text-[10px] font-sans uppercase tracking-widest font-bold mb-2" style="color:#C0392B;">SURVEY TIDAK TERSEDIA</div>
            <h1 class="font-bold text-2xl text-ink mb-3 tracking-tight">
                {{ $survey->judul }}
            </h1>
            <p class="text-sm leading-relaxed" style="color:#6E675F;">{{ $message }}</p>
        </div>
        <div class="p-4 bg-white rounded-xl border text-xs" style="border-color:#E5E0D8; color:#6E675F;">
            <p>Jika Anda yakin survey ini seharusnya aktif, hubungi guru/pengelola kewirausahaan Anda.</p>
        </div>
        <p class="text-xs" style="color:#A19A91;">Platform Survey Riset Pasar &middot; {{ date('Y') }}</p>
    </div>
</body>
</html>
