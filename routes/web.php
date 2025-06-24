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

