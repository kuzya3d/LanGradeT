<?php

namespace Database\Seeders;

use App\Models\Lesson;
use Illuminate\Database\Seeder;

class GrammarConceptsSeeder extends Seeder
{
    public function run(): void
    {
        Lesson::updateOrCreate(
            ['slug' => 'grammar-basic-concepts'],
            [
                'title' => 'Грамматика: основные понятия',
                'level' => 'A1',
                'type' => 'grammar',
                'summary' => 'Каркас английской речи: части речи, порядок слов, формы слова и смысл грамматической ошибки.',
                'position' => 6,
                'content' => '<h2>Зачем нужна грамматика</h2><p>Грамматика показывает, как слова меняют форму и как соединяются в предложение. Без нее можно знать отдельные слова, но трудно точно передать мысль.</p><h2>Части речи</h2><table><tr><th>Термин</th><th>Функция</th><th>Пример</th></tr><tr><td>Noun</td><td>называет предмет или человека</td><td>a student, a house</td></tr><tr><td>Pronoun</td><td>заменяет существительное</td><td>I, he, them</td></tr><tr><td>Adjective</td><td>описывает существительное</td><td>beautiful, kind</td></tr><tr><td>Verb</td><td>действие или состояние</td><td>run, be, feel</td></tr><tr><td>Adverb</td><td>уточняет действие</td><td>quickly, often</td></tr><tr><td>Preposition</td><td>связывает слова</td><td>on, in, near</td></tr><tr><td>Conjunction</td><td>соединяет части</td><td>and, but, because</td></tr></table><h2>Почему ошибка может менять смысл</h2><p>Сравните: I stopped talking to him — я перестал с ним разговаривать. I stopped to talk to him — я остановился, чтобы поговорить с ним. Маленькая грамматическая разница меняет смысл.</p><h2>Мини-план изучения</h2><ul><li>Сначала части речи и порядок слов.</li><li>Затем Present Simple и to be.</li><li>Потом вопросы, отрицания, артикли и предлоги.</li></ul><div class="note">Грамматику лучше учить через короткие предложения и тесты, а не через длинные определения.</div>',
            ]
        );
    }
}
