<?php

namespace App\Http\Controllers\Pay;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StatusController extends Controller
{
    public function index(Request $request)
    {
        $this->commonData($request);
        
        return $this->view('pages/pay/status', [
        ]);
    }
}
