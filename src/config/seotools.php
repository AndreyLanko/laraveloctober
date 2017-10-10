<?php

return [
    'meta'      => [
        'defaults'       => [
            'title'        => false,
            'description'  => false,
            'separator'    => ' - ',
            'keywords'     => [],
            'canonical'    => false,
        ],

        /*
         * Webmaster tags are always added.
         */
        'webmaster_tags' => [
            'google'    => null,
            'bing'      => null,
            'alexa'     => null,
            'pinterest' => null,
            'yandex'    => null,
        ],
    ],
    'opengraph' => [
        'defaults' => [
            'title'       => false,
            'description' => false,
            'url'         => false,
            'type'        => false,
            'site_name'   => false,
            'images'      => [],
        ],
    ],
    'twitter' => [
        'defaults' => [
          //'card'        => '',
          //'site'        => '@',
        ],
    ],
];
