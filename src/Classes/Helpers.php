<?php

namespace Perevorot\LaravelOctober\Classes;

use Localization;

class Helpers
{
    public static function getRouteURL($route, $model)
    {
        $attributes=[];

        if(!empty($model) && is_array($model)) {
            foreach($model as $item) {
                array_push($attributes, !empty($item->slug->slug) ? $item->slug->slug : $item->{$item->primaryKey});
            }
        } elseif(!empty($model)) {
            $attributes[]=!empty($model->slug->slug) ? $model->slug->slug : $model->{$model->primaryKey};
        }

        return Localization::getLocalizedURL(null, route($route, $attributes));
    }

    public static function getLocalizedRouteURL($route, $attributes = [])
    {
        return Localization::getLocalizedURL(null, route($route, $attributes));
    }

    public static function fileGetThumbPath($image, $width, $height)
    {
        return self::fileGetPublicDir($image->disk_name).'/thumb_' . $image->id . '_' . $width . 'x' . $height . '_0_0_auto.' . pathinfo($image->disk_name, PATHINFO_EXTENSION);
    }

    public static function fileGetPublicPath($file)
    {
        return self::fileGetPublicDir($file->disk_name).'/'.$file->disk_name;
    }

    public static function fileGetPublicDir($file)
    {
        $path='/storage/app/uploads/public';

        $split=str_split($file, 3);

        for($i=0;$i<3;$i++) {
            $path.='/'.$split[$i];
        }

        return $path;
    }

    public static function fileGetMediaPath($file)
    {
        $path='/storage/app/media';

        return $path.$file;
    }

    public static function fileGetFullStoragePath($file)
    {
        return env('APP_URL').'/storage/'.$file;
    }
}
