<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Survey;
use App\Models\Question;
use App\Models\Respondent;
use App\Models\Answer;

class BanperPkkStudyCaseSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@smkn2indramayu.sch.id'],
            [
                'name'     => 'Admin Kewirausahaan',
                'password' => bcrypt('admin123'),
                'role'     => 'ADMIN',
            ]
        );

        // Remove old instance of this survey if exists
        $existingSurvey = Survey::where('judul', 'LIKE', '%Banper PKK 2026%')->first();
        if ($existingSurvey) {
            $existingSurvey->delete();
        }

        // ═════════════════════════════════════════════════════════════════════
        // SURVEY: RISET PASAR & ANALISIS SWEET SPOT PRODUK BANPER PKK 2026
        // 4 Kelompok Kewirausahaan:
        // 1. JANGKAR MAS (Olahan Ikan & Hasil Laut - Pembimbing: Nina Komalasari, S.Pi)
        // 2. KULINJER (Kuliner & Kudapan Khas Indramayu - Pembimbing: Cipto Karaton, S.Pi)
        // 3. SANTAP (Sajian Makanan Cepat Saji / Rice Bowl - Pembimbing: Dandi Saefudin, S.Tr.Pi)
        // 4. SUSU KEDELAI NUMANI (Minuman Kedelai Alami - Pembimbing: Dian Novitasari, S.Tr.T)
        // ═════════════════════════════════════════════════════════════════════
        $survey = Survey::create([
            'user_id'             => $admin->id,
            'judul'               => 'Riset Pasar & Evaluasi Sweet Spot Produk Kewirausahaan Banper PKK 2026',
            'deskripsi'           => 'Survey riset preferensi konsumen, uji cita rasa, kemasan, saluran distribusi, dan penentuan sweet spot harga (WTP) untuk 4 Kelompok Kewirausahaan Siswa Banper PKK 2026 SMKN 2 Indramayu: Jangkar Mas, Kulinjer, Santap, dan Susu Kedelai Numani.',
            'status'              => 'PUBLISHED',
            'limit_one_response'  => false,
            'tanggal_mulai'       => now()->subDays(7)->toDateString(),
            'tanggal_selesai'     => now()->addDays(30)->toDateString(),
        ]);

        // 18 Structured Questions using ALL permitted question types (no IMAGE_UPLOAD, no DATE)
        $questions = [
            // Q1: SINGLE_CHOICE - Kategori Paling Menarik
            [
                'tipe'  => 'SINGLE_CHOICE',
                'teks'  => 'Dari 4 unit usaha kewirausahaan Banper PKK 2026 berikut, kategori produk mana yang paling menarik perhatian Anda untuk dibeli?',
                'opsi'  => ['JANGKAR MAS (Olahan Ikan & Hasil Laut Pesisir)', 'KULINJER (Snack & Kudapan Khas Indramayu)', 'SANTAP (Menu Makanan Siap Saji / Rice Bowl)', 'SUSU KEDELAI NUMANI (Minuman Kedelai Alami Aneka Rasa)'],
                'wajib' => true,
            ],
            // Q2: MULTIPLE_CHOICE - Varian Rasa Susu Kedelai Numani
            [
                'tipe'  => 'MULTIPLE_CHOICE',
                'teks'  => '[SUSU KEDELAI NUMANI] Varian rasa susu kedelai apa saja yang paling Anda inginkan? (Boleh pilih lebih dari satu)',
                'opsi'  => ['Original Vanilla', 'Cokelat Belgia', 'Matcha Green Tea', 'Gula Aren Organik', 'Strawberry Cream', 'Kopi Kedelai'],
                'wajib' => true,
            ],
            // Q3: NUMBER - WTP Susu Kedelai Numani (Sweet Spot 1)
            [
                'tipe'  => 'NUMBER',
                'teks'  => '[SUSU KEDELAI NUMANI] Berapa harga (Rupiah) yang bersedia Anda bayar untuk 1 botol Susu Kedelai Numani 250ml siap minum?',
                'opsi'  => null,
                'wajib' => true,
            ],
            // Q4: SINGLE_CHOICE - Produk Unggulan Jangkar Mas
            [
                'tipe'  => 'SINGLE_CHOICE',
                'teks'  => '[JANGKAR MAS] Produk olahan perikanan mana yang paling berpotensi Anda beli secara rutin?',
                'opsi'  => ['Abon Ikan Bandeng Tanpa Duri', 'Kerupuk Ikan Tenggiri Khas Pesisir', 'Nugget Ikan Laut Crispy', 'Sambal Cumi / Teri Balado Kemasan'],
                'wajib' => true,
            ],
            // Q5: NUMBER - WTP Jangkar Mas (Sweet Spot 2)
            [
                'tipe'  => 'NUMBER',
                'teks'  => '[JANGKAR MAS] Berapa harga (Rupiah) yang bersedia Anda bayar untuk 1 kemasan produk olahan ikan Jangkar Mas isi 150-200 gram?',
                'opsi'  => null,
                'wajib' => true,
            ],
            // Q6: MULTIPLE_CHOICE - Varian Snack Kulinjer
            [
                'tipe'  => 'MULTIPLE_CHOICE',
                'teks'  => '[KULINJER] Jenis kudapan dan snack khas apa yang paling Anda harapkan diproduksi kelompok Kulinjer? (Boleh pilih lebih dari satu)',
                'opsi'  => ['Keripik Tette / Singkong Pedas Manis', 'Stik Keju Rumput Laut', 'Rengginang Mini Aneka Bumbu', 'Pastel Kering Isi Abon Ikan', 'Kue Kering Khas Mangga'],
                'wajib' => true,
            ],
            // Q7: NUMBER - WTP Kulinjer (Sweet Spot 3)
            [
                'tipe'  => 'NUMBER',
                'teks'  => '[KULINJER] Berapa harga (Rupiah) yang bersedia Anda bayar untuk 1 pouch standing ziplock snack Kulinjer isi 120-150 gram?',
                'opsi'  => null,
                'wajib' => true,
            ],
            // Q8: SINGLE_CHOICE - Menu Santap Rice Bowl
            [
                'tipe'  => 'SINGLE_CHOICE',
                'teks'  => '[SANTAP] Menu rice bowl / sajian cepat saji apa yang paling menggugah selera Anda untuk sarapan atau makan siang?',
                'opsi'  => ['Rice Bowl Cumi Saus Tiram Telur Mata Sapi', 'Rice Bowl Ayam Suwir Pedas Kemangi', 'Rice Bowl Ikan Asam Manis Sayur Segar', 'Nasi Bakar Tongkol Rica-Rica'],
                'wajib' => true,
            ],
            // Q9: NUMBER - WTP Santap (Sweet Spot 4)
            [
                'tipe'  => 'NUMBER',
                'teks'  => '[SANTAP] Berapa harga (Rupiah) yang bersedia Anda bayar untuk 1 porsi lengkap Rice Bowl SANTAP hangat dan higienis?',
                'opsi'  => null,
                'wajib' => true,
            ],
            // Q10: LIKERT - Kualitas & Higienitas
            [
                'tipe'  => 'LIKERT',
                'teks'  => 'Seberapa yakin Anda terhadap standar kebersihan (higienitas), sanitasi, dan keamanan pangan olahan siswa kewirausahaan Banper PKK?',
                'opsi'  => ['1', '2', '3', '4', '5'],
                'wajib' => true,
            ],
            // Q11: LIKERT - Desain Kemasan & Branding
            [
                'tipe'  => 'LIKERT',
                'teks'  => 'Seberapa penting kualitas kemasan (standing pouch aluminium, label informasi nilai gizi, tanggal kadaluarsa) dalam keputusan membeli Anda?',
                'opsi'  => ['1', '2', '3', '4', '5'],
                'wajib' => true,
            ],
            // Q12: MULTIPLE_CHOICE - Faktor Penentu Pembelian
            [
                'tipe'  => 'MULTIPLE_CHOICE',
                'teks'  => 'Faktor apa saja yang paling mempengaruhi keputusan Anda saat membeli produk kuliner & minuman kewirausahaan sekolah? (Pilih yang relevan)',
                'opsi'  => ['Cita Rasa yang Lezat & Konsisten', 'Harga Bersahabat bagi Pelajar/Guru', 'Kebersihan & Legalitas Halal/P-IRT', 'Kemasan Modern & Praktis Dibawa', 'Mendukung Kreativitas Siswa Sekolah'],
                'wajib' => true,
            ],
            // Q13: SINGLE_CHOICE - Estimasi Frekuensi Konsumsi
            [
                'tipe'  => 'SINGLE_CHOICE',
                'teks'  => 'Seberapa sering Anda memperkirakan akan membeli produk-produk kewirausahaan Banper PKK 2026 jika sudah tersedia rutin?',
                'opsi'  => ['Hampir Setiap Hari Sekolah (3-5 kali seminggu)', '1 - 2 Kali Seminggu', '2 - 3 Kali Sebulan', 'Hanya Saat Acara Khusus / Bazar Pameran'],
                'wajib' => true,
            ],
            // Q14: SINGLE_CHOICE - Preferensi Saluran Pembelian
            [
                'tipe'  => 'SINGLE_CHOICE',
                'teks'  => 'Melalui jalur mana Anda paling nyaman memesan atau membeli produk dari 4 kelompok ini?',
                'opsi'  => ['Kantin & Galeri Kewirausahaan SMKN 2 Indramayu', 'Pre-Order melalui WhatsApp Bisnis / Grup Kelas', 'Bazar Rutin Tiap Hari Jumat di Sekolah', 'Titip Jual di Koperasi Sekolah'],
                'wajib' => true,
            ],
            // Q15: LIKERT - Kesediaan Merekomendasikan (NPS / Dukungan)
            [
                'tipe'  => 'LIKERT',
                'teks'  => 'Seberapa besar kerelaan Anda untuk merekomendasikan produk Jangkar Mas, Kulinjer, Santap, dan Susu Kedelai Numani kepada teman, keluarga, dan media sosial?',
                'opsi'  => ['1', '2', '3', '4', '5'],
                'wajib' => true,
            ],
            // Q16: SHORT_TEXT - Ide Tagline / Slogan Produk
            [
                'tipe'  => 'SHORT_TEXT',
                'teks'  => 'Berikan satu kata atau frasa singkat (tagline) yang menurut Anda paling cocok untuk mencerminkan keunggulan produk siswa ini.',
                'opsi'  => null,
                'wajib' => false,
            ],
            // Q17: SHORT_TEXT - Rekomendasi Format Kemasan / Porsi
            [
                'tipe'  => 'SHORT_TEXT',
                'teks'  => 'Tuliskan usulan ukuran porsi atau format kemasan khusus yang paling Anda harapkan (contoh: paket bundling hemat, botol saku, pouch mini).',
                'opsi'  => null,
                'wajib' => false,
            ],
            // Q18: LONG_TEXT - Kritik, Saran, & Harapan Lengkap
            [
                'tipe'  => 'LONG_TEXT',
                'teks'  => 'Tuliskan kritik, masukan, dan harapan Anda untuk seluruh kelompok (Jangkar Mas, Kulinjer, Santap, Susu Kedelai Numani) agar usaha ini dapat berkembang berkelanjutan dan sukses di pasar umum.',
                'opsi'  => null,
                'wajib' => false,
            ],
        ];

        $createdQuestions = [];
        $order = 1;
        foreach ($questions as $q) {
            $createdQuestions[] = Question::create([
                'survey_id'       => $survey->id,
                'tipe_pertanyaan' => $q['tipe'],
                'teks_pertanyaan' => $q['teks'],
                'opsi_jawaban'    => $q['opsi'] ? json_encode($q['opsi']) : null,
                'wajib_diisi'     => $q['wajib'],
                'urutan'          => $order++,
            ]);
        }

        // ═════════════════════════════════════════════════════════════════════
        // 20 GROUNDED RESPONDENTS WITH REALISTIC ANSWERS
        // ═════════════════════════════════════════════════════════════════════
        $respondentsData = [
            [
                'nisn' => '0068112001',
                'q1'  => 'SANTAP (Menu Makanan Siap Saji / Rice Bowl)',
                'q2'  => ['Cokelat Belgia', 'Matcha Green Tea'],
                'q3'  => 6000,
                'q4'  => 'Sambal Cumi / Teri Balado Kemasan',
                'q5'  => 18000,
                'q6'  => ['Stik Keju Rumput Laut', 'Rengginang Mini Aneka Bumbu'],
                'q7'  => 10000,
                'q8'  => 'Rice Bowl Cumi Saus Tiram Telur Mata Sapi',
                'q9'  => 15000,
                'q10' => '5',
                'q11' => '5',
                'q12' => ['Cita Rasa yang Lezat & Konsisten', 'Harga Bersahabat bagi Pelajar/Guru', 'Kebersihan & Legalitas Halal/P-IRT'],
                'q13' => '1 - 2 Kali Seminggu',
                'q14' => 'Kantin & Galeri Kewirausahaan SMKN 2 Indramayu',
                'q15' => '5',
                'q16' => 'Rasa Juara Karya Siswa',
                'q17' => 'Paket hemat kombo Rice Bowl Santap plus Susu Kedelai dingin seharga Rp 20.000',
                'q18' => 'Untuk kelompok Santap, pastikan nasi disajikan hangat dan sambal cuminya tidak terlalu berminyak. Susu kedelai Numani cokelat sangat segar jika disajikan dingin.',
            ],
            [
                'nisn' => '0068112002',
                'q1'  => 'SUSU KEDELAI NUMANI (Minuman Kedelai Alami Aneka Rasa)',
                'q2'  => ['Original Vanilla', 'Gula Aren Organik'],
                'q3'  => 5000,
                'q4'  => 'Abon Ikan Bandeng Tanpa Duri',
                'q5'  => 15000,
                'q6'  => ['Keripik Tette / Singkong Pedas Manis'],
                'q7'  => 8000,
                'q8'  => 'Rice Bowl Ayam Suwir Pedas Kemangi',
                'q9'  => 14000,
                'q10' => '4',
                'q11' => '4',
                'q12' => ['Harga Bersahabat bagi Pelajar/Guru', 'Kemasan Modern & Praktis Dibawa'],
                'q13' => 'Hampir Setiap Hari Sekolah (3-5 kali seminggu)',
                'q14' => 'Kantin & Galeri Kewirausahaan SMKN 2 Indramayu',
                'q15' => '4',
                'q16' => 'Sehat Alami Setiap Hari',
                'q17' => 'Botol 250ml siap minum dengan segel plastik steril',
                'q18' => 'Susu kedelai Numani sangat cocok untuk alternatif minuman sehat pengganti es teh manis. Pertahankan rasa kedelai alami tanpa bau langu.',
            ],
            [
                'nisn' => '0068112003',
                'q1'  => 'JANGKAR MAS (Olahan Ikan & Hasil Laut Pesisir)',
                'q2'  => ['Original Vanilla', 'Strawberry Cream'],
                'q3'  => 7000,
                'q4'  => 'Kerupuk Ikan Tenggiri Khas Pesisir',
                'q5'  => 20000,
                'q6'  => ['Pastel Kering Isi Abon Ikan', 'Kue Kering Khas Mangga'],
                'q7'  => 12000,
                'q8'  => 'Rice Bowl Ikan Asam Manis Sayur Segar',
                'q9'  => 16000,
                'q10' => '5',
                'q11' => '5',
                'q12' => ['Cita Rasa yang Lezat & Konsisten', 'Mendukung Kreativitas Siswa Sekolah'],
                'q13' => '1 - 2 Kali Seminggu',
                'q14' => 'Pre-Order melalui WhatsApp Bisnis / Grup Kelas',
                'q15' => '5',
                'q16' => 'Cita Rasa Bahari Asli Indramayu',
                'q17' => 'Standing pouch ziplock agar kerupuk ikan tetap renyah tahan lama',
                'q18' => 'Kelompok Jangkar Mas punya potensi besar untuk oleh-oleh khas daerah. Desain labelnya harus dibuat elegan agar bisa dititipkan di minimarket dan rest area.',
            ],
            [
                'nisn' => '0068112004',
                'q1'  => 'KULINJER (Snack & Kudapan Khas Indramayu)',
                'q2'  => ['Cokelat Belgia', 'Kopi Kedelai'],
                'q3'  => 6000,
                'q4'  => 'Nugget Ikan Laut Crispy',
                'q5'  => 18000,
                'q6'  => ['Keripik Tette / Singkong Pedas Manis', 'Stik Keju Rumput Laut'],
                'q7'  => 10000,
                'q8'  => 'Nasi Bakar Tongkol Rica-Rica',
                'q9'  => 15000,
                'q10' => '4',
                'q11' => '4',
                'q12' => ['Cita Rasa yang Lezat & Konsisten', 'Harga Bersahabat bagi Pelajar/Guru'],
                'q13' => '2 - 3 Kali Sebulan',
                'q14' => 'Bazar Rutin Tiap Hari Jumat di Sekolah',
                'q15' => '4',
                'q16' => 'Ngemil Asik Khas Indramayu',
                'q17' => 'Pouch travel pack 100 gram dengan varian pedas berlevel',
                'q18' => 'Camilan Kulinjer sangat pas untuk teman belajar kelompok. Tingkat kepedasannya mohon dibuat pilihan level 1 sampai 3.',
            ],
            [
                'nisn' => '0068112005',
                'q1'  => 'SANTAP (Menu Makanan Siap Saji / Rice Bowl)',
                'q2'  => ['Matcha Green Tea', 'Gula Aren Organik'],
                'q3'  => 8000,
                'q4'  => 'Abon Ikan Bandeng Tanpa Duri',
                'q5'  => 22000,
                'q6'  => ['Stik Keju Rumput Laut'],
                'q7'  => 10000,
                'q8'  => 'Rice Bowl Cumi Saus Tiram Telur Mata Sapi',
                'q9'  => 18000,
                'q10' => '5',
                'q11' => '5',
                'q12' => ['Kebersihan & Legalitas Halal/P-IRT', 'Kemasan Modern & Praktis Dibawa'],
                'q13' => '1 - 2 Kali Seminggu',
                'q14' => 'Kantin & Galeri Kewirausahaan SMKN 2 Indramayu',
                'q15' => '5',
                'q16' => 'Makan Enak, Cepat & Higienis',
                'q17' => 'Paper bowl ramah lingkungan yang tahan panas microwave',
                'q18' => 'Penyajian Rice Bowl Santap harus cepat saat jam istirahat agar siswa tidak telat masuk kelas. Rasa cumi saus tiramnya sangat istimewa.',
            ],
            [
                'nisn' => '0068112006',
                'q1'  => 'SUSU KEDELAI NUMANI (Minuman Kedelai Alami Aneka Rasa)',
                'q2'  => ['Original Vanilla', 'Cokelat Belgia', 'Strawberry Cream'],
                'q3'  => 5000,
                'q4'  => 'Sambal Cumi / Teri Balado Kemasan',
                'q5'  => 16000,
                'q6'  => ['Rengginang Mini Aneka Bumbu'],
                'q7'  => 9000,
                'q8'  => 'Rice Bowl Ayam Suwir Pedas Kemangi',
                'q9'  => 13000,
                'q10' => '4',
                'q11' => '4',
                'q12' => ['Harga Bersahabat bagi Pelajar/Guru', 'Cita Rasa yang Lezat & Konsisten'],
                'q13' => 'Hampir Setiap Hari Sekolah (3-5 kali seminggu)',
                'q14' => 'Titip Jual di Koperasi Sekolah',
                'q15' => '4',
                'q16' => 'Kesegaran Kedelai Asli Pelajar',
                'q17' => 'Cup press atau botol 250ml dengan label komposisi jelas',
                'q18' => 'Susu kedelai Numani rasa stroberi sangat disukai siswi. Harganya kalau bisa tetap Rp 5.000 - Rp 6.000 agar ramah kantong siswa.',
            ],
            [
                'nisn' => '0068112007',
                'q1'  => 'JANGKAR MAS (Olahan Ikan & Hasil Laut Pesisir)',
                'q2'  => ['Gula Aren Organik'],
                'q3'  => 6000,
                'q4'  => 'Nugget Ikan Laut Crispy',
                'q5'  => 25000,
                'q6'  => ['Pastel Kering Isi Abon Ikan'],
                'q7'  => 11000,
                'q8'  => 'Nasi Bakar Tongkol Rica-Rica',
                'q9'  => 15000,
                'q10' => '5',
                'q11' => '5',
                'q12' => ['Cita Rasa yang Lezat & Konsisten', 'Mendukung Kreativitas Siswa Sekolah'],
                'q13' => '1 - 2 Kali Seminggu',
                'q14' => 'Bazar Rutin Tiap Hari Jumat di Sekolah',
                'q15' => '5',
                'q16' => 'Hasil Laut Juara SMKN 2',
                'q17' => 'Frozen pack vakum 250 gram untuk nugget dan abon ikan',
                'q18' => 'Jangkar Mas membuktikan potensi jurusan perikanan SMKN 2 Indramayu. Nugget ikannya gurih dan tidak amis sama sekali, sangat cocok untuk bekal anak.',
            ],
            [
                'nisn' => '0068112008',
                'q1'  => 'KULINJER (Snack & Kudapan Khas Indramayu)',
                'q2'  => ['Cokelat Belgia', 'Matcha Green Tea'],
                'q3'  => 7000,
                'q4'  => 'Kerupuk Ikan Tenggiri Khas Pesisir',
                'q5'  => 18000,
                'q6'  => ['Keripik Tette / Singkong Pedas Manis', 'Kue Kering Khas Mangga'],
                'q7'  => 10000,
                'q8'  => 'Rice Bowl Ikan Asam Manis Sayur Segar',
                'q9'  => 15000,
                'q10' => '4',
                'q11' => '4',
                'q12' => ['Kemasan Modern & Praktis Dibawa', 'Harga Bersahabat bagi Pelajar/Guru'],
                'q13' => '2 - 3 Kali Sebulan',
                'q14' => 'Kantin & Galeri Kewirausahaan SMKN 2 Indramayu',
                'q15' => '4',
                'q16' => 'Krenyes Gurih Tradisional Modern',
                'q17' => 'Kemasan toples mini untuk hampers hari raya atau acara sekolah',
                'q18' => 'Kulinjer perlu memanfaatkan olahan mangga khas Indramayu lebih optimal sebagai pembeda dengan camilan daerah lain.',
            ],
            [
                'nisn' => '0068112009',
                'q1'  => 'SANTAP (Menu Makanan Siap Saji / Rice Bowl)',
                'q2'  => ['Original Vanilla'],
                'q3'  => 6000,
                'q4'  => 'Sambal Cumi / Teri Balado Kemasan',
                'q5'  => 20000,
                'q6'  => ['Stik Keju Rumput Laut'],
                'q7'  => 9000,
                'q8'  => 'Rice Bowl Cumi Saus Tiram Telur Mata Sapi',
                'q9'  => 16000,
                'q10' => '5',
                'q11' => '4',
                'q12' => ['Cita Rasa yang Lezat & Konsisten', 'Kebersihan & Legalitas Halal/P-IRT'],
                'q13' => '1 - 2 Kali Seminggu',
                'q14' => 'Pre-Order melalui WhatsApp Bisnis / Grup Kelas',
                'q15' => '5',
                'q16' => 'Santapan Mantap Rasa Hebat',
                'q17' => 'Porsi regular dan jumbo dengan opsi tambahan sambal sachet',
                'q18' => 'Pilihan lauk seafood di Santap sangat sesuai dengan ciri khas pesisir Indramayu. Pertahankan bumbu gurihnya.',
            ],
            [
                'nisn' => '0068112010',
                'q1'  => 'SUSU KEDELAI NUMANI (Minuman Kedelai Alami Aneka Rasa)',
                'q2'  => ['Cokelat Belgia', 'Kopi Kedelai'],
                'q3'  => 5000,
                'q4'  => 'Abon Ikan Bandeng Tanpa Duri',
                'q5'  => 17000,
                'q6'  => ['Rengginang Mini Aneka Bumbu'],
                'q7'  => 8000,
                'q8'  => 'Rice Bowl Ayam Suwir Pedas Kemangi',
                'q9'  => 14000,
                'q10' => '4',
                'q11' => '4',
                'q12' => ['Harga Bersahabat bagi Pelajar/Guru', 'Mendukung Kreativitas Siswa Sekolah'],
                'q13' => 'Hampir Setiap Hari Sekolah (3-5 kali seminggu)',
                'q14' => 'Titip Jual di Koperasi Sekolah',
                'q15' => '4',
                'q16' => 'Energi Kedelai Sehat Setiap Saat',
                'q17' => 'Pouch standing dengan sedotan higienis terbungkus plastik',
                'q18' => 'Kopi kedelai Numani sangat unik! Rasanya pas untuk teman belajar di perpustakaan tanpa membuat asam lambung naik.',
            ],
            [
                'nisn' => '0068112011',
                'q1'  => 'JANGKAR MAS (Olahan Ikan & Hasil Laut Pesisir)',
                'q2'  => ['Matcha Green Tea', 'Strawberry Cream'],
                'q3'  => 7000,
                'q4'  => 'Abon Ikan Bandeng Tanpa Duri',
                'q5'  => 22000,
                'q6'  => ['Pastel Kering Isi Abon Ikan'],
                'q7'  => 11000,
                'q8'  => 'Nasi Bakar Tongkol Rica-Rica',
                'q9'  => 15000,
                'q10' => '5',
                'q11' => '5',
                'q12' => ['Kebersihan & Legalitas Halal/P-IRT', 'Cita Rasa yang Lezat & Konsisten'],
                'q13' => '1 - 2 Kali Seminggu',
                'q14' => 'Kantin & Galeri Kewirausahaan SMKN 2 Indramayu',
                'q15' => '5',
                'q16' => 'Ikan Olahan Berkualitas Pesisir',
                'q17' => 'Toples segel alumunium foil untuk abon bandeng agar awet 6 bulan',
                'q18' => 'Abon bandeng Jangkar Mas tanpa duri sangat disukai guru dan orang tua murid. Kemasannya sudah layak masuk supermarket.',
            ],
            [
                'nisn' => '0068112012',
                'q1'  => 'SANTAP (Menu Makanan Siap Saji / Rice Bowl)',
                'q2'  => ['Gula Aren Organik', 'Original Vanilla'],
                'q3'  => 6000,
                'q4'  => 'Kerupuk Ikan Tenggiri Khas Pesisir',
                'q5'  => 19000,
                'q6'  => ['Stik Keju Rumput Laut'],
                'q7'  => 10000,
                'q8'  => 'Rice Bowl Ayam Suwir Pedas Kemangi',
                'q9'  => 15000,
                'q10' => '4',
                'q11' => '5',
                'q12' => ['Cita Rasa yang Lezat & Konsisten', 'Kemasan Modern & Praktis Dibawa'],
                'q13' => '1 - 2 Kali Seminggu',
                'q14' => 'Pre-Order melalui WhatsApp Bisnis / Grup Kelas',
                'q15' => '5',
                'q16' => 'Kelezatan Nasi Bowl Idola',
                'q17' => 'Bowl bersekat pemisah kuah/saus agar tidak lembek saat dibawa jalan',
                'q18' => 'Porsi nasi dan lauk ayam suwir kemangi di Santap sangat pas mengenyangkan. Pelayanan ramah dan pesanan via WA cepat ditanggapi.',
            ],
            [
                'nisn' => '0068112013',
                'q1'  => 'KULINJER (Snack & Kudapan Khas Indramayu)',
                'q2'  => ['Cokelat Belgia'],
                'q3'  => 5000,
                'q4'  => 'Sambal Cumi / Teri Balado Kemasan',
                'q5'  => 16000,
                'q6'  => ['Keripik Tette / Singkong Pedas Manis', 'Rengginang Mini Aneka Bumbu'],
                'q7'  => 9000,
                'q8'  => 'Rice Bowl Cumi Saus Tiram Telur Mata Sapi',
                'q9'  => 14000,
                'q10' => '4',
                'q11' => '4',
                'q12' => ['Harga Bersahabat bagi Pelajar/Guru', 'Cita Rasa yang Lezat & Konsisten'],
                'q13' => '2 - 3 Kali Sebulan',
                'q14' => 'Bazar Rutin Tiap Hari Jumat di Sekolah',
                'q15' => '4',
                'q16' => 'Rasa Asli Cemilan Indramayu',
                'q17' => 'Kemasan ekonomis harga Rp 5.000 untuk jajanan harian di kantin',
                'q18' => 'Keripik singkong Kulinjer teksturnya sangat renyah dan bumbu pedas manisnya meresap. Sukses terus untuk tim Kulinjer.',
            ],
            [
                'nisn' => '0068112014',
                'q1'  => 'SUSU KEDELAI NUMANI (Minuman Kedelai Alami Aneka Rasa)',
                'q2'  => ['Matcha Green Tea', 'Original Vanilla'],
                'q3'  => 6000,
                'q4'  => 'Nugget Ikan Laut Crispy',
                'q5'  => 18000,
                'q6'  => ['Kue Kering Khas Mangga'],
                'q7'  => 10000,
                'q8'  => 'Rice Bowl Ikan Asam Manis Sayur Segar',
                'q9'  => 15000,
                'q10' => '5',
                'q11' => '4',
                'q12' => ['Kebersihan & Legalitas Halal/P-IRT', 'Harga Bersahabat bagi Pelajar/Guru'],
                'q13' => 'Hampir Setiap Hari Sekolah (3-5 kali seminggu)',
                'q14' => 'Kantin & Galeri Kewirausahaan SMKN 2 Indramayu',
                'q15' => '5',
                'q16' => 'Susu Sehat Generasi Hebat',
                'q17' => 'Sediakan opsi botol 500ml untuk konsumsi keluarga di rumah',
                'q18' => 'Susu Kedelai Numani Matcha punya aroma teh hijau yang wangi dan manisnya seimbang. Sangat direkomendasikan untuk program gizi anak.',
            ],
            [
                'nisn' => '0068112015',
                'q1'  => 'JANGKAR MAS (Olahan Ikan & Hasil Laut Pesisir)',
                'q2'  => ['Cokelat Belgia', 'Gula Aren Organik'],
                'q3'  => 7000,
                'q4'  => 'Sambal Cumi / Teri Balado Kemasan',
                'q5'  => 24000,
                'q6'  => ['Pastel Kering Isi Abon Ikan'],
                'q7'  => 12000,
                'q8'  => 'Nasi Bakar Tongkol Rica-Rica',
                'q9'  => 16000,
                'q10' => '5',
                'q11' => '5',
                'q12' => ['Cita Rasa yang Lezat & Konsisten', 'Kebersihan & Legalitas Halal/P-IRT'],
                'q13' => '1 - 2 Kali Seminggu',
                'q14' => 'Pre-Order melalui WhatsApp Bisnis / Grup Kelas',
                'q15' => '5',
                'q16' => 'Sambal Pesisir Gurih Menggigit',
                'q17' => 'Jar kaca atau plastik food-grade tahan tumpah dengan seal alumunium',
                'q18' => 'Sambal cumi Jangkar Mas cuminya melimpah dan tidak pelit. Rasa pedasnya pas di lidah masyarakat Jawa Barat.',
            ],
            [
                'nisn' => '0068112016',
                'q1'  => 'SANTAP (Menu Makanan Siap Saji / Rice Bowl)',
                'q2'  => ['Kopi Kedelai', 'Original Vanilla'],
                'q3'  => 6000,
                'q4'  => 'Kerupuk Ikan Tenggiri Khas Pesisir',
                'q5'  => 20000,
                'q6'  => ['Stik Keju Rumput Laut', 'Pastel Kering Isi Abon Ikan'],
                'q7'  => 10000,
                'q8'  => 'Rice Bowl Cumi Saus Tiram Telur Mata Sapi',
                'q9'  => 15000,
                'q10' => '5',
                'q11' => '5',
                'q12' => ['Cita Rasa yang Lezat & Konsisten', 'Kemasan Modern & Praktis Dibawa'],
                'q13' => '1 - 2 Kali Seminggu',
                'q14' => 'Kantin & Galeri Kewirausahaan SMKN 2 Indramayu',
                'q15' => '5',
                'q16' => 'Pilihan Makan Siang Praktis Berkualitas',
                'q17' => 'Porsi hemat bundling dengan minuman Susu Numani',
                'q18' => 'Kolaborasi antara Santap dan Susu Kedelai Numani sangat bagus jika dibuat paket makan siang lengkap. Sangat menghemat pengeluaran siswa.',
            ],
            [
                'nisn' => '0068112017',
                'q1'  => 'KULINJER (Snack & Kudapan Khas Indramayu)',
                'q2'  => ['Strawberry Cream', 'Cokelat Belgia'],
                'q3'  => 5000,
                'q4'  => 'Abon Ikan Bandeng Tanpa Duri',
                'q5'  => 17000,
                'q6'  => ['Stik Keju Rumput Laut', 'Rengginang Mini Aneka Bumbu'],
                'q7'  => 9000,
                'q8'  => 'Rice Bowl Ayam Suwir Pedas Kemangi',
                'q9'  => 14000,
                'q10' => '4',
                'q11' => '4',
                'q12' => ['Harga Bersahabat bagi Pelajar/Guru', 'Mendukung Kreativitas Siswa Sekolah'],
                'q13' => '2 - 3 Kali Sebulan',
                'q14' => 'Titip Jual di Koperasi Sekolah',
                'q15' => '4',
                'q16' => 'Cemilan Enak Bikin Nagih',
                'q17' => 'Kemasan zip-lock tebal agar bisa disimpan berhari-hari',
                'q18' => 'Stik keju rumput laut Kulinjer teksturnya crunchy sekali. Cocok untuk teman nonton atau mengerjakan tugas kelompok.',
            ],
            [
                'nisn' => '0068112018',
                'q1'  => 'SUSU KEDELAI NUMANI (Minuman Kedelai Alami Aneka Rasa)',
                'q2'  => ['Original Vanilla', 'Gula Aren Organik'],
                'q3'  => 6000,
                'q4'  => 'Nugget Ikan Laut Crispy',
                'q5'  => 19000,
                'q6'  => ['Kue Kering Khas Mangga'],
                'q7'  => 10000,
                'q8'  => 'Rice Bowl Ikan Asam Manis Sayur Segar',
                'q9'  => 15000,
                'q10' => '5',
                'q11' => '4',
                'q12' => ['Kebersihan & Legalitas Halal/P-IRT', 'Cita Rasa yang Lezat & Konsisten'],
                'q13' => 'Hampir Setiap Hari Sekolah (3-5 kali seminggu)',
                'q14' => 'Kantin & Galeri Kewirausahaan SMKN 2 Indramayu',
                'q15' => '5',
                'q16' => 'Nutrisi Alami Rasa Istimewa',
                'q17' => 'Botol kurva ergonomis 250ml siap bawa',
                'q18' => 'Susu kedelai gula aren rasanya pas, tidak terlalu manis dan tidak ada endapan ampas kasar. Salut untuk tim Susu Kedelai Numani.',
            ],
            [
                'nisn' => '0068112019',
                'q1'  => 'JANGKAR MAS (Olahan Ikan & Hasil Laut Pesisir)',
                'q2'  => ['Matcha Green Tea', 'Cokelat Belgia'],
                'q3'  => 7000,
                'q4'  => 'Abon Ikan Bandeng Tanpa Duri',
                'q5'  => 21000,
                'q6'  => ['Pastel Kering Isi Abon Ikan'],
                'q7'  => 11000,
                'q8'  => 'Nasi Bakar Tongkol Rica-Rica',
                'q9'  => 16000,
                'q10' => '5',
                'q11' => '5',
                'q12' => ['Cita Rasa yang Lezat & Konsisten', 'Mendukung Kreativitas Siswa Sekolah'],
                'q13' => '1 - 2 Kali Seminggu',
                'q14' => 'Pre-Order melalui WhatsApp Bisnis / Grup Kelas',
                'q15' => '5',
                'q16' => 'Pelopor Produk Bahari Sekolah',
                'q17' => 'Kemasan pouch premium kedap udara dengan jendela transparan',
                'q18' => 'Nasi bakar tongkol dan abon bandeng Jangkar Mas adalah produk yang punya prospek paling cerah untuk dipasarkan keluar Indramayu.',
            ],
            [
                'nisn' => '0068112020',
                'q1'  => 'SANTAP (Menu Makanan Siap Saji / Rice Bowl)',
                'q2'  => ['Original Vanilla', 'Cokelat Belgia', 'Kopi Kedelai'],
                'q3'  => 6000,
                'q4'  => 'Sambal Cumi / Teri Balado Kemasan',
                'q5'  => 20000,
                'q6'  => ['Keripik Tette / Singkong Pedas Manis', 'Stik Keju Rumput Laut'],
                'q7'  => 10000,
                'q8'  => 'Rice Bowl Cumi Saus Tiram Telur Mata Sapi',
                'q9'  => 15000,
                'q10' => '5',
                'q11' => '5',
                'q12' => ['Cita Rasa yang Lezat & Konsisten', 'Harga Bersahabat bagi Pelajar/Guru', 'Kemasan Modern & Praktis Dibawa'],
                'q13' => '1 - 2 Kali Seminggu',
                'q14' => 'Kantin & Galeri Kewirausahaan SMKN 2 Indramayu',
                'q15' => '5',
                'q16' => 'Solusi Lapar Cepat & Nikmat',
                'q17' => 'Paket komplit nasi, lauk, telur, kerupuk, dan minuman',
                'q18' => 'Program kewirausahaan Banper PKK 2026 ini sangat memberdayakan siswa. Semua 4 kelompok menunjukkan inovasi produk yang nyata dan kompetitif di pasaran.',
            ],
        ];

        // Seed Respondents & Answers
        foreach ($respondentsData as $data) {
            $resp = Respondent::create([
                'survey_id'    => $survey->id,
                'nisn'         => $data['nisn'],
                'submitted_at' => now()->subDays(rand(1, 6))->subHours(rand(1, 12)),
            ]);

            Answer::create(['respondent_id' => $resp->id, 'question_id' => $createdQuestions[0]->id,  'jawaban' => $data['q1']]);
            Answer::create(['respondent_id' => $resp->id, 'question_id' => $createdQuestions[1]->id,  'jawaban' => json_encode($data['q2'])]);
            Answer::create(['respondent_id' => $resp->id, 'question_id' => $createdQuestions[2]->id,  'jawaban' => (string)$data['q3']]);
            Answer::create(['respondent_id' => $resp->id, 'question_id' => $createdQuestions[3]->id,  'jawaban' => $data['q4']]);
            Answer::create(['respondent_id' => $resp->id, 'question_id' => $createdQuestions[4]->id,  'jawaban' => (string)$data['q5']]);
            Answer::create(['respondent_id' => $resp->id, 'question_id' => $createdQuestions[5]->id,  'jawaban' => json_encode($data['q6'])]);
            Answer::create(['respondent_id' => $resp->id, 'question_id' => $createdQuestions[6]->id,  'jawaban' => (string)$data['q7']]);
            Answer::create(['respondent_id' => $resp->id, 'question_id' => $createdQuestions[7]->id,  'jawaban' => $data['q8']]);
            Answer::create(['respondent_id' => $resp->id, 'question_id' => $createdQuestions[8]->id,  'jawaban' => (string)$data['q9']]);
            Answer::create(['respondent_id' => $resp->id, 'question_id' => $createdQuestions[9]->id,  'jawaban' => $data['q10']]);
            Answer::create(['respondent_id' => $resp->id, 'question_id' => $createdQuestions[10]->id, 'jawaban' => $data['q11']]);
            Answer::create(['respondent_id' => $resp->id, 'question_id' => $createdQuestions[11]->id, 'jawaban' => json_encode($data['q12'])]);
            Answer::create(['respondent_id' => $resp->id, 'question_id' => $createdQuestions[12]->id, 'jawaban' => $data['q13']]);
            Answer::create(['respondent_id' => $resp->id, 'question_id' => $createdQuestions[13]->id, 'jawaban' => $data['q14']]);
            Answer::create(['respondent_id' => $resp->id, 'question_id' => $createdQuestions[14]->id, 'jawaban' => $data['q15']]);
            Answer::create(['respondent_id' => $resp->id, 'question_id' => $createdQuestions[15]->id, 'jawaban' => $data['q16']]);
            Answer::create(['respondent_id' => $resp->id, 'question_id' => $createdQuestions[16]->id, 'jawaban' => $data['q17']]);
            Answer::create(['respondent_id' => $resp->id, 'question_id' => $createdQuestions[17]->id, 'jawaban' => $data['q18']]);
        }

        $this->command->info('[OK] BanperPkkStudyCaseSeeder berhasil: 1 Survey Banper PKK 2026, 18 Pertanyaan, 20 Responden Terverifikasi.');
    }
}
