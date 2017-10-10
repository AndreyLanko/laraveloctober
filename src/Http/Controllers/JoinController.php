<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class JoinController extends Controller
{
    public function one(Request $request, $slug)
    {
        return $this->view('pages/join/one', [
        ]);
    }
}
