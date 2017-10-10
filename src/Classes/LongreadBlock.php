<?php

namespace Perevorot\LaravelOctober\Classes;

abstract class LongreadBlock
{
    protected $block;
    protected $data = [];

    public function __construct($block)
    {
        $this->block = $block;
    }

    public function get()
    {
        $this->set('block', !empty($this->block->value) ? $this->block->value : []);

        return view('longread/'.$this->block->alias, $this->data)->render();
    }

    public function set($key, $value)
    {
        $this->data[$key]=$value;
    }

    abstract public function parse();
}
