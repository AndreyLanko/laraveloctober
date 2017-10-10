<?php

$routeGroup=[
    'prefix' => 'backend',
];

Route::group($routeGroup, function () {
	Route::any('preview/longread/{user_id}/{model_id}/{model}/{column}', 'BackendController@longreadPreview');
});
