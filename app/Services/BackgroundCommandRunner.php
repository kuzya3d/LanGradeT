<?php

namespace App\Services;

class BackgroundCommandRunner
{
    public function run(string $command): bool
    {
        $php = config('services.background.php_binary')
            ?: (PHP_SAPI === 'cli' ? PHP_BINARY : 'php');
        $artisan = base_path('artisan');

        if (PHP_OS_FAMILY === 'Windows') {
            $fullCommand = sprintf(
                'start /B "" %s %s %s',
                escapeshellarg($php),
                escapeshellarg($artisan),
                $command
            );
        } else {
            $log = storage_path('logs/background-artisan.log');

            if (! is_dir(dirname($log))) {
                @mkdir(dirname($log), 0775, true);
            }

            $fullCommand = sprintf(
                'nohup %s %s %s >> %s 2>&1 &',
                escapeshellarg($php),
                escapeshellarg($artisan),
                $command,
                escapeshellarg($log)
            );
        }

        $handle = @popen($fullCommand, 'r');

        if ($handle === false) {
            return false;
        }

        pclose($handle);

        return true;
    }
}
