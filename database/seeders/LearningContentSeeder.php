<?php

namespace Database\Seeders;

use App\Models\Achievement;
use App\Models\Lesson;
use App\Models\Sentence;
use App\Models\TestType;
use Illuminate\Database\Seeder;

class LearningContentSeeder extends Seeder
{
    public function run(): void
    {
        $testTypes = [
            ['code' => 'multiple-choice', 'title' => 'Быстрый выбор перевода', 'description' => 'Выбор правильного перевода из четырех вариантов.', 'xp_reward' => 12, 'icon' => 'target'],
            ['code' => 'gap-fill', 'title' => 'Пропущенное слово', 'description' => 'Ввод недостающего слова в предложении.', 'xp_reward' => 15, 'icon' => 'edit'],
            ['code' => 'sentence-builder', 'title' => 'Собери предложение', 'description' => 'Тренировка порядка слов в английском предложении.', 'xp_reward' => 18, 'icon' => 'layout'],
            ['code' => 'phonetics', 'title' => 'Фонетика', 'description' => 'Определение слова по транскрипции.', 'xp_reward' => 14, 'icon' => 'volume'],
            ['code' => 'word-sprint', 'title' => 'Словарный спринт', 'description' => 'Быстрая проверка пар слово-перевод.', 'xp_reward' => 10, 'icon' => 'zap'],
        ];

        foreach ($testTypes as $type) {
            TestType::updateOrCreate(['code' => $type['code']], $type);
        }

        $lessons = [
            ['Фонетика: первые звуки', 'phonetics-first-sounds', 'A0', 'phonetics', 'Разница между буквами и звуками, чтение простых транскрипций.', "В английском буква и звук часто не совпадают. Начните с коротких гласных: cat [kæt], pen [pen], big [bɪɡ]. Повторяйте слово вслух и сравнивайте транскрипцию.", 1],
            ['Present Simple', 'present-simple', 'A1', 'grammar', 'Настоящее простое время для привычек и фактов.', "Формула: I/you/we/they + verb. He/she/it + verb+s. Вопросы строятся через do/does: Do you work? Does she read?", 2],
            ['Порядок слов', 'word-order', 'A1', 'grammar', 'Базовая схема английского предложения.', "Обычный порядок: кто + действие + что/где/когда. Пример: I read a book every evening. Не переставляйте глагол в конец, как иногда хочется по-русски.", 3],
            ['Артикли a/an/the', 'articles-a-an-the', 'A1', 'grammar', 'Как говорить об одном предмете и о конкретном предмете.', "A/an используем, когда предмет один и новый для собеседника: I have a book. The используем, когда понятно, о каком предмете речь: The book is on the table.", 4],
            ['Мини-диалоги', 'simple-dialogues', 'A1', 'speaking', 'Фразы для первого общения.', "Hello! How are you? I am fine, thanks. What is your name? My name is Alex. Where are you from? I am from Russia.", 5],
        ];

        foreach ($lessons as [$title, $slug, $level, $type, $summary, $content, $position]) {
            Lesson::updateOrCreate(compact('slug'), compact('title', 'level', 'type', 'summary', 'content', 'position'));
        }

        $sentences = [
            ['I drink water every day.', 'Я пью воду каждый день.', 'A1', 'daily', 'I drink ___ every day.'],
            ['She reads a book in the evening.', 'Она читает книгу вечером.', 'A1', 'daily', 'She reads a ___ in the evening.'],
            ['We learn English at school.', 'Мы учим английский в школе.', 'A1', 'study', 'We learn ___ at school.'],
            ['My friend speaks English well.', 'Мой друг хорошо говорит по-английски.', 'A1', 'people', 'My friend ___ English well.'],
            ['The bus is late today.', 'Автобус сегодня опаздывает.', 'A1', 'travel', 'The ___ is late today.'],
            ['I want coffee, please.', 'Я хочу кофе, пожалуйста.', 'A1', 'food', 'I want ___, please.'],
            ['This room is small but clean.', 'Эта комната маленькая, но чистая.', 'A1', 'home', 'This room is ___ but clean.'],
            ['Her father is a doctor.', 'Ее отец врач.', 'A1', 'people', 'Her father is a ___.'],
            ['Open the window, please.', 'Открой окно, пожалуйста.', 'A1', 'home', 'Open the ___, please.'],
            ['I have a ticket to the city.', 'У меня есть билет в город.', 'A1', 'travel', 'I have a ___ to the city.'],
        ];

        foreach ($sentences as [$english, $russian, $level, $topic, $hint]) {
            Sentence::updateOrCreate(compact('english'), compact('russian', 'level', 'topic', 'hint'));
        }

        $achievements = [
            ['first_steps', 'Первые шаги', 'Набрать первые 10 XP.', 'star', 5, 'xp', 10],
            ['practice_5', 'Разогрев', 'Пройти 5 тренировок.', 'target', 10, 'attempts', 5],
            ['vocabulary_20', 'Личный словарь', 'Добавить 20 слов в личный словарь.', 'book', 15, 'words', 20],
            ['perfect', 'Без ошибки', 'Пройти тест на 100%.', 'trophy', 20, 'perfect_tests', 1],
            ['xp_100', 'Уверенный старт', 'Набрать 100 XP.', 'zap', 30, 'xp', 100],
            ['xp_500', 'Хороший темп', 'Набрать 500 XP.', '🚀', 50, 'xp', 500],
            ['first_steps', 'Первые шаги', 'Набрать первые 10 XP.', '⭐', 5, 'xp', 10],
            ['xp_100', 'Уверенный старт', 'Набрать 100 XP.', '⚡', 30, 'xp', 100],
            ['xp_1000', 'Большой прогресс', 'Набрать 1000 XP.', '🏅', 80, 'xp', 1000],
            ['practice_1', 'Первый тест', 'Пройти любую тренировку.', '🎯', 5, 'attempts', 1],
            ['practice_5', 'Разогрев', 'Пройти 5 тренировок.', '🎯', 10, 'attempts', 5],
            ['practice_10', 'Вошел во вкус', 'Пройти 10 тренировок.', '🧩', 15, 'attempts', 10],
            ['practice_25', 'Стабильная практика', 'Пройти 25 тренировок.', '📈', 25, 'attempts', 25],
            ['perfect', 'Без ошибок', 'Пройти тест на 100%.', '🏆', 20, 'perfect_tests', 1],
            ['perfect_3', 'Чистая серия', 'Пройти 3 теста на 100%.', '🥇', 35, 'perfect_tests', 3],
            ['perfect_10', 'Мастер точности', 'Пройти 10 тестов на 100%.', '💎', 70, 'perfect_tests', 10],
            ['vocabulary_1', 'Первое слово', 'Добавить первое слово в личный словарь.', '📘', 5, 'words', 1],
            ['vocabulary_10', 'Мини-словарь', 'Добавить 10 слов в личный словарь.', '📗', 10, 'words', 10],
            ['vocabulary_20', 'Личный словарь', 'Добавить 20 слов в личный словарь.', '📚', 15, 'words', 20],
            ['vocabulary_50', 'Словарный запас', 'Добавить 50 слов в личный словарь.', '🗂️', 30, 'words', 50],
            ['vocabulary_100', 'Коллекционер слов', 'Добавить 100 слов в личный словарь.', '🧠', 60, 'words', 100],
            ['streak_1', 'День в деле', 'Зайти на сайт сегодня.', '🔥', 5, 'streak_days', 1],
            ['streak_3', 'Три дня подряд', 'Поддерживать стрик 3 дня.', '🔥', 15, 'streak_days', 3],
            ['streak_7', 'Неделя практики', 'Поддерживать стрик 7 дней.', '📅', 30, 'streak_days', 7],
            ['streak_14', 'Две недели фокуса', 'Поддерживать стрик 14 дней.', '🗓️', 50, 'streak_days', 14],
            ['favorite_lesson_1', 'Урок в закладках', 'Добавить первый урок в избранное.', '💚', 5, 'favorite_lessons', 1],
            ['favorite_collection_1', 'Набор под рукой', 'Добавить первую подборку в избранное.', '📌', 5, 'favorite_collections', 1],
            ['friend_1', 'Первый друг', 'Добавить первого друга.', '👥', 10, 'friends', 1],
            ['friends_3', 'Учебная компания', 'Добавить 3 друзей.', '🤝', 20, 'friends', 3],
            ['ai_message_1', 'Вопрос тьютору', 'Отправить первое сообщение AI-тьютору.', '💬', 5, 'ai_messages', 1],
        ];

        Achievement::whereNotIn('code', collect($achievements)->pluck(0)->unique())->delete();

        foreach ($achievements as [$code, $title, $description, $icon, $xpBonus, $conditionType, $conditionValue]) {
            Achievement::updateOrCreate(
                ['code' => $code],
                [
                    'title' => $title,
                    'description' => $description,
                    'icon' => $icon,
                    'xp_bonus' => $xpBonus,
                    'condition_type' => $conditionType,
                    'condition_value' => $conditionValue,
                ]
            );
        }
    }
}
