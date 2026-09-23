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
        // SURVEY: RISET PASAR & EVALUASI SWEET SPOT PRODUK BANPER PKK 2026
        // 4 Kelompok Siswa:
        // 1. JANGKAR MAS (Olahan Ikan & Hasil Laut - Pembimbing: Nina Komalasari, S.Pi)
        // 2. KULINJER (Snack & Kudapan Khas Indramayu - Pembimbing: Cipto Karaton, S.Pi)
        // 3. SANTAP (Makanan Siap Saji / Rice Bowl - Pembimbing: Dandi Saefudin, S.Tr.Pi)
        // 4. SUSU KEDELAI NUMANI (Minuman Kedelai Alami - Pembimbing: Dian Novitasari, S.Tr.T)
        // ═════════════════════════════════════════════════════════════════════
        $survey = Survey::create([
            'user_id'             => $admin->id,
            'judul'               => 'Riset Pasar & Evaluasi Sweet Spot Produk Kewirausahaan Banper PKK 2026',
            'deskripsi'           => 'Survey riset preferensi konsumen, uji cita rasa, kemasan, saluran distribusi, dan penentuan sweet spot harga (WTP) untuk 4 Kelompok Kewirausahaan Siswa Banper PKK 2026 SMKN 2 Indramayu: Jangkar Mas, Kulinjer, Santap, dan Susu Kedelai Numani.',
            'status'              => 'PUBLISHED',
            'limit_one_response'  => false,
            'tanggal_mulai'       => now()->subDays(14)->toDateString(),
            'tanggal_selesai'     => now()->addDays(30)->toDateString(),
        ]);

        // 18 Structured Questions using ALL permitted question types (no IMAGE_UPLOAD, no DATE)
        $questions = [
            // Q1: SINGLE_CHOICE - Kategori Paling Menarik
            [
                'tipe'  => 'SINGLE_CHOICE',
                'teks'  => 'Dari 4 unit usaha kewirausahaan Banper PKK 2026 berikut, kategori produk mana yang paling menarik perhatian Anda untuk dibeli?',
                'opsi'  => [
                    'JANGKAR MAS (Olahan Ikan & Hasil Laut Pesisir)',
                    'KULINJER (Snack & Kudapan Khas Indramayu)',
                    'SANTAP (Menu Makanan Siap Saji / Rice Bowl)',
                    'SUSU KEDELAI NUMANI (Minuman Kedelai Alami Aneka Rasa)'
                ],
                'wajib' => true,
            ],
            // Q2: MULTIPLE_CHOICE - Varian Rasa Susu Kedelai Numani
            [
                'tipe'  => 'MULTIPLE_CHOICE',
                'teks'  => '[SUSU KEDELAI NUMANI] Varian rasa susu kedelai apa saja yang paling Anda inginkan? (Boleh pilih lebih dari satu)',
                'opsi'  => [
                    'Original Vanilla',
                    'Cokelat Belgia',
                    'Matcha Green Tea',
                    'Gula Aren Organik',
                    'Strawberry Cream',
                    'Kopi Kedelai'
                ],
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
                'opsi'  => [
                    'Abon Ikan Bandeng Tanpa Duri',
                    'Kerupuk Ikan Tenggiri Khas Pesisir',
                    'Nugget Ikan Laut Crispy',
                    'Sambal Cumi / Teri Balado Kemasan'
                ],
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
                'opsi'  => [
                    'Keripik Tette / Singkong Pedas Manis',
                    'Stik Keju Rumput Laut',
                    'Rengginang Mini Aneka Bumbu',
                    'Pastel Kering Isi Abon Ikan',
                    'Kue Kering Khas Mangga'
                ],
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
                'opsi'  => [
                    'Rice Bowl Cumi Saus Tiram Telur Mata Sapi',
                    'Rice Bowl Ayam Suwir Pedas Kemangi',
                    'Rice Bowl Ikan Asam Manis Sayur Segar',
                    'Nasi Bakar Tongkol Rica-Rica'
                ],
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
                'opsi'  => [
                    'Cita Rasa yang Lezat & Konsisten',
                    'Harga Bersahabat bagi Pelajar/Guru',
                    'Kebersihan & Legalitas Halal/P-IRT',
                    'Kemasan Modern & Praktis Dibawa',
                    'Mendukung Kreativitas Siswa Sekolah'
                ],
                'wajib' => true,
            ],
            // Q13: SINGLE_CHOICE - Estimasi Frekuensi Konsumsi
            [
                'tipe'  => 'SINGLE_CHOICE',
                'teks'  => 'Seberapa sering Anda memperkirakan akan membeli produk-produk kewirausahaan Banper PKK 2026 jika sudah tersedia rutin?',
                'opsi'  => [
                    'Hampir Setiap Hari Sekolah (3-5 kali seminggu)',
                    '1 - 2 Kali Seminggu',
                    '2 - 3 Kali Sebulan',
                    'Hanya Saat Acara Khusus / Bazar Pameran'
                ],
                'wajib' => true,
            ],
            // Q14: SINGLE_CHOICE - Preferensi Saluran Pembelian
            [
                'tipe'  => 'SINGLE_CHOICE',
                'teks'  => 'Melalui jalur mana Anda paling nyaman memesan atau membeli produk dari 4 kelompok ini?',
                'opsi'  => [
                    'Kantin & Galeri Kewirausahaan SMKN 2 Indramayu',
                    'Pre-Order melalui WhatsApp Bisnis / Grup Kelas',
                    'Bazar Rutin Tiap Hari Jumat di Sekolah',
                    'Titip Jual di Koperasi Sekolah'
                ],
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
        // POOLS FOR REALISTIC GROUNDED DATA GENERATION (125 RESPONDENTS)
        // ═════════════════════════════════════════════════════════════════════
        $q1Categories = [
            'SANTAP (Menu Makanan Siap Saji / Rice Bowl)',
            'SANTAP (Menu Makanan Siap Saji / Rice Bowl)',
            'SANTAP (Menu Makanan Siap Saji / Rice Bowl)',
            'SUSU KEDELAI NUMANI (Minuman Kedelai Alami Aneka Rasa)',
            'SUSU KEDELAI NUMANI (Minuman Kedelai Alami Aneka Rasa)',
            'JANGKAR MAS (Olahan Ikan & Hasil Laut Pesisir)',
            'JANGKAR MAS (Olahan Ikan & Hasil Laut Pesisir)',
            'KULINJER (Snack & Kudapan Khas Indramayu)',
        ];

        $q2SoyFlavorsPool = [
            ['Cokelat Belgia', 'Matcha Green Tea'],
            ['Original Vanilla', 'Gula Aren Organik'],
            ['Original Vanilla', 'Strawberry Cream'],
            ['Cokelat Belgia', 'Kopi Kedelai'],
            ['Matcha Green Tea', 'Gula Aren Organik'],
            ['Original Vanilla', 'Cokelat Belgia', 'Strawberry Cream'],
            ['Gula Aren Organik'],
            ['Cokelat Belgia'],
            ['Original Vanilla'],
            ['Kopi Kedelai', 'Original Vanilla'],
            ['Strawberry Cream', 'Cokelat Belgia'],
            ['Matcha Green Tea', 'Original Vanilla'],
        ];

        // Q3 WTP Susu Kedelai Numani (Sweet Spot: Rp 5.000 - Rp 6.000)
        $q3SoyWtpPool = [5000, 5000, 6000, 5000, 6000, 7000, 5000, 6000, 8000, 5000];

        $q4JangkarProducts = [
            'Sambal Cumi / Teri Balado Kemasan',
            'Abon Ikan Bandeng Tanpa Duri',
            'Kerupuk Ikan Tenggiri Khas Pesisir',
            'Nugget Ikan Laut Crispy',
            'Sambal Cumi / Teri Balado Kemasan',
            'Abon Ikan Bandeng Tanpa Duri',
        ];

        // Q5 WTP Jangkar Mas (Sweet Spot: Rp 20.000)
        $q5JangkarWtpPool = [15000, 18000, 20000, 20000, 22000, 25000, 18000, 20000, 24000, 19000];

        $q6KulinjerPool = [
            ['Stik Keju Rumput Laut', 'Rengginang Mini Aneka Bumbu'],
            ['Keripik Tette / Singkong Pedas Manis'],
            ['Pastel Kering Isi Abon Ikan', 'Kue Kering Khas Mangga'],
            ['Keripik Tette / Singkong Pedas Manis', 'Stik Keju Rumput Laut'],
            ['Stik Keju Rumput Laut'],
            ['Rengginang Mini Aneka Bumbu'],
            ['Pastel Kering Isi Abon Ikan'],
            ['Keripik Tette / Singkong Pedas Manis', 'Kue Kering Khas Mangga'],
            ['Kue Kering Khas Mangga'],
            ['Stik Keju Rumput Laut', 'Pastel Kering Isi Abon Ikan'],
        ];

        // Q7 WTP Kulinjer (Sweet Spot: Rp 10.000)
        $q7KulinjerWtpPool = [8000, 9000, 10000, 10000, 11000, 12000, 10000, 9000, 10000, 8000];

        $q8SantapMenus = [
            'Rice Bowl Cumi Saus Tiram Telur Mata Sapi',
            'Rice Bowl Ayam Suwir Pedas Kemangi',
            'Rice Bowl Cumi Saus Tiram Telur Mata Sapi',
            'Rice Bowl Ikan Asam Manis Sayur Segar',
            'Nasi Bakar Tongkol Rica-Rica',
            'Rice Bowl Ayam Suwir Pedas Kemangi',
        ];

        // Q9 WTP Santap (Sweet Spot: Rp 15.000)
        $q9SantapWtpPool = [13000, 14000, 15000, 15000, 16000, 15000, 18000, 14000, 15000, 16000];

        // Q10 & Q11 Likert (4 & 5 dominate, occasional 3)
        $likertHighPool = ['5', '5', '4', '5', '4', '5', '4', '4', '5', '3'];

        $q12DecisionFactorsPool = [
            ['Cita Rasa yang Lezat & Konsisten', 'Harga Bersahabat bagi Pelajar/Guru', 'Kebersihan & Legalitas Halal/P-IRT'],
            ['Harga Bersahabat bagi Pelajar/Guru', 'Kemasan Modern & Praktis Dibawa'],
            ['Cita Rasa yang Lezat & Konsisten', 'Mendukung Kreativitas Siswa Sekolah'],
            ['Cita Rasa yang Lezat & Konsisten', 'Harga Bersahabat bagi Pelajar/Guru'],
            ['Kebersihan & Legalitas Halal/P-IRT', 'Kemasan Modern & Praktis Dibawa'],
            ['Harga Bersahabat bagi Pelajar/Guru', 'Cita Rasa yang Lezat & Konsisten'],
            ['Kebersihan & Legalitas Halal/P-IRT', 'Cita Rasa yang Lezat & Konsisten'],
            ['Kemasan Modern & Praktis Dibawa', 'Harga Bersahabat bagi Pelajar/Guru'],
            ['Cita Rasa yang Lezat & Konsisten', 'Kebersihan & Legalitas Halal/P-IRT'],
            ['Harga Bersahabat bagi Pelajar/Guru', 'Mendukung Kreativitas Siswa Sekolah'],
        ];

        $q13Frequencies = [
            '1 - 2 Kali Seminggu',
            'Hampir Setiap Hari Sekolah (3-5 kali seminggu)',
            '1 - 2 Kali Seminggu',
            '2 - 3 Kali Sebulan',
            '1 - 2 Kali Seminggu',
            'Hanya Saat Acara Khusus / Bazar Pameran',
        ];

        $q14Channels = [
            'Kantin & Galeri Kewirausahaan SMKN 2 Indramayu',
            'Kantin & Galeri Kewirausahaan SMKN 2 Indramayu',
            'Pre-Order melalui WhatsApp Bisnis / Grup Kelas',
            'Bazar Rutin Tiap Hari Jumat di Sekolah',
            'Titip Jual di Koperasi Sekolah',
            'Kantin & Galeri Kewirausahaan SMKN 2 Indramayu',
        ];

        $q16Taglines = [
            'Rasa Juara Karya Siswa SMKN 2',
            'Sehat Alami Setiap Hari',
            'Cita Rasa Bahari Asli Indramayu',
            'Ngemil Asik Khas Indramayu',
            'Makan Enak, Cepat & Higienis',
            'Kesegaran Kedelai Asli Pelajar',
            'Hasil Laut Juara SMKN 2',
            'Krenyes Gurih Tradisional Modern',
            'Santapan Mantap Rasa Hebat',
            'Energi Kedelai Sehat Setiap Saat',
            'Ikan Olahan Berkualitas Pesisir',
            'Kelezatan Nasi Bowl Idola',
            'Rasa Asli Cemilan Indramayu',
            'Susu Sehat Generasi Hebat',
            'Sambal Pesisir Gurih Menggigit',
            'Pilihan Makan Siang Praktis Berkualitas',
            'Cemilan Enak Bikin Nagih',
            'Nutrisi Alami Rasa Istimewa',
            'Pelopor Produk Bahari Sekolah',
            'Solusi Lapar Cepat & Nikmat',
            'Karya Mandiri Rasa Sejati',
            'Inovasi Kuliner Pelajar Juara',
            'Lezat Berkelas Harga Pas',
            'Pangan Sehat Generasi Maju',
            'Asli Indramayu Rasa Bermutu',
        ];

        $q17Packagings = [
            'Paket hemat kombo Rice Bowl Santap plus Susu Kedelai dingin seharga Rp 20.000',
            'Botol 250ml siap minum dengan segel plastik steril',
            'Standing pouch ziplock agar kerupuk ikan tetap renyah tahan lama',
            'Pouch travel pack 100 gram dengan varian pedas berlevel',
            'Paper bowl ramah lingkungan yang tahan panas microwave',
            'Cup press atau botol 250ml dengan label komposisi jelas',
            'Frozen pack vakum 250 gram untuk nugget dan abon ikan',
            'Kemasan toples mini untuk hampers hari raya atau acara sekolah',
            'Porsi regular dan jumbo dengan opsi tambahan sambal sachet',
            'Pouch standing dengan sedotan higienis terbungkus plastik',
            'Toples segel alumunium foil untuk abon bandeng agar awet 6 bulan',
            'Bowl bersekat pemisah kuah/saus agar tidak lembek saat dibawa jalan',
            'Kemasan ekonomis harga Rp 5.000 untuk jajanan harian di kantin',
            'Sediakan opsi botol 500ml untuk konsumsi keluarga di rumah',
            'Jar kaca atau plastik food-grade tahan tumpah dengan seal alumunium',
            'Porsi hemat bundling dengan minuman Susu Numani',
            'Kemasan zip-lock tebal agar bisa disimpan berhari-hari',
            'Botol kurva ergonomis 250ml siap bawa',
            'Kemasan pouch premium kedap udara dengan jendela transparan',
            'Paket komplit nasi, lauk, telur, kerupuk, dan minuman',
        ];

        $q18Feedbacks = [
            'Untuk kelompok Santap, pastikan nasi disajikan hangat dan sambal cuminya tidak terlalu berminyak. Susu kedelai Numani cokelat sangat segar jika disajikan dingin.',
            'Susu kedelai Numani sangat cocok untuk alternatif minuman sehat pengganti es teh manis. Pertahankan rasa kedelai alami tanpa bau langu.',
            'Kelompok Jangkar Mas punya potensi besar untuk oleh-oleh khas daerah. Desain labelnya harus dibuat elegan agar bisa dititipkan di minimarket dan rest area.',
            'Camilan Kulinjer sangat pas untuk teman belajar kelompok. Tingkat kepedasannya mohon dibuat pilihan level 1 sampai 3.',
            'Penyajian Rice Bowl Santap harus cepat saat jam istirahat agar siswa tidak telat masuk kelas. Rasa cumi saus tiramnya sangat istimewa.',
            'Susu kedelai Numani rasa stroberi sangat disukai siswi. Harganya kalau bisa tetap Rp 5.000 - Rp 6.000 agar ramah kantong siswa.',
            'Jangkar Mas membuktikan potensi jurusan perikanan SMKN 2 Indramayu. Nugget ikannya gurih dan tidak amis sama sekali, sangat cocok untuk bekal anak.',
            'Kulinjer perlu memanfaatkan olahan mangga khas Indramayu lebih optimal sebagai pembeda dengan camilan daerah lain.',
            'Pilihan lauk seafood di Santap sangat sesuai dengan ciri khas pesisir Indramayu. Pertahankan bumbu gurihnya.',
            'Kopi kedelai Numani sangat unik! Rasanya pas untuk teman belajar di perpustakaan tanpa membuat asam lambung naik.',
            'Abon bandeng Jangkar Mas tanpa duri sangat disukai guru dan orang tua murid. Kemasannya sudah layak masuk supermarket.',
            'Porsi nasi dan lauk ayam suwir kemangi di Santap sangat pas mengenyangkan. Pelayanan ramah dan pesanan via WA cepat ditanggapi.',
            'Keripik singkong Kulinjer teksturnya sangat renyah dan bumbu pedas manisnya meresap. Sukses terus untuk tim Kulinjer.',
            'Susu Kedelai Numani Matcha punya aroma teh hijau yang wangi dan manisnya seimbang. Sangat direkomendasikan untuk program gizi anak.',
            'Sambal cumi Jangkar Mas cuminya melimpah dan tidak pelit. Rasa pedasnya pas di lidah masyarakat Jawa Barat.',
            'Kolaborasi antara Santap dan Susu Kedelai Numani sangat bagus jika dibuat paket makan siang lengkap. Sangat menghemat pengeluaran siswa.',
            'Stik keju rumput laut Kulinjer teksturnya crunchy sekali. Cocok untuk teman nonton atau mengerjakan tugas kelompok.',
            'Susu kedelai gula aren rasanya pas, tidak terlalu manis dan tidak ada endapan ampas kasar. Salut untuk tim Susu Kedelai Numani.',
            'Nasi bakar tongkol dan abon bandeng Jangkar Mas adalah produk yang punya prospek paling cerah untuk dipasarkan keluar Indramayu.',
            'Program kewirausahaan Banper PKK 2026 ini sangat memberdayakan siswa. Semua 4 kelompok menunjukkan inovasi produk yang nyata dan kompetitif di pasaran.',
            'Seluruh produk sudah sangat baik, tingkatkan konsistensi stok agar tidak cepat habis saat jam istirahat kedua.',
            'Promosi di media sosial seperti Instagram dan TikTok perlu diperbanyak dengan video proses pembuatan yang higienis.',
            'Sediakan pembayaran non-tunai seperti QRIS di stan galeri agar transaksi siswa dan guru lebih praktis.',
            'Rasa bumbu olahan laut Jangkar Mas sangat kaya rempah, sangat membanggakan sekolah perikanan kita.',
            'Pertahankan kualitas bahan baku alami tanpa pengawet berbahaya agar konsumen setia terus bertambah.',
        ];

        // ═════════════════════════════════════════════════════════════════════
        // GENERATE 125 RESPONDENTS & ANSWERS
        // ═════════════════════════════════════════════════════════════════════
        $totalTargetRespondents = 125;

        for ($i = 1; $i <= $totalTargetRespondents; $i++) {
            $nisn = sprintf('006811%04d', $i);
            $submittedAt = now()->subDays(rand(1, 12))->subHours(rand(1, 14))->subMinutes(rand(1, 55));

            $resp = Respondent::create([
                'survey_id'    => $survey->id,
                'nisn'         => $nisn,
                'submitted_at' => $submittedAt,
            ]);

            $ansQ1  = $q1Categories[($i - 1) % count($q1Categories)];
            $ansQ2  = $q2SoyFlavorsPool[($i - 1) % count($q2SoyFlavorsPool)];
            $ansQ3  = (string) $q3SoyWtpPool[($i - 1) % count($q3SoyWtpPool)];
            $ansQ4  = $q4JangkarProducts[($i - 1) % count($q4JangkarProducts)];
            $ansQ5  = (string) $q5JangkarWtpPool[($i - 1) % count($q5JangkarWtpPool)];
            $ansQ6  = $q6KulinjerPool[($i - 1) % count($q6KulinjerPool)];
            $ansQ7  = (string) $q7KulinjerWtpPool[($i - 1) % count($q7KulinjerWtpPool)];
            $ansQ8  = $q8SantapMenus[($i - 1) % count($q8SantapMenus)];
            $ansQ9  = (string) $q9SantapWtpPool[($i - 1) % count($q9SantapWtpPool)];
            $ansQ10 = $likertHighPool[($i - 1) % count($likertHighPool)];
            $ansQ11 = $likertHighPool[($i + 1) % count($likertHighPool)];
            $ansQ12 = $q12DecisionFactorsPool[($i - 1) % count($q12DecisionFactorsPool)];
            $ansQ13 = $q13Frequencies[($i - 1) % count($q13Frequencies)];
            $ansQ14 = $q14Channels[($i - 1) % count($q14Channels)];
            $ansQ15 = $likertHighPool[($i + 2) % count($likertHighPool)];
            $ansQ16 = $q16Taglines[($i - 1) % count($q16Taglines)];
            $ansQ17 = $q17Packagings[($i - 1) % count($q17Packagings)];
            $ansQ18 = $q18Feedbacks[($i - 1) % count($q18Feedbacks)];

            Answer::create(['respondent_id' => $resp->id, 'question_id' => $createdQuestions[0]->id,  'jawaban' => $ansQ1]);
            Answer::create(['respondent_id' => $resp->id, 'question_id' => $createdQuestions[1]->id,  'jawaban' => json_encode($ansQ2)]);
            Answer::create(['respondent_id' => $resp->id, 'question_id' => $createdQuestions[2]->id,  'jawaban' => $ansQ3]);
            Answer::create(['respondent_id' => $resp->id, 'question_id' => $createdQuestions[3]->id,  'jawaban' => $ansQ4]);
            Answer::create(['respondent_id' => $resp->id, 'question_id' => $createdQuestions[4]->id,  'jawaban' => $ansQ5]);
            Answer::create(['respondent_id' => $resp->id, 'question_id' => $createdQuestions[5]->id,  'jawaban' => json_encode($ansQ6)]);
            Answer::create(['respondent_id' => $resp->id, 'question_id' => $createdQuestions[6]->id,  'jawaban' => $ansQ7]);
            Answer::create(['respondent_id' => $resp->id, 'question_id' => $createdQuestions[7]->id,  'jawaban' => $ansQ8]);
            Answer::create(['respondent_id' => $resp->id, 'question_id' => $createdQuestions[8]->id,  'jawaban' => $ansQ9]);
            Answer::create(['respondent_id' => $resp->id, 'question_id' => $createdQuestions[9]->id,  'jawaban' => $ansQ10]);
            Answer::create(['respondent_id' => $resp->id, 'question_id' => $createdQuestions[10]->id, 'jawaban' => $ansQ11]);
            Answer::create(['respondent_id' => $resp->id, 'question_id' => $createdQuestions[11]->id, 'jawaban' => json_encode($ansQ12)]);
            Answer::create(['respondent_id' => $resp->id, 'question_id' => $createdQuestions[12]->id, 'jawaban' => $ansQ13]);
            Answer::create(['respondent_id' => $resp->id, 'question_id' => $createdQuestions[13]->id, 'jawaban' => $ansQ14]);
            Answer::create(['respondent_id' => $resp->id, 'question_id' => $createdQuestions[14]->id, 'jawaban' => $ansQ15]);
            Answer::create(['respondent_id' => $resp->id, 'question_id' => $createdQuestions[15]->id, 'jawaban' => $ansQ16]);
            Answer::create(['respondent_id' => $resp->id, 'question_id' => $createdQuestions[16]->id, 'jawaban' => $ansQ17]);
            Answer::create(['respondent_id' => $resp->id, 'question_id' => $createdQuestions[17]->id, 'jawaban' => $ansQ18]);
        }

        $this->command->info("[OK] BanperPkkStudyCaseSeeder berhasil: 1 Survey Banper PKK 2026, 18 Pertanyaan, {$totalTargetRespondents} Responden Terverifikasi.");
    }
}
