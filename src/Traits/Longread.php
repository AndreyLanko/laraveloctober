<?php

namespace Perevorot\LaravelOctober\Traits;

use Perevorot\LaravelOctober\Scopes\TranslatableScope;
use Perevorot\LaravelOctober\Models\SystemFile;
use Localization;
use DB;

trait Longread
{
    public function longreadValue($mutator, $value)
    {
        $blocks=$this->getLongreadValue($mutator);
        $this->longreadProccessFiles($blocks);

        if (!empty($blocks)) {
            $html=[];

            foreach ($blocks as $block) {
                $parsed=$this->processBlockClass($block);

                if (!empty($parsed)) {
                    $html[]=$parsed;
                }
            }

            return implode('', $html);
        }

        return $value;
    }

    private function processBlockClass($block)
    {
        $namespace = '\App\Longread\\' . ucfirst(camel_case($block->alias));

        if (!class_exists($namespace)) {
            return [];
        }

        $block = new $namespace($block);

        $block->parse();

        return $block->get();
    }

    public function isLongreadMutator($mutator)
    {
        return !empty($this->longread) && in_array($mutator, $this->longread);
    }

    private function longreadProccessFiles(&$blocks)
    {
        $ids=[];
        $fields=[];

        foreach ($blocks as $block) {
            if (!empty($block->files)) {
                foreach ($block->files as $field => $file) {
                    array_push($fields, $file);
                }
            }
        }

        $files=SystemFile::where('attachment_type', $this->backendModel)->where('attachment_id', $this->id)->whereIn('field', $fields)->get();

        if($files) {
            foreach ($blocks as $k=>$block) {
                if (!empty($block->files)) {
                    foreach ($block->files as $field => $file) {
                        $function=(ends_with($field, 's')?'filter':'first');

                        $block->value->{$field} = $files->$function(function($systemFile) use($file) {
                            return $systemFile->field==$file;
                        });
                    }

                    $blocks[$k]=$block;
                }
            }
        }
    }

    private function getLongreadValue($mutator)
    {
        $value=!empty($this->attributes[$mutator.'_'.Localization::getCurrentLocale()]) ? json_decode($this->attributes[$mutator.'_'.Localization::getCurrentLocale()]) : '';

        $value=empty($value) && !empty($this->attributes[$mutator]) ? json_decode($this->attributes[$mutator]) : $value;

        return $value;
    }
}
