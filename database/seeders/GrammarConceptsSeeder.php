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
                'summary' => 'Базовый каркас английской грамматики: части речи, члены предложения, порядок слов и смысл формы слова.',
                'position' => 6,
                'content' => '<h2>Зачем нужна грамматика</h2><p>Грамматика объясняет, как слова меняют форму и соединяются в предложение. Можно знать много отдельных слов, но без грамматики трудно точно сказать, кто что делает, когда это происходит и к кому относится действие.</p><h2>Части речи</h2><table><tr><th>Термин</th><th>Функция</th><th>Примеры</th><th>В предложении</th></tr><tr><td>Noun</td><td>Называет человека, предмет, место или идею</td><td>student, city, music</td><td>The student reads.</td></tr><tr><td>Pronoun</td><td>Заменяет существительное</td><td>I, she, it, them</td><td>She reads.</td></tr><tr><td>Verb</td><td>Показывает действие или состояние</td><td>go, read, be, have</td><td>I have a book.</td></tr><tr><td>Adjective</td><td>Описывает существительное</td><td>new, small, interesting</td><td>This is a new book.</td></tr><tr><td>Adverb</td><td>Уточняет действие, частотность или качество</td><td>slowly, often, well</td><td>He speaks slowly.</td></tr><tr><td>Preposition</td><td>Показывает связь: место, время, направление</td><td>in, on, at, to, with</td><td>She is at home.</td></tr><tr><td>Conjunction</td><td>Соединяет слова и части предложения</td><td>and, but, because</td><td>I like tea and coffee.</td></tr></table><h2>Члены предложения</h2><table><tr><th>Вопрос</th><th>Роль</th><th>Пример</th></tr><tr><td>Кто? Что?</td><td>Подлежащее</td><td>My friend speaks English.</td></tr><tr><td>Что делает?</td><td>Сказуемое</td><td>My friend speaks English.</td></tr><tr><td>Кого? Что?</td><td>Дополнение</td><td>She reads a book.</td></tr><tr><td>Где? Когда? Как?</td><td>Обстоятельство</td><td>We study at school every day.</td></tr></table><h2>Базовый порядок слов</h2><p>В английском утверждении чаще всего используется порядок: кто + действие + что + где + когда.</p><table><tr><th>Кто</th><th>Действие</th><th>Что</th><th>Где</th><th>Когда</th></tr><tr><td>I</td><td>read</td><td>a book</td><td>at home</td><td>in the evening</td></tr><tr><td>They</td><td>play</td><td>football</td><td>in the park</td><td>on Sundays</td></tr></table><h2>Форма слова меняет смысл</h2><p>Маленькие элементы вроде -s, -ed, артикля или вспомогательного глагола могут менять время, число и тип предложения.</p><table><tr><th>Разница</th><th>Смысл</th></tr><tr><td>She work / She works</td><td>Во втором варианте правильная форма Present Simple для she.</td></tr><tr><td>I worked / I work</td><td>Прошлое действие / настоящее регулярное действие.</td></tr><tr><td>a book / the book</td><td>Какая-то книга / конкретная книга.</td></tr><tr><td>Do you work? / You work.</td><td>Вопрос / утверждение.</td></tr></table><h2>Что изучать сначала</h2><ul><li>Порядок слов в простом предложении.</li><li>To be: am, is, are.</li><li>Present Simple и окончание -s.</li><li>Вопросы и отрицания с do/does.</li><li>Артикли a/an/the на базовом уровне.</li><li>Множественное число существительных.</li></ul><h2>Типичные ошибки новичка</h2><ul><li>Переводить русскую фразу слово в слово.</li><li>Пропускать am/is/are, потому что в русском “есть” часто не произносится.</li><li>Забывать вспомогательные глаголы do/does в вопросах.</li><li>Учить правило отдельно от примеров.</li></ul><div class="note">Хорошая проверка любого английского предложения: найдите подлежащее и глагол. Если непонятно, кто выполняет действие и какое именно действие происходит, предложение нужно упростить.</div>',
            ]
        );
    }
}
