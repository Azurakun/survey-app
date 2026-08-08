# 📖 Panduan Penggunaan & Tutorial GitHub
## Platform Survey Riset Pasar Kewirausahaan — SMKN 2 Indramayu

Selamat datang di panduan resmi penggunaan dan pengembang untuk **Platform Survey Riset Pasar Kewirausahaan SMKN 2 Indramayu**. Dokumen ini disusun sebagai petunjuk teknis dan panduan operasional bagi pengembang (*developer*), guru/admin, serta pengguna repository GitHub ini.

---

## 📋 Daftar Isi

1. [Tentang Proyek](#-tentang-proyek)
2. [Prasyarat Sistem](#-prasyarat-sistem)
3. [Panduan Instalasi & Setup Lokal](#-panduan-instalasi--setup-lokal)
4. [Struktur & Alur Git di GitHub](#-struktur--alur-git-di-github)
5. [Panduan Pengguna (User Guide)](#-panduan-pengguna-user-guide)
   - [A. Akses & Login Admin](#a-akses--login-admin)
   - [B. Pengelolaan & Pembuat Survei (Survey Builder)](#b-pengelolaan--pembuat-survei-survey-builder)
   - [C. Analitik & Visualisasi Data](#c-analitik--visualisasi-data)
   - [D. Ekspor Data Laporan (Excel)](#d-ekspor-data-laporan-excel)
   - [E. Alur Responden Siswa](#e-alur-responden-siswa)
6. [Struktur Arsitektur Kode](#-struktur-arsitektur-kode)
7. [Panduan Kontribusi Code (GitHub Workflow)](#-panduan-kontribusi-code-github-workflow)
8. [Troubleshooting & Pemecahan Masalah](#-troubleshooting--pemecahan-masalah)
9. [Lisensi & Hak Cipta](#-lisensi--hak-cipta)

---

## 🎯 Tentang Proyek

**Survey Pasar App 2** adalah aplikasi berbasis web yang dirancang khusus untuk mendukung program **Kewirausahaan di SMKN 2 Indramayu**. Aplikasi ini memungkinkan guru dan tim kewirausahaan membuat riset pasar digital, mengumpulkan data responden siswa dengan validasi NISN, serta menganalisis minat pasar, rata-rata *Willingness To Pay* (WTP), dan tingkat kepuasan produk secara *real-time*.

> [!NOTE]
> Aplikasi ini dibangun menggunakan **Laravel 12**, **Alpine.js**, **Tailwind CSS**, dan **Chart.js**.

---

## 💻 Prasyarat Sistem

Sebelum menjalankan atau berkontribusi pada proyek ini, pastikan perangkat Anda telah terpasang perangkat lunak berikut:

| Perangkat Lunak | Versi Minimal | Keterangan |
| :--- | :--- | :--- |
| **PHP** | `8.2.0` atau lebih baru | Disarankan ekstensi: `pdo_sqlite`, `gd`, `fileinfo`, `mbstring` |
| **Composer** | `2.5.0` atau lebih baru | Dependency manager untuk PHP |
| **Node.js & NPM** | `18.0.0` / NPM `9+` | Untuk build asset frontend (Vite) |
| **Git** | `2.30+` | Version control system |
| **Database** | SQLite (Default) / MySQL | SQLite disarankan untuk *development* cepat |

---

## 🚀 Panduan Instalasi & Setup Lokal

Ikuti langkah-langkah di bawah ini untuk memasang aplikasi di lingkungan lokal (komputer Anda):

### 1. Clone Repository dari GitHub

Buka terminal (Git Bash / PowerShell / Terminal) lalu jalankan:

```bash
git clone https://github.com/username/survey-pasar-app.git
cd survey-pasar-app
```

### 2. Install Dependensi PHP & Node.js

```bash
# Install package PHP melalui Composer
composer install

# Install package Frontend melalui NPM
npm install
```

### 3. Konfigurasi Environment (`.env`)

Salin file contoh `.env.example` menjadi `.env`:

```bash
cp .env.example .env
```

Sesuaikan konfigurasi database pada file `.env`. Untuk SQLite (default):

```env
APP_NAME="Survey Riset Pasar SMKN 2 Indramayu"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=sqlite
```

### 4. Generate App Key & Migrasi Database

```bash
# Generate key aplikasi Laravel
php artisan key:generate

# Jalankan migrasi database beserta data awal (Seeder)
php artisan migrate:fresh --seed

# Buat symlink storage untuk akses file/foto produk yang diunggah
php artisan storage:link
```

### 5. Jalankan Server Pengembang

Untuk menjalankan server pengembang:

```bash
# Menggunakan command gabungan composer dev:
composer dev

# Atau menjalankan manual:
php artisan serve
```

Akses aplikasi melalui browser di alamat: **`http://localhost:8000`**

---

## 🔑 Akun Default Pengujian

| Role | Email | Password | Akses Utama |
| :--- | :--- | :--- | :--- |
| **Admin / Guru** | `admin@smkn2indramayu.sch.id` | `admin123` | Full Access Dashboard & Survey Builder |

---

## 🔀 Struktur & Alur Git di GitHub

Proyek ini menerapkan standar alur kerja **Git Flow** yang rapi:

* **`main` / `master`**: Branch produksi (harus selalu stabil & teruji).
* **`development` / `dev`**: Branch pengembangan utama tempat penggabungan fitur baru.
* **`feature/nama-fitur`**: Branch khusus untuk pengerjaan fitur baru.
* **`fix/nama-bug`**: Branch khusus untuk perbaikan bug/isu.

---

## 👤 Panduan Pengguna (User Guide)

### A. Akses & Login Admin

1. Buka halaman utama aplikasi atau navigasi ke `/login`.
2. Masukkan **Email** (`admin@smkn2indramayu.sch.id`) dan **Password** (`admin123`).
3. Setelah berhasil login, Anda akan diarahkan ke **Dashboard Analytics**.

---

### B. Pengelolaan & Pembuat Survei (Survey Builder)

#### 1. Membuat Survei Baru
1. Masuk ke menu **Survei** pada sidebar.
2. Klik tombol **`+ Buat Survei Baru`**.
3. Isi informasi dasar survei:
   - **Judul Survei** (contoh: *Riset Pasar Produk Inovasi Kuliner Siswa*)
   - **Deskripsi & Petunjuk Pengisian**
   - **Target Tanggal Selesai**
4. Klik **Simpan & Lanjutkan ke Builder**.

#### 2. Menambahkan Pertanyaan (8 Tipe Pertanyaan)
Aplikasi mendukung 8 tipe pertanyaan yang fleksibel:

| Kode Tipe | Nama Tipe | Fungsi & Contoh Penggunaan |
| :--- | :--- | :--- |
| `SINGLE_CHOICE` | Pilihan Tunggal | Memilih 1 opsi dari beberapa pilihan (Radio Button). Contoh: Jenis Kelamin, Kelas. |
| `MULTIPLE_CHOICE` | Pilihan Ganda | Memilih lebih dari 1 opsi (Checkbox). Contoh: Alasan menyukai produk. |
| `LIKERT` | Skala Likert | Skala nilai 1–5 (Sangat Tidak Setuju s/d Sangat Setuju). Contoh: Rating Kemasan. |
| `NUMBER` | Angka / Harga | Input nominal angka (Rp). Contoh: Willingness To Pay / Potensi Harga Beli. |
| `SHORT_TEXT` | Teks Singkat | Input jawaban singkat (1 baris). Contoh: Nama Produk Favorit. |
| `LONG_TEXT` | Teks Panjang | Input esai/textarea. Contoh: Saran & Kritik Pengembangan. |
| `IMAGE_UPLOAD` | Unggah Foto | Upload file gambar JPG/PNG. Contoh: Foto Produk Referensi Responden. |
| `DATE` | Tanggal | Picker tanggal khusus. Contoh: Tanggal Pembelian Terakhir. |

> [!TIP]
> Anda dapat mengubah urutan pertanyaan dengan fitur *Drag & Drop* atau tombol panah naik/turun di Survey Builder.

#### 3. Mengubah Status Survei
* **DRAFT**: Survei masih dalam tahap penyusunan, belum bisa diisi siswa.
* **PUBLISHED**: Survei aktif dan siap menerima respon dari siswa melalui link publik.
* **CLOSED**: Survei ditutup, tidak menerima respon baru tetapi analitik tetap dapat diakses.

---

### C. Analitik & Visualisasi Data

1. Pilih survei yang ingin dianalisis dari daftar survei.
2. Klik tombol **`Analitik`**.
3. Dashboard Analitik akan menyajikan:
   - **Indeks Minat Pasar**: Persentase minat keseluruhan berdasarkan kalkulasi otomatis.
   - **Rata-rata WTP (Willingness to Pay)**: Rata-rata estimasi harga beli yang sanggup dibayar siswa.
   - **Skor Kepuasan**: Grafik distribusi nilai Likert.
   - **Grafik Interaktif (Chart.js)**: Diagram lingkaran (donut) dan diagram batang (bar chart) per pertanyaan.
   - **Filter Data**: Berdasarkan rentang tanggal pengisian.

---

### D. Ekspor Data Laporan (Excel)

Untuk kebutuhan laporan fisiknya:
1. Masuk ke halaman **Analitik Survei**.
2. Klik tombol **`Ekspor Excel (.xlsx)`** di pojok kanan atas.
3. File `.xlsx` rapi berisi seluruh data mentah responden dan jawaban detail akan terunduh secara otomatis.

---

### E. Alur Responden Siswa

1. Siswa mengakses link survei publik (contoh: `http://localhost:8000/s/riset-produk-2026`).
2. Siswa mengisi identitas awal: **Nama Lengkap**, **NISN**, dan **Kelas/Jurusan**.
3. Sistem akan memvalidasi **NISN**:
   > [!IMPORTANT]
   > Setiap NISN hanya diperbolehkan mengisi survei sebanyak **1 kali** untuk mencegah duplikasi data.
4. Siswa menjawab pertanyaan dalam format *Step-by-Step Wizard* (1 pertanyaan per langkah) yang interaktif.
5. Setelah selesai, siswa menekan **Kirim Survei** dan mendapatkan konfirmasi sukses.

---

## 🏗️ Struktur Arsitektur Kode

```text
Survey Pasar App 2/
├── app/
│   ├── Exports/
│   │   └── SurveyRawExport.php          # Class Ekspor Excel (Maatwebsite)
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/
│   │   │   │   ├── AnalyticsController.php  # Olah data analitik & grafik
│   │   │   │   ├── DashboardController.php  # Summary metrics dashboard
│   │   │   │   ├── ProfileController.php    # Kelola akun admin
│   │   │   │   └── SurveyController.php     # CRUD & Builder Survei
│   │   │   ├── AuthController.php        # Auth login/logout admin
│   │   │   └── StudentSurveyController.php # Form wizard survei siswa
│   ├── Models/
│   │   ├── Answer.php                   # Jawaban per pertanyaan
│   │   ├── Question.php                 # Schema pertanyaan survei
│   │   ├── Respondent.php               # Data identitas siswa
│   │   └── Survey.php                   # Core model survei
├── database/
│   ├── migrations/                      # Skema tabel database
│   └── seeders/                         # Data awal dummy/testing
├── resources/
│   └── views/
│       ├── admin/                       # Halaman kelola admin
│       ├── layouts/                     # Master template Blade
│       └── student/                     # Tampilan wizard siswa
└── routes/
    └── web.php                          # Deklarasi URL & middleware
```

---

## 🛠️ Panduan Kontribusi Code (GitHub Workflow)

Bagi pengembang yang ingin berkontribusi menambah fitur atau memperbaiki isu:

### 1. Buat Branch Baru
Selalu buat branch baru berdasarkan isu yang dikerjakan:
```bash
git checkout -b feature/tambah-export-pdf
```

### 2. Format Commit Message Standard
Gunakan format commit konvensional (*Conventional Commits*):
* `feat:` untuk penambahan fitur baru (contoh: `feat: tambah jenis pertanyaan skala 1-10`)
* `fix:` untuk perbaikan bug (contoh: `fix: perbaiki validasi nisn ganda`)
* `docs:` untuk perubahan dokumentasi (contoh: `docs: perbarui panduan instalasi di README`)
* `style:` untuk kerapian kode / perbaikan format CSS

```bash
git add .
git commit -m "feat: tambahkan fitur cetak laporan PDF"
```

### 3. Push & Buat Pull Request (PR)

```bash
git push origin feature/tambah-export-pdf
```

- Buka repository di GitHub.
- Klik tombol **Compare & pull request**.
- Tuliskan deskripsi ringkas mengenai perubahan yang Anda lakukan.
- Minta review dari pembimbing / tim *lead developer*.

---

## ❓ Troubleshooting & Pemecahan Masalah

### 1. Gambar/Foto yang Diunggah Tidak Muncul (Broken Link)
**Penyebab**: Storage link Laravel belum dibuat.  
**Solusi**:
```bash
php artisan storage:link
```

### 2. Error `Class "Maatwebsite\Excel\ExcelServiceProvider" not found`
**Penyebab**: Package Composer belum terinstall sempurna.  
**Solusi**:
```bash
composer install
```

### 3. Lupa Password Admin
**Solusi**: Gunakan Laravel Tinker untuk mereset password:
```bash
php artisan tinker
```
Lalu jalankan kode PHP berikut:
```php
\App\Models\User::where('email', 'admin@smkn2indramayu.sch.id')->update(['password' => bcrypt('passwordbaru')]);
exit;
```

---

## 📄 Lisensi & Hak Cipta

Hak Cipta © 2026 **SMKN 2 Indramayu** — Program Keahlian Kewirausahaan.  
Dikembangkan untuk kepentingan edukasi dan riset pembelajaran siswa.
