<?php

namespace Haijin\Specs;

class Undefined_Method_Error extends \RuntimeException
{
    protected $method_name;

    /// Initializing

    public function __construct($message, $method_name)
    {
        parent::__construct( $message );

        $this->method_name = $method_name;
    }

    /// Accessing

    public function get_method_name_name()
    {
        return $this->method_name;
    }
}