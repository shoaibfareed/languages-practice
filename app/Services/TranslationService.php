<?php

namespace App\Services;

use App\Models\Tag;
use App\Models\Translation;
use Illuminate\Support\Facades\Cache;

class TranslationService
{
    public function create(array $data)
    {
        $translation = Translation::create([
            'key' => $data['key'],
            'content' => $data['content'],
            'locale' => $data['locale'],
        ]);

        $this->syncTags($translation, $data['tags'] ?? []);

        $this->forgetCache($translation->locale);

        return $translation->load('tags');
    }

    public function update(Translation $translation, array $data)
    {
        $translation->update($data);

        if (isset($data['tags'])) {
            $this->syncTags($translation, $data['tags']);
        }

        $this->forgetCache($translation->locale);

        return $translation->load('tags');
    }

    public function delete(Translation $translation)
    {
        $locale = $translation->locale;

        $translation->delete();

        $this->forgetCache($locale);
    }

    private function syncTags(Translation $translation, array $tags)
    {
        $tagIds = [];

        foreach ($tags as $tagName) {
            $tag = Tag::firstOrCreate(['name' => $tagName]);
            $tagIds[] = $tag->id;
        }

        $translation->tags()->sync($tagIds);
    }

    private function forgetCache(): void
    {
        Cache::tags(['translations'])->flush();
    }

    public function export(string $locale): array
    {
        return Cache::tags(['translations'])->remember(
            "translations:{$locale}",
            now()->addHour(),
            function () use ($locale) {

                $data = [];

                Translation::query()
                    ->select(['key', 'content'])
                    ->where('locale', $locale)
                    ->orderBy('key')
                    ->chunk(1000, function ($rows) use (&$data) {
                        foreach ($rows as $row) {
                            $data[$row->key] = $row->content;
                        }
                    });

                return $data;
            }
        );
    }
}
