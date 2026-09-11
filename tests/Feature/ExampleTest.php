<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_single_article_page_returns_successful_response(): void
    {
        $category = \App\Models\Category::create(['name' => 'Design']);
        $user = \App\Models\User::factory()->create(['username' => 'testuser']);
        $article = \App\Models\Article::create([
            'category_id' => $category->id,
            'created_by' => $user->id,
            'title' => 'Fresh font pairings for 2024',
            'content' => 'Simple, reliable font combos you can drop into modern interfaces.',
            'image_url' => 'https://images.unsplash.com/photo-1585829365295-ab7cd400c167?w=1400',
        ]);

        $response = $this->get('/articles/' . $article->id);
        $response->assertStatus(200);
        $response->assertSee('Fresh font pairings for 2024');
        $response->assertSee('design');
    }

    public function test_tag_category_page_returns_successful_response(): void
    {
        $category = \App\Models\Category::create(['name' => 'Wallpapers']);
        $user = \App\Models\User::factory()->create(['username' => 'testuser2']);
        \App\Models\Article::create([
            'category_id' => $category->id,
            'created_by' => $user->id,
            'title' => 'Minimalist Architecture Wallpapers',
            'content' => 'Curated high-resolution wallpapers for creative workstations.',
            'image_url' => 'https://images.unsplash.com/photo-1513694203232-719a280e022f?w=1400',
        ]);

        $response = $this->get('/tag/wallpapers');
        $response->assertStatus(200);
        $response->assertSee('Wallpapers');
        $response->assertSee('Minimalist Architecture Wallpapers');
    }

    public function test_tag_category_page_with_multiword_slug_returns_successful_response(): void
    {
        $category = \App\Models\Category::create(['name' => 'Info Sekolah']);
        $user = \App\Models\User::factory()->create(['username' => 'testuser3']);
        \App\Models\Article::create([
            'category_id' => $category->id,
            'created_by' => $user->id,
            'title' => 'Pengumuman Ujian Semester',
            'content' => 'Jadwal dan tata tertib ujian semester genap.',
            'image_url' => 'https://images.unsplash.com/photo-1513694203232-719a280e022f?w=1400',
        ]);

        $response = $this->get('/tag/info-sekolah');
        $response->assertStatus(200);
        $response->assertSee('Info Sekolah');
        $response->assertSee('Pengumuman Ujian Semester');
    }

    public function test_admin_can_access_article_create_and_edit_pages(): void
    {
        $category = \App\Models\Category::create(['name' => 'Seni']);
        $user = \App\Models\User::factory()->create(['username' => 'admin_test']);
        $article = \App\Models\Article::create([
            'category_id' => $category->id,
            'created_by' => $user->id,
            'title' => 'Sample Article For Edit',
            'content' => 'Content of sample article.',
        ]);

        // Unauthenticated access redirects to login
        $this->get('/admin/articles/create')->assertRedirect('/login');
        $this->get('/admin/articles/' . $article->id . '/edit')->assertRedirect('/login');

        // Authenticated admin access
        /** @var \App\Models\User $user */
        $this->actingAs($user);

        $createResponse = $this->get('/admin/articles/create');
        $createResponse->assertStatus(200);
        $createResponse->assertSee('Tulis Artikel Baru');

        $editResponse = $this->get('/admin/articles/' . $article->id . '/edit');
        $editResponse->assertStatus(200);
        $editResponse->assertSee('Edit Artikel');
        $editResponse->assertSee('Sample Article For Edit');
    }

    public function test_admin_dashboard_renders_charts_successfully(): void
    {
        /** @var \App\Models\User $user */
        $user = \App\Models\User::factory()->create(['username' => 'admin_chart']);
        $this->actingAs($user);

        $response = $this->get('/admin/dashboard');
        $response->assertStatus(200);
        $response->assertSee('Tren Publikasi Artikel');
        $response->assertSee('Proporsi Kategori');
        $response->assertSee('monthlyTrendChart');
        $response->assertSee('categoryDistributionChart');
    }

    public function test_user_can_login_with_valid_credentials_and_remember(): void
    {
        \App\Models\User::factory()->create([
            'username' => 'petugas_mading',
            'password' => bcrypt('secret12345'),
        ]);

        $response = $this->post('/login', [
            'username' => 'petugas_mading',
            'password' => 'secret12345',
            'remember' => '1',
        ]);

        $response->assertRedirect('/admin/dashboard');
        $this->assertAuthenticated();
    }

    public function test_user_cannot_login_with_invalid_credentials(): void
    {
        $response = $this->post('/login', [
            'username' => 'unknown_user',
            'password' => 'wrongpass',
        ]);
        $response->assertSessionHasErrors('username');
        $this->assertGuest();
    }

    public function test_category_filter_pills_render_as_navigable_links(): void
    {
        $cat1 = \App\Models\Category::create(['name' => 'Akademik']);
        $cat2 = \App\Models\Category::create(['name' => 'Ekstrakurikuler']);

        $response = $this->get('/tag/akademik');
        $response->assertStatus(200);
        $response->assertSee(route('mading.index'));
        $response->assertSee(route('mading.tag', 'ekstrakurikuler'));
        $response->assertSee('carbon-tag-btn active', false);
    }
}
