<?php

namespace Haijin\Specs;

class SpecBase
{
    protected $description;
    protected $nested_description;
    protected $context;

    /// Initializing

    public function __construct($description, $nested_description)
    {
        $this->description = $description;
        $this->nested_description = $nested_description;
        $this->context = new SpecContext();
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
}