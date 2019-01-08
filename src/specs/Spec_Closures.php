<?php

namespace Haijin\Specs;

class Spec_Closures
{
    public $before_all_closure;
    public $before_each_closure;
    public $after_all_closure;
    public $after_each_closure;

    public function __construct()
    {
        $this->before_all_closure = null;
        $this->before_each_closure = null;
        $this->after_all_closure = null;
        $this->after_each_closure = null;
    }
}