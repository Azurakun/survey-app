<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Survey;
use App\Models\Question;
use App\Models\Respondent;
use App\Models\Answer;

class TefaTkjSurveySeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('email', 'admin@smkn2indramayu.sch.id')->firstOrFail();

        $survey = Survey::create([
            'user_id'         => $admin->id,
            'judul'           => 'Riset Pasar TEFA TKJ: Toko Komputer & Jasa Reparasi SMKN 2 Indramayu',
            'deskripsi'       => 'Survey riset pasar untuk mengukur preferensi produk, tingkat kepercayaan konsumen, elastisitas harga jasa reparasi, dan potensi permintaan terhadap layanan toko komputer TEFA (Teaching Factory) Teknik Komputer dan Jaringan SMKN 2 Indramayu.',
            'status'          => 'PUBLISHED',
            'tanggal_mulai'   => now()->subDays(30)->toDateString(),
            'tanggal_selesai' => now()->addDays(30)->toDateString(),
        ]);

        // 30 Questions
        $q01 = Question::create(['survey_id'=>$survey->id,'tipe_pertanyaan'=>'SINGLE_CHOICE','teks_pertanyaan'=>'Perangkat teknologi apa yang paling sering Anda gunakan sehari-hari?','opsi_jawaban'=>json_encode(['Laptop/Notebook','PC Desktop','Smartphone saja','Tablet/iPad','Semua perangkat']),'wajib_diisi'=>true,'urutan'=>1]);
        $q02 = Question::create(['survey_id'=>$survey->id,'tipe_pertanyaan'=>'SINGLE_CHOICE','teks_pertanyaan'=>'Apa tujuan utama penggunaan komputer/laptop Anda?','opsi_jawaban'=>json_encode(['Belajar & Tugas Sekolah','Gaming & Hiburan','Kerja & Bisnis Online','Desain Grafis & Editing','Browsing & Media Sosial']),'wajib_diisi'=>true,'urutan'=>2]);
        $q03 = Question::create(['survey_id'=>$survey->id,'tipe_pertanyaan'=>'SINGLE_CHOICE','teks_pertanyaan'=>'Seberapa sering perangkat komputer/laptop Anda mengalami masalah teknis?','opsi_jawaban'=>json_encode(['Sangat Sering (>3x/tahun)','Sering (2-3x/tahun)','Kadang-kadang (1x/tahun)','Jarang (< 1x/2 tahun)','Tidak Pernah']),'wajib_diisi'=>true,'urutan'=>3]);
        $q04 = Question::create(['survey_id'=>$survey->id,'tipe_pertanyaan'=>'MULTIPLE_CHOICE','teks_pertanyaan'=>'Masalah teknis apa yang paling sering Anda alami? (Boleh pilih lebih dari satu)','opsi_jawaban'=>json_encode(['Laptop/PC lambat & lemot','Layar rusak/retak','Keyboard tidak berfungsi','Baterai boros/rusak','Terkena virus/malware','Hardisk/SSD bermasalah','Koneksi WiFi tidak stabil','Overheat & mati sendiri']),'wajib_diisi'=>true,'urutan'=>4]);
        $q05 = Question::create(['survey_id'=>$survey->id,'tipe_pertanyaan'=>'SINGLE_CHOICE','teks_pertanyaan'=>'Kemana biasanya Anda membawa perangkat saat mengalami kerusakan?','opsi_jawaban'=>json_encode(['Toko servis profesional berbayar','Minta tolong teman/kenalan','Servis sendiri (DIY)','Service center resmi brand','Belum tahu/bingung']),'wajib_diisi'=>true,'urutan'=>5]);
        $q06 = Question::create(['survey_id'=>$survey->id,'tipe_pertanyaan'=>'LIKERT','teks_pertanyaan'=>'Seberapa besar kepercayaan Anda terhadap kemampuan teknisi siswa TKJ SMKN 2 Indramayu dalam memperbaiki perangkat?','opsi_jawaban'=>json_encode(['1','2','3','4','5']),'wajib_diisi'=>true,'urutan'=>6]);
        $q07 = Question::create(['survey_id'=>$survey->id,'tipe_pertanyaan'=>'LIKERT','teks_pertanyaan'=>'Seberapa penting garansi pengerjaan (7-30 hari) bagi Anda dalam memilih jasa reparasi?','opsi_jawaban'=>json_encode(['1','2','3','4','5']),'wajib_diisi'=>true,'urutan'=>7]);
        $q08 = Question::create(['survey_id'=>$survey->id,'tipe_pertanyaan'=>'SINGLE_CHOICE','teks_pertanyaan'=>'Apakah Anda bersedia mencoba jasa reparasi TEFA TKJ jika harganya lebih terjangkau dari toko biasa?','opsi_jawaban'=>json_encode(['Ya, sangat bersedia','Ya, mungkin bersedia','Masih ragu-ragu','Tidak, lebih percaya toko profesional','Tidak tertarik']),'wajib_diisi'=>true,'urutan'=>8]);
        $q09 = Question::create(['survey_id'=>$survey->id,'tipe_pertanyaan'=>'MULTIPLE_CHOICE','teks_pertanyaan'=>'Layanan apa yang Anda harapkan ada di TEFA TKJ? (Boleh pilih lebih dari satu)','opsi_jawaban'=>json_encode(['Servis & reparasi laptop/PC','Instalasi OS & software','Pembersihan debu & thermal paste','Upgrade RAM/SSD/HDD','Rakit PC custom','Jual komponen & aksesoris komputer','Kursus & pelatihan komputer','Cetak dokumen & scan']),'wajib_diisi'=>true,'urutan'=>9]);
        $q10 = Question::create(['survey_id'=>$survey->id,'tipe_pertanyaan'=>'LIKERT','teks_pertanyaan'=>'Seberapa puas Anda jika TEFA TKJ menyediakan layanan antar-jemput perangkat ke rumah (pickup service)?','opsi_jawaban'=>json_encode(['1','2','3','4','5']),'wajib_diisi'=>true,'urutan'=>10]);
        $q11 = Question::create(['survey_id'=>$survey->id,'tipe_pertanyaan'=>'NUMBER','teks_pertanyaan'=>'Berapa tarif jasa yang bersedia Anda bayar untuk install ulang OS Windows? (dalam Rupiah)','opsi_jawaban'=>null,'wajib_diisi'=>true,'urutan'=>11]);
        $q12 = Question::create(['survey_id'=>$survey->id,'tipe_pertanyaan'=>'NUMBER','teks_pertanyaan'=>'Berapa tarif jasa yang bersedia Anda bayar untuk pembersihan total (clean dust + ganti thermal paste)? (dalam Rupiah)','opsi_jawaban'=>null,'wajib_diisi'=>true,'urutan'=>12]);
        $q13 = Question::create(['survey_id'=>$survey->id,'tipe_pertanyaan'=>'NUMBER','teks_pertanyaan'=>'Berapa tarif jasa yang bersedia Anda bayar untuk upgrade SSD/RAM (jasa pasang saja, tidak termasuk komponen)? (dalam Rupiah)','opsi_jawaban'=>null,'wajib_diisi'=>true,'urutan'=>13]);
        $q14 = Question::create(['survey_id'=>$survey->id,'tipe_pertanyaan'=>'NUMBER','teks_pertanyaan'=>'Berapa tarif jasa yang bersedia Anda bayar untuk perbaikan keyboard laptop? (dalam Rupiah)','opsi_jawaban'=>null,'wajib_diisi'=>true,'urutan'=>14]);
        $q15 = Question::create(['survey_id'=>$survey->id,'tipe_pertanyaan'=>'NUMBER','teks_pertanyaan'=>'Berapa tarif jasa yang bersedia Anda bayar untuk servis laptop overheat/mati sendiri? (dalam Rupiah)','opsi_jawaban'=>null,'wajib_diisi'=>true,'urutan'=>15]);
        $q16 = Question::create(['survey_id'=>$survey->id,'tipe_pertanyaan'=>'MULTIPLE_CHOICE','teks_pertanyaan'=>'Komponen atau aksesoris komputer apa yang paling ingin Anda beli jika tersedia di TEFA TKJ? (Boleh pilih lebih dari satu)','opsi_jawaban'=>json_encode(['SSD (Solid State Drive)','RAM Laptop/PC','Headset Gaming','Mouse & Keyboard','Webcam HD','Cooling Pad Laptop','Flash Drive / USB','Kabel HDMI & Adapter']),'wajib_diisi'=>true,'urutan'=>16]);
        $q17 = Question::create(['survey_id'=>$survey->id,'tipe_pertanyaan'=>'SINGLE_CHOICE','teks_pertanyaan'=>'Berapa anggaran yang Anda siapkan untuk membeli aksesoris/komponen komputer per bulan?','opsi_jawaban'=>json_encode(['< Rp 50.000','Rp 50.000 - Rp 150.000','Rp 150.000 - Rp 300.000','Rp 300.000 - Rp 500.000','> Rp 500.000']),'wajib_diisi'=>true,'urutan'=>17]);
        $q18 = Question::create(['survey_id'=>$survey->id,'tipe_pertanyaan'=>'SINGLE_CHOICE','teks_pertanyaan'=>'Apakah Anda tertarik dengan layanan rakit PC custom sesuai budget dari TEFA TKJ?','opsi_jawaban'=>json_encode(['Sangat Tertarik','Tertarik','Cukup Tertarik','Kurang Tertarik','Tidak Tertarik']),'wajib_diisi'=>true,'urutan'=>18]);
        $q19 = Question::create(['survey_id'=>$survey->id,'tipe_pertanyaan'=>'SINGLE_CHOICE','teks_pertanyaan'=>'Berapa budget total yang siap Anda alokasikan jika ingin merakit PC gaming/kerja bersama TEFA TKJ?','opsi_jawaban'=>json_encode(['< Rp 3.000.000','Rp 3.000.000 - Rp 5.000.000','Rp 5.000.000 - Rp 8.000.000','Rp 8.000.000 - Rp 12.000.000','> Rp 12.000.000']),'wajib_diisi'=>true,'urutan'=>19]);
        $q20 = Question::create(['survey_id'=>$survey->id,'tipe_pertanyaan'=>'MULTIPLE_CHOICE','teks_pertanyaan'=>'Fitur toko komputer apa yang paling penting bagi Anda? (Boleh pilih lebih dari satu)','opsi_jawaban'=>json_encode(['Harga kompetitif & terjangkau','Garansi produk & jasa jelas','Teknisi berpengalaman & terlatih','Lokasi mudah dijangkau','Layanan konsultasi gratis','Tersedia layanan online/WhatsApp','Spare part original & berkualitas']),'wajib_diisi'=>true,'urutan'=>20]);
        $q21 = Question::create(['survey_id'=>$survey->id,'tipe_pertanyaan'=>'LIKERT','teks_pertanyaan'=>'Seberapa penting transparansi estimasi biaya sebelum pengerjaan dimulai bagi Anda?','opsi_jawaban'=>json_encode(['1','2','3','4','5']),'wajib_diisi'=>true,'urutan'=>21]);
        $q22 = Question::create(['survey_id'=>$survey->id,'tipe_pertanyaan'=>'LIKERT','teks_pertanyaan'=>'Seberapa penting update status pengerjaan via WhatsApp selama reparasi berlangsung?','opsi_jawaban'=>json_encode(['1','2','3','4','5']),'wajib_diisi'=>true,'urutan'=>22]);
        $q23 = Question::create(['survey_id'=>$survey->id,'tipe_pertanyaan'=>'SINGLE_CHOICE','teks_pertanyaan'=>'Berapa lama maksimal waktu pengerjaan reparasi yang masih bisa Anda tolerir?','opsi_jawaban'=>json_encode(['1 hari (same day)','2-3 hari kerja','3-5 hari kerja','1 minggu','Lebih dari 1 minggu tidak masalah']),'wajib_diisi'=>true,'urutan'=>23]);
        $q24 = Question::create(['survey_id'=>$survey->id,'tipe_pertanyaan'=>'SINGLE_CHOICE','teks_pertanyaan'=>'Metode pembayaran apa yang Anda paling sukai untuk jasa TEFA TKJ?','opsi_jawaban'=>json_encode(['Tunai / Cash','Transfer Bank','QRIS / Dompet Digital (GoPay, OVO, DANA)','Cicilan 0%','Semua metode tersedia']),'wajib_diisi'=>true,'urutan'=>24]);
        $q25 = Question::create(['survey_id'=>$survey->id,'tipe_pertanyaan'=>'SINGLE_CHOICE','teks_pertanyaan'=>'Apakah Anda akan merekomendasikan TEFA TKJ kepada teman/keluarga jika pelayanannya memuaskan?','opsi_jawaban'=>json_encode(['Pasti akan merekomendasikan','Kemungkinan besar akan','Mungkin iya mungkin tidak','Kemungkinan tidak','Tidak akan merekomendasikan']),'wajib_diisi'=>true,'urutan'=>25]);
        $q26 = Question::create(['survey_id'=>$survey->id,'tipe_pertanyaan'=>'MULTIPLE_CHOICE','teks_pertanyaan'=>'Dari mana Anda biasanya mendapatkan informasi tentang toko servis komputer? (Boleh pilih lebih dari satu)','opsi_jawaban'=>json_encode(['Rekomendasi teman/keluarga','Instagram / TikTok','Google Maps / Search','WhatsApp Group','Banner & spanduk fisik','Marketplace (Tokopedia/Shopee)']),'wajib_diisi'=>true,'urutan'=>26]);
        $q27 = Question::create(['survey_id'=>$survey->id,'tipe_pertanyaan'=>'SINGLE_CHOICE','teks_pertanyaan'=>'Program promosi apa yang paling menarik minat Anda untuk mencoba TEFA TKJ?','opsi_jawaban'=>json_encode(['Diskon 20% untuk pelajar/mahasiswa','Gratis konsultasi & diagnosa awal','Free instalasi driver & antivirus','Program loyalty: servis ke-5 gratis','Paket bundling (servis + aksesoris hemat)']),'wajib_diisi'=>true,'urutan'=>27]);
        $q28 = Question::create(['survey_id'=>$survey->id,'tipe_pertanyaan'=>'LIKERT','teks_pertanyaan'=>'Seberapa besar kemungkinan Anda menggunakan layanan TEFA TKJ dalam 6 bulan ke depan?','opsi_jawaban'=>json_encode(['1','2','3','4','5']),'wajib_diisi'=>true,'urutan'=>28]);
        $q29 = Question::create(['survey_id'=>$survey->id,'tipe_pertanyaan'=>'SHORT_TEXT','teks_pertanyaan'=>'Layanan reparasi/komputer apa yang paling Anda butuhkan namun belum tersedia di sekitar Anda?','opsi_jawaban'=>null,'wajib_diisi'=>false,'urutan'=>29]);
        $q30 = Question::create(['survey_id'=>$survey->id,'tipe_pertanyaan'=>'LONG_TEXT','teks_pertanyaan'=>'Berikan saran dan masukan Anda untuk TEFA TKJ SMKN 2 Indramayu agar dapat menjadi pilihan utama jasa reparasi dan toko komputer terpercaya di Indramayu.','opsi_jawaban'=>null,'wajib_diisi'=>false,'urutan'=>30]);

        // Answer pools
        $p01=['Laptop/Notebook','Laptop/Notebook','PC Desktop','Laptop/Notebook','Smartphone saja','Laptop/Notebook','PC Desktop','Semua perangkat','Tablet/iPad','Laptop/Notebook'];
        $p02=['Belajar & Tugas Sekolah','Gaming & Hiburan','Belajar & Tugas Sekolah','Kerja & Bisnis Online','Belajar & Tugas Sekolah','Gaming & Hiburan','Desain Grafis & Editing','Belajar & Tugas Sekolah','Browsing & Media Sosial','Gaming & Hiburan'];
        $p03=['Sering (2-3x/tahun)','Kadang-kadang (1x/tahun)','Sangat Sering (>3x/tahun)','Kadang-kadang (1x/tahun)','Jarang (< 1x/2 tahun)','Sering (2-3x/tahun)','Kadang-kadang (1x/tahun)','Sering (2-3x/tahun)','Tidak Pernah','Kadang-kadang (1x/tahun)'];
        $p04=[['Laptop/PC lambat & lemot','Terkena virus/malware'],['Overheat & mati sendiri','Laptop/PC lambat & lemot'],['Layar rusak/retak','Keyboard tidak berfungsi'],['Hardisk/SSD bermasalah','Laptop/PC lambat & lemot'],['Baterai boros/rusak','Overheat & mati sendiri'],['Koneksi WiFi tidak stabil','Terkena virus/malware'],['Laptop/PC lambat & lemot','Baterai boros/rusak','Overheat & mati sendiri'],['Keyboard tidak berfungsi','Layar rusak/retak'],['Terkena virus/malware','Hardisk/SSD bermasalah'],['Laptop/PC lambat & lemot','Koneksi WiFi tidak stabil','Baterai boros/rusak']];
        $p05=['Toko servis profesional berbayar','Minta tolong teman/kenalan','Toko servis profesional berbayar','Minta tolong teman/kenalan','Servis sendiri (DIY)','Toko servis profesional berbayar','Minta tolong teman/kenalan','Service center resmi brand','Belum tahu/bingung','Toko servis profesional berbayar'];
        $pL6=['4','5','4','5','4','4','5','3','4','5'];
        $pL7=['5','5','4','5','5','4','5','5','4','5'];
        $p08=['Ya, sangat bersedia','Ya, sangat bersedia','Ya, mungkin bersedia','Ya, sangat bersedia','Masih ragu-ragu','Ya, mungkin bersedia','Ya, sangat bersedia','Ya, mungkin bersedia','Masih ragu-ragu','Ya, sangat bersedia'];
        $p09=[['Servis & reparasi laptop/PC','Instalasi OS & software'],['Instalasi OS & software','Pembersihan debu & thermal paste','Upgrade RAM/SSD/HDD'],['Upgrade RAM/SSD/HDD','Servis & reparasi laptop/PC'],['Jual komponen & aksesoris komputer','Servis & reparasi laptop/PC'],['Rakit PC custom','Upgrade RAM/SSD/HDD','Instalasi OS & software'],['Servis & reparasi laptop/PC','Pembersihan debu & thermal paste'],['Kursus & pelatihan komputer','Instalasi OS & software'],['Cetak dokumen & scan','Jual komponen & aksesoris komputer'],['Upgrade RAM/SSD/HDD','Pembersihan debu & thermal paste','Servis & reparasi laptop/PC'],['Rakit PC custom','Jual komponen & aksesoris komputer','Servis & reparasi laptop/PC']];
        $pL10=['4','5','3','4','5','4','3','5','4','4'];
        $p11=[35000,40000,50000,35000,45000,50000,40000,30000,50000,45000,60000,35000,40000,50000,55000];
        $p12=[50000,60000,75000,50000,65000,75000,60000,50000,80000,70000,75000,55000,60000,50000,65000];
        $p13=[30000,35000,50000,30000,40000,50000,35000,25000,50000,45000,40000,30000,35000,50000,55000];
        $p14=[75000,100000,125000,80000,100000,150000,90000,75000,120000,100000,110000,80000,90000,100000,125000];
        $p15=[75000,100000,125000,80000,100000,150000,100000,80000,125000,100000,120000,90000,100000,125000,150000];
        $p16=[['SSD (Solid State Drive)','RAM Laptop/PC'],['Mouse & Keyboard','Headset Gaming'],['SSD (Solid State Drive)','Flash Drive / USB'],['Webcam HD','Mouse & Keyboard'],['Cooling Pad Laptop','RAM Laptop/PC','SSD (Solid State Drive)'],['Headset Gaming','Mouse & Keyboard'],['Kabel HDMI & Adapter','Flash Drive / USB'],['RAM Laptop/PC','Cooling Pad Laptop'],['SSD (Solid State Drive)','Mouse & Keyboard','Headset Gaming'],['Flash Drive / USB','Webcam HD','Kabel HDMI & Adapter']];
        $p17=['Rp 50.000 - Rp 150.000','Rp 150.000 - Rp 300.000','Rp 50.000 - Rp 150.000','< Rp 50.000','Rp 150.000 - Rp 300.000','Rp 300.000 - Rp 500.000','Rp 50.000 - Rp 150.000','Rp 150.000 - Rp 300.000','< Rp 50.000','Rp 50.000 - Rp 150.000'];
        $p18=['Sangat Tertarik','Tertarik','Sangat Tertarik','Cukup Tertarik','Tertarik','Sangat Tertarik','Tertarik','Cukup Tertarik','Kurang Tertarik','Sangat Tertarik'];
        $p19=['Rp 3.000.000 - Rp 5.000.000','Rp 5.000.000 - Rp 8.000.000','Rp 3.000.000 - Rp 5.000.000','< Rp 3.000.000','Rp 5.000.000 - Rp 8.000.000','Rp 8.000.000 - Rp 12.000.000','Rp 3.000.000 - Rp 5.000.000','< Rp 3.000.000','Rp 5.000.000 - Rp 8.000.000','Rp 3.000.000 - Rp 5.000.000'];
        $p20=[['Harga kompetitif & terjangkau','Garansi produk & jasa jelas'],['Teknisi berpengalaman & terlatih','Garansi produk & jasa jelas'],['Harga kompetitif & terjangkau','Lokasi mudah dijangkau'],['Layanan konsultasi gratis','Harga kompetitif & terjangkau','Garansi produk & jasa jelas'],['Tersedia layanan online/WhatsApp','Harga kompetitif & terjangkau'],['Spare part original & berkualitas','Teknisi berpengalaman & terlatih'],['Garansi produk & jasa jelas','Lokasi mudah dijangkau'],['Harga kompetitif & terjangkau','Layanan konsultasi gratis'],['Tersedia layanan online/WhatsApp','Garansi produk & jasa jelas','Teknisi berpengalaman & terlatih'],['Spare part original & berkualitas','Harga kompetitif & terjangkau']];
        $pL21=['5','5','5','4','5','5','4','5','5','4'];
        $pL22=['4','5','4','4','5','3','4','5','4','4'];
        $p23=['2-3 hari kerja','1 hari (same day)','2-3 hari kerja','3-5 hari kerja','2-3 hari kerja','1 hari (same day)','2-3 hari kerja','3-5 hari kerja','1 hari (same day)','2-3 hari kerja'];
        $p24=['QRIS / Dompet Digital (GoPay, OVO, DANA)','Tunai / Cash','Transfer Bank','QRIS / Dompet Digital (GoPay, OVO, DANA)','Semua metode tersedia','Tunai / Cash','QRIS / Dompet Digital (GoPay, OVO, DANA)','Transfer Bank','Tunai / Cash','Semua metode tersedia'];
        $p25=['Pasti akan merekomendasikan','Kemungkinan besar akan','Pasti akan merekomendasikan','Kemungkinan besar akan','Mungkin iya mungkin tidak','Pasti akan merekomendasikan','Kemungkinan besar akan','Pasti akan merekomendasikan','Kemungkinan besar akan','Mungkin iya mungkin tidak'];
        $p26=[['Rekomendasi teman/keluarga','Instagram / TikTok'],['Google Maps / Search','Rekomendasi teman/keluarga'],['WhatsApp Group','Instagram / TikTok'],['Rekomendasi teman/keluarga','Google Maps / Search'],['Instagram / TikTok','Banner & spanduk fisik'],['Marketplace (Tokopedia/Shopee)','Google Maps / Search'],['Rekomendasi teman/keluarga','WhatsApp Group'],['Instagram / TikTok','Google Maps / Search','Rekomendasi teman/keluarga'],['Banner & spanduk fisik','Rekomendasi teman/keluarga'],['WhatsApp Group','Marketplace (Tokopedia/Shopee)']];
        $p27=['Diskon 20% untuk pelajar/mahasiswa','Gratis konsultasi & diagnosa awal','Free instalasi driver & antivirus','Program loyalty: servis ke-5 gratis','Paket bundling (servis + aksesoris hemat)','Diskon 20% untuk pelajar/mahasiswa','Gratis konsultasi & diagnosa awal','Diskon 20% untuk pelajar/mahasiswa','Paket bundling (servis + aksesoris hemat)','Gratis konsultasi & diagnosa awal'];
        $pL28=['4','5','4','3','5','4','5','3','4','5'];
        $p29=['Layanan recovery data dari hardisk rusak yang terjangkau','Jasa setting jaringan LAN/WiFi untuk rumahan dan warnet','Servis printer semua merk dengan harga bersaing','Konsultasi gratis sebelum memutuskan ganti komponen','Jasa install software desain & editing khusus pelajar','Layanan servis cepat di hari yang sama untuk kedaruratan','Toko yang jual RAM second berkualitas dengan garansi','Service laptop gaming dengan teknisi yang paham spesifikasi','Backup & restore data sebelum servis dengan aman','Jasa pembuatan bootable USB & recovery Windows otomatis'];
        $p30=['TEFA TKJ sangat potensial, tolong pastikan teknisinya sudah tersertifikasi agar konsumen lebih percaya.','Harapannya ada nomor WhatsApp aktif yang bisa dihubungi 24 jam untuk konsultasi darurat.','Buatkan papan nama/signage yang jelas agar lokasi TEFA TKJ mudah ditemukan masyarakat sekitar.','Minta tolong ada SOP pengerjaan tertulis yang transparan dan bisa dilihat konsumen.','Sangat mendukung program TEFA, semoga bisa buka layanan pickup & delivery ke seluruh Indramayu.','Harga yang terjangkau adalah nilai jual utama, pertahankan dan pasang harga resmi di papan tarif.','Bagus sekali jika TEFA TKJ ada di Google Maps agar mudah dicari warga sekitar sekolah.','Tolong sediakan spare part yang lengkap termasuk charger dan baterai laptop populer.','Kalau ada program magang atau belajar bareng teknisi TEFA saat servis, itu nilai plus yang luar biasa.','Semoga TEFA TKJ bisa berkembang jadi pusat layanan IT terpercaya di Indramayu.'];

        for ($i = 1; $i <= 500; $i++) {
            $resp = Respondent::create(['survey_id'=>$survey->id,'nisn'=>sprintf('0098765%03d',$i),'submitted_at'=>now()->subDays(rand(1,29))->subHours(rand(0,23))]);
            $x=$i%10; $y=$i%15;
            $lb=($i%13===0)?-1:0;
            Answer::create(['respondent_id'=>$resp->id,'question_id'=>$q01->id,'jawaban'=>$p01[$x]]);
            Answer::create(['respondent_id'=>$resp->id,'question_id'=>$q02->id,'jawaban'=>$p02[$x]]);
            Answer::create(['respondent_id'=>$resp->id,'question_id'=>$q03->id,'jawaban'=>$p03[$x]]);
            Answer::create(['respondent_id'=>$resp->id,'question_id'=>$q04->id,'jawaban'=>json_encode($p04[$x])]);
            Answer::create(['respondent_id'=>$resp->id,'question_id'=>$q05->id,'jawaban'=>$p05[$x]]);
            Answer::create(['respondent_id'=>$resp->id,'question_id'=>$q06->id,'jawaban'=>(string)max(1,(int)$pL6[$x]+$lb)]);
            Answer::create(['respondent_id'=>$resp->id,'question_id'=>$q07->id,'jawaban'=>$pL7[$x]]);
            Answer::create(['respondent_id'=>$resp->id,'question_id'=>$q08->id,'jawaban'=>$p08[$x]]);
            Answer::create(['respondent_id'=>$resp->id,'question_id'=>$q09->id,'jawaban'=>json_encode($p09[$x])]);
            Answer::create(['respondent_id'=>$resp->id,'question_id'=>$q10->id,'jawaban'=>$pL10[$x]]);
            Answer::create(['respondent_id'=>$resp->id,'question_id'=>$q11->id,'jawaban'=>(string)$p11[$y]]);
            Answer::create(['respondent_id'=>$resp->id,'question_id'=>$q12->id,'jawaban'=>(string)$p12[$y]]);
            Answer::create(['respondent_id'=>$resp->id,'question_id'=>$q13->id,'jawaban'=>(string)$p13[$y]]);
            Answer::create(['respondent_id'=>$resp->id,'question_id'=>$q14->id,'jawaban'=>(string)$p14[$y]]);
            Answer::create(['respondent_id'=>$resp->id,'question_id'=>$q15->id,'jawaban'=>(string)$p15[$y]]);
            Answer::create(['respondent_id'=>$resp->id,'question_id'=>$q16->id,'jawaban'=>json_encode($p16[$x])]);
            Answer::create(['respondent_id'=>$resp->id,'question_id'=>$q17->id,'jawaban'=>$p17[$x]]);
            Answer::create(['respondent_id'=>$resp->id,'question_id'=>$q18->id,'jawaban'=>$p18[$x]]);
            Answer::create(['respondent_id'=>$resp->id,'question_id'=>$q19->id,'jawaban'=>$p19[$x]]);
            Answer::create(['respondent_id'=>$resp->id,'question_id'=>$q20->id,'jawaban'=>json_encode($p20[$x])]);
            Answer::create(['respondent_id'=>$resp->id,'question_id'=>$q21->id,'jawaban'=>$pL21[$x]]);
            Answer::create(['respondent_id'=>$resp->id,'question_id'=>$q22->id,'jawaban'=>$pL22[$x]]);
            Answer::create(['respondent_id'=>$resp->id,'question_id'=>$q23->id,'jawaban'=>$p23[$x]]);
            Answer::create(['respondent_id'=>$resp->id,'question_id'=>$q24->id,'jawaban'=>$p24[$x]]);
            Answer::create(['respondent_id'=>$resp->id,'question_id'=>$q25->id,'jawaban'=>$p25[$x]]);
            Answer::create(['respondent_id'=>$resp->id,'question_id'=>$q26->id,'jawaban'=>json_encode($p26[$x])]);
            Answer::create(['respondent_id'=>$resp->id,'question_id'=>$q27->id,'jawaban'=>$p27[$x]]);
            Answer::create(['respondent_id'=>$resp->id,'question_id'=>$q28->id,'jawaban'=>(string)max(1,(int)$pL28[$x]+$lb)]);
            Answer::create(['respondent_id'=>$resp->id,'question_id'=>$q29->id,'jawaban'=>$p29[$x]]);
            Answer::create(['respondent_id'=>$resp->id,'question_id'=>$q30->id,'jawaban'=>$p30[$x]]);
        }

        $this->command->info('[OK] TefaTkjSurveySeeder: 500 responden berhasil di-seed!');
    }
}
