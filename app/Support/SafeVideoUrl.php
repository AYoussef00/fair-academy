<?php

namespace App\Support;

final class SafeVideoUrl
{
    public static function forFrontend(?string $url): ?string
    {
        if ($url === null || trim($url) === '') {
            return null;
        }

        $parsed = parse_url($url);
        if (! isset($parsed['scheme']) || strtolower($parsed['scheme']) !== 'https') {
            return null;
        }

        $host = isset($parsed['host']) ? strtolower($parsed['host']) : '';
        $allowed = array_map('strtolower', config('video.allowed_embed_hosts', []));

        if (! in_array($host, $allowed, true)) {
            return null;
        }

        return $url;
    }
}
