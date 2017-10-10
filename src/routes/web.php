<?php

$routeGroup=[
    'prefix' => Localization::setLocale(),
    'middleware' => [
        //'localeSessionRedirect',
        'localizationRedirect',
        'localeViewPath',
        'redirectTrailingSlash',
        'caching'
    ]
];

Route::group($routeGroup, function () {
	Route::get('{slug}', 'PageController@page')->name('page');
	Route::get('/', 'PageController@homepage')->name('homepage');
});
