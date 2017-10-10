<?php

namespace App\Http\Controllers\Pay;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Service;
use SEO;

class ServiceController extends Controller
{
    public function index(Request $request, $slug)
    {
        $this->commonData($request);

        $service=Service::bySlug($slug)->first();

        if (!$service) {
            SEO::setRoute('page');
            $controller = app()->make('App\Http\Controllers\PageController');

            return $controller->callAction('page', array($request, $slug));
        }

        if (!$service || empty($service->provider->services)) {
            abort(404);
        }

        $category=$service->provider->categories[0];

        if (!$category) {
            abort(404);
        }

        SEO::setData([
            'service'=>$service,
            'category'=>$category
        ]);

        return $this->view('pages/pay/service', [
            'service'=>$service,
            'category'=>$category
        ]);
    }
}
