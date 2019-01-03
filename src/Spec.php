<?php

namespace Haijin\Specs;

class Spec
{
    protected $description;

    /// Accessors

    public function get_description()
    {
        return $this->description;
    }

    /// DSL

    public function describe($description_text, $closure)
    {
        $this->description = $description_text;

        $closure->call( $this, $this );
    }

    public function it($description_text, $closure)
    {
        $this->describe( $description_text, $closure );
    }

    public function expect($value)
    {
        return Expectation::on( $value );
    }

    /// Definition

    public function define_in_file($spec_file)
    {
        $spec = $this;

        require_once( $spec_file );
    }
}