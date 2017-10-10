<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Bus\DispatchesJobs;
use App\Classes\SystemSettings;
use Illuminate\Http\Request;
use App\Models\Page;
use Localization;
use Route;
use SEO;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public $page;
    public $path;
    public $settings;
    public $inited;

    public function commonData(Request $request)
    {
        if(!$this->inited) {
            $this->path = $this->getPagePath($request);
            $this->route = Route::current();
            $this->page = $this->getCurrentPage($request);
            $this->settings = SystemSettings::instance('ibox');
            $this->inited = true;

            if ($this->page) {
                SEO::setData([
                    'page' => $this->page
                ]);
            }elseif ($this->isPageRoute()){
                abort(404);
            }
        }
    }

    public function view(String $partial, Array $params=[])
    {
        return view($partial, array_merge($params, $this->baseData()));
    }

    private function baseData()
    {
        return [
            'menuMain' => $this->getMenu(1),
            'menuBottom' => $this->getMenu(2),
            'settings' => $this->settings,
            'page' => $this->page,
            'path' => $this->path,
        ];
    }

    private function getCurrentPage(Request $request)
    {
        $slug = !empty($this->route->parameters['slug']) ? $this->route->parameters['slug'] : '';

        $page = Page::enabled()->where(function($q) use($slug) {
            $q->where('route_type', Route::currentRouteName());
            $q->whereRaw('route_id '.($slug ? '="'.$slug.'"':' IS NULL'));
        })->orWhere(function($q){
            $q->where('url', $this->path);
        })->first();

        return $page ? $page : false;
    }

    private function isPageRoute()
    {
        return Route::currentRouteName()=='page';
    }

    private function getMenu($menuId)
    {
        $pages=Page::inMenu($menuId)->orderBy('nest_left', 'ASC')->get();

        return $pages;
    }

    public function getPagePath(Request $request)
    {
        $defaultLocale = Localization::getDefaultLocale();
        $locales = Localization::getSupportedLanguagesKeys();

        if (Localization::hideDefaultLocaleInURL()) {
            $locales=array_diff($locales, [$defaultLocale]);
        }

        $path=trim($request->path(), '/').'/';

        foreach ($locales as $locale) {
            if (starts_with($path, $locale.'/')) {
                $path=substr($path, 3);
            }
        }

        $path = trim($path, '/');

        return '/'.$path;
    }
}
