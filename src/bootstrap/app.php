<?php

/*
|--------------------------------------------------------------------------
| Create The Application
|--------------------------------------------------------------------------
|
| The first thing we will do is create a new Laravel application instance
| which serves as the "glue" for all the components of Laravel, and is
| the IoC container for the system binding all of the various parts.
|
*/

$app = new Illuminate\Foundation\Application(
    realpath(__DIR__.'/../')
);


function localizedUrl($route, $attributes = [])
{
    return \App\Classes\Helpers::getLocalizedRouteURL($route, $attributes);
}

function href($route, $model = null)
{
    return \App\Classes\Helpers::getRouteURL($route, $model);
}

function mediaPath($file)
{
    return \App\Classes\Helpers::fileGetMediaPath($file);
}

function storagePath($file)
{
    return \App\Classes\Helpers::fileGetFullStoragePath($file);
}

function buildedAsset($scriptName)
{
    list($type, $fileName)=explode('/', $scriptName);

    $asset_manifest=public_path($type.'/asset-manifest.json');

    if(!file_exists($asset_manifest)){
        App::abort(404, 'File '.$type.'/asset-manifest.json not found');
    }

    $asset_manifest=@json_decode(file_get_contents($asset_manifest), true);

    $file=array_key_exists($fileName, $asset_manifest) ? ltrim($asset_manifest[$fileName], 'static/js/') : $scriptName;

    return '/'.$type.'/'.$file;
}

/*
|--------------------------------------------------------------------------
| Bind Important Interfaces
|--------------------------------------------------------------------------
|
| Next, we need to bind some important interfaces into the container so
| we will be able to resolve them when needed. The kernels serve the
| incoming requests to this application from both the web and CLI.
|
*/

$app->singleton(
    Illuminate\Contracts\Http\Kernel::class,
    App\Http\Kernel::class
);

$app->singleton(
    Illuminate\Contracts\Console\Kernel::class,
    App\Console\Kernel::class
);

$app->singleton(
    Illuminate\Contracts\Debug\ExceptionHandler::class,
    App\Exceptions\Handler::class
);

/*
|--------------------------------------------------------------------------
| Return The Application
|--------------------------------------------------------------------------
|
| This script returns the application instance. The instance is given to
| the calling script so we can separate the building of the instances
| from the actual running of the application and sending responses.
|
*/

return $app;
