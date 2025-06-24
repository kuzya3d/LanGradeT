<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Word;

class UserDictionaryController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $userWords = $user->words()->get();

        return view('dictionary.index', compact('userWords'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'english' => 'required|string|max:255',
            'russian' => 'required|string|max:255',
        ]);

        $user = Auth::user();

        // Проверим, есть ли такое слово в базе
        $word = Word::firstOrCreate(
            ['english' => $request->english],
            ['russian' => $request->russian, 'image' => 'default.jpg'] // Можно поставить дефолтное изображение
        );

        // Привяжем слово к пользователю, если ещё нет
        if (!$user->words()->where('word_id', $word->id)->exists()) {
            $user->words()->attach($word->id);
        }

        return redirect()->route('dictionary.index')->with('success', 'Слово добавлено в ваш словарь!');
    }

    public function destroy(Word $word)
{
    $user = auth()->user();

    // Удаляем только если слово действительно привязано к пользователю
    if ($user->words()->where('word_id', $word->id)->exists()) {
        $user->words()->detach($word->id);
        return redirect()->route('dictionary.index')->with('success', 'Слово удалено из вашего словаря.');
    }

    return redirect()->route('dictionary.index')->with('error', 'Слово не найдено в вашем словаре.');
}

}
