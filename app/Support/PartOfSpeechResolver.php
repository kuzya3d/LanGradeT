<?php

namespace App\Support;

use Illuminate\Support\Str;

class PartOfSpeechResolver
{
    private const DETERMINERS = ['a', 'an', 'the', 'this', 'that', 'these', 'those', 'my', 'your', 'his', 'her', 'its', 'our', 'their'];

    private const PRONOUNS = ['i', 'you', 'he', 'she', 'it', 'we', 'they', 'me', 'him', 'her', 'us', 'them', 'who', 'whom', 'whose'];

    private const PREPOSITIONS = [
        'about', 'above', 'across', 'after', 'against', 'around', 'at', 'before', 'behind', 'below', 'between',
        'by', 'during', 'for', 'from', 'in', 'inside', 'into', 'near', 'of', 'off', 'on', 'over', 'through',
        'to', 'under', 'with', 'without',
    ];

    private const CONJUNCTIONS = ['and', 'because', 'but', 'if', 'or', 'so', 'than', 'though', 'unless', 'when', 'while'];

    private const QUESTION_ADVERBS = ['how', 'when', 'where', 'why'];

    private const ADVERBS = [
        'again', 'always', 'badly', 'carefully', 'early', 'far', 'fast', 'hard', 'here', 'late', 'never',
        'often', 'quickly', 'really', 'slowly', 'sometimes', 'soon', 'there', 'today', 'tomorrow', 'usually',
        'well', 'yesterday',
    ];

    private const SCHOOL_SUBJECTS = [
        'art', 'biology', 'chemistry', 'english', 'french', 'geography', 'history', 'literature', 'math',
        'music', 'physics', 'science', 'spanish', 'technology',
    ];

    private const COMMON_VERBS = [
        'accept', 'achieve', 'add', 'agree', 'allow', 'appear', 'apply', 'arrive', 'ask', 'avoid', 'bake',
        'be', 'become', 'believe', 'blend', 'boil', 'bring', 'build', 'buy', 'carry', 'catch', 'cause',
        'choose', 'chop', 'clean', 'click', 'close', 'come', 'comment', 'connect', 'continue', 'cook',
        'create', 'dance', 'decide', 'develop', 'dice', 'discuss', 'download', 'draw', 'dream', 'drink',
        'drive', 'dust', 'eat', 'enjoy', 'explain', 'fall', 'feed', 'feel', 'find', 'finish', 'fly',
        'follow', 'forget', 'fry', 'get', 'give', 'go', 'grill', 'grind', 'happen', 'hate', 'have',
        'hear', 'help', 'hope', 'improve', 'include', 'iron', 'join', 'know', 'learn', 'leave', 'like',
        'listen', 'love', 'make', 'mash', 'mix', 'mop', 'move', 'need', 'notice', 'offer', 'open',
        'organize', 'paint', 'pay', 'play', 'polish', 'post', 'prepare', 'read', 'receive', 'remember',
        'return', 'rise', 'roast', 'run', 'save', 'search', 'see', 'sell', 'send', 'serve', 'sew',
        'share', 'show', 'sit', 'sleep', 'slice', 'speak', 'stand', 'start', 'steam', 'stir', 'study',
        'subscribe', 'sweep', 'swim', 'take', 'teach', 'think', 'travel', 'try', 'turn', 'understand',
        'update', 'upload', 'use', 'vacuum', 'visit', 'wait', 'walk', 'want', 'wash', 'watch', 'whisk',
        'wipe', 'work', 'write',
    ];

    private const COMMON_ADJECTIVES = [
        'able', 'active', 'adventurous', 'ambitious', 'arrogant', 'available', 'average', 'bad', 'bald',
        'basic', 'beautiful', 'beige', 'beloved', 'big', 'black', 'blue', 'brave', 'bright', 'british',
        'brown', 'busy', 'careful', 'caring', 'cheerful', 'chubby', 'clear', 'cold', 'colorful', 'common',
        'companionable', 'complete', 'compassionate', 'curious', 'cyan', 'dark', 'darling', 'dedicated',
        'delayed', 'different', 'difficult', 'dirty', 'distinguished', 'easy', 'elderly', 'fair', 'fast',
        'festive', 'flawless', 'free', 'freezing', 'fresh', 'full', 'general', 'genetic', 'generous',
        'good', 'gorgeous', 'gray', 'green', 'hard', 'hardworking', 'happy', 'helpful', 'high', 'hot',
        'hydrating', 'important', 'impatient', 'indigo', 'interested', 'ivory', 'lavender', 'lean', 'light',
        'local', 'long', 'loyal', 'low', 'magenta', 'main', 'maroon', 'medieval', 'modern', 'motivated',
        'moody', 'natural', 'navy', 'negative', 'new', 'old', 'olive', 'orange', 'pale', 'petite', 'pink',
        'plump', 'polite', 'possible', 'purple', 'radiant', 'real', 'recent', 'red', 'reliable',
        'responsive', 'responsible', 'right', 'royal', 'same', 'short', 'shy', 'sick', 'slow', 'small',
        'social', 'special', 'stocky', 'straight', 'strong', 'stubborn', 'stunning', 'supportive', 'tan',
        'teal', 'thoughtful', 'tired', 'trending', 'true', 'trustworthy', 'turquoise', 'unkempt', 'violet',
        'cloudy', 'rainy', 'snowy', 'sunny', 'warm', 'white', 'windy', 'yellow', 'young', 'youthful',
    ];

    public function resolve(string $english, string $russian = '', ?string $current = null): string
    {
        $word = Str::of($english)->lower()->trim()->squish()->toString();
        $translation = Str::of($russian)->lower()->replace('ё', 'е')->squish()->toString();

        if ($word === '') {
            return $current ?: 'noun';
        }

        if (preg_match('/\s/u', $word)) {
            return 'phrase';
        }

        if (in_array($word, self::DETERMINERS, true)) {
            return 'determiner';
        }

        if (in_array($word, self::PRONOUNS, true) || in_array($word, ['what', 'which'], true)) {
            return 'pronoun';
        }

        if (in_array($word, self::QUESTION_ADVERBS, true)) {
            return 'adverb';
        }

        if (in_array($word, self::CONJUNCTIONS, true)) {
            return 'conjunction';
        }

        if (in_array($word, self::PREPOSITIONS, true)) {
            return 'preposition';
        }

        if (in_array($word, self::ADVERBS, true) || (Str::endsWith($word, 'ly') && $this->looksAdverbial($translation))) {
            return 'adverb';
        }

        if (in_array($word, self::SCHOOL_SUBJECTS, true)) {
            return 'noun';
        }

        $hasVerbMeaning = $this->hasRussianVerbMeaning($translation);
        $hasAdjectiveMeaning = $this->hasRussianAdjectiveMeaning($translation);
        $hasNounMeaning = $this->hasRussianNounMeaning($translation);

        if (in_array($word, self::COMMON_ADJECTIVES, true) && $hasVerbMeaning) {
            return 'adjective/verb';
        }

        if (in_array($word, self::COMMON_VERBS, true) || $hasVerbMeaning) {
            if ($hasNounMeaning && ! $this->isMostlyAction($word, $translation)) {
                return 'noun/verb';
            }

            if ($hasAdjectiveMeaning && ! $this->isMostlyAction($word, $translation)) {
                return 'adjective/verb';
            }

            return 'verb';
        }

        $knownAdjective = in_array($word, self::COMMON_ADJECTIVES, true) || ($this->hasAdjectiveSuffix($word) && ! $hasNounMeaning);
        if ($knownAdjective || $hasAdjectiveMeaning) {
            if ($hasNounMeaning && ! $knownAdjective) {
                return 'noun';
            }

            if ($hasNounMeaning) {
                return 'noun/adjective';
            }

            return 'adjective';
        }

        if (Str::endsWith($word, 'ing') && $hasAdjectiveMeaning) {
            return 'adjective';
        }

        return 'noun';
    }

    private function hasRussianVerbMeaning(string $translation): bool
    {
        return (bool) preg_match('/\b(?:есть|идти|расти|нести|вести|лечь|мочь|помочь|[\p{L}-]*(?:ться|ать|ять|еть|ить|оть|уть|ыть|овать|евать|ировать|ечь|ичь))\b/u', $translation);
    }

    private function hasRussianAdjectiveMeaning(string $translation): bool
    {
        preg_match_all('/\b[\p{L}-]+\b/u', $translation, $matches);

        foreach ($matches[0] ?? [] as $token) {
            if (preg_match('/(?:ание|ение|тие|ствие|ость|ность|ство|ция|сия|изм|ист|тель|мент|лог|графия|ика|ина|ка|ок|ик|ер|ор|ир|ар|ур|ия|ие)$/u', $token)) {
                continue;
            }

            if (preg_match('/(?:ый|ий|ой|ая|яя|ое|ее|ые|ого|его|ому|ему|ым|им|ых|их|ущий|ющий|вший|енный|анный)$/u', $token)) {
                return true;
            }
        }

        return false;
    }

    private function hasRussianNounMeaning(string $translation): bool
    {
        if ($translation === '') {
            return false;
        }

        if ($this->looksAdverbial($translation)) {
            return false;
        }

        if (preg_match('/\b(?:сейв|лайк|пост|кредит|депозит|обновление|загрузка|скачивание|подписка|лента|корм|раковина|пыль|швабра|железо|танец|краска|работа|напиток|надежда|любовь|предложение|уведомление|возврат|капитал|сила|комната|паста|дерево|каша|свет|утро|переход|центр|район|окрестности|пациент|хогманай|день|секунда|загар|полночь|ночь)\b/u', $translation)) {
            return true;
        }

        preg_match_all('/\b[\p{L}-]+\b/u', $translation, $matches);

        foreach ($matches[0] ?? [] as $token) {
            if (preg_match('/(?:ание|ение|тие|ствие|ость|ность|ство|ция|сия|изм|ист|тель|мент|лог|графия|ика|ина|ка|ки|ок|ик|ер|ор|ир|ар|ур|ия|ие|чь)$/u', $token)) {
                return true;
            }
        }

        return ! $this->hasRussianVerbMeaning($translation) && ! $this->hasRussianAdjectiveMeaning($translation);
    }

    private function hasAdjectiveSuffix(string $word): bool
    {
        return (bool) preg_match('/(?:able|ible|al|ant|ary|ed|ent|ful|ic|ical|ive|less|ous|y)$/', $word);
    }

    private function looksAdverbial(string $translation): bool
    {
        return (bool) preg_match('/\b(?:быстро|медленно|хорошо|плохо|осторожно|облачно|ясно|часто|редко|обычно|иногда|сегодня|завтра|вчера|здесь|там|далеко|рано|поздно|сильно)\b/u', $translation);
    }

    private function isMostlyAction(string $word, string $translation): bool
    {
        if (preg_match('/\b(?:получать|наслаждайтесь|сохранить|подписаться|поделиться|обновить|скачать|загрузить|продать|купить|готовить|мыть|убирать|подметать|протирать|шить|варить|жарить|нарезать|смешивать|подавать)\b/u', $translation)) {
            return true;
        }

        return in_array($word, [
            'accept', 'achieve', 'add', 'agree', 'allow', 'appear', 'apply', 'arrive', 'ask', 'avoid',
            'become', 'believe', 'build', 'carry', 'catch', 'choose', 'close', 'come', 'continue',
            'create', 'decide', 'develop', 'discuss', 'explain', 'feel', 'finish', 'follow', 'forget',
            'give', 'go', 'happen', 'hate', 'hear', 'improve', 'include', 'join', 'know', 'learn',
            'leave', 'listen', 'move', 'need', 'open', 'prepare', 'receive', 'remember', 'send',
            'show', 'speak', 'start', 'take', 'think', 'try', 'understand', 'use', 'visit', 'want',
            'write',
        ], true);
    }
}
