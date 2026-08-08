<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Survey;
use App\Models\Question;
use App\Models\Respondent;
use App\Models\Answer;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ─── Admin User ────────────────────────────────────────────────
        $admin = User::firstOrCreate(
            ['email' => 'admin@smkn2indramayu.sch.id'],
            [
                'name'     => 'Admin SMKN 2 Indramayu',
                'password' => Hash::make('admin123'),
            ]
        );

        // ─── Survey 1: PUBLISHED (with full data) ─────────────────────
        $survey = Survey::create([
            'user_id'         => $admin->id,
            'judul'           => 'Riset Pasar Inovasi Produk Olahan Mangga Indramayu 2026',
            'deskripsi'       => 'Survey ini bertujuan untuk mengukur minat pasar, estimasi harga beli, dan masukan konsumen terhadap produk inovasi olahan mangga yang dikembangkan oleh siswa program Kewirausahaan SMKN 2 Indramayu.',
            'status'          => 'PUBLISHED',
            'tanggal_mulai'   => now()->subDays(14)->toDateString(),
            'tanggal_selesai' => now()->addDays(14)->toDateString(),
        ]);

        // 8 Questions (all types)
        $q1 = Question::create([
            'survey_id'       => $survey->id,
            'tipe_pertanyaan' => 'SINGLE_CHOICE',
            'teks_pertanyaan' => 'Seberapa tertarik Anda mencoba produk olahan mangga inovasi dari siswa SMKN 2 Indramayu?',
            'opsi_jawaban'    => json_encode(['Sangat Tertarik', 'Tertarik', 'Cukup Tertarik', 'Kurang Tertarik', 'Tidak Tertarik']),
            'wajib_diisi'     => true,
            'urutan'          => 1,
        ]);

        $q2 = Question::create([
            'survey_id'       => $survey->id,
            'tipe_pertanyaan' => 'MULTIPLE_CHOICE',
            'teks_pertanyaan' => 'Produk olahan mangga mana yang paling menarik minat Anda? (Boleh pilih lebih dari satu)',
            'opsi_jawaban'    => json_encode(['Dodol Mangga Premium', 'Keripik Mangga Crispy', 'Selai Mangga Artisan', 'Mangga Kering Organik', 'Jus Mangga Segar Kemasan', 'Es Krim Mangga']),
            'wajib_diisi'     => true,
            'urutan'          => 2,
        ]);

        $q3 = Question::create([
            'survey_id'       => $survey->id,
            'tipe_pertanyaan' => 'LIKERT',
            'teks_pertanyaan' => 'Seberapa puas Anda dengan tampilan kemasan produk olahan mangga yang didesain siswa?',
            'opsi_jawaban'    => json_encode(['1','2','3','4','5']),
            'wajib_diisi'     => true,
            'urutan'          => 3,
        ]);

        $q4 = Question::create([
            'survey_id'       => $survey->id,
            'tipe_pertanyaan' => 'NUMBER',
            'teks_pertanyaan' => 'Berapa harga yang bersedia Anda bayar untuk 1 kemasan (250gr) produk olahan mangga ini (dalam Rupiah)?',
            'wajib_diisi'     => true,
            'urutan'          => 4,
        ]);

        $q5 = Question::create([
            'survey_id'       => $survey->id,
            'tipe_pertanyaan' => 'SHORT_TEXT',
            'teks_pertanyaan' => 'Di mana lokasi pembelian produk ini yang paling Anda inginkan?',
            'wajib_diisi'     => false,
            'urutan'          => 5,
        ]);

        $q6 = Question::create([
            'survey_id'       => $survey->id,
            'tipe_pertanyaan' => 'LONG_TEXT',
            'teks_pertanyaan' => 'Apa masukan atau saran Anda untuk meningkatkan kualitas produk olahan mangga siswa SMKN 2 Indramayu?',
            'wajib_diisi'     => false,
            'urutan'          => 6,
        ]);

        $q7 = Question::create([
            'survey_id'       => $survey->id,
            'tipe_pertanyaan' => 'DATE',
            'teks_pertanyaan' => 'Kapan tanggal rencana Anda berbelanja produk ini pertama kali?',
            'wajib_diisi'     => false,
            'urutan'          => 7,
        ]);

        $q8 = Question::create([
            'survey_id'       => $survey->id,
            'tipe_pertanyaan' => 'IMAGE_UPLOAD',
            'teks_pertanyaan' => 'Unggah foto produk olahan mangga Anda sendiri atau inspirasi kemasan yang Anda sukai (opsional).',
            'wajib_diisi'     => false,
            'urutan'          => 8,
        ]);

        // ─── 12 Dummy Respondents ────────────────────────────────────
        $respondentsData = [
            ['0012345678', 4, 'Sangat Tertarik', ['Dodol Mangga Premium','Keripik Mangga Crispy'], '4', 25000, 'Minimarket', 'Kualitas rasa perlu ditingkatkan, kemasan sangat menarik!'],
            ['0023456789', 5, 'Tertarik', ['Selai Mangga Artisan','Mangga Kering Organik'], '5', 35000, 'Online (Tokopedia)', 'Harap tambah varian rasa pedas manis.'],
            ['0034567890', 3, 'Cukup Tertarik', ['Jus Mangga Segar Kemasan'], '3', 18000, 'Kantin Sekolah', 'Cukup bagus, perlu logo yang lebih jelas.'],
            ['0045678901', 6, 'Sangat Tertarik', ['Dodol Mangga Premium','Keripik Mangga Crispy','Es Krim Mangga'], '5', 30000, 'Pasar Tradisional', 'Saya suka semuanya! Semangat buat adik-adik SMK.'],
            ['0056789012', 2, 'Kurang Tertarik', ['Mangga Kering Organik'], '2', 12000, 'Warung dekat rumah', 'Harga terlalu mahal untuk produk siswa.'],
            ['0067890123', 4, 'Tertarik', ['Dodol Mangga Premium','Selai Mangga Artisan'], '4', 28000, 'Supermarket', 'Kemasan terlihat profesional dan menarik sekali.'],
            ['0078901234', 5, 'Sangat Tertarik', ['Keripik Mangga Crispy','Jus Mangga Segar Kemasan','Es Krim Mangga'], '4', 22000, 'Online (Shopee)', 'Produk inovatif! Cocok untuk oleh-oleh khas Indramayu.'],
            ['0089012345', 3, 'Cukup Tertarik', ['Dodol Mangga Premium'], '3', 20000, 'Minimarket', 'Semoga bisa lebih fresh tanpa pengawet.'],
            ['0090123456', 4, 'Tertarik', ['Selai Mangga Artisan','Mangga Kering Organik'], '4', 32000, 'Toko oleh-oleh', 'Variasi rasa sudah bagus, perlu sertifikat halal.'],
            ['0001234567', 5, 'Sangat Tertarik', ['Dodol Mangga Premium','Keripik Mangga Crispy','Selai Mangga Artisan','Jus Mangga Segar Kemasan'], '5', 45000, 'Online (semua platform)', 'Branding "Mangga Indramayu" harus lebih ditonjolkan!'],
            ['0011234567', 2, 'Cukup Tertarik', ['Mangga Kering Organik','Es Krim Mangga'], '3', 15000, 'Kantin Sekolah', null],
            ['0022345678', 4, 'Sangat Tertarik', ['Keripik Mangga Crispy','Jus Mangga Segar Kemasan'], '5', 27000, 'Minimarket', 'Beli kalau ada di minimarket deket rumah.'],
        ];

        $baseDate = now()->subDays(12);

        foreach ($respondentsData as $i => $data) {
            [$nisn, $likertVal, $singleChoice, $multiChoices, $likertStr, $price, $shortText, $longText] = $data;

            $respondent = Respondent::create([
                'survey_id'    => $survey->id,
                'nisn'         => $nisn,
                'submitted_at' => $baseDate->copy()->addDays($i)->addHours(rand(7, 17))->addMinutes(rand(0, 59)),
            ]);

            // Q1 Single choice
            Answer::create(['respondent_id' => $respondent->id, 'question_id' => $q1->id, 'jawaban' => $singleChoice]);
            // Q2 Multiple choice
            Answer::create(['respondent_id' => $respondent->id, 'question_id' => $q2->id, 'jawaban' => json_encode($multiChoices)]);
            // Q3 Likert
            Answer::create(['respondent_id' => $respondent->id, 'question_id' => $q3->id, 'jawaban' => $likertStr]);
            // Q4 Number
            Answer::create(['respondent_id' => $respondent->id, 'question_id' => $q4->id, 'jawaban' => (string)$price]);
            // Q5 Short Text
            if ($shortText) {
                Answer::create(['respondent_id' => $respondent->id, 'question_id' => $q5->id, 'jawaban' => $shortText]);
            }
            // Q6 Long Text (optional)
            if ($longText) {
                Answer::create(['respondent_id' => $respondent->id, 'question_id' => $q6->id, 'jawaban' => $longText]);
            }
            // Q7 Date (alternate)
            if ($i % 3 === 0) {
                Answer::create(['respondent_id' => $respondent->id, 'question_id' => $q7->id, 'jawaban' => now()->addDays(rand(7,30))->toDateString()]);
            }
            // Q8 IMAGE_UPLOAD (skip — no actual file in seeder)
        }

        // ─── Survey 2: DRAFT (for status variety) ────────────────────
        Survey::create([
            'user_id'   => $admin->id,
            'judul'     => 'Survey Minat Produk Kerajinan Tangan Batik Indramayu 2026',
            'deskripsi' => 'Menguji potensi pasar untuk produk kerajinan tangan batik khas Indramayu yang dikembangkan siswa program Kewirausahaan.',
            'status'    => 'DRAFT',
        ]);

        $this->command->info('✅ Seeder selesai: 1 admin, 2 survey (1 published + 1 draft), 12 respondents, 55+ jawaban.');
    }
}
