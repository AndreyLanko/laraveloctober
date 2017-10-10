<?php

namespace App\Models;

use App\Classes\Model;

class SeoExternal extends Model
{
    public $table = 'perevorot_seo_external';

    public function scopeEnabled($query)
    {
        return $query->where($this->table.'.is_active', true);
    }
}
