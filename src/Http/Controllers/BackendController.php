<?php

namespace App\Http\Controllers;

use Debugbar;
use App\Models\LongreadPreview;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BackendController extends Controller
{
    public function longreadPreview($user_id, $model_id, $model, $column)
    {
        Debugbar::disable();

        $preview=LongreadPreview::where('user_id', $user_id)->where('model_id', $model_id)->where('model', $model)->where('field', $column)->first();

        if(!empty($preview)) {
            $preview->id=$model_id;

            $frontendModelClassName='\App\Models\\'.ucfirst($model);

            if (!class_exists($frontendModelClassName)) {
                return '';
            }

            $frontendModel=new $frontendModelClassName;
            $preview->backendModel=$frontendModel->backendModel;

            return $preview->longread;
        }

        return '';
    }
}
