<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TranslationTest extends TestCase
{
    /**
     * Test For Translationss.
     */
    public function test_can_create_translation()
    {
        $response = $this->postJson('/api/translations', [
            'key' => 'home.title',
            'content' => 'Hello',
            'locale' => 'en',
            'tags' => ['web']
        ]);

        $response->assertStatus(201);
    }

    public function test_export_response_time()
    {
        $start = microtime(true);

        $response = $this->get('/api/translations/export?locale=en');

        $time = microtime(true) - $start;

        $response->assertStatus(200);

        $this->assertLessThan(0.5, $time);
    }   
}
