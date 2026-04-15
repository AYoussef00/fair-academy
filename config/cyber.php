<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Weights for security score calculation
    |--------------------------------------------------------------------------
    */
    'score' => [
        'base' => 100,
        'vulnerability_penalty' => [
            'low' => 1,
            'medium' => 3,
            'high' => 7,
            'critical' => 12,
        ],
        'missing_protection_penalty' => 5,
    ],

    /*
    |--------------------------------------------------------------------------
    | Static description of protections we expect
    |--------------------------------------------------------------------------
    */
    'protections' => [
        'sql_injection' => [
            'label' => 'حماية من حقن SQL',
        ],
        'csrf' => [
            'label' => 'حماية من CSRF',
        ],
        'auth' => [
            'label' => 'حماية المصادقة وتسجيل الدخول',
        ],
        'rate_limiting' => [
            'label' => 'تحديد معدل الطلبات (Rate limiting)',
        ],
        'security_headers' => [
            'label' => 'ترويسات أمنية (Security headers)',
        ],
        'input_validation' => [
            'label' => 'التحقق من صحة المدخلات',
        ],
    ],
];
