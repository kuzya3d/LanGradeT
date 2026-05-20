<?php

namespace App\Support;

use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;

class AiMessageRenderer
{
    public static function html(string $content): HtmlString
    {
        return new HtmlString(self::toHtml($content));
    }

    public static function toHtml(string $content): string
    {
        $content = e($content);
        $content = self::linkProjectPaths($content);

        return Str::markdown($content, [
            'html_input' => 'strip',
            'allow_unsafe_links' => false,
        ]);
    }

    private static function linkProjectPaths(string $content): string
    {
        $paths = [
            '/dictionary',
            '/collections/all',
            '/collections',
            '/tests',
            '/lessons',
            '/profile',
            '/leaderboard',
            '/ai-tutor',
        ];

        foreach ($paths as $path) {
            $content = preg_replace(
                '~(?<!\]\()(?<!href=&quot;)'.preg_quote($path, '~').'(?![\w/.-])~u',
                '['.$path.']('.$path.')',
                $content,
            ) ?? $content;
        }

        return $content;
    }
}
