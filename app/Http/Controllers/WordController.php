<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WordController extends Controller
{
    public function addToVocabulary($id)
{
    $user = auth()->user();
    $user->words()->syncWithoutDetaching([$id]);

    return back()->with('success', 'Слово добавлено в ваш словарь!');
}

}
