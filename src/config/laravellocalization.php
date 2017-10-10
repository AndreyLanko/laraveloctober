<?php

return [
    'supportedLocales' => [
        'en' => [
            'name' => 'English',
            'script' => 'Latn',
            'native' => 'English',
            'regional' => 'en_GB'
        ],
        'ru' => [
            'name' => 'Russian',
            'script' => 'Cyrl',
            'native' => 'Русский',
            'regional' => 'ru_RU'
        ],
        'ua' => [
            'name' => 'Ukrainian',
            'script' => 'Cyrl',
            'native' => 'Українська',
            'regional' => 'uk_UA'
        ],
    ],
    'useAcceptLanguageHeader' => false,
    'hideDefaultLocaleInURL' => true,
    'localesOrder' => ['ua', 'ru', 'en'],
];
