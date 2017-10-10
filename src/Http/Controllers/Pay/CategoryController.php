<?php

namespace App\Http\Controllers\Pay;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use SEO;

class CategoryController extends Controller
{
    public function category(Request $request, $slug = null)
    {
        $this->commonData($request);

        $categories=Category::enabled()->get();
        $category = null;

        if($slug) {
            $category=array_first($categories, function($category) use($slug) {
                return !empty($category->slug) && $category->slug->slug == $slug || $category->CATEGORY_ID==$slug;
            });
        }

        if ($slug && !$category) {
            SEO::setRoute('pay-service');
            $controller = app()->make('App\Http\Controllers\Pay\ServiceController');

            return $controller->callAction('index', array($request, $slug));
        }

        if($category) {
            $categories->each(function($item) use($category) {
                $item->is_active = $item->CATEGORY_ID==$category->CATEGORY_ID;
            });

            SEO::setData([
                'category'=>$category
            ]);
        }

        return $this->view('pages/pay/category', [
            'category' => $category,
            'categories' => $categories,
        ]);
    }
}
