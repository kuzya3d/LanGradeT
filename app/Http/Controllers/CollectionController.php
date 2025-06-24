<?php

namespace App\Http\Controllers;

use App\Models\Collection;
use Illuminate\Support\Facades\Auth;

class CollectionController extends Controller
{
    public function index()
    {
        $collections = Collection::all();
        return view('collections.index', compact('collections'));
    }

    public function show(Collection $collection)
    {
        // Загружаем связанные слова через связь
        $collection->load('words');

        // Получаем ID слов, которые пользователь уже добавил в свой словарь
        $userWordIds = Auth::check()
            ? Auth::user()->words()->pluck('word_id')->toArray()
            : [];

        return view('collections.show', [
            'collection' => $collection,
            'words' => $collection->words,
            'userWordIds' => $userWordIds
        ]);
    }
}
