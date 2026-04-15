<?php

namespace App\Support;

final class HtmlSanitizer
{
    private const ALLOWED_TAGS = '<p><br><strong><em><b><i><u><ul><ol><li><h2><h3><h4><span>';

    public static function sanitize(?string $html): ?string
    {
        if ($html === null || trim($html) === '') {
            return $html;
        }

        $cleaned = strip_tags($html, self::ALLOWED_TAGS);
        $cleaned = preg_replace('/<(\w+)[^>]*>/', '<$1>', $cleaned);

        return $cleaned;
    }
}
