<?php

namespace Haijin\Specs;

class Spec
{
    protected $context;

    /// Initializing

    public function __construct()
    {
        $this->context = new SpecContext( "" );
    }

    /// Accessors

    public function get_context()
    {
        return $this->context;
    }

    public function set_nested_description($nested_description)
    {
        $this->context->nested_description = $nested_description;
    }

    public function set_description($description)
    {
        $this->context->description = $description;
    }

    public function get_full_description()
    {
        $description = $this->context->nested_description;

        if( ! empty( $description ) ) {
            $description .= " ";
        }

        $description .= $this->context->description;

        return $description;
    }

    /// DSL

    public function describe($description_text, $closure)
    {
        $nested_spec = new self();
        $nested_spec->set_nested_description( $this->get_full_description() );
        $nested_spec->set_description( $description_text );

        $nested_spec->eval( $closure );
    }

    public function it($description_text, $closure)
    {
        $this->describe( $description_text, $closure );
    }

    public function expect($value)
    {
        return new ValueExpectation( $this->get_full_description(), $value );
    }

    public function eval($closure)
    {
        $closure->call( $this, $this );
    }

    /// Definition

    public function define_in_file($spec_file)
    {
        $spec = $this;

        require( $spec_file );
    }
}