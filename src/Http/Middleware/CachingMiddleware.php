<?php

namespace Perevorot\LaravelOctober\Http\Middleware;

use DateTime;
use Closure;
use Cache;

class CachingMiddleware
{
    /**
    * @var Request
    */
    protected $request;

    /**
    * @var array
    */
    protected $data = [
        '%%csrf_token%%' => [
            'type' => 'string'
        ]
    ];

    public function __construct()
    {
        $this->initReplaceData();
    }
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $this->request = $request;

        $response = $this->getResponse($next);

        $response = $response->setContent($this->replaceDynamicContent($response->content()));

        return $response;
    }

    protected function getResponse(Closure $next)
    {
        if($this->isCacheIgnore()) {
            return $next($this->request);
        }

        $cacheKey = $this->request->getPathInfo();

        if(!Cache::has($cacheKey)) {
            $response = $next($this->request);

            if($response->status()!=200) {
                return $response;
            }

            $response->original = '';

            $timestamp=new DateTime();

            $content=$response->content();

            if(starts_with($response->headers->get('content-type'), 'text/html')) {
                $content.='<!--cached: '.$cacheKey.' '.$timestamp->format('c').'-->';
            }

            $response->setContent($content);

            Cache::put($cacheKey, $response, env('CACHE_PAGE_LIFETIME'));
        }

        return Cache::get($cacheKey);
    }

    protected function isCacheIgnore()
    {
        if(!env('CACHE_PAGE_LIFETIME')){
            return true;
        }

        if(method_exists($this->request->route()->controller, '__isCacheIgnore')) {
            return $this->request->route()->controller->__isCacheIgnore($this->request);
        }
    }

    protected function replaceDynamicContent($content)
    {
        foreach($this->data as $placeHolder => $replace)
        {
            $method = 'replace' . ucfirst($replace['type']) . 'Content';

            $content = method_exists($this, $method) ?
                $this->{$method}($content, $placeHolder, $replace) :
                $content;
        }

        return $content;
    }

    /**
     * @var string $content
     * @var string $placeholder
     * @var array $replace
     * @return string
     */
    protected function replaceViewContent($content, $placeHolder, $replace)
    {
        return str_replace($placeHolder, view($replace['data'])->render(), $content);
    }

    /**
     * @var string $content
     * @var string $placeholder
     * @var array $replace
     * @return string
     */
    protected function replaceStringContent($content, $placeHolder, $replace)
    {
        return str_replace($placeHolder, $replace['data'], $content);
    }

    protected function initReplaceData()
    {
        $this->data['%%csrf_token%%']['data'] = csrf_token();

        return $this;
    }
}
