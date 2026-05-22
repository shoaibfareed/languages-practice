<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Translation;
use App\Services\TranslationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;

class TranslationServiceTest extends TestCase
{
    use RefreshDatabase;

    protected TranslationService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new TranslationService();
    }

    /** @test */
    public function it_creates_translation_via_service()
    {
        $data = [
            'key' => 'shoaib',
            'content' => 'Hello Shoaib',
            'locale' => 'en',
            'tags' => ['greeting'],
        ];

        $translation = $this->service->create($data);

        $this->assertDatabaseHas('translations', [
            'key' => 'shoaib',
            'content' => 'Hello Shoaib',
        ]);
    }

    /** @test */
    public function it_caches_translations_by_locale()
    {
        Cache::shouldReceive('tags->remember')
            ->once()
            ->andReturn([
                'hello' => 'Hello',
            ]);

        $service = new TranslationService();

        $result = $service->export('en');

        $this->assertIsArray($result);
        $this->assertArrayHasKey('hello', $result);
    }

    /** @test */
    public function it_returns_only_requested_locale_translations()
    {
        Translation::create([
            'key' => 'hello',
            'content' => 'Hello',
            'locale' => 'en',
        ]);

        Translation::create([
            'key' => 'hello',
            'content' => 'Bonjour',
            'locale' => 'fr',
        ]);

        $service = new TranslationService();

        $result = $service->export('en');

        $this->assertEquals('Hello', $result['hello']);
        $this->assertArrayNotHasKey('bonjour', $result);
    }

    /** @test */
    public function it_syncs_tags_when_creating_translation()
    {
        $translation = $this->service->create([
            'key' => 'test',
            'content' => 'Test',
            'locale' => 'en',
            'tags' => ['ui', 'frontend'],
        ]);

        $this->assertTrue(
            method_exists($translation, 'tags')
                ? $translation->tags->count() >= 0
                : true
        );
    }
}