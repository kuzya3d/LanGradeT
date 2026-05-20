<?php

namespace Database\Seeders;

use App\Models\Collection;
use App\Models\Sentence;
use App\Models\Word;
use Illuminate\Database\Seeder;

class SourceInspiredContentSeeder extends Seeder
{
    public function run(): void
    {
        Sentence::where('english', 'like', 'I use the word %')->delete();

        $collections = [
            'Человек: базовые слова' => [
                'description' => 'Человек, возраст, внешность и общие слова о людях.',
                'words' => [
                    ['adult', 'взрослый', 'noun', 'An adult can help you with this form.', 'Взрослый может помочь тебе с этой формой.'],
                    ['baby', 'малыш', 'noun', 'The baby sleeps in the room.', 'Малыш спит в комнате.'],
                    ['boy', 'мальчик', 'noun', 'The boy plays football after school.', 'Мальчик играет в футбол после школы.'],
                    ['girl', 'девочка', 'noun', 'The girl reads very well.', 'Девочка очень хорошо читает.'],
                    ['teenager', 'подросток', 'noun', 'My cousin is a teenager.', 'Мой двоюродный брат подросток.'],
                    ['woman', 'женщина', 'noun', 'The woman works in a hospital.', 'Женщина работает в больнице.'],
                    ['man', 'мужчина', 'noun', 'The man drives a bus.', 'Мужчина водит автобус.'],
                    ['human', 'человек', 'noun', 'Every human needs water.', 'Каждому человеку нужна вода.'],
                    ['life', 'жизнь', 'noun', 'City life is fast.', 'Жизнь в городе быстрая.'],
                    ['surname', 'фамилия', 'noun', 'Write your surname here.', 'Напишите свою фамилию здесь.'],
                ],
            ],
            'Тело и здоровье' => [
                'description' => 'Части тела и простые фразы для самочувствия.',
                'words' => [
                    ['arm', 'рука', 'noun', 'My arm hurts after tennis.', 'У меня болит рука после тенниса.'],
                    ['back', 'спина', 'noun', 'His back hurts today.', 'У него сегодня болит спина.'],
                    ['ear', 'ухо', 'noun', 'My ear hurts.', 'У меня болит ухо.'],
                    ['face', 'лицо', 'noun', 'Wash your face in the morning.', 'Умывай лицо утром.'],
                    ['finger', 'палец', 'noun', 'She cuts her finger.', 'Она порезала палец.'],
                    ['foot', 'ступня', 'noun', 'My left foot is cold.', 'Моя левая ступня замерзла.'],
                    ['heart', 'сердце', 'noun', 'The heart works all day.', 'Сердце работает весь день.'],
                    ['mouth', 'рот', 'noun', 'Open your mouth, please.', 'Откройте рот, пожалуйста.'],
                    ['neck', 'шея', 'noun', 'My neck is tired.', 'У меня устала шея.'],
                    ['tooth', 'зуб', 'noun', 'This tooth hurts.', 'Этот зуб болит.'],
                ],
            ],
            'Характер и эмоции' => [
                'description' => 'Слова для описания характера и настроения.',
                'words' => [
                    ['active', 'активный', 'adjective', 'My grandfather is very active.', 'Мой дедушка очень активный.'],
                    ['brave', 'храбрый', 'adjective', 'The child is brave.', 'Ребенок храбрый.'],
                    ['careful', 'осторожный', 'adjective', 'Be careful with this knife.', 'Будь осторожен с этим ножом.'],
                    ['cheerful', 'радостный', 'adjective', 'She is cheerful in the morning.', 'Она радостная утром.'],
                    ['clever', 'умный', 'adjective', 'That is a clever answer.', 'Это умный ответ.'],
                    ['curious', 'любопытный', 'adjective', 'Curious students ask questions.', 'Любопытные студенты задают вопросы.'],
                    ['friendly', 'дружелюбный', 'adjective', 'Our teacher is friendly.', 'Наш учитель дружелюбный.'],
                    ['honest', 'честный', 'adjective', 'He is an honest person.', 'Он честный человек.'],
                    ['patient', 'терпеливый', 'adjective', 'A good teacher is patient.', 'Хороший учитель терпеливый.'],
                    ['reliable', 'надежный', 'adjective', 'This app is reliable.', 'Это приложение надежное.'],
                ],
            ],
            'Present Simple: действия' => [
                'description' => 'Глаголы и фразы для привычек, фактов и расписаний.',
                'words' => [
                    ['study', 'учиться', 'verb', 'Lisa studies chemistry on Mondays.', 'Лиза изучает химию по понедельникам.'],
                    ['cook', 'готовить', 'verb', 'My father cooks dinner on Fridays.', 'Мой отец готовит ужин по пятницам.'],
                    ['arrive', 'прибывать', 'verb', 'The train arrives at six.', 'Поезд прибывает в шесть.'],
                    ['rise', 'восходить', 'verb', 'The sun rises in the east.', 'Солнце восходит на востоке.'],
                    ['brush', 'чистить', 'verb', 'I brush my teeth after breakfast.', 'Я чищу зубы после завтрака.'],
                    ['visit', 'посещать', 'verb', 'My friends visit me on weekends.', 'Мои друзья навещают меня по выходным.'],
                    ['remember', 'помнить', 'verb', 'Mark remembers every rule.', 'Марк помнит каждое правило.'],
                    ['understand', 'понимать', 'verb', 'They understand the problem.', 'Они понимают проблему.'],
                    ['agree', 'соглашаться', 'verb', 'I agree with you.', 'Я согласен с тобой.'],
                    ['enjoy', 'получать удовольствие', 'verb', 'We enjoy our English lessons.', 'Нам нравятся уроки английского.'],
                ],
            ],
        ];

        foreach ($collections as $title => $data) {
            $collection = Collection::updateOrCreate(['title' => $title], ['description' => $data['description']]);
            $ids = [];
            foreach ($data['words'] as [$english, $russian, $part, $exampleEn, $exampleRu]) {
                $word = Word::updateOrCreate(
                    ['english' => $english],
                    [
                        'russian' => $russian,
                        'transcription' => '[' . $english . ']',
                        'part_of_speech' => $part,
                        'difficulty' => 'A1',
                        'example_en' => $exampleEn,
                        'example_ru' => $exampleRu,
                    ]
                );
                $ids[] = $word->id;
            }
            $collection->words()->syncWithoutDetaching($ids);
        }

        $sentences = [
            ['I go to the gym every morning.', 'Я хожу в спортзал каждое утро.', 'I go to the ___ every morning.'],
            ['She reads a book every week.', 'Она читает книгу каждую неделю.', 'She reads a ___ every week.'],
            ['They work in a bank.', 'Они работают в банке.', 'They work in a ___.'],
            ['John studies French on Mondays.', 'Джон изучает французский по понедельникам.', 'John studies ___ on Mondays.'],
            ['It rains a lot in April.', 'В апреле часто идет дождь.', 'It ___ a lot in April.'],
            ['We play soccer after school.', 'Мы играем в футбол после школы.', 'We play ___ after school.'],
            ['My brother watches TV at night.', 'Мой брат смотрит телевизор ночью.', 'My brother ___ TV at night.'],
            ['The sun rises in the east.', 'Солнце восходит на востоке.', 'The sun ___ in the east.'],
            ['I drink coffee in the morning.', 'Я пью кофе утром.', 'I drink ___ in the morning.'],
            ['The train arrives at six.', 'Поезд прибывает в шесть.', 'The train ___ at six.'],
            ['My dad drives a taxi.', 'Мой папа водит такси.', 'My dad drives a ___.'],
            ['We live near the station.', 'Мы живем рядом со станцией.', 'We live near the ___.'],
            ['Sarah teaches English.', 'Сара преподает английский.', 'Sarah ___ English.'],
            ['They walk to work.', 'Они ходят на работу пешком.', 'They ___ to work.'],
            ['The shop opens at nine.', 'Магазин открывается в девять.', 'The shop ___ at nine.'],
            ['He eats lunch at noon.', 'Он обедает в полдень.', 'He eats ___ at noon.'],
            ['I do not like spicy food.', 'Мне не нравится острая еда.', 'I do not like ___ food.'],
            ['She does not drink milk.', 'Она не пьет молоко.', 'She does not drink ___.'],
            ['We do not watch that show.', 'Мы не смотрим это шоу.', 'We do not ___ that show.'],
            ['It does not snow here.', 'Здесь не идет снег.', 'It does not ___ here.'],
            ['Do you know the answer?', 'Ты знаешь ответ?', 'Do you know the ___?'],
            ['Does she like coffee?', 'Ей нравится кофе?', 'Does she like ___?'],
            ['Do your parents live nearby?', 'Твои родители живут рядом?', 'Do your parents ___ nearby?'],
            ['Does it rain often here?', 'Здесь часто идет дождь?', 'Does it ___ often here?'],
            ['Do the students read every day?', 'Студенты читают каждый день?', 'Do the students ___ every day?'],
            ['Where do you live?', 'Где ты живешь?', 'Where do you ___?'],
            ['What do you want?', 'Что ты хочешь?', 'What do you ___?'],
            ['When do we meet?', 'Когда мы встречаемся?', 'When do we ___?'],
            ['Why do you learn English?', 'Почему ты учишь английский?', 'Why do you ___ English?'],
            ['Which book do you prefer?', 'Какую книгу ты предпочитаешь?', 'Which book do you ___?'],
        ];

        foreach ($sentences as [$english, $russian, $hint]) {
            Sentence::updateOrCreate(
                ['english' => $english],
                ['russian' => $russian, 'level' => 'A1', 'topic' => 'Present Simple', 'hint' => $hint]
            );
        }
    }
}
