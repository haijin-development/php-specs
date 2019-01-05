<?php

namespace Haijin\Specs;

class Spec extends SpecBase
{
    protected $closure;

    public function __construct($description, $nested_description, $context, $closure)
    {
        parent::__construct( $description, $nested_description, $context );

        $this->closure = $closure;
    }

    /// Accessing

    public function get_closure()
    {
        return $this->closure;
    }

    /// DSL

    /**
     * This method actually is neved called, but still documents the intention.
     */
    public function expect($value)
    {
        throw new Exception( "See class SpecEvaluator for this method implementation." );
    }

    /// Evaluating

    public function evaluate_with($spec_evaluator)
    {
        $spec_evaluator->evaluate_spec( $this );
    }

}