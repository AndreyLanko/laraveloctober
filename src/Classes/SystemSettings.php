<?php

namespace Perevorot\LaravelOctober\Classes;

use Perevorot\LaravelOctober\Models\SystemSetting;

class SystemSettings
{
    protected static $instances=[
        'perevorot_seo_settings'=>[
            'backendModel'=>'Perevorot\Seo\Models\Settings',
            'translatable'=>[
                'title',
                'description',
                'keywords',
                'og_title',
                'og_sitename',
                'og_description'
            ],
            'attachments'=>[
                'og_image'
            ]
        ]
    ];

    public static $instance;

    public static function instance($instanceLabel)
    {
        if(!empty(self::$instances) && array_key_exists($instanceLabel, self::$instances)) {
            $instance=new SystemSetting(self::$instances[$instanceLabel]);
            $instance=$instance->where('item', $instanceLabel)->first();

            self::$instance = !empty($instance) ? $instance->parse(self::$instances[$instanceLabel]) : null;

            return self::$instance;
        }
    }
}
