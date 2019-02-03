<?php

namespace Haijin\Specs;

class Spec extends Spec_Base
{
    protected $closure;
    protected $line_number;

    public function __construct($description, $nested_description, $context, $closure)
    {
        parent::__construct( $description, $nested_description, $context );

        $this->closure = $closure;
        $this->line_number = null;
    }

    /// Accessing

    public function get_closure()
    {
        return $this->closure;
    }

    public function set_line_number($line_number)
    {
        $this->line_number = $line_number;
    }

    public function get_line_number()
    {
        return $this->line_number;
    }

    public function restrict_to_line_number($line_number)
    {
        return $this;
    }

    public function is_in_line_number($line_number)
    {
        return $this->line_number > $line_number;
    }

    /// DSL

    /**
     * This method actually is neved called, but still documents the intention.
     */
    public function expect($value)
    {
        throw new Exception( "See class Spec_Evaluator for this method implementation." );
    }

    /// Evaluating

    public function evaluate_with($spec_evaluator)
    {
        $spec_evaluator->___evaluate_spec( $this );
    }

}