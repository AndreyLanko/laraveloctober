<?php

namespace Perevorot\LaravelOctober\Providers;

use Illuminate\Support\ServiceProvider;
use Perevorot\LaravelOctober\Extensions\BladeExtensions;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        BladeExtensions::extend();
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // $this->app->singleton(MyPackage::class, function () {
        //     return new MyPackage();
        // });
        //
        // Mcamara\LaravelLocalization\LaravelLocalizationServiceProvider::class,
        // App\Providers\SEOServiceProvider::class,

$tmp=new \Mcamara\LaravelLocalization\Facades\LaravelLocalization();
dd($tmp);
        $this->app->alias(\Mcamara\LaravelLocalization\Facades\LaravelLocalization::class, 'Localization');
        $this->app->alias(\Perevorot\LaravelOctober\Facades\SEO::class, 'Localization');

    }
}
