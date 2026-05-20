<?php

namespace App\Http\Controllers;

use App\Models\Collection;
use App\Models\Word;
use App\Services\AchievementService;
use Illuminate\Support\Facades\Auth;

class CollectionController extends Controller
{
    public function index()
    {
        $collections = Collection::withCount('words')->orderBy('title')->get();
        $allWordsCount = Word::publicWords()->count();

        return view('collections.index', compact('collections', 'allWordsCount'));
    }

    public function show($collection)
    {
        if ($collection === 'all') {
            $words = Word::publicWords()->orderBy('english')->paginate(80);

            return view('collections.all', compact('words'));
        }

        $collection = Collection::with('words')->findOrFail($collection);
        $userWordIds = Auth::check() ? Auth::user()->words()->pluck('word_id')->toArray() : [];

        return view('collections.show', [
            'collection' => $collection,
            'words' => $collection->words,
            'userWordIds' => $userWordIds,
        ]);
    }

    public function favorite(Collection $collection)
    {
        $user = Auth::user();
        $exists = $user->favoriteCollections()->where('collection_id', $collection->id)->exists();
        $exists ? $user->favoriteCollections()->detach($collection->id) : $user->favoriteCollections()->attach($collection->id);
        app(AchievementService::class)->sync($user);

        return back()->with('success', $exists ? 'Подборка удалена из избранного.' : 'Подборка добавлена в избранное.');
    }
}
