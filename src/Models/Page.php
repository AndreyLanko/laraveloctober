<?php

namespace App\Models;

use App\Traits\Translatable;
use App\Traits\Attachments;
use App\Traits\Longread;
use App\Classes\Helpers;
use App\Classes\Model;
use Localization;

class Page extends Model
{
    use Translatable;
    use Attachments;
    use Longread;

    public $table = 'perevorot_page_page';
    public $backendModel='Perevorot\Page\Models\Page';

    const PAGE_TYPE_STATIC=1;
    const PAGE_TYPE_ALIAS=2;
    const PAGE_TYPE_EXTERNAL=3;
    const PAGE_TYPE_ROUTE=4;

    protected $translatable=[
        ['title', 'primary'=>true],
    ];

    protected $longread=[
        'longread',
    ];

    protected $attachments=[
        'icon',
        'icon_over'
    ];

    public function scopeEnabled($query)
    {
        return $query->where('is_disabled', false);
    }

    public function scopeInMenu($query, $menuId)
    {
        return $query->enabled()
                ->with('alias')
                ->with('icon')
                ->with('route')
                ->with('icon_over')
                ->where('is_hidden', false)
                ->where('menu_id', $menuId);
    }

    public function alias()
    {
        return $this->hasOne('App\Models\Page', 'id', 'alias_page_id');
    }

    // public function route()
    // {
    //     $class=false;
    //
    //     switch($this->route_name) {
    //         case 'pay-category':
    //             $class=Category::class;
    //         break;
    //         case 'pay-service':
    //             $class=Service::class;
    //         break;
    //     }
    //
    //     return $class ? $this->hasOne($class, $model->primaryKey, 'route_name_slug') : $this;
    // }

    public function route()
    {
        return $this->morphTo();
    }

    public function getHasChildrenAttribute($value)
    {
        return $this->nest_right > $this->nest_left+1;
    }

    public function getChildrenAttribute($value)
    {
        return $this->hasChildren ? $this->where('is_disabled', false)->where('is_hidden', false)->where('nest_left', '>', $this->nest_left)->where('nest_right', '<', $this->nest_right)->get() : [];
    }

    public function getUrlAttribute($value)
    {
        $url='';

        switch($this->type)
        {
            case self::PAGE_TYPE_STATIC:
                $url=!empty($this->attributes['url']) ? Localization::getLocalizedURL(null, $this->attributes['url']) : '';
                break;

            case self::PAGE_TYPE_ALIAS:
                if($this->id!=$this->alias_page_id) {
                    $url=$this->alias->url;
                }
                break;

            case self::PAGE_TYPE_EXTERNAL:
                $url=$this->url_external;
                break;

            case self::PAGE_TYPE_ROUTE:
                if($this->route_name && !$this->route_id){
                    $url=href($this->route_name);
                }elseif($this->route_type && $this->route_id){
                    $url=href($this->route_type, $this->route);
                }elseif($this->route_type){
                    $url=href($this->route_type);
                }else{
                    $url='';
                }

                break;
        }

        return $url;
    }

    public function getIsActiveAttribute($value)
    {
        $fullUrl=request()->fullUrl();

        return $this->url == $fullUrl || array_first($this->children, function($child) {
            return $child->isActive;
        });
    }
}
