<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use App\Services\AchievementService;

class LessonController extends Controller
{
    public function index()
    {
        $lessons = Lesson::orderBy('position')->get()->groupBy('type');

        return view('lessons.index', compact('lessons'));
    }

    public function show(Lesson $lesson)
    {
        return view('lessons.show', compact('lesson'));
    }

    public function favorite(Lesson $lesson)
    {
        $user = auth()->user();
        $exists = $user->favoriteLessons()->where('lesson_id', $lesson->id)->exists();
        $exists ? $user->favoriteLessons()->detach($lesson->id) : $user->favoriteLessons()->attach($lesson->id);
        app(AchievementService::class)->sync($user);

        return back()->with('success', $exists ? 'Урок удален из избранного.' : 'Урок добавлен в избранное.');
    }
}
