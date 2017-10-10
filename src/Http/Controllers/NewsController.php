<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use News;

class NewsController extends Controller
{
    public function index(Request $request)
    {
        $this->commonData($request);

        return $this->view('pages/news/index', [
        ]);
    }

    public function one(Request $request, $slug)
    {
        $this->commonData($request);
        
	    //$one=News::enabled()->where('url', $slug)->first();
        $one=[];

        //if(!$one){
	    //    abort(404);
        //}

        return $this->view('pages/news/one', [
	        'one'=>$one
        ]);
    }
}
