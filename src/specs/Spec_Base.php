<?php

namespace Haijin\Specs;

abstract class Spec_Base
{
    protected $description;
    protected $nested_description;
    protected $context;

    /// Initializing

    public function __construct($description, $nested_description, $context)
    {
        if( $context === null ) {
            $context = new Spec_Context();
        } else {
            $context = clone $context;
        }

        $this->description = $description;
        $this->nested_description = $nested_description;
        $this->context = $context;
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