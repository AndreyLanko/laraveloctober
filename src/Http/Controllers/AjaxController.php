<?php

namespace App\Http\Controllers;

use Debugbar;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Service;
use App\Models\Provider;
use Illuminate\Http\Request;

class AjaxController extends Controller
{
    public function sliderServices(Request $request, $category = 0)
    {
        $query=strip_tags(trim($request->input('query')));
        $categoryModel=Category::enabled();
        $json=[];

        if (!empty((int) $category)) {
            $category=$categoryModel->find($category);
            $providers=$category->providers->chunk(12);
        }elseif (!empty($query)) {
            $services=Service::enabled()->select('PROVIDER_ID')->whereRaw('LOWER(SERVICE_DETECTION) LIKE ?', '%'.mb_strtolower($query).'%')->get()->keyBy('PROVIDER_ID');
            $providerIds=array_keys($services->toArray());

            $providers=Provider::enabled()->whereIn('PROVIDER_ID', $providerIds)->get()->chunk(12);
        }

        if(!empty($providers)){
            foreach($providers as $providerPage) {
                $row=[];

                foreach($providerPage as $provider){
                    array_push($row, [
                        'name'=>$provider->PROVIDER_NAME,
                        'logo'=>($provider->logo ? '/storage/'.$provider->logo->filename : ''),
                        'href'=>href('pay-service', [(!empty($category) ? $category : $provider->categories[0]), $provider->services[0]])
                    ]);
                }

                array_push($json, $row);
            }
        }

        return response()->json($json);
    }

    public function __isCacheIgnore(Request $request)
    {
        return !empty(strip_tags(trim($request->input('query'))));
    }
}
