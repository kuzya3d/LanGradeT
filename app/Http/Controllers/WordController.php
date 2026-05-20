<?php

namespace App\Http\Controllers;

use App\Models\Word;

class WordController extends Controller
{
    public function addToVocabulary($id)
    {
        $user = auth()->user();
        $word = Word::visibleTo($user)->findOrFail($id);
        $user->words()->syncWithoutDetaching([$word->id]);

        return back()->with('success', 'Слово добавлено в ваш словарь!');
    }
}
