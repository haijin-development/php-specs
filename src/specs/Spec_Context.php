<?php

namespace Haijin\Specs;

class Spec_Context
{
    public $named_expressions;
    public $methods;

    /// Initializing

    public function __construct()
    {
        $this->named_expressions = [];
        $this->methods = [];
    }

    /// Named expressions

    public function at_named_expression_put($expression_name, $closure)
    {
        $this->named_expressions[ $expression_name ] = $closure;
    }

    public function get_named_expression($expression_name)
    {
        return $this->named_expressions[ $expression_name ];
    }

    public function has_named_expression($expression_name)
    {
        return array_key_exists( $expression_name, $this->named_expressions );
    }

    /// Methods

    public function at_method_put($method_name, $closure)
    {
        $this->methods[ $method_name ] = $closure;
    }

    public function get_method($method_name)
    {
        return $this->methods[ $method_name ];
    }

    public function has_method($method_name)
    {
        return array_key_exists( $method_name, $this->methods );
    }
}