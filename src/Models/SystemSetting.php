<?php

namespace App\Models;

use App\Traits\Translatable;
use App\Traits\Attachments;
use App\Classes\Model;

class SystemSetting extends Model
{
    use Translatable;
    use Attachments;

    protected $table = 'system_settings';

    public $backendModel;
    public $translatable;
    public $attachments;

    public function __construct($attributes)
    {
        $this->setAttributes($attributes);

        parent::__construct();
    }

    public function parse($attributes)
    {
        $this->setAttributes($attributes);

        $data=json_decode($this->value);

        foreach($data as $key=>$value) {
            $this->attributes[$key]=$value;
        }

        return $this;
    }

    private function setAttributes($attributes)
    {
        foreach($attributes as $method=>$value) {
            $this->{$method}=$value;
        }
    }
}
