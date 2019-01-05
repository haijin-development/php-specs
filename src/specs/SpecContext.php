<?php

namespace Haijin\Specs;

class SpecContext
{
    public $named_expressions;

    /// Initializing

    public function __construct()
    {
        $this->named_expressions = [];
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
}