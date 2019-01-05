<?php

namespace Haijin\Specs;

class SpecDescription extends SpecBase
{
    protected $nested_specs;

    public function __construct($description, $nested_description)
    {
        parent::__construct( $description, $nested_description );

        $this->nested_specs = [];
    }

    /// DSL

    public function describe($description_text, $closure)
    {
        $nested_spec_description = new self( $description_text, $this->get_full_description() );

        $this->nested_specs[] = $nested_spec_description;

        $nested_spec_description->eval( $closure );
    }

    public function it($description_text, $closure)
    {
        $nested_spec = new Spec( $description_text, $this->get_full_description(), $closure );

        $this->nested_specs[] = $nested_spec;
    }

    /// Evaluating

    public function evaluate($statistics)
    {
        foreach( $this->nested_specs as $spec ) {
            $spec->evaluate( $statistics );
        }
    }
}