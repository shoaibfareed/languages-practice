<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use App\Models\Translation;
use App\Http\Requests\StoreTranslationRequest;
use App\Http\Requests\UpdateTranslationRequest;
use Illuminate\Support\Facades\Cache;
use App\Services\TranslationService;
use App\Http\Resources\TranslationResource;
use App\Http\Resources\TranslationCollection;
use Illuminate\Http\Request;

class TranslationController extends Controller
{

    public function __construct(
        private TranslationService $service
    ) {}

    public function index()
    {
       return new TranslationCollection(
            Translation::with('tags')->paginate(20)
        );
    }

    public function show(Translation $translation)
    {
        return new TranslationResource(
            $translation->load('tags')
        );
    }

    public function store(StoreTranslationRequest $request)
    {
        return response()->json(
            new TranslationResource(
                $this->service->create($request->validated())
            ),
        201
    );
    }

    public function update(UpdateTranslationRequest $request, Translation $translation)
    {
        return new TranslationResource(
            $this->service->update($translation, $request->validated())
        );
    }

    public function destroy(Translation $translation)
    {
        $this->service->delete($translation);

        return response()->json(['message' => 'Deleted']);
    }

    public function search(Request $request)
    {
        $query = Translation::with('tags');

        if ($request->filled('key')) {
            $query->where('key', 'LIKE', '%' . $request->key . '%');
        }

        if ($request->filled('content')) {
            $query->where('content', 'LIKE', '%' . $request->content . '%');
        }

        if ($request->filled('locale')) {
            $query->where('locale', $request->locale);
        }

        if ($request->filled('tag')) {
            $query->whereHas('tags', function ($q) use ($request) {
                $q->where('name', $request->tag);
            });
        }

        return new TranslationCollection(
            $query->paginate(20)
        );
    }

    public function export(Request $request)
    {
        $locale = $request->input('locale', 'en');

        return response()->json(
            $this->service->export($locale)
        );
    }
}
