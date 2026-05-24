<?php

namespace Tests\Unit;

use App\Support\PartOfSpeechResolver;
use PHPUnit\Framework\TestCase;

class PartOfSpeechResolverTest extends TestCase
{
    public function test_resolves_parts_of_speech_from_word_and_translation_context(): void
    {
        $resolver = new PartOfSpeechResolver();

        $this->assertSame('verb', $resolver->resolve('boil', 'кипятить, варить', 'noun'));
        $this->assertSame('adjective', $resolver->resolve('cloudy', 'облачно', 'noun'));
        $this->assertSame('noun/verb', $resolver->resolve('sink', 'раковина, тонуть', 'noun'));
        $this->assertSame('noun', $resolver->resolve('beard', 'Борода', 'adjective'));
        $this->assertSame('adverb', $resolver->resolve('where', 'где', 'question word'));
        $this->assertSame('preposition', $resolver->resolve('without', 'без', 'function word'));
        $this->assertSame('noun', $resolver->resolve('math', 'Математика', 'school subject'));
        $this->assertSame('phrase', $resolver->resolve('i am head over heels for you', 'я без ума от тебя', 'phrase'));
    }
}
