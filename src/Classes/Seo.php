<?php

namespace Perevorot\LaravelOctober\Classes;

use Artesaos\SEOTools\Facades\SEOTools;
use Artesaos\SEOTools\Facades\SEOMeta;
use Perevorot\LaravelOctober\Models\SeoExternal;
use Perevorot\LaravelOctober\Models\SeoTags;
use Localization;
use Request;
use Blade;

class SEO extends SEOTools
{
    private $external=[
        'head'=>[],
        'body_top'=>[],
        'body_bottom'=>[]
    ];

    private $route;

    public $possibleUrl;
    public $templateData=[];

    public function head()
    {
        $this->parseExternal();
        $this->parseTags();

        return SEOTools::generate().$this->output('head');
    }

    public function bodyTop()
    {
        return $this->output('body_top');
    }

    public function bodyBottom()
    {
        return $this->output('body_bottom');
    }

    private function parseTags()
    {
        if(!$this->route) {
            $this->route=Request::route()->getName();
        }

        $tag=SeoTags::where(function ($q) {
            $q->where(function ($q) {
                if ($this->route) {
                    $q->where('seo_url_type', 0);
                    $q->where(function ($q) {
                        $q->where('route', $this->route);
                        $q->orWhere('route', '');
                    });
                }
            })->orWhere(function ($q) {
                $q->where('seo_url_type', 1);
                $q->whereIn('url_mask', $this->getPossibleUrl());
            });
        })->orderByRaw("FIELD(url_mask, '".implode("', '", $this->getPossibleUrl())."')")->first();

        if ($tag) {
            if (!empty(trim($tag->title))) {
                SEOMeta::setTitle($this->parseTemplate($tag->title));
            }

            if (!empty(trim($tag->description))) {
                SEOMeta::setDescription($this->parseTemplate($tag->description));
            }

            $keywords=!empty($tag->keywords) ? explode(',', $this->parseTemplate($tag->keywords)) : [];
            $keywords=array_map('trim', $keywords);

            if (!empty($keywords)) {
                SEOMeta::setKeywords($keywords);
            }

            if (!empty(trim($tag->canonical))) {
                SEOMeta::setCanonical($tag->canonical);
            }

            if (!empty(trim($tag->og_title))) {
                SEOTools::opengraph()->setTitle($this->parseTemplate($tag->og_title));
            }

            if (!empty(trim($tag->og_description))) {
                SEOTools::opengraph()->setDescription($this->parseTemplate($tag->og_description));
            }

            if (!empty(trim($tag->meta_tags))) {
                array_push($this->external['head'], $tag->meta_tags);
            }
        }
    }

    private function parseExternal()
    {
        if(!$this->route) {
            $this->route=Request::route()->getName();
        }

        $external=SeoExternal::enabled()->where(function ($q) {
            $q->where(function ($q) {
                if ($this->route) {
                    $q->where('seo_url_type', 0);
                    $q->where(function ($q) {
                        $q->where('route', $this->route);
                        $q->orWhere('route', '');
                    });
                }
            })->orWhere(function ($q) {
                $q->where('seo_url_type', 1);
                $q->whereIn('url_mask', $this->getPossibleUrl());
            });
        })->get();

        if ($external) {
            foreach ($external as $item) {
                foreach (['head', 'body_top', 'body_bottom'] as $type) {
                    if (!empty(trim($item->{$type}))) {
                        array_push($this->external[$type], trim($item->{$type}));
                    }
                }
            }
        }
    }

    public function setRoute($routeName)
    {
        $this->route=$routeName;
    }

    public function setData($data)
    {
        $this->templateData=$data;
    }

    private function output($type)
    {
        return implode('', $this->external[$type]);
    }

    private function getPossibleUrl()
    {
        if ($this->possibleUrl) {
            return $this->possibleUrl;
        }

        $url = Request::path();

        if (Localization::getDefaultLocale()!=Localization::getCurrentLocale()) {
            $url=ltrim($url, '/'.Localization::getCurrentLocale());
        }

        $url = '/'.trim($url, '/');

        $urls = [$url];
        $segments=preg_split("/[\/,-]+/", $url);
        $str=str_split($url);

        $dividers=[
            '/'=>[],
            '-'=>[]
        ];

        foreach ($str as $k => $one) {
            if (in_array($one, ['/', '-'])) {
                $dividers[$one][]=$k;
            }
        }

        $i = sizeof($segments);

        array_pop($segments);

        while ($i > 1) {
            $urls[]=implode('/', $segments).'/*';
            array_pop($segments);
            $i--;
        }

        foreach ($urls as $key => $url) {
            foreach (['/', '-'] as $divider) {
                foreach ($dividers[$divider] as $char_position) {
                    if (strlen($url)>=$char_position) {
                        $url=substr_replace($url, $divider, $char_position, 1);
                    }
                }

                $urls[$key]=$url;
            }
        }

        $this->possibleUrl=$urls;

        return $urls;
    }

    public function parseTemplate($template)
    {
        return !empty(trim($template)) ? str_replace("\r\n", "", $this->renderBladeTemplate($template, $this->templateData)) : '';
    }

    public function renderBladeTemplate($__string, $__data)
    {
        $php = Blade::compileString($__string);

        $obLevel = ob_get_level();
        ob_start();
        extract($__data, EXTR_SKIP);

        try {
            eval('?' . '>' . $php);
        } catch (Exception $e) {
            while (ob_get_level() > $obLevel) {
                ob_end_clean();
            }
            throw $e;
        } catch (Throwable $e) {
            while (ob_get_level() > $obLevel) {
                ob_end_clean();
            }
            throw new FatalThrowableError($e);
        }

        return ob_get_clean();
    }
}
