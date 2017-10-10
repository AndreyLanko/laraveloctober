<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function homepage(Request $request)
    {
        $this->commonData($request);

        return $this->view('pages/page/page');
    }

    public function page(Request $request)
    {
        $this->commonData($request);

        return $this->view('pages/page/page');
    }
}
