<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Survey;
use App\Models\Question;
use App\Models\Respondent;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;

class RoleAndShareAnalyticsTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $viewer;
    protected Survey $survey;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'email' => 'admin@test.com',
            'role'  => 'ADMIN',
        ]);

        $this->viewer = User::factory()->create([
            'email' => 'viewer@test.com',
            'role'  => 'VIEWER',
        ]);

        $this->survey = Survey::create([
            'user_id'                  => $this->admin->id,
            'judul'                    => 'Survey Riset Pasar Inovasi Produk Siswa',
            'deskripsi'                => 'Deskripsi survey pengujian untuk unit testing.',
            'status'                   => 'PUBLISHED',
            'share_token'              => Str::random(32),
            'public_analytics_enabled' => false,
        ]);

        $question = Question::create([
            'survey_id'       => $this->survey->id,
            'tipe_pertanyaan' => 'SINGLE_CHOICE',
            'teks_pertanyaan' => 'Seberapa tertarik Anda dengan inovasi produk?',
            'opsi_jawaban'    => json_encode(['Tertarik', 'Kurang Tertarik']),
            'wajib_diisi'     => true,
            'urutan'          => 1,
        ]);

        $respondent = Respondent::create([
            'survey_id'    => $this->survey->id,
            'nisn'         => '1234567890',
            'submitted_at' => now(),
        ]);

        $respondent->answers()->create([
            'question_id' => $question->id,
            'jawaban'     => 'Tertarik',
        ]);
    }

    public function test_admin_has_full_crud_and_builder_access(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.surveys.builder', $this->survey->id));
        $response->assertStatus(200);

        $createResponse = $this->actingAs($this->admin)->get(route('admin.surveys.create'));
        $createResponse->assertRedirect();
        $this->assertEquals(2, Survey::count());
    }

    public function test_admin_can_toggle_and_regenerate_share_token(): void
    {
        // Toggle on
        $toggleRes = $this->actingAs($this->admin)->postJson(route('admin.surveys.toggle-share', $this->survey->id));
        $toggleRes->assertStatus(200);
        $toggleRes->assertJson(['success' => true, 'enabled' => true]);
        $this->assertTrue($this->survey->fresh()->public_analytics_enabled);

        // Regenerate token
        $oldToken = $this->survey->share_token;
        $regenRes = $this->actingAs($this->admin)->postJson(route('admin.surveys.regenerate-share-token', $this->survey->id));
        $regenRes->assertStatus(200);
        $regenRes->assertJson(['success' => true]);
        $newToken = $this->survey->fresh()->share_token;
        $this->assertNotEquals($oldToken, $newToken);
    }

    public function test_viewer_can_view_dashboard_and_analytics(): void
    {
        $dashRes = $this->actingAs($this->viewer)->get(route('admin.dashboard'));
        $dashRes->assertStatus(200);

        $indexRes = $this->actingAs($this->viewer)->get(route('admin.surveys.index'));
        $indexRes->assertStatus(200);

        $analyticsRes = $this->actingAs($this->viewer)->get(route('admin.surveys.analytics', $this->survey->id));
        $analyticsRes->assertStatus(200);
        $analyticsRes->assertSee('Survey Riset Pasar Inovasi Produk Siswa');
    }

    public function test_viewer_is_forbidden_from_builder_and_mutative_actions(): void
    {
        // Builder access redirects with error flash
        $builderRes = $this->actingAs($this->viewer)->get(route('admin.surveys.builder', $this->survey->id));
        $builderRes->assertRedirect(route('admin.surveys.index'));
        $builderRes->assertSessionHas('error');

        // Create survey redirects with error flash
        $createRes = $this->actingAs($this->viewer)->get(route('admin.surveys.create'));
        $createRes->assertRedirect(route('admin.surveys.index'));
        $createRes->assertSessionHas('error');

        // Toggle share via JSON is blocked with HTTP 403
        $toggleRes = $this->actingAs($this->viewer)->postJson(route('admin.surveys.toggle-share', $this->survey->id));
        $toggleRes->assertStatus(403);
        $toggleRes->assertJson(['success' => false]);

        // Delete survey redirects with error flash
        $deleteRes = $this->actingAs($this->viewer)->delete(route('admin.surveys.destroy', $this->survey->id));
        $deleteRes->assertRedirect(route('admin.surveys.index'));
        $deleteRes->assertSessionHas('error');
    }

    public function test_public_share_link_disabled_by_default(): void
    {
        $this->assertFalse($this->survey->public_analytics_enabled);

        $response = $this->get(route('analytics.share', $this->survey->share_token));
        $response->assertStatus(200);
        $response->assertSee('Tautan Hasil Survei Ditutup');
    }

    public function test_public_share_link_accessible_when_enabled(): void
    {
        $this->survey->update(['public_analytics_enabled' => true]);

        $response = $this->get(route('analytics.share', $this->survey->share_token));
        $response->assertStatus(200);
        $response->assertSee('Hasil Riset Pasar — ' . $this->survey->judul);
        $response->assertSee('1 Responden');
    }

    public function test_public_share_link_returns_404_on_invalid_token(): void
    {
        $response = $this->get(route('analytics.share', 'non-existent-token-123456'));
        $response->assertStatus(404);
    }
}
