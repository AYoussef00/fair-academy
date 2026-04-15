<?php

return [
    /*
    |--------------------------------------------------------------------------
    | مسموح دومينات تضمين الفيديو (embed)
    |--------------------------------------------------------------------------
    | عند استخدام حقل video_url في الدروس، يُقبل فقط روابط https من هذه الدومينات
    | لتقليل مخاطر XSS عبر iframe.
    */
    'allowed_embed_hosts' => [
        'www.youtube.com',
        'youtube.com',
        'www.youtube-nocookie.com',
        'youtube-nocookie.com',
        'vimeo.com',
        'www.vimeo.com',
        'player.vimeo.com',
    ],
];
