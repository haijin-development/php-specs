<?php

namespace Haijin\Specs;

class Spec extends SpecBase
{
    protected $closure;

    public function __construct($description, $nested_description, $closure)
    {
        parent::__construct( $description, $nested_description );

        $this->closure = $closure;
    }

    /// DSL

    public function expect($value)
    {
        return new ValueExpectation( $this->get_full_description(), $value );
    }

    /// Evaluating

    public function evaluate($statistics)
    {
        $this->evaluate_collecting_failures( $this->closure, $statistics );
    }
}