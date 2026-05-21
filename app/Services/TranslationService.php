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

    public function export(string $locale)
    {
        return Cache::tags(['translations'])->remember(
            "v1:{$locale}",
            3600,
            function () use ($locale) {

                return Translation::where('locale', $locale)
                    ->select('key', 'content')
                    ->orderBy('key')
                    ->get()
                    ->pluck('content', 'key')
                    ->toArray();
            }
        );
    }
}
