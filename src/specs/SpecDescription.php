<?php

namespace Haijin\Specs;

class SpecDescription extends SpecBase
{
    protected $nested_specs_descriptions;
    protected $nested_specs;

    public function __construct($description, $nested_description)
    {
        parent::__construct( $description, $nested_description );

        $this->nested_specs_descriptions = [];
        $this->nested_specs = [];
    }

    /// Collecting specs

    public function get_all_specs()
    {
        $specs = [];

        $specs = array_merge( $specs, $this->nested_specs );

        foreach( $this->nested_specs_descriptions as $spec_description ) {
            $specs = array_merge( $specs, $spec_description->get_all_specs() );
        }

        return $specs;
    }

    /// DSL

    public function describe($description_text, $closure)
    {
        $nested_spec_description = new self( $description_text, $this->get_full_description() );

        $this->nested_specs_descriptions[] = $nested_spec_description;

        $nested_spec_description->eval( $closure );
    }

    public function it($description_text, $closure)
    {
        $nested_spec = new Spec( $description_text, $this->get_full_description(), $closure );

        $this->nested_specs[] = $nested_spec;
    }
}