<?php

namespace Haijin\Specs;

class SpecDescription extends SpecBase
{
    protected $nested_specs;

    public function __construct($description, $nested_description, $context)
    {
        parent::__construct( $description, $nested_description, $context );

        $this->nested_specs = [];
    }

    /// Accessing

    public function get_nested_specs()
    {
        return $this->nested_specs;
    }

    /// DSL

    public function describe($description_text, $closure)
    {
        $nested_spec_description = new self(
            $description_text,
            $this->get_full_description(),
            $this->context
        );

        $this->nested_specs[] = $nested_spec_description;

        $nested_spec_description->eval( $closure );
    }

    public function xdescribe($description_text, $closure)
    {
    }

    public function let($expression_name, $closure)
    {
        $this->context->at_named_expression_put( $expression_name, $closure );
    }

    public function it($description_text, $closure)
    {
        $nested_spec = new Spec(
            $description_text,
            $this->get_full_description(),
            $this->context,
            $closure
        );

        $this->nested_specs[] = $nested_spec;
    }

    public function xit($description_text, $closure)
    {
    }

    /// Double dispatch evaluations

    public function evaluate_with($spec_evaluator)
    {
        $spec_evaluator->evaluate_spec_description( $this );
    }
}