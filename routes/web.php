<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InitialTestController;
use App\Http\Controllers\CollectionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserDictionaryController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\PracticeController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\LeaderboardController;
use App\Http\Controllers\CommunityController;
use App\Http\Controllers\AiTutorController;

Route::get('/', [HomeController::class, 'index'])->name('home');

//онли аутх
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::post('/logout', [ProfileController::class, 'logout'])->name('logout');

    //личныйсл
    Route::get('/dictionary', [UserDictionaryController::class, 'index'])->name('dictionary.index');
    Route::post('/dictionary', [UserDictionaryController::class, 'store'])->name('dictionary.store');
    Route::delete('/dictionary/{word}', [UserDictionaryController::class, 'destroy'])->name('dictionary.destroy');

    //tесты
    Route::get('/tests/compile-word', [TestController::class, 'compileWord'])->name('tests.compile-word');
    Route::get('/tests/translation', [TestController::class, 'translation'])->name('tests.translation');
    Route::post('/tests/compile-word', [TestController::class, 'submitCompileWord'])->name('tests.compile-word.submit');
    Route::post('/tests/compile-word/submit', [TestController::class, 'submitCompileWord'])->name('tests.submit-compile-word');
    Route::post('/tests/translation', [TestController::class, 'submitTranslation'])->name('tests.translation.submit');
    Route::get('/tests/flashcards', [TestController::class, 'flashcards'])->name('tests.flashcards');
    Route::get('/tests/multiple-choice', [PracticeController::class, 'multipleChoice'])->name('tests.multiple-choice');
    Route::get('/tests/gap-fill', [PracticeController::class, 'gapFill'])->name('tests.gap-fill');
    Route::get('/tests/sentence-builder', [PracticeController::class, 'sentenceBuilder'])->name('tests.sentence-builder');
    Route::get('/tests/phonetics', [PracticeController::class, 'phonetics'])->name('tests.phonetics');
    Route::get('/tests/word-sprint', [PracticeController::class, 'wordSprint'])->name('tests.word-sprint');
    Route::post('/tests/{mode}/submit', [PracticeController::class, 'submit'])->name('tests.modern-submit');

    Route::get('/community', [CommunityController::class, 'index'])->name('community.index');
    Route::get('/community/updates', [CommunityController::class, 'updates'])->name('community.updates');
    Route::post('/community', [CommunityController::class, 'start'])->name('community.start');
    Route::post('/community/{conversation}', [CommunityController::class, 'reply'])->name('community.reply');
    Route::delete('/community/{conversation}', [CommunityController::class, 'destroy'])->name('community.destroy');
    Route::post('/community/friends/{user}', [CommunityController::class, 'addFriend'])->name('community.add-friend');
    Route::delete('/community/friends/{user}', [CommunityController::class, 'removeFriend'])->name('community.remove-friend');

    Route::get('/ai-tutor', [AiTutorController::class, 'index'])->name('ai.index');
    Route::post('/ai-tutor', [AiTutorController::class, 'ask'])->name('ai.ask');
    Route::get('/ai-tutor/messages/{message}', [AiTutorController::class, 'message'])->name('ai.message');
    Route::delete('/ai-tutor', [AiTutorController::class, 'clear'])->name('ai.clear');
    Route::post('/collections/{collection}/favorite', [CollectionController::class, 'favorite'])->name('collections.favorite');
    Route::post('/lessons/{lesson}/favorite', [LessonController::class, 'favorite'])->name('lessons.favorite');
});

//открытые маршруты для гостей
Route::get('/login', [AuthController::class, 'loginView'])->name('login');
Route::get('/register', [AuthController::class, 'registerView'])->name('register');

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

Route::get('/initial-test', [InitialTestController::class, 'show'])->name('initial-test.show');
Route::post('/initial-test', [InitialTestController::class, 'submit'])->name('initial-test.submit');
Route::get('/tests', [TestController::class, 'index'])->name('tests.index');

Route::get('/collections', [CollectionController::class, 'index'])->name('collections.index');
Route::get('/collections/{collection}', [CollectionController::class, 'show'])->name('collections.show');
Route::post('/words/{id}/add', [\App\Http\Controllers\WordController::class, 'addToVocabulary'])->middleware('auth')->name('words.add');
Route::get('/lessons', [LessonController::class, 'index'])->name('lessons.index');
Route::get('/lessons/{lesson:slug}', [LessonController::class, 'show'])->name('lessons.show');
Route::get('/leaderboard', [LeaderboardController::class, 'index'])->name('leaderboard.index');
Route::get('/users/{user}', [ProfileController::class, 'show'])->name('users.show');
Route::post('/profile/avatar', [ProfileController::class, 'avatar'])->middleware('auth')->name('profile.avatar');
Route::post('/profile/bio', [ProfileController::class, 'bio'])->middleware('auth')->name('profile.bio');
