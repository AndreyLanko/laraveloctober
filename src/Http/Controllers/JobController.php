<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class JobController extends Controller
{
    public function index()
    {
        return view('pages/job/index', [
        ]);
    }

    public function one(Request $request, $slug)
    {
	    //$one=News::enabled()->where('url', $slug)->first();
        $one=[];

        //if(!$one){
	    //    abort(404);
        //}

        return $this->view('pages/job/one', [
	        'one'=>$one
        ]);
    }
}
