<?php

namespace App\Services;

class BackgroundCommandRunner
{
    public function run(string $command): bool
    {
        $php = PHP_BINARY;
        $artisan = base_path('artisan');
        $fullCommand = sprintf(
            'start /B "" %s %s %s',
            escapeshellarg($php),
            escapeshellarg($artisan),
            $command
        );

        $handle = @popen($fullCommand, 'r');

        if ($handle === false) {
            return false;
        }

        pclose($handle);

        return true;
    }
}
