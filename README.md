# Survey Riset Pasar Kewirausahaan — SMKN 2 Indramayu

Platform survey riset pasar digital untuk program Kewirausahaan SMKN 2 Indramayu. Memudahkan pembuatan survey, pengumpulan data siswa, dan analisis hasil riset pasar produk kewirausahaan dalam satu web application.

## 🚀 Cara Menjalankan Lokal

### Prasyarat
- PHP 8.2+ (XAMPP atau standalone)
- Composer
- SQLite (default) atau MySQL

### Langkah Setup

```bash
# 1. Clone / buka direktori proyek
cd "Survey Pasar App 2"

# 2. Install dependencies (jika belum)
php composer.phar install --ignore-platform-req=ext-gd

# 3. Salin file konfigurasi
cp .env.example .env

# 4. Generate app key
php artisan key:generate

# 5. Jalankan migrasi database + seed data
php artisan migrate:fresh --seed

# 6. Buat symlink storage (untuk upload foto)
php artisan storage:link

# 7. Jalankan server lokal
php artisan serve
```

Buka browser: **http://localhost:8000**

## 🔑 Akun Default

| Role | Email | Password |
|------|-------|----------|
| Admin/Guru | `admin@smkn2indramayu.sch.id` | `admin123` |

## 📋 Fitur Utama

### Admin (Guru/Pengelola)
- **Dashboard** — Market Analytics Framework: Indeks Minat Pasar, Rata-rata WTP, Skor Kepuasan, Total Responden
- **Survey Builder** — Buat dan edit survey dengan 8 tipe pertanyaan, live preview, reorder pertanyaan
- **Manajemen Survey** — Filter by status (PUBLISHED/DRAFT/CLOSED), badge periode aktif, salin link
- **Analitik Survey** — Chart.js per pertanyaan (donut/bar), filter tanggal, modal detail responden
- **Ekspor XLSX** — Download data mentah dalam format Excel
- **Profil & Keamanan** — Edit nama, email, ganti password

### Siswa (Responden)
- Form survey wizard 1-pertanyaan-per-langkah
- Validasi NISN unik (tidak bisa submit dua kali)
- Upload foto produk
- Halaman sukses setelah submit

## 🎨 Tipe Pertanyaan (8 Jenis)

| Kode | Nama | Deskripsi |
|------|------|-----------|
| `SINGLE_CHOICE` | Pilihan Tunggal | Radio button |
| `MULTIPLE_CHOICE` | Pilihan Ganda | Checkbox |
| `LIKERT` | Skala Likert | Skala 1–5 |
| `NUMBER` | Angka/Harga | Input nominal (Rp) |
| `SHORT_TEXT` | Teks Singkat | Input text |
| `LONG_TEXT` | Teks Panjang | Textarea esai |
| `IMAGE_UPLOAD` | Unggah Foto | Upload JPG/PNG |
| `DATE` | Tanggal | Date picker |

## 🗄️ Struktur Database

```
users (admin/guru)
└── surveys (survey riset pasar)
    ├── questions (pertanyaan survey)
    │   └── answers (jawaban siswa)
    └── respondents (data siswa + NISN)
        └── answers (jawaban siswa)
```

## ⚙️ Teknologi

- **Backend**: Laravel 12 + PHP 8.2
- **Database**: SQLite (dev) / MySQL (prod)
- **CSS**: Tailwind CSS (CDN) — Palet Mango/Leaf/Terra
- **JS**: Alpine.js + Chart.js + Lucide Icons
- **Fonts**: Fraunces (serif) + Plus Jakarta Sans
- **Export**: maatwebsite/excel (XLSX)

## 📁 Struktur Proyek

```
app/
├── Http/Controllers/
│   ├── Admin/
│   │   ├── DashboardController.php
│   │   ├── SurveyController.php
│   │   ├── AnalyticsController.php
│   │   └── ProfileController.php
│   ├── AuthController.php
│   └── StudentSurveyController.php
├── Models/
│   ├── Survey.php
│   ├── Question.php
│   ├── Respondent.php
│   └── Answer.php
└── Exports/
    └── SurveyRawExport.php
resources/views/
├── layouts/admin.blade.php
├── admin/
│   ├── login.blade.php
│   ├── dashboard.blade.php
│   ├── profile.blade.php
│   └── surveys/
│       ├── index.blade.php
│       ├── builder.blade.php
│       └── analytics.blade.php
├── student/
│   ├── survey.blade.php
│   └── error.blade.php
└── welcome.blade.php
```

---

**SMKN 2 Indramayu** — Program Kewirausahaan © {{ date('Y') }}
