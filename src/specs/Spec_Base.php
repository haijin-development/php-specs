<?php

namespace Haijin\Specs;

abstract class Spec_Base
{
    protected $description;
    protected $nested_description;
    protected $context;
    protected $skipping;

    /// Initializing

    public function __construct($description, $nested_description, $context)
    {
        $this->description = $description;
        $this->nested_description = $nested_description;
        $this->context = clone $context;
        $this->skipping = false;
    }

    /// Accessors

    public function get_context()
    {
        return $this->context;
    }

    public function get_full_description()
    {
        $description = $this->nested_description;

        if( ! empty( $description ) ) {
            $description .= " ";
        }

        $description .= $this->description;

        return $description;
    }

    public function is_skipping()
    {
        return $this->skipping;
    }

    public function be_skipping($bool)
    {
        $this->skipping = $bool;
    }

    /// Definition

    public function define_in_file($spec_file)
    {
        $spec = $this;

        require( $spec_file );
    }

    public function eval($closure)
    {
        $closure->call( $this, $this );
    }

    /// Double dispatch evaluations

    abstract public function evaluate_with($spec_evaluator);
}