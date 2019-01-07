<?php

namespace Haijin\Specs;

class ValueExpectation
{
    /// Instance methods

    protected $spec_binding;
    protected $description;
    protected $value;
    protected $negated;
    protected $stored_params;

    /// Accessors

    public function __construct($spec_binding, $description, $value)
    {
        $this->spec_binding = $spec_binding;
        $this->description = $description;
        $this->value = $value;
        $this->negated = false;
        $this->stored_params = [];
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

    public function store_param_at( $param_name, $value )
    {
        $this->stored_params[ $param_name ] = $value;
    }

    public function __call($method_name, $params)
    {
        $particle_closure = ValueExpectations::particle_at( $method_name );

        if( $particle_closure !== null ) {

            $particle_closure->call( $this, ...$params );

            return $this;
        }

        $definition = ValueExpectations::expectation_at( $method_name );

        $evaluation = new ValueExpectationEvaluation(
            $this->spec_binding,
            $this->description,
            $this->value,
            $this->stored_params
        );

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