<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Content-Security-Policy (CSP)
    |--------------------------------------------------------------------------
    | توجيهات CSP. المفتاح = اسم التوجيه، القيمة = مصفوفة مصادر مسموحة.
    | استخدم 'none' أو 'self' أو عناوين مثل https://cdn.example.com
    */
    'csp' => [
        'default-src' => ["'self'"],
        'script-src' => ["'self'", "'unsafe-inline'", "'unsafe-eval'", 'https://cdn.tailwindcss.com', 'https://cdn.jsdelivr.net'],
        'style-src' => ["'self'", "'unsafe-inline'", 'https://fonts.googleapis.com', 'https://cdn.tailwindcss.com'],
        'font-src' => ["'self'", 'https://fonts.gstatic.com'],
        'img-src' => ["'self'", 'data:', 'https:'],
        'frame-src' => ["'self'", 'https:'],
        'connect-src' => ["'self'"],
        'base-uri' => ["'self'"],
        'form-action' => ["'self'"],
    ],
];
