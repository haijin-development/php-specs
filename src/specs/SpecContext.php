<?php

namespace Haijin\Specs;

class SpecContext
{
    public $description;
    public $nested_description;

    public function __construct()
    {
        $this->description = "";
        $this->nested_description = "";
    }
}