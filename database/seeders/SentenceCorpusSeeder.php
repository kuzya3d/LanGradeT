<?php

namespace Database\Seeders;

use App\Models\Sentence;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SentenceCorpusSeeder extends Seeder
{
    public function run(): void
    {
        $sentences = $this->buildSentences();

        DB::transaction(function () use ($sentences) {
            Sentence::query()->delete();
            Sentence::insert($sentences);
        });
    }

    private function buildSentences(): array
    {
        $path = database_path('seeders/data/sentences.txt');
        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) ?: [];
        $now = now();

        return collect($lines)
            ->map(fn (string $line) => $this->parseLine($line))
            ->filter()
            ->unique('english')
            ->values()
            ->map(fn (array $sentence) => [
                'english' => $sentence['english'],
                'russian' => $sentence['russian'],
                'level' => $this->levelFor($sentence['english']),
                'topic' => 'custom-sentence-corpus',
                'format' => $this->formatFor($sentence['english']),
                'hint' => $this->storedGapHint($sentence['english']),
                'created_at' => $now,
                'updated_at' => $now,
            ])
            ->all();
    }

    private function parseLine(string $line): ?array
    {
        $parts = preg_split('/\s*(?:—|-{2,}|–)\s*/u', trim($line), 2);

        if (count($parts) !== 2) {
            return null;
        }

        $english = trim($parts[0]);
        $russian = trim($parts[1]);

        if ($english === '' || $russian === '') {
            return null;
        }

        return compact('english', 'russian');
    }

    private function levelFor(string $english): string
    {
        $wordCount = str_word_count($english);

        return match (true) {
            $wordCount <= 5 => 'A1',
            $wordCount <= 8 => 'A2',
            default => 'B1',
        };
    }

    private function formatFor(string $english): string
    {
        $plain = Str::lower($english);

        return match (true) {
            Str::contains($plain, '?') => 'question',
            preg_match('/\b(am|is|are)\s+\w+ing\b/', $plain) === 1 => 'present-continuous',
            preg_match('/\b(was|were|had|called|arrived|bought|opened|closed|visited|moved|forgot|lost|went)\b/', $plain) === 1 => 'past-simple',
            preg_match('/\b(can|need to|want to|going to|like to)\b/', $plain) === 1 => 'modal-or-infinitive',
            preg_match('/\b(am|is|are)\b/', $plain) === 1 => 'to-be',
            default => 'present-simple',
        };
    }

    private function storedGapHint(string $english): string
    {
        $word = $this->gapCandidates($english)->first();

        return $word ? $this->replaceFirstWord($english, $word, '___') : $english;
    }

    public function randomGap(string $english): array
    {
        $candidates = $this->gapCandidates($english)->values();

        if ($candidates->isEmpty()) {
            return ['question' => $english, 'answer' => ''];
        }

        $answer = $candidates->random();

        return [
            'question' => $this->replaceFirstWord($english, $answer, '___'),
            'answer' => $answer,
        ];
    }

    private function gapCandidates(string $english)
    {
        $skip = [
            'a', 'an', 'the', 'i', 'you', 'he', 'she', 'it', 'we', 'they',
            'my', 'your', 'his', 'her', 'our', 'their', 'this', 'that',
            'to', 'in', 'on', 'at', 'for', 'of', 'with', 'about', 'and',
        ];

        preg_match_all('/\b[a-z][a-z\']*\b/i', $english, $matches);

        return collect($matches[0] ?? [])
            ->map(fn (string $word) => trim($word, "'"))
            ->filter(fn (string $word) => mb_strlen($word) > 2)
            ->reject(fn (string $word) => in_array(Str::lower($word), $skip, true))
            ->values();
    }

    private function replaceFirstWord(string $sentence, string $word, string $replacement): string
    {
        return preg_replace('/\b'.preg_quote($word, '/').'\b/i', $replacement, $sentence, 1) ?? $sentence;
    }
}
