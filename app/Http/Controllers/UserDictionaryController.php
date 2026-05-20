<?php

namespace App\Http\Controllers;

use App\Models\Word;
use App\Services\PhoneticTranscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserDictionaryController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $userWords = $user->words()->get();

        return view('dictionary.index', compact('userWords'));
    }

    public function store(Request $request, PhoneticTranscriber $transcriber)
    {
        $request->validate([
            'english' => 'required|string|max:255',
            'russian' => 'required|string|max:255',
        ]);

        $user = Auth::user();
        $english = trim($request->english);
        $russian = preg_replace('/\s*\/\s*/u', ', ', trim($request->russian)) ?? trim($request->russian);
        $transcription = $transcriber->transcribe($english);

        $word = Word::publicWords()->where('english', $english)->first();

        if (! $word) {
            $word = Word::where('user_id', $user->id)->where('english', $english)->first();
        }

        if ($word) {
            if ($word->user_id === $user->id) {
                $word->forceFill([
                    'russian' => $russian,
                    'transcription' => $word->transcription ?: $transcription,
                    'part_of_speech' => str_contains($english, ' ') ? 'phrase' : ($word->part_of_speech ?: 'noun'),
                ])->save();
            }
        } else {
            $word = Word::create([
                'user_id' => $user->id,
                'english' => $english,
                'russian' => $russian,
                'transcription' => $transcription,
                'part_of_speech' => str_contains($english, ' ') ? 'phrase' : 'noun',
            ]);
        }

        if ($word->transcription === null && $transcription !== null) {
            $word->forceFill(['transcription' => $transcription])->save();
        }

        if (! $user->words()->where('word_id', $word->id)->exists()) {
            $user->words()->attach($word->id);
        }

        return redirect()->route('dictionary.index')->with('success', 'Слово добавлено в ваш словарь!');
    }

    public function destroy(Word $word)
    {
        $user = auth()->user();

        if ($user->words()->where('word_id', $word->id)->exists()) {
            $user->words()->detach($word->id);

            return redirect()->route('dictionary.index')->with('success', 'Слово удалено из вашего словаря.');
        }

        return redirect()->route('dictionary.index')->with('error', 'Слово не найдено в вашем словаре.');
    }
}
