<?php

namespace Perevorot\LaravelOctober\Models;

use Perevorot\LaravelOctober\Traits\Longread;
use Perevorot\LaravelOctober\Classes\Model;

class LongreadPreview extends Model
{
    use Longread;

    protected $table = 'perevorot_longread_preview';

    protected $longread=[
        'longread',
    ];

}
