<?php

use App\Support\PartOfSpeechResolver;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $resolver = app(PartOfSpeechResolver::class);

        DB::table('words')
            ->select(['id', 'english', 'russian', 'part_of_speech'])
            ->orderBy('id')
            ->lazyById()
            ->each(function (object $word) use ($resolver): void {
                $partOfSpeech = $resolver->resolve(
                    (string) $word->english,
                    (string) $word->russian,
                    $word->part_of_speech,
                );

                if ($partOfSpeech === $word->part_of_speech) {
                    return;
                }

                DB::table('words')
                    ->where('id', $word->id)
                    ->update([
                        'part_of_speech' => $partOfSpeech,
                        'updated_at' => now(),
                    ]);
            });
    }

    public function down(): void
    {
        // The previous values were generated inconsistently, so this data cleanup is intentionally one-way.
    }
};
