<?php

namespace Tests\Feature;

use App\Models\Answer;
use App\Models\Question;
use App\Models\Respondent;
use App\Models\Survey;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SurveyBugFixesTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::first() ?? User::factory()->create();
    }

    /**
     * Test 1: Answer model accessor returns jawaban for backward/AI compatibility.
     */
    public function test_answer_model_nilai_jawaban_accessor_works(): void
    {
        $survey = Survey::create([
            'user_id' => $this->user->id,
            'judul'   => 'Test Accessor Survey',
            'status'  => 'PUBLISHED',
        ]);

        $question = Question::create([
            'survey_id'       => $survey->id,
            'tipe_pertanyaan' => 'SHORT_TEXT',
            'teks_pertanyaan' => 'Pertanyaan uji',
            'urutan'          => 1,
        ]);

        $respondent = Respondent::create([
            'survey_id' => $survey->id,
            'nisn'      => 'NISN12345',
        ]);

        $answer = Answer::create([
            'respondent_id' => $respondent->id,
            'question_id'   => $question->id,
            'jawaban'       => 'Ini jawaban uji',
        ]);

        $this->assertEquals('Ini jawaban uji', $answer->jawaban);
        $this->assertEquals('Ini jawaban uji', $answer->nilai_jawaban);
    }

    /**
     * Test 2: Survey date boundary checks in isAcceptingResponses and getClosedMessage.
     */
    public function test_survey_date_boundaries_enforced(): void
    {
        // Expired survey
        $expiredSurvey = Survey::create([
            'user_id'         => $this->user->id,
            'judul'           => 'Expired Survey',
            'status'          => 'PUBLISHED',
            'tanggal_mulai'   => now()->subDays(10),
            'tanggal_selesai' => now()->subDays(1),
        ]);

        $this->assertFalse($expiredSurvey->isAcceptingResponses());
        $this->assertStringContainsString('telah berakhir', $expiredSurvey->getClosedMessage());

        // Future survey
        $futureSurvey = Survey::create([
            'user_id'         => $this->user->id,
            'judul'           => 'Future Survey',
            'status'          => 'PUBLISHED',
            'tanggal_mulai'   => now()->addDays(2),
            'tanggal_selesai' => now()->addDays(10),
        ]);

        $this->assertFalse($futureSurvey->isAcceptingResponses());
        $this->assertStringContainsString('belum dibuka', $futureSurvey->getClosedMessage());
    }

    /**
     * Test 3: Multiple choice required validation fails on empty array.
     */
    public function test_required_multiple_choice_validation_blocks_empty_array(): void
    {
        $survey = Survey::create([
            'user_id'             => $this->user->id,
            'judul'               => 'MC Test Survey',
            'status'              => 'PUBLISHED',
            'limit_one_response'  => false,
        ]);

        $mcQuestion = Question::create([
            'survey_id'       => $survey->id,
            'tipe_pertanyaan' => 'MULTIPLE_CHOICE',
            'teks_pertanyaan' => 'Pilih hobi Anda',
            'opsi_jawaban'    => json_encode(['Membaca', 'Coding', 'Gaming']),
            'wajib_diisi'     => true,
            'urutan'          => 1,
        ]);

        // Submit with empty array
        $response = $this->postJson(route('student.survey.submit', $survey->id), [
            'nisn'    => '12345678',
            'answers' => [
                $mcQuestion->id => [],
            ],
        ]);

        $response->assertStatus(422);
        $response->assertJsonFragment([
            'error' => 'Pertanyaan "Pilih hobi Anda" wajib diisi.',
        ]);

        // Submit with selected option
        $validResponse = $this->postJson(route('student.survey.submit', $survey->id), [
            'nisn'    => '12345678',
            'answers' => [
                $mcQuestion->id => ['Coding'],
            ],
        ]);

        $validResponse->assertStatus(200);
        $validResponse->assertJson(['success' => true]);
    }

    /**
     * Test 4: When limit_one_response is false, duplicate NISN submissions succeed without DB 500 error.
     */
    public function test_multi_response_allowed_when_limit_one_response_false(): void
    {
        $survey = Survey::create([
            'user_id'            => $this->user->id,
            'judul'              => 'Unlimited Response Survey',
            'status'             => 'PUBLISHED',
            'limit_one_response' => false,
        ]);

        $q = Question::create([
            'survey_id'       => $survey->id,
            'tipe_pertanyaan' => 'SHORT_TEXT',
            'teks_pertanyaan' => 'Nama makanan favorit',
            'wajib_diisi'     => false,
            'urutan'          => 1,
        ]);

        // First submission
        $res1 = $this->postJson(route('student.survey.submit', $survey->id), [
            'nisn'    => '99887766',
            'answers' => [$q->id => 'Nasi Goreng'],
        ]);
        $res1->assertStatus(200);

        // Second submission by same NISN should succeed without SQL unique error
        $res2 = $this->postJson(route('student.survey.submit', $survey->id), [
            'nisn'    => '99887766',
            'answers' => [$q->id => 'Mie Ayam'],
        ]);
        $res2->assertStatus(200);

        $this->assertEquals(2, Respondent::where('survey_id', $survey->id)->where('nisn', '99887766')->count());
    }

    /**
     * Test 5: When limit_one_response is true, duplicate NISN submission is blocked with 422.
     */
    public function test_single_response_strictly_enforced_when_limit_one_response_true(): void
    {
        $survey = Survey::create([
            'user_id'            => $this->user->id,
            'judul'              => 'Single Response Survey',
            'status'             => 'PUBLISHED',
            'limit_one_response' => true,
        ]);

        $q = Question::create([
            'survey_id'       => $survey->id,
            'tipe_pertanyaan' => 'SHORT_TEXT',
            'teks_pertanyaan' => 'Pertanyaan',
            'wajib_diisi'     => false,
            'urutan'          => 1,
        ]);

        // First submission
        $res1 = $this->postJson(route('student.survey.submit', $survey->id), [
            'nisn'    => '11223344',
            'answers' => [$q->id => 'Jawaban 1'],
        ]);
        $res1->assertStatus(200);

        // Second submission should be rejected with 422
        $res2 = $this->postJson(route('student.survey.submit', $survey->id), [
            'nisn'    => '11223344',
            'answers' => [$q->id => 'Jawaban 2'],
        ]);
        $res2->assertStatus(422);
        $this->assertStringContainsString('telah pernah menanggapi survey ini', $res2->json('error'));
    }

    /**
     * Test 6: Verify NUMBER question statistics in analytics.
     */
    public function test_analytics_number_stats_calculation(): void
    {
        $survey = Survey::create([
            'user_id' => $this->user->id,
            'judul'   => 'Pricing Analytics Survey',
            'status'  => 'PUBLISHED',
        ]);

        $q = Question::create([
            'survey_id'       => $survey->id,
            'tipe_pertanyaan' => 'NUMBER',
            'teks_pertanyaan' => 'Berapa harga yang rela dibayar?',
            'urutan'          => 1,
        ]);

        $prices = [10000, 20000, 30000];
        foreach ($prices as $idx => $p) {
            $resp = Respondent::create([
                'survey_id' => $survey->id,
                'nisn'      => 'NISN00' . $idx,
            ]);
            Answer::create([
                'respondent_id' => $resp->id,
                'question_id'   => $q->id,
                'jawaban'       => (string) $p,
            ]);
        }

        $this->actingAs($this->user);
        $response = $this->get(route('admin.surveys.analytics', $survey->id));
        $response->assertStatus(200);

        $analytics = $response->viewData('analytics');
        $this->assertNotEmpty($analytics);
        $stats = $analytics[0]['stats'];

        $this->assertEquals(3, $stats['count']);
        $this->assertEquals(10000, $stats['min']);
        $this->assertEquals(30000, $stats['max']);
        $this->assertEquals(20000, $stats['avg']);
        $this->assertEquals(20000, $stats['median']);
    }
}
