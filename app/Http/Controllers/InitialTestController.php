<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InitialTestController extends Controller
{
    public function show()
    {
        $sections = $this->sections();

        return view('tests.initial', compact('sections'));
    }

    public function submit(Request $request)
    {
        if ($request->boolean('timed_out')) {
            if (Auth::check()) {
                $currentLevel = Auth::user()->english_level ?? 'A0';
                $xpLevel = User::levelFromXp((int) Auth::user()->xp);
                Auth::user()->update(['english_level' => User::higherLevel($currentLevel, $xpLevel)]);
            }

            return view('tests.result', [
                'correct' => 0,
                'total' => collect($this->sections())->sum(fn ($section) => count($section['items'])),
                'level' => Auth::check() ? Auth::user()->fresh()->english_level : 'A0',
                'placementLevel' => 'A0',
                'score' => 0,
                'sectionStats' => collect($this->sections())->mapWithKeys(fn ($section, $key) => [$key => ['correct' => 0, 'total' => count($section['items'])]])->all(),
                'recommendation' => 'Время вышло, поэтому тест засчитан как минимальный уровень. Попробуйте пройти ещё раз спокойно: начните с простых слов, коротких фраз и базовой грамматики.',
                'timedOut' => true,
            ]);
        }

        $answers = $request->input('answers', []);
        $questions = collect($this->sections())->flatMap(fn ($section, $key) => collect($section['items'])->map(fn ($item) => $item + ['section' => $key]));

        $totalWeight = 0;
        $earnedWeight = 0;
        $correctCount = 0;
        $sectionStats = [];

        foreach ($questions as $index => $question) {
            $weight = $this->points($question['level']);
            $isCorrect = ($answers[$index] ?? null) === $question['answer'];
            $section = $question['section'];

            $totalWeight += $weight;
            $earnedWeight += $isCorrect ? $weight : 0;
            $correctCount += $isCorrect ? 1 : 0;

            $sectionStats[$section] ??= ['correct' => 0, 'total' => 0];
            $sectionStats[$section]['correct'] += $isCorrect ? 1 : 0;
            $sectionStats[$section]['total']++;
        }

        $score = (int) round(($earnedWeight / max($totalWeight, 1)) * 100);
        $level = $this->placementLevel($score, $sectionStats);
        $currentLevel = Auth::check() ? Auth::user()->english_level : 'A0';
        $xpLevel = Auth::check() ? User::levelFromXp((int) Auth::user()->xp) : 'A0';
        $finalLevel = User::higherLevel(User::higherLevel($currentLevel, $level), $xpLevel);

        if (Auth::check()) {
            Auth::user()->update(['english_level' => $finalLevel]);
        }

        $recommendations = [
            'A0' => 'Начните с алфавита, фонетики, to be, простых слов и коротких фраз.',
            'A1' => 'Закрепите базовый словарь, Present Simple, вопросы и простые бытовые диалоги.',
            'A2' => 'Тренируйте there is/are, прошедшее время, устойчивые фразы и понимание коротких текстов.',
            'B1' => 'Добавляйте времена, фразовые глаголы, порядок слов и больше заданий на смысл предложений.',
            'B2' => 'Фокус на нюансах времен, условных предложениях, связках и точности перевода.',
            'C1' => 'Поддерживайте уровень чтением, письмом, разговорной практикой и разбором тонких отличий.',
        ];

        return view('tests.result', [
            'correct' => $correctCount,
            'total' => $questions->count(),
            'level' => $finalLevel,
            'placementLevel' => $level,
            'score' => $score,
            'sectionStats' => $sectionStats,
            'recommendation' => $recommendations[$finalLevel],
            'timedOut' => false,
        ]);
    }

    private function sections(): array
    {
        return [
            'vocabulary' => [
                'title' => 'Слова',
                'hint' => 'Выберите наиболее точный перевод.',
                'items' => [
                    ['question' => 'cat', 'answer' => 'кот', 'options' => ['кот', 'дом', 'молоко', 'книга'], 'level' => 'A1'],
                    ['question' => 'water', 'answer' => 'вода', 'options' => ['еда', 'вода', 'окно', 'город'], 'level' => 'A1'],
                    ['question' => 'school', 'answer' => 'школа', 'options' => ['работа', 'школа', 'улица', 'семья'], 'level' => 'A1'],
                    ['question' => 'small', 'answer' => 'маленький', 'options' => ['новый', 'маленький', 'тёплый', 'быстрый'], 'level' => 'A1'],
                    ['question' => 'doctor', 'answer' => 'врач', 'options' => ['учитель', 'водитель', 'врач', 'друг'], 'level' => 'A1'],
                    ['question' => 'usually', 'answer' => 'обычно', 'options' => ['обычно', 'никогда', 'сейчас', 'завтра'], 'level' => 'A2'],
                    ['question' => 'reliable', 'answer' => 'надежный', 'options' => ['редкий', 'надежный', 'быстрый', 'мягкий'], 'level' => 'A2'],
                    ['question' => 'although', 'answer' => 'хотя', 'options' => ['поэтому', 'хотя', 'снаружи', 'почти'], 'level' => 'B1'],
                    ['question' => 'sustainable', 'answer' => 'устойчивый', 'options' => ['устойчивый', 'временный', 'смешной', 'официальный'], 'level' => 'B2'],
                    ['question' => 'ambiguous', 'answer' => 'двусмысленный', 'options' => ['очевидный', 'двусмысленный', 'случайный', 'обязательный'], 'level' => 'C1'],
                ],
            ],
            'phrases' => [
                'title' => 'Словосочетания',
                'hint' => 'Проверьте устойчивые выражения и бытовые фразы.',
                'items' => [
                    ['question' => 'good morning', 'answer' => 'доброе утро', 'options' => ['доброе утро', 'спокойной ночи', 'до встречи', 'как дела'], 'level' => 'A1'],
                    ['question' => 'thank you', 'answer' => 'спасибо', 'options' => ['пожалуйста', 'извините', 'спасибо', 'привет'], 'level' => 'A1'],
                    ['question' => 'at home', 'answer' => 'дома', 'options' => ['в школе', 'дома', 'на улице', 'на работе'], 'level' => 'A1'],
                    ['question' => 'on Monday', 'answer' => 'в понедельник', 'options' => ['утром', 'в понедельник', 'на столе', 'в городе'], 'level' => 'A1'],
                    ['question' => 'a cup of tea', 'answer' => 'чашка чая', 'options' => ['чашка чая', 'стакан воды', 'кусок хлеба', 'тарелка супа'], 'level' => 'A2'],
                    ['question' => 'go shopping', 'answer' => 'ходить за покупками', 'options' => ['идти домой', 'ходить за покупками', 'делать уроки', 'ждать автобус'], 'level' => 'A2'],
                    ['question' => 'make a decision', 'answer' => 'принять решение', 'options' => ['сделать ошибку', 'принять решение', 'создать правило', 'задать вопрос'], 'level' => 'A2'],
                    ['question' => 'pay attention', 'answer' => 'обращать внимание', 'options' => ['платить заранее', 'обращать внимание', 'получать письмо', 'держать обещание'], 'level' => 'B1'],
                    ['question' => 'take into account', 'answer' => 'принимать во внимание', 'options' => ['войти в аккаунт', 'принимать во внимание', 'снять деньги', 'начать сначала'], 'level' => 'B2'],
                    ['question' => 'to some extent', 'answer' => 'в некоторой степени', 'options' => ['в конце концов', 'в некоторой степени', 'без сомнения', 'вместо этого'], 'level' => 'C1'],
                ],
            ],
            'grammar' => [
                'title' => 'Грамматика',
                'hint' => 'Выберите форму, которая делает предложение правильным.',
                'items' => [
                    ['question' => 'I ___ a student.', 'answer' => 'am', 'options' => ['am', 'is', 'are', 'be'], 'level' => 'A1'],
                    ['question' => 'They ___ from London.', 'answer' => 'are', 'options' => ['am', 'is', 'are', 'does'], 'level' => 'A1'],
                    ['question' => 'He ___ a car.', 'answer' => 'has', 'options' => ['have', 'has', 'is', 'do'], 'level' => 'A1'],
                    ['question' => 'She ___ coffee every morning.', 'answer' => 'drinks', 'options' => ['drink', 'drinks', 'drinking', 'to drink'], 'level' => 'A1'],
                    ['question' => 'We ___ TV now.', 'answer' => 'are watching', 'options' => ['watch', 'watches', 'are watching', 'watched'], 'level' => 'A2'],
                    ['question' => 'I ___ to school yesterday.', 'answer' => 'went', 'options' => ['go', 'goes', 'went', 'going'], 'level' => 'A2'],
                    ['question' => 'There ___ two books on the table.', 'answer' => 'are', 'options' => ['is', 'are', 'am', 'be'], 'level' => 'A2'],
                    ['question' => 'I have lived here ___ 2020.', 'answer' => 'since', 'options' => ['for', 'since', 'during', 'by'], 'level' => 'B1'],
                    ['question' => 'If I had more time, I ___ help you.', 'answer' => 'would', 'options' => ['will', 'would', 'did', 'have'], 'level' => 'B2'],
                ],
            ],
            'sentences' => [
                'title' => 'Понимание предложений',
                'hint' => 'Выберите смысл всего предложения, а не отдельных слов.',
                'items' => [
                    ['question' => 'My name is Anna.', 'answer' => 'Меня зовут Анна.', 'options' => ['Меня зовут Анна.', 'Анна дома.', 'Я знаю Анну.', 'Анна моя сестра.'], 'level' => 'A1'],
                    ['question' => 'The book is on the table.', 'answer' => 'Книга на столе.', 'options' => ['Книга под столом.', 'Книга на столе.', 'Стол в комнате.', 'Я читаю книгу.'], 'level' => 'A1'],
                    ['question' => 'I like this city.', 'answer' => 'Мне нравится этот город.', 'options' => ['Я живу в городе.', 'Мне нравится этот город.', 'Город большой.', 'Я вижу город.'], 'level' => 'A1'],
                    ['question' => 'Can you help me?', 'answer' => 'Ты можешь мне помочь?', 'options' => ['Ты можешь мне помочь?', 'Я могу помочь тебе.', 'Ты знаешь меня?', 'Мне нужна книга.'], 'level' => 'A1'],
                    ['question' => 'She is going to visit her friend.', 'answer' => 'Она собирается навестить друга.', 'options' => ['Она уже навестила друга.', 'Она собирается навестить друга.', 'Она работает с другом.', 'Она ищет друга.'], 'level' => 'A2'],
                    ['question' => 'I have never been to London.', 'answer' => 'Я никогда не был в Лондоне.', 'options' => ['Я часто бываю в Лондоне.', 'Я никогда не был в Лондоне.', 'Я живу в Лондоне.', 'Я еду в Лондон завтра.'], 'level' => 'A2'],
                    ['question' => 'I am looking for my keys.', 'answer' => 'Я ищу ключи.', 'options' => ['Я смотрю на ключи.', 'Я ищу ключи.', 'Я нашел ключи.', 'Я потерял телефон.'], 'level' => 'A2'],
                    ['question' => 'The meeting was called off.', 'answer' => 'Встречу отменили.', 'options' => ['Встречу отменили.', 'Встречу начали.', 'Встречу перенесли на утро.', 'На встречу позвали.'], 'level' => 'B1'],
                    ['question' => 'She is used to working at night.', 'answer' => 'Она привыкла работать ночью.', 'options' => ['Она работала ночью раньше.', 'Она привыкла работать ночью.', 'Она использовала ночную смену.', 'Она хочет работать ночью.'], 'level' => 'B2'],
                    ['question' => 'Had I known earlier, I would have warned you.', 'answer' => 'Если бы я знал раньше, я бы предупредил тебя.', 'options' => ['Я узнал раньше и предупредил тебя.', 'Если бы я знал раньше, я бы предупредил тебя.', 'Когда я узнаю, я предупрежу тебя.', 'Я не хотел тебя предупреждать.'], 'level' => 'C1'],
                ],
            ],
        ];
    }

    private function placementLevel(int $score, array $sectionStats): string
    {
        $sectionPercent = function (string $section) use ($sectionStats): int {
            $stats = $sectionStats[$section] ?? ['correct' => 0, 'total' => 1];

            return (int) round(($stats['correct'] / max($stats['total'], 1)) * 100);
        };

        $grammar = $sectionPercent('grammar');
        $phrases = $sectionPercent('phrases');
        $sentences = $sectionPercent('sentences');

        return match (true) {
            $score >= 96 && $grammar >= 75 && $phrases >= 75 && $sentences >= 75 => 'C1',
            $score >= 88 && $grammar >= 75 && $phrases >= 75 && $sentences >= 75 => 'B2',
            $score >= 75 && $grammar >= 50 && $phrases >= 50 && $sentences >= 50 => 'B1',
            $score >= 60 && $grammar >= 50 && $sentences >= 50 => 'A2',
            $score >= 45 => 'A1',
            default => 'A0',
        };
    }

    private function points(string $level): int
    {
        return ['A1' => 1, 'A2' => 2, 'B1' => 3, 'B2' => 4, 'C1' => 5][$level] ?? 1;
    }
}
