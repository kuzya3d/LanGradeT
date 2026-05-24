<?php

namespace App\Services;

class PhoneticTranscriber
{
    public function transcribe(string $word, bool $keepSingleSyllableStress = false): ?string
    {
        $word = trim($word);

        if ($word === '' || ! preg_match('/[a-z]/i', $word)) {
            return null;
        }

        $python = config('services.g2p.python', 'python');
        $script = base_path('scripts/g2p_transcribe.py');

        if (! is_file($script)) {
            return null;
        }

        $command = escapeshellarg($python).' '.escapeshellarg($script);

        if ($keepSingleSyllableStress) {
            $command .= ' --keep-single-syllable-stress';
        }

        $command .= ' '.escapeshellarg($word);
        $output = [];
        $exitCode = 1;

        exec($command, $output, $exitCode);

        if ($exitCode !== 0 || $output === []) {
            return null;
        }

        $transcription = trim(implode('', $output));

        return preg_match('/^\[[^\]]+\]$/u', $transcription) ? $transcription : null;
    }
}
