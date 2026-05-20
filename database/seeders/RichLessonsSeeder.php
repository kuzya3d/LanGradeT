<?php

namespace Database\Seeders;

use App\Models\Lesson;
use Illuminate\Database\Seeder;

class RichLessonsSeeder extends Seeder
{
    public function run(): void
    {
        $html = [
            'present-simple' => [
                'summary' => 'Present Simple нужен для привычек, фактов, расписаний и регулярных действий.',
                'content' => '<h2>Когда использовать</h2><ul><li>Привычки: I read every evening.</li><li>Факты: Water is important.</li><li>Расписание: The lesson starts at nine.</li></ul><h2>Формы</h2><table><tr><th>Кто</th><th>Утверждение</th><th>Отрицание</th><th>Вопрос</th></tr><tr><td>I/you/we/they</td><td>I work</td><td>I do not work</td><td>Do you work?</td></tr><tr><td>he/she/it</td><td>She works</td><td>She does not work</td><td>Does she work?</td></tr></table><h2>Окончание -s</h2><p>После he/she/it добавляется -s: reads, drinks, learns. После -sh, -ch, -x, -o часто добавляется -es: watches, goes.</p><div class="note">В вопросе и отрицании окончание -s уходит в does: Does she read? She does not read.</div><h2>Маркеры</h2><p>always, usually, often, sometimes, never, every day, on Mondays.</p><h2>Мини-практика</h2><p>Составьте 5 предложений о себе: I usually..., I never..., I often...</p>',
            ],
            'phonetics-first-sounds' => [
                'summary' => 'Фонетика помогает читать слова и не путать английское написание со звучанием.',
                'content' => '<h2>Буква и звук</h2><p>В английском одна буква может звучать по-разному. Поэтому рядом со словом полезна транскрипция: cat [kæt], name [neɪm], book [bʊk].</p><h2>Частые звуки</h2><table><tr><th>Звук</th><th>Пример</th><th>Как тренировать</th></tr><tr><td>[æ]</td><td>cat, apple</td><td>Откройте рот шире, звук между “э” и “а”.</td></tr><tr><td>[ɪ]</td><td>big, fish</td><td>Короткий звук, не тянуть как “и-и”.</td></tr><tr><td>[iː]</td><td>tea, read</td><td>Длинный звук, тянется.</td></tr><tr><td>[θ]</td><td>think</td><td>Кончик языка между зубами, без “с”.</td></tr></table><h2>Правило тренировки</h2><ul><li>Сначала смотрите на транскрипцию.</li><li>Произнесите слово медленно.</li><li>Сравните похожие пары: ship/sheep, bit/beat.</li></ul><div class="note">Не пытайтесь читать все русскими буквами. Это быстро закрепляет неправильное произношение.</div>',
            ],
            'word-order' => [
                'summary' => 'Порядок слов - основа понятного английского предложения.',
                'content' => '<h2>Базовая схема</h2><p>Английское предложение обычно строится так: кто + действие + что + где + когда.</p><table><tr><th>Кто</th><th>Действие</th><th>Что</th><th>Где/когда</th></tr><tr><td>I</td><td>read</td><td>a book</td><td>in the evening</td></tr><tr><td>She</td><td>drinks</td><td>tea</td><td>every morning</td></tr></table><h2>Почему это важно</h2><p>В русском порядок гибкий, а в английском он показывает связи между словами. “I like coffee” понятно, а “Coffee like I” ломает смысл.</p><h2>Вопросы</h2><p>В вопросах появляется вспомогательное слово: Do you like coffee? Where do you live?</p><h2>Частые ошибки</h2><ul><li>Ставить глагол в конец предложения.</li><li>Забывать do/does в вопросах.</li><li>Переводить русскую фразу слово в слово.</li></ul>',
            ],
            'articles-a-an-the' => [
                'summary' => 'Артикли помогают понять, говорим ли мы о любом предмете или о конкретном.',
                'content' => '<h2>A / an</h2><p>Используем, когда предмет один и новый для собеседника: I have a book. She is an engineer.</p><h2>The</h2><p>Используем, когда предмет уже известен или единственный в ситуации: The book is on the table. The sun is bright.</p><table><tr><th>Ситуация</th><th>Пример</th></tr><tr><td>Первое упоминание</td><td>I see a dog.</td></tr><tr><td>Повторное упоминание</td><td>The dog is small.</td></tr><tr><td>Профессия</td><td>He is a doctor.</td></tr></table><h2>Когда артикля нет</h2><ul><li>Перед множественным числом в общем смысле: Dogs are friendly.</li><li>Перед неисчисляемыми словами в общем смысле: Water is important.</li></ul>',
            ],
            'questions-basic' => [
                'summary' => 'Базовые вопросы строятся через do/does или через am/is/are.',
                'content' => '<h2>Вопросы с to be</h2><table><tr><th>Утверждение</th><th>Вопрос</th></tr><tr><td>You are ready.</td><td>Are you ready?</td></tr><tr><td>She is tired.</td><td>Is she tired?</td></tr></table><h2>Вопросы с обычными глаголами</h2><p>Нужны do/does: Do you study? Does he work?</p><h2>Вопросительные слова</h2><table><tr><th>Слово</th><th>Значение</th><th>Пример</th></tr><tr><td>What</td><td>что</td><td>What do you read?</td></tr><tr><td>Where</td><td>где</td><td>Where do you live?</td></tr><tr><td>Why</td><td>почему</td><td>Why do you learn English?</td></tr></table>',
            ],
        ];

        foreach ($html as $slug => $data) {
            Lesson::where('slug', $slug)->update($data);
        }

        Lesson::all()->each(function (Lesson $lesson) {
            if (str_contains($lesson->content, '<h2>')) {
                return;
            }

            $lesson->update([
                'content' => '<h2>Цель урока</h2><p>' . e($lesson->summary) . '</p><h2>Основное правило</h2><p>' . e($lesson->content) . '</p><h2>Примеры</h2><table><tr><th>Английский</th><th>Русский</th><th>Комментарий</th></tr><tr><td>I learn English every day.</td><td>Я учу английский каждый день.</td><td>Простое утверждение.</td></tr><tr><td>Do you understand this rule?</td><td>Ты понимаешь это правило?</td><td>Вопрос для самопроверки.</td></tr><tr><td>This example is important.</td><td>Этот пример важен.</td><td>Полезная фраза для учебы.</td></tr></table><h2>Типичные ошибки</h2><ul><li>Переводить русское предложение слово в слово.</li><li>Пропускать служебные слова, которые нужны в английском.</li><li>Учить правило без собственных примеров.</li></ul><h2>Как закрепить</h2><p>Составьте 3 коротких предложения о себе, затем проверьте порядок слов и форму глагола. Если урок связан с текущими тестами, используйте кнопку закрепления ниже.</p><div class="note">Лучше сделать 5 маленьких правильных предложений, чем одно длинное и запутанное.</div>',
            ]);
        });
    }
}
