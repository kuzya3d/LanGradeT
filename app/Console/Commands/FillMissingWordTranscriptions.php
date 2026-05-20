<?php

namespace App\Console\Commands;

use App\Models\Word;
use App\Services\PhoneticTranscriber;
use Illuminate\Console\Command;

class FillMissingWordTranscriptions extends Command
{
    protected $signature = 'words:fill-transcriptions
        {--only-user : Update only words attached to users}
        {--normalize-generated-stress : Replace only transcriptions that match the old generated single-syllable stress format}';

    protected $description = 'Fill missing word transcriptions with local g2p-en.';

    public function handle(PhoneticTranscriber $transcriber): int
    {
        $normalizeGeneratedStress = (bool) $this->option('normalize-generated-stress');
        $query = Word::query();

        if (! $normalizeGeneratedStress) {
            $query->where(function ($query) {
                $query->whereNull('transcription')
                    ->orWhere('transcription', '');
            });
        }

        if ($this->option('only-user')) {
            $query->whereHas('users');
        }

        $updated = 0;
        $skipped = 0;

        foreach ($query->orderBy('english')->get() as $word) {
            $transcription = $transcriber->transcribe($word->english);

            if ($transcription === null) {
                $skipped++;
                $this->warn("Skipped: {$word->english}");
                continue;
            }

            if ($normalizeGeneratedStress) {
                $oldGenerated = $transcriber->transcribe($word->english, true);

                if ($word->transcription !== $oldGenerated || $word->transcription === $transcription) {
                    $skipped++;
                    continue;
                }
            }

            $word->forceFill(['transcription' => $transcription])->save();
            $updated++;
            $this->line("{$word->english}: {$transcription}");
        }

        $this->info("Updated: {$updated}; skipped: {$skipped}");

        return self::SUCCESS;
    }
}
