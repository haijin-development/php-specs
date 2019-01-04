<?php

namespace Haijin\Specs;

class ValueExpectation
{
    /// Instance methods

    protected $description;
    protected $value;
    protected $negated;

    /// Accessors

    public function __construct($description, $value)
    {
        $this->description = $description;
        $this->value = $value;
        $this->negated = false;
    }

    ///  DSL

    public function to()
    {
        return $this;
    }

    public function not()
    {
        $this->negated = true;

        return $this;
    }

    public function __call($method_name, $params)
    {
        $definition = ValueExpectations::definition_at( $method_name );

        $evaluation = new ValueExpectationEvaluation( $this->description, $this->value );

        $this->evaluate_expectation_definition_with( $definition, $evaluation, $params );
    }

    public function evaluate_expectation_definition_with($definition, $evaluation, $params)
    {
        $definition->evaluate_before_closure_with( $evaluation, $params );

        try {
            if( ! $this->negated ) {
                $definition->evaluate_assertion_closure_with( $evaluation, $params );
            } else {
                $definition->evaluate_negation_closure_with( $evaluation, $params );
            }
        } finally {
            $definition->evaluate_after_closure_with( $evaluation, $params );
        }
    }
}