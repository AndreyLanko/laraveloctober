<?php

namespace App\Models;

use App\Traits\Translatable;
use App\Classes\Model;

class SeoTags extends Model
{
    use Translatable;

    public $table = 'perevorot_seo_seo';
    
    public $backendModel='Perevorot\Seo\Models\Seo';
    
    public $translatable = [
        'title',
        'description',
        'keywords',
        'og_title',
        'og_sitename',
        'og_description'
    ];
}
