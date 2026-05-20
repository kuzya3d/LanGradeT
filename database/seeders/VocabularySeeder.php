<?php

namespace Database\Seeders;

use App\Models\Collection;
use App\Models\Word;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VocabularySeeder extends Seeder
{
    public function run(): void
    {
        $collections = require database_path('seeders/data/vocabulary.php');

        DB::table('words')->update([
            'example_en' => null,
            'example_ru' => null,
        ]);

        foreach ($collections as $title => $data) {
            $collection = Collection::updateOrCreate(
                ['title' => $title],
                [
                    'description' => $data['description'],
                ],
            );

            $payload = [];

            foreach ($data['words'] as [$english, $russian, $transcription, $partOfSpeech, $difficulty]) {
                $payload[] = [
                    'english' => $english,
                    'russian' => $this->normalizeTranslations($english, $russian),
                    'transcription' => $transcription,
                    'part_of_speech' => $partOfSpeech,
                    'difficulty' => $difficulty,
                    'example_en' => null,
                    'example_ru' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            foreach ($payload as $wordData) {
                Word::updateOrCreate(
                    ['english' => $wordData['english'], 'user_id' => null],
                    $wordData,
                );
            }

            $wordIds = Word::publicWords()->whereIn('english', array_column($payload, 'english'))->pluck('id')->all();
            $collection->words()->sync($wordIds);
        }
    }

    private function normalizeTranslations(string $english, string $russian): string
    {
        $russian = preg_replace('/\s*\/\s*/u', ', ', $russian) ?? $russian;

        if ($english === 'i am head over heels for you' && ! str_contains(mb_strtolower($russian), 'без ума от тебя')) {
            $russian .= ', я без ума от тебя';
        }

        return $russian;
    }
}
