<?php

namespace App\Http\Controllers\Pay;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CardController extends Controller
{
    public function index(Request $request)
    {
        $this->commonData($request);

        return $this->view('pages/pay/card', [
        ]);
    }
}
