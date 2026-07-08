<?php

declare(strict_types=1);

use App\Models\News;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

describe('News', function () {

    it('returns published news to public', function () {
        News::factory()->count(5)->create();

        $this->getJson('/api/v1/news')
             ->assertOk()
             ->assertJsonStructure(['data' => [['id', 'title', 'content']]]);
    });

    it('returns 404 for draft news on public endpoint', function () {
        $news = News::factory()->draft()->create();

        $this->getJson("/api/v1/news/{$news->id}")->assertNotFound();
    });

    it('allows admin to create news', function () {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin, 'sanctum')
                         ->postJson('/api/v1/admin/news', [
                             'title'   => 'Test News',
                             'content' => 'Some content here',
                             'status'  => 'published',
                             'published_at' => now()->toDateTimeString(),
                         ]);

        $response->assertCreated()->assertJsonStructure(['news' => ['id', 'title']]);
    });

    it('prevents customer from creating news', function () {
        $customer = User::factory()->create();

        $this->actingAs($customer, 'sanctum')
             ->postJson('/api/v1/admin/news', [
                 'title'   => 'Illegal News',
                 'content' => 'Content',
                 'status'  => 'published',
             ])->assertForbidden();
    });
});
