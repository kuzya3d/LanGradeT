<?php

namespace Database\Seeders;

use App\Models\Collection;
use App\Models\Lesson;
use App\Models\Sentence;
use App\Models\Word;
use Illuminate\Database\Seeder;

class ExpandedLearningSeeder extends Seeder
{
    public function run(): void
    {
        $topics = [
            'Семья и люди' => [
                'description' => 'Родственники, друзья и слова для рассказа о себе.',
                'words' => [
                    ['brother', 'брат', 'noun', 'My brother is a student.', 'Мой брат студент.'],
                    ['sister', 'сестра', 'noun', 'Her sister is at home.', 'Ее сестра дома.'],
                    ['child', 'ребенок', 'noun', 'The child is happy.', 'Ребенок счастлив.'],
                    ['parents', 'родители', 'noun', 'My parents work today.', 'Мои родители сегодня работают.'],
                    ['person', 'человек', 'noun', 'He is a kind person.', 'Он добрый человек.'],
                    ['people', 'люди', 'noun', 'People speak different languages.', 'Люди говорят на разных языках.'],
                    ['name', 'имя', 'noun', 'What is your name?', 'Как тебя зовут?'],
                    ['age', 'возраст', 'noun', 'What is your age?', 'Сколько тебе лет?'],
                ],
            ],
            'Время и дни' => [
                'description' => 'Дни недели, части дня и частые слова о расписании.',
                'words' => [
                    ['today', 'сегодня', 'adverb', 'Today is Monday.', 'Сегодня понедельник.'],
                    ['tomorrow', 'завтра', 'adverb', 'See you tomorrow.', 'Увидимся завтра.'],
                    ['yesterday', 'вчера', 'adverb', 'I worked yesterday.', 'Я работал вчера.'],
                    ['morning', 'утро', 'noun', 'I study in the morning.', 'Я учусь утром.'],
                    ['evening', 'вечер', 'noun', 'We read in the evening.', 'Мы читаем вечером.'],
                    ['week', 'неделя', 'noun', 'A week has seven days.', 'В неделе семь дней.'],
                    ['month', 'месяц', 'noun', 'This month is busy.', 'Этот месяц занятой.'],
                    ['year', 'год', 'noun', 'A year is long.', 'Год длинный.'],
                ],
            ],
            'Погода' => [
                'description' => 'Погода и простые описания состояния дня.',
                'words' => [
                    ['weather', 'погода', 'noun', 'The weather is good.', 'Погода хорошая.'],
                    ['wind', 'ветер', 'noun', 'The wind is strong.', 'Ветер сильный.'],
                    ['hot', 'жаркий', 'adjective', 'It is hot today.', 'Сегодня жарко.'],
                    ['cold', 'холодный', 'adjective', 'The water is cold.', 'Вода холодная.'],
                    ['warm', 'теплый', 'adjective', 'The room is warm.', 'Комната теплая.'],
                    ['sunny', 'солнечный', 'adjective', 'It is sunny outside.', 'На улице солнечно.'],
                    ['cloudy', 'облачный', 'adjective', 'The sky is cloudy.', 'Небо облачное.'],
                    ['rainy', 'дождливый', 'adjective', 'It is rainy today.', 'Сегодня дождливо.'],
                ],
            ],
            'Здоровье' => [
                'description' => 'Базовые слова для врача, самочувствия и тела.',
                'words' => [
                    ['head', 'голова', 'noun', 'My head hurts.', 'У меня болит голова.'],
                    ['hand', 'рука', 'noun', 'Raise your hand.', 'Подними руку.'],
                    ['eye', 'глаз', 'noun', 'Her eyes are blue.', 'Ее глаза синие.'],
                    ['body', 'тело', 'noun', 'A healthy body is important.', 'Здоровое тело важно.'],
                    ['health', 'здоровье', 'noun', 'Health is important.', 'Здоровье важно.'],
                    ['sick', 'больной', 'adjective', 'I am sick today.', 'Я сегодня болен.'],
                    ['tired', 'уставший', 'adjective', 'She is tired after work.', 'Она устала после работы.'],
                    ['medicine', 'лекарство', 'noun', 'Take this medicine.', 'Прими это лекарство.'],
                ],
            ],
            'Учеба' => [
                'description' => 'Слова для школы, уроков и самостоятельного обучения.',
                'words' => [
                    ['lesson', 'урок', 'noun', 'This lesson is easy.', 'Этот урок легкий.'],
                    ['question', 'вопрос', 'noun', 'I have a question.', 'У меня есть вопрос.'],
                    ['answer', 'ответ', 'noun', 'Your answer is correct.', 'Твой ответ правильный.'],
                    ['mistake', 'ошибка', 'noun', 'This mistake is common.', 'Эта ошибка частая.'],
                    ['rule', 'правило', 'noun', 'Learn this rule.', 'Выучи это правило.'],
                    ['example', 'пример', 'noun', 'Read the example.', 'Прочитай пример.'],
                    ['practice', 'практика', 'noun', 'Practice helps a lot.', 'Практика очень помогает.'],
                    ['homework', 'домашнее задание', 'noun', 'Do your homework.', 'Сделай домашнее задание.'],
                ],
            ],
            'Эмоции' => [
                'description' => 'Как говорить о настроении и реакции.',
                'words' => [
                    ['happy', 'счастливый', 'adjective', 'I am happy today.', 'Я сегодня счастлив.'],
                    ['sad', 'грустный', 'adjective', 'He is sad.', 'Он грустный.'],
                    ['angry', 'злой', 'adjective', 'Do not be angry.', 'Не злись.'],
                    ['calm', 'спокойный', 'adjective', 'She is calm.', 'Она спокойная.'],
                    ['afraid', 'испуганный', 'adjective', 'I am not afraid.', 'Я не боюсь.'],
                    ['surprised', 'удивленный', 'adjective', 'We are surprised.', 'Мы удивлены.'],
                    ['interested', 'заинтересованный', 'adjective', 'He is interested in English.', 'Он интересуется английским.'],
                    ['bored', 'скучающий', 'adjective', 'The students are bored.', 'Студентам скучно.'],
                ],
            ],
            'Частые наречия' => [
                'description' => 'Слова, которые делают простые предложения точнее.',
                'words' => [
                    ['always', 'всегда', 'adverb', 'I always drink water.', 'Я всегда пью воду.'],
                    ['usually', 'обычно', 'adverb', 'She usually reads at night.', 'Она обычно читает ночью.'],
                    ['often', 'часто', 'adverb', 'We often walk here.', 'Мы часто гуляем здесь.'],
                    ['sometimes', 'иногда', 'adverb', 'Sometimes I drink tea.', 'Иногда я пью чай.'],
                    ['never', 'никогда', 'adverb', 'He never eats fish.', 'Он никогда не ест рыбу.'],
                    ['quickly', 'быстро', 'adverb', 'Read quickly.', 'Читай быстро.'],
                    ['slowly', 'медленно', 'adverb', 'Speak slowly, please.', 'Говорите медленно, пожалуйста.'],
                    ['well', 'хорошо', 'adverb', 'You speak well.', 'Ты хорошо говоришь.'],
                ],
            ],
            'Вопросительные слова' => [
                'description' => 'Основа вопросов в диалоге.',
                'words' => [
                    ['what', 'что', 'pronoun', 'What is this?', 'Что это?'],
                    ['where', 'где', 'adverb', 'Where do you live?', 'Где ты живешь?'],
                    ['when', 'когда', 'adverb', 'When do you work?', 'Когда ты работаешь?'],
                    ['why', 'почему', 'adverb', 'Why are you here?', 'Почему ты здесь?'],
                    ['who', 'кто', 'pronoun', 'Who is your teacher?', 'Кто твой учитель?'],
                    ['how', 'как', 'adverb', 'How are you?', 'Как дела?'],
                    ['which', 'который', 'pronoun', 'Which book do you like?', 'Какая книга тебе нравится?'],
                    ['whose', 'чей', 'pronoun', 'Whose bag is this?', 'Чья это сумка?'],
                ],
            ],
        ];

        foreach ($topics as $title => $topic) {
            $collection = Collection::updateOrCreate(
                ['title' => $title],
                ['description' => $topic['description']]
            );

            $ids = [];
            foreach ($topic['words'] as [$english, $russian, $part, $exampleEn, $exampleRu]) {
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

                Sentence::updateOrCreate(
                    ['english' => $exampleEn],
                    [
                        'russian' => $exampleRu,
                        'level' => 'A1',
                        'topic' => $title,
                        'hint' => preg_replace('/\b' . preg_quote($english, '/') . '\b/i', '___', $exampleEn, 1),
                    ]
                );
            }

            $collection->words()->syncWithoutDetaching($ids);
        }

        $lessons = [
            ['to-be', 'Глагол to be', 'grammar', 'A1', 'am/is/are для описания себя, людей и предметов.', 'To be переводится как "быть", но в русском настоящем времени часто пропускается. I am a student. She is tired. They are at home. В отрицании добавляем not: I am not ready. В вопросе переносим am/is/are вперед: Are you ready?'],
            ['pronouns', 'Местоимения', 'grammar', 'A1', 'I, you, he, she, it, we, they и объектные формы.', 'Личные местоимения заменяют существительные: I, you, he, she, it, we, they. После глагола часто нужны объектные формы: me, you, him, her, it, us, them. Пример: I know him. She helps me.'],
            ['plural-nouns', 'Множественное число', 'grammar', 'A1', 'Как образовывать plural nouns.', 'Обычно добавляем -s: book - books. После шипящих добавляем -es: box - boxes. Есть исключения: child - children, person - people, man - men.'],
            ['questions-basic', 'Базовые вопросы', 'grammar', 'A1', 'Do/does, am/is/are и вопросительные слова.', 'В Present Simple используем do/does: Do you work? Does she study? С to be переносим глагол вперед: Are you tired? Вопросительные слова ставим в начало: Where do you live?'],
            ['there-is', 'There is / There are', 'grammar', 'A1', 'Как говорить “есть/находится”.', 'There is используем для одного предмета: There is a book on the table. There are для нескольких: There are two chairs in the room. В вопросе: Is there a shop near here?'],
            ['can-modal', 'Can: умение и просьба', 'grammar', 'A1', 'Can для “могу/умею”.', 'После can глагол идет без to и без -s: I can swim. She can speak English. Вопрос: Can you help me? Отрицание: I cannot или can’t.'],
            ['past-simple-start', 'Past Simple: старт', 'grammar', 'A2', 'Первые прошедшие действия.', 'Для правильных глаголов добавляем -ed: work - worked. Для неправильных нужно учить форму: go - went, have - had, do - did. В вопросах используем did: Did you work yesterday?'],
            ['future-going-to', 'Going to', 'grammar', 'A2', 'Планы и намерения.', 'Be going to используем для планов: I am going to learn English. She is going to visit her friend. Вопрос: Are you going to study today?'],
            ['reading-simple-text', 'Как читать простой текст', 'reading', 'A1', 'Стратегия чтения без перевода каждого слова.', 'Сначала найдите знакомые слова и тему. Затем посмотрите на глаголы: они держат смысл. Незнакомое слово можно угадать по контексту, а потом проверить. Не останавливайтесь на каждой детали.'],
            ['speaking-about-yourself', 'Рассказ о себе', 'speaking', 'A1', 'Шаблон первого монолога.', 'Используйте структуру: name, age, city, work/study, hobbies, English goal. Пример: My name is Anna. I live in Moscow. I am a student. I like music. I learn English for travel.'],
        ];

        foreach ($lessons as [$slug, $title, $type, $level, $summary, $content]) {
            Lesson::updateOrCreate(
                ['slug' => $slug],
                compact('title', 'type', 'level', 'summary', 'content') + ['position' => 10 + strlen($slug)]
            );
        }
    }
}
