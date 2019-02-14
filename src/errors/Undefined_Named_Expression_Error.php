<?php

namespace Haijin\Specs;

class Undefined_Named_Expression_Error extends \RuntimeException
{
    protected $expression_name;

    /// Initializing

    public function __construct($message, $expression_name)
    {
        parent::__construct( $message );

        $this->expression_name = $expression_name;
    }

    /// Accessing

    public function get_expression_name()
    {
        return $this->expression_name;
    }
}