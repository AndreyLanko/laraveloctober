<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ErrorController extends Controller
{
    public function validateJson(Request $request, $locale)
    {
        $this->commonData($request);
        
        $commonMessage=$this->settings->locale($locale)->validateErrorCommon;
        $messages=explode(PHP_EOL, $this->settings->locale($locale)->validateErrors);
        $labels=explode(PHP_EOL, $this->settings->validateErrorLabels);

        $messages=array_map('trim', $messages);
        $labels=array_map('trim', $labels);

        $validateMessages=[
            'message' => $commonMessage ? $commonMessage : 'не указано стандартное сообщение об ошибке'
        ];

        $commonValidateMessages=[];

        foreach($labels as $k=>$label) {
            if(!empty($messages[$k])) {
                $validateMessages['common'][$label]=$messages[$k];
            }
        }

        if(!empty($commonValidateMessages)){
            $validateMessages['common']=$commonValidateMessages;
        }

        return response()->json($validateMessages);
    }
}
