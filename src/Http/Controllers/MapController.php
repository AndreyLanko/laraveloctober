<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\SalePoint;

class MapController extends Controller
{
    public function index(Request $request)
    {
        $this->commonData($request);

        $salepoints=SalePoint::enabled()->get();

        return $this->view('pages/map/index', [
            'salepoints'=>$salepoints
        ]);
    }
}
