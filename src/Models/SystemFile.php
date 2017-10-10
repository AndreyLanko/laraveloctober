<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SystemFile extends Model
{
    protected $table = 'system_files';

    public function getPathAttribute()
    {
        return env('APP_URL').'/storage/app/uploads/public/' . $this->getDirectory() . $this->disk_name;
    }

    protected function getDirectory()
    {
        return implode('/', array_slice(str_split($this->disk_name, 3), 0, 3)) . '/';
    }

    public function getDescriptionAttribute()
    {
        return $this->parseJsonDescription('description');
    }

    public function getUrlAttribute()
    {
        return $this->parseJsonDescription('url');
    }

    public function getIsTargetBlankAttribute()
    {
        return $this->parseJsonDescription('is_target_blank');
    }

    private function parseJsonDescription($field)
    {
        $json=json_decode($this->attributes['description']);

        if(is_object($json)) {
            return $json->{$field};
        }

        if(!empty($this->attributes[$field])) {
            return $this->attributes[$field];
        }

        return '';
    }
}
