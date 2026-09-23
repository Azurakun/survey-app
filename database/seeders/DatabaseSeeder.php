<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Survey;
use App\Models\Question;
use App\Models\Respondent;
use App\Models\Answer;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ─── Admin & Viewer Users ─────────────────────────────────────
        $admin = User::firstOrCreate(
            ['email' => 'admin@smkn2indramayu.sch.id'],
            [
                'name'     => 'Admin Kewirausahaan',
                'password' => Hash::make('admin123'),
                'role'     => 'ADMIN',
            ]
        );
        $admin->update(['role' => 'ADMIN']);

        $viewer = User::firstOrCreate(
            ['email' => 'viewer@smkn2indramayu.sch.id'],
            [
                'name'     => 'Guru Penilai / Pengamat',
                'password' => Hash::make('viewer123'),
                'role'     => 'VIEWER',
            ]
        );
        $viewer->update(['role' => 'VIEWER']);

        // Wipe old survey data to populate 3 brand new 180-degree distinct surveys
        Survey::query()->delete();

        // ═════════════════════════════════════════════════════════════════════
        // SURVEY 1: KULINER & OLAHAN MANGGA INOVATIF (34 RESPONDEN)
        // ═════════════════════════════════════════════════════════════════════
        $survey1 = Survey::create([
            'user_id'         => $admin->id,
            'judul'           => 'Riset Pasar Inovasi Produk Kuliner Olahan Mangga Khas Indramayu',
            'deskripsi'       => 'Survey riset pasar mengukur minat konsumen, estimasi daya beli (WTP), dan masukan kemasan terhadap produk inovasi kuliner olahan mangga buatan siswa Kewirausahaan.',
            'status'          => 'PUBLISHED',
            'tanggal_mulai'   => now()->subDays(15)->toDateString(),
            'tanggal_selesai' => now()->addDays(15)->toDateString(),
        ]);

        $q1_1 = Question::create([
            'survey_id'       => $survey1->id,
            'tipe_pertanyaan' => 'SINGLE_CHOICE',
            'teks_pertanyaan' => 'Seberapa tertarik Anda membeli produk olahan mangga inovatif karya siswa?',
            'opsi_jawaban'    => json_encode(['Sangat Tertarik', 'Tertarik', 'Cukup Tertarik', 'Kurang Tertarik', 'Tidak Tertarik']),
            'wajib_diisi'     => true,
            'urutan'          => 1,
        ]);

        $q1_2 = Question::create([
            'survey_id'       => $survey1->id,
            'tipe_pertanyaan' => 'MULTIPLE_CHOICE',
            'teks_pertanyaan' => 'Olahan varian mangga apa yang paling Anda minati? (Boleh pilih lebih dari satu)',
            'opsi_jawaban'    => json_encode(['Keripik Mangga Crispy', 'Dodol Mangga Premium', 'Selai Mangga Artisan', 'Jus Mangga Kemasan', 'Sirup Mangga Organik']),
            'wajib_diisi'     => true,
            'urutan'          => 2,
        ]);

        $q1_3 = Question::create([
            'survey_id'       => $survey1->id,
            'tipe_pertanyaan' => 'LIKERT',
            'teks_pertanyaan' => 'Seberapa puas Anda dengan desain visual kemasan dan higienitas produk kuliner siswa?',
            'opsi_jawaban'    => json_encode(['1','2','3','4','5']),
            'wajib_diisi'     => true,
            'urutan'          => 3,
        ]);

        $q1_4 = Question::create([
            'survey_id'       => $survey1->id,
            'tipe_pertanyaan' => 'NUMBER',
            'teks_pertanyaan' => 'Berapa harga (Rupiah) yang bersedia Anda bayar untuk 1 kemasan porsi hemat (200-250 gram)?',
            'wajib_diisi'     => true,
            'urutan'          => 4,
        ]);

        $q1_5 = Question::create([
            'survey_id'       => $survey1->id,
            'tipe_pertanyaan' => 'SHORT_TEXT',
            'teks_pertanyaan' => 'Melalui saluran mana Anda lebih suka membeli produk kuliner ini?',
            'wajib_diisi'     => false,
            'urutan'          => 5,
        ]);

        $q1_6 = Question::create([
            'survey_id'       => $survey1->id,
            'tipe_pertanyaan' => 'LONG_TEXT',
            'teks_pertanyaan' => 'Masukan dan saran Anda untuk meningkatkan cita rasa dan kemasan produk kuliner siswa.',
            'wajib_diisi'     => false,
            'urutan'          => 6,
        ]);

        // Generate 34 respondents for Survey 1
        $singles1 = ['Sangat Tertarik', 'Tertarik', 'Sangat Tertarik', 'Tertarik', 'Cukup Tertarik', 'Sangat Tertarik', 'Tertarik', 'Kurang Tertarik'];
        $multis1  = [
            ['Keripik Mangga Crispy', 'Dodol Mangga Premium'],
            ['Keripik Mangga Crispy', 'Jus Mangga Kemasan'],
            ['Dodol Mangga Premium', 'Selai Mangga Artisan'],
            ['Sirup Mangga Organik', 'Keripik Mangga Crispy'],
            ['Jus Mangga Kemasan', 'Dodol Mangga Premium']
        ];
        $prices1  = [18000, 20000, 25000, 22000, 30000, 15000, 28000, 35000, 24000, 20000];
        $channels1 = ['Kantin Sekolah', 'WhatsApp Group', 'Minimarket Terdekat', 'Instagram / Shopee', 'Stand Bazar Sekolah'];
        $feedbacks1 = [
            'Rasa mangganya sangat terasa alami, tolong kemasannya dibuat ziplock.',
            'Kemasan sudah bagus dan higienis, harga Rp 20.000 sangat pas.',
            'Tambahkan varian pedas manis untuk keripik mangga.',
            'Produknya unik sekali, sangat cocok untuk oleh-oleh khas Indramayu.',
            'Mohon cantumkan tanggal kadaluarsa dan izin P-IRT di kemasan.',
            'Porsi keripik agak diperbanyak sedikit biar lebih puas.'
        ];

        for ($i = 1; $i <= 34; $i++) {
            $nisn = sprintf('0012345%03d', $i);
            $resp = Respondent::create([
                'survey_id'    => $survey1->id,
                'nisn'         => $nisn,
                'submitted_at' => now()->subDays(rand(1, 14))->subHours(rand(1, 10)),
            ]);

            $singleVal = $singles1[($i - 1) % count($singles1)];
            $multiVal  = $multis1[($i - 1) % count($multis1)];
            $likertVal = (string) rand(3, 5);
            if ($i % 7 === 0) $likertVal = '2';
            $priceVal  = $prices1[($i - 1) % count($prices1)];
            $chanVal   = $channels1[($i - 1) % count($channels1)];
            $feedVal   = $feedbacks1[($i - 1) % count($feedbacks1)];

            Answer::create(['respondent_id' => $resp->id, 'question_id' => $q1_1->id, 'jawaban' => $singleVal]);
            Answer::create(['respondent_id' => $resp->id, 'question_id' => $q1_2->id, 'jawaban' => json_encode($multiVal)]);
            Answer::create(['respondent_id' => $resp->id, 'question_id' => $q1_3->id, 'jawaban' => $likertVal]);
            Answer::create(['respondent_id' => $resp->id, 'question_id' => $q1_4->id, 'jawaban' => (string)$priceVal]);
            Answer::create(['respondent_id' => $resp->id, 'question_id' => $q1_5->id, 'jawaban' => $chanVal]);
            Answer::create(['respondent_id' => $resp->id, 'question_id' => $q1_6->id, 'jawaban' => $feedVal]);
        }


        // ═════════════════════════════════════════════════════════════════════
        // SURVEY 2: TEKNOLOGI & JASA SERVIS PC / LAPTOP (32 RESPONDEN)
        // ═════════════════════════════════════════════════════════════════════
        $survey2 = Survey::create([
            'user_id'         => $admin->id,
            'judul'           => 'Riset Pasar Jasa Servis Gadget & Rakit PC Custom Siswa TKJ',
            'deskripsi'       => 'Survey mengukur potensi permintaan pasar, tingkat kepercayaan konsumen, dan daya beli terhadap jasa perbaikan laptop, pembersihan PC, dan rakitan PC gaming karya siswa jurusan TKJ.',
            'status'          => 'PUBLISHED',
            'tanggal_mulai'   => now()->subDays(10)->toDateString(),
            'tanggal_selesai' => now()->addDays(20)->toDateString(),
        ]);

        $q2_1 = Question::create([
            'survey_id'       => $survey2->id,
            'tipe_pertanyaan' => 'SINGLE_CHOICE',
            'teks_pertanyaan' => 'Seberapa besar kebutuhan Anda terhadap jasa perawatan & perbaikan perangkat komputer/laptop?',
            'opsi_jawaban'    => json_encode(['Sangat Dibutuhkan', 'Cukup Dibutuhkan', 'Kadang-kadang', 'Jarang Butuh', 'Tidak Butuh']),
            'wajib_diisi'     => true,
            'urutan'          => 1,
        ]);

        $q2_2 = Question::create([
            'survey_id'       => $survey2->id,
            'tipe_pertanyaan' => 'MULTIPLE_CHOICE',
            'teks_pertanyaan' => 'Layanan servis teknologi apa yang paling Anda butuhkan saat ini? (Boleh pilih lebih dari satu)',
            'opsi_jawaban'    => json_encode(['Clean Dust & Ganti Thermal Paste', 'Rakit PC Custom Gaming/Editing', 'Instalasi OS & Software', 'Upgrade SSD/RAM Laptop', 'Perbaikan Keyboard/Layar']),
            'wajib_diisi'     => true,
            'urutan'          => 2,
        ]);

        $q2_3 = Question::create([
            'survey_id'       => $survey2->id,
            'tipe_pertanyaan' => 'LIKERT',
            'teks_pertanyaan' => 'Seberapa percaya Anda terhadap garansi layanan dan pengerjaan teknisi siswa SMKN 2 Indramayu?',
            'opsi_jawaban'    => json_encode(['1','2','3','4','5']),
            'wajib_diisi'     => true,
            'urutan'          => 3,
        ]);

        $q2_4 = Question::create([
            'survey_id'       => $survey2->id,
            'tipe_pertanyaan' => 'NUMBER',
            'teks_pertanyaan' => 'Berapa tarif biaya jasa perbaikan/maintenance ringan yang menurut Anda ideal (dalam Rupiah)?',
            'opsi_jawaban'    => null,
            'wajib_diisi'     => true,
            'urutan'          => 4,
        ]);

        $q2_5 = Question::create([
            'survey_id'       => $survey2->id,
            'tipe_pertanyaan' => 'SHORT_TEXT',
            'teks_pertanyaan' => 'Lokasi penyerahan unit mana yang paling Anda sukai?',
            'opsi_jawaban'    => null,
            'wajib_diisi'     => false,
            'urutan'          => 5,
        ]);

        $q2_6 = Question::create([
            'survey_id'       => $survey2->id,
            'tipe_pertanyaan' => 'LONG_TEXT',
            'teks_pertanyaan' => 'Apa ekspektasi atau fitur jaminan tambahan yang Anda harapkan dari layanan servis ini?',
            'opsi_jawaban'    => null,
            'wajib_diisi'     => false,
            'urutan'          => 6,
        ]);

        // Generate 32 respondents for Survey 2
        $singles2 = ['Sangat Dibutuhkan', 'Cukup Dibutuhkan', 'Sangat Dibutuhkan', 'Cukup Dibutuhkan', 'Kadang-kadang'];
        $multis2  = [
            ['Clean Dust & Ganti Thermal Paste', 'Upgrade SSD/RAM Laptop'],
            ['Rakit PC Custom Gaming/Editing', 'Instalasi OS & Software'],
            ['Upgrade SSD/RAM Laptop', 'Perbaikan Keyboard/Layar'],
            ['Clean Dust & Ganti Thermal Paste', 'Rakit PC Custom Gaming/Editing'],
            ['Instalasi OS & Software']
        ];
        $prices2  = [75000, 100000, 120000, 150000, 200000, 85000, 250000, 100000];
        $locations2 = ['Lab Komputer TKJ SMK', 'Layanan Antar-Jemput Rumah', 'Booth TeFA SMKN 2', 'Lab Komputer TKJ SMK'];
        $feedbacks2 = [
            'Berikan garansi minimal 1 bulan untuk pengerjaan perbaikan.',
            'Sangat membantu karena biaya di toko komputer luar cukup mahal.',
            'Semoga ada layanan konsultasi pemilihan spesifikasi PC gratis.',
            'Pengerjaan harap tepat waktu dan ada tanda terima unit resmi.',
            'Tambahkan layanan instalasi software desain grafis dan editing.'
        ];

        for ($i = 1; $i <= 32; $i++) {
            $nisn = sprintf('0023456%03d', $i);
            $resp = Respondent::create([
                'survey_id'    => $survey2->id,
                'nisn'         => $nisn,
                'submitted_at' => now()->subDays(rand(1, 9))->subHours(rand(1, 10)),
            ]);

            $singleVal = $singles2[($i - 1) % count($singles2)];
            $multiVal  = $multis2[($i - 1) % count($multis2)];
            $likertVal = (string) rand(4, 5);
            if ($i % 8 === 0) $likertVal = '3';
            $priceVal  = $prices2[($i - 1) % count($prices2)];
            $locVal    = $locations2[($i - 1) % count($locations2)];
            $feedVal   = $feedbacks2[($i - 1) % count($feedbacks2)];

            Answer::create(['respondent_id' => $resp->id, 'question_id' => $q2_1->id, 'jawaban' => $singleVal]);
            Answer::create(['respondent_id' => $resp->id, 'question_id' => $q2_2->id, 'jawaban' => json_encode($multiVal)]);
            Answer::create(['respondent_id' => $resp->id, 'question_id' => $q2_3->id, 'jawaban' => $likertVal]);
            Answer::create(['respondent_id' => $resp->id, 'question_id' => $q2_4->id, 'jawaban' => (string)$priceVal]);
            Answer::create(['respondent_id' => $resp->id, 'question_id' => $q2_5->id, 'jawaban' => $locVal]);
            Answer::create(['respondent_id' => $resp->id, 'question_id' => $q2_6->id, 'jawaban' => $feedVal]);
        }


        // ═════════════════════════════════════════════════════════════════════
        // SURVEY 3: FASHION & CRAFTING ECO-PRINT BATIK (35 RESPONDEN)
        // ═════════════════════════════════════════════════════════════════════
        $survey3 = Survey::create([
            'user_id'         => $admin->id,
            'judul'           => 'Riset Pasar Produk Aksesori Fashion & Eco-Print Batik Indramayu',
            'deskripsi'       => 'Survey riset pasar mengukur minat konsumen terhadap produk kerajinan tangan fashion eco-print dan batik khas Indramayu berbasis ramah lingkungan buatan siswa Kriya.',
            'status'          => 'PUBLISHED',
            'tanggal_mulai'   => now()->subDays(12)->toDateString(),
            'tanggal_selesai' => now()->addDays(18)->toDateString(),
        ]);

        $q3_1 = Question::create([
            'survey_id'       => $survey3->id,
            'tipe_pertanyaan' => 'SINGLE_CHOICE',
            'teks_pertanyaan' => 'Seberapa tertarik Anda mengoleksi aksesoris fashion bermotif Batik Eco-Print buatan tangan siswa?',
            'opsi_jawaban'    => json_encode(['Sangat Tertarik', 'Tertarik', 'Cukup Tertarik', 'Kurang Tertarik', 'Tidak Tertarik']),
            'wajib_diisi'     => true,
            'urutan'          => 1,
        ]);

        $q3_2 = Question::create([
            'survey_id'       => $survey3->id,
            'tipe_pertanyaan' => 'MULTIPLE_CHOICE',
            'teks_pertanyaan' => 'Produk crafting fashion apa yang paling ingin Anda beli? (Boleh pilih lebih dari satu)',
            'opsi_jawaban'    => json_encode(['Tote Bag Eco-Print', 'Pouch Kosmetik Batik', 'Syal Motif Khas Indramayu', 'Card Holder Kulit Batik', 'Outer Fashion Eco-Print']),
            'wajib_diisi'     => true,
            'urutan'          => 2,
        ]);

        $q3_3 = Question::create([
            'survey_id'       => $survey3->id,
            'tipe_pertanyaan' => 'LIKERT',
            'teks_pertanyaan' => 'Seberapa tinggi nilai estetika dan keunikan produk kerajinan tangan siswa menurut Anda?',
            'opsi_jawaban'    => json_encode(['1','2','3','4','5']),
            'wajib_diisi'     => true,
            'urutan'          => 3,
        ]);

        $q3_4 = Question::create([
            'survey_id'       => $survey3->id,
            'tipe_pertanyaan' => 'NUMBER',
            'teks_pertanyaan' => 'Berapa kisaran harga yang menurut Anda pantas untuk 1 item tote bag/aksesoris eco-print ini (dalam Rupiah)?',
            'opsi_jawaban'    => null,
            'wajib_diisi'     => true,
            'urutan'          => 4,
        ]);

        $q3_5 = Question::create([
            'survey_id'       => $survey3->id,
            'tipe_pertanyaan' => 'SHORT_TEXT',
            'teks_pertanyaan' => 'Untuk momen apa Anda biasanya membeli produk kerajinan batik ini?',
            'opsi_jawaban'    => null,
            'wajib_diisi'     => false,
            'urutan'          => 5,
        ]);

        $q3_6 = Question::create([
            'survey_id'       => $survey3->id,
            'tipe_pertanyaan' => 'LONG_TEXT',
            'teks_pertanyaan' => 'Apa keunikan atau pesan budaya yang perlu ditonjolkan pada kemasan produk kerajinan siswa?',
            'opsi_jawaban'    => null,
            'wajib_diisi'     => false,
            'urutan'          => 6,
        ]);

        // Generate 35 respondents for Survey 3
        $singles3 = ['Sangat Tertarik', 'Tertarik', 'Sangat Tertarik', 'Tertarik', 'Cukup Tertarik'];
        $multis3  = [
            ['Tote Bag Eco-Print', 'Pouch Kosmetik Batik'],
            ['Outer Fashion Eco-Print', 'Syal Motif Khas Indramayu'],
            ['Tote Bag Eco-Print', 'Card Holder Kulit Batik'],
            ['Pouch Kosmetik Batik', 'Syal Motif Khas Indramayu'],
            ['Tote Bag Eco-Print', 'Outer Fashion Eco-Print']
        ];
        $prices3  = [45000, 65000, 75000, 85000, 120000, 50000, 95000, 150000];
        $moments3 = ['Hadiah / Kado Spesial', 'Koleksi Pribadi', 'Souvenir Acara / Pameran', 'Koleksi Pribadi'];
        $feedbacks3 = [
            'Motif dedaunan alami sangat cantik dan beda dari produk pabrikan.',
            'Tambahkan hangtag penjelasan tentang pewarnaan alami ramah lingkungan.',
            'Jahitannya sangat rapi, layak masuk pameran fashion lokal.',
            'Beri kotak kemasan bernuansa etnik yang cocok langsung untuk hadiah.',
            'Harga Rp 65.000 - Rp 85.000 sangat layak untuk karya handmade.'
        ];

        for ($i = 1; $i <= 35; $i++) {
            $nisn = sprintf('0034567%03d', $i);
            $resp = Respondent::create([
                'survey_id'    => $survey3->id,
                'nisn'         => $nisn,
                'submitted_at' => now()->subDays(rand(1, 11))->subHours(rand(1, 10)),
            ]);

            $singleVal = $singles3[($i - 1) % count($singles3)];
            $multiVal  = $multis3[($i - 1) % count($multis3)];
            $likertVal = (string) rand(4, 5);
            if ($i % 9 === 0) $likertVal = '3';
            $priceVal  = $prices3[($i - 1) % count($prices3)];
            $momVal    = $moments3[($i - 1) % count($moments3)];
            $feedVal   = $feedbacks3[($i - 1) % count($feedbacks3)];

            Answer::create(['respondent_id' => $resp->id, 'question_id' => $q3_1->id, 'jawaban' => $singleVal]);
            Answer::create(['respondent_id' => $resp->id, 'question_id' => $q3_2->id, 'jawaban' => json_encode($multiVal)]);
            Answer::create(['respondent_id' => $resp->id, 'question_id' => $q3_3->id, 'jawaban' => $likertVal]);
            Answer::create(['respondent_id' => $resp->id, 'question_id' => $q3_4->id, 'jawaban' => (string)$priceVal]);
            Answer::create(['respondent_id' => $resp->id, 'question_id' => $q3_5->id, 'jawaban' => $momVal]);
            Answer::create(['respondent_id' => $resp->id, 'question_id' => $q3_6->id, 'jawaban' => $feedVal]);
        }

        $this->command->info('[OK] DatabaseSeeder selesai: 3 Survey sample + TEFA TKJ.');

        // ─── TEFA TKJ Survey: 500 Responden ───────────────────────────
        $this->call(TefaTkjSurveySeeder::class);
    }
}
