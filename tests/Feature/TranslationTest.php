<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class TranslationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test creating a translation.
     */
    public function test_can_create_translation(): void
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $response = $this->postJson('/api/translations', [
            'key' => 'home.title',
            'content' => 'Hello',
            'locale' => 'en',
            'tags' => ['web']
        ]);

        $response->assertStatus(201);
    }

    /**
     * Test export response time.
     */
    public function test_export_response_time()
    {
        // First request warms cache
        $this->getJson('/api/export?locale=en');

        $start = microtime(true);

        // Second request uses cache
        $response = $this->getJson('/api/export?locale=en');

        $time = microtime(true) - $start;

        $response->assertStatus(200);

        $this->assertLessThan(0.5, $time);
    }
}
