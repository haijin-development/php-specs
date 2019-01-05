<?php

namespace Haijin\Specs;

class SpecEvaluator
{
    protected $statistics;
    protected $current_spec;
    protected $resolved_named_expressions;
    protected $after_each_spec_closure;

    /// Initializing

    public function __construct()
    {
        $this->statistics = $this->new_specs_statistics();
        $this->current_spec = null;
        $this->resolved_named_expressions = [];
        $this->after_each_spec_closure = null;
    }

    public function reset()
    {
        $this->statistics = $this->new_specs_statistics();
        $this->current_spec = null;
        $this->resolved_named_expressions = [];
    }

    /// Accessing

    public function get_statistics()
    {
        return $this->statistics;
    }

    public function get_invalid_expectations()
    {
        return $this->statistics->get_invalid_expectations();
    }

    public function after_each_spec_do($closure)
    {
        $this->after_each_spec_closure = $closure;
    }

    /// Evaluating

    public function evaluate($spec)
    {
        $current_resolved_named_expressions = $this->resolved_named_expressions;

        try {

            $spec->evaluate_with( $this );

            if( $this->after_each_spec_closure !== null ) {
                $this->after_each_spec_closure->call( $this, $spec, "passed" );
            }

        } catch( ExpectationFailureSignal $signal ) {

            $this->statistics->add_invalid_expectation(
                new ExpectationFailure(
                    $signal->get_description(),
                    $signal->get_message(),
                    $signal->get_trace()
                )
            );

            if( $this->after_each_spec_closure !== null ) {
                $this->after_each_spec_closure->call( $this, $spec, "failed" );
            }

        } finally {

            $this->resolved_named_expressions = $current_resolved_named_expressions;

        }
    }

    public function evaluate_spec_description($spec_description)
    {
        foreach( $spec_description->get_nested_specs() as $spec ) {
            $this->evaluate( $spec );
        }
    }

    public function evaluate_spec($spec)
    {
        $this->current_spec = $spec;

        try {

            $spec->get_closure()->call( $this );

        } finally {

            $this->current_spec = null;
        }
    }

    public function expect($value)
    {
        return $this->new_value_expectation(
            $this->current_spec->get_full_description(),
            $value
        );
    }

    public function __get($property)
    {
        if( $this->has_named_expression( $property ) ) {
            return $this->evaluate_named_expression( $property );
        }

        $this->raise_undefined_named_expression( $property );
    }

    /// Named expressions

    public function has_named_expression($expression_name)
    {
        return $this->current_spec->get_context()->has_named_expression( $expression_name );
    }

    public function evaluate_named_expression($expression_name)
    {
        if( array_key_exists( $expression_name, $this->resolved_named_expressions ) ) {
            return $this->resolved_named_expressions[ $expression_name ];
        }

        $closure = $this->get_named_expression( $expression_name );

        $resolved_expression_value = $closure->call( $this );

        $this->resolved_named_expressions[ $expression_name ] = $resolved_expression_value;

        return $resolved_expression_value;
    }

    public function get_named_expression($expression_name)
    {
        return $this->current_spec->get_context()->get_named_expression( $expression_name );
    }

    /// Creating instances

    protected function new_specs_statistics()
    {
        return new SpecsStatistics();
    }

    protected function new_value_expectation($full_description, $value)
    {
        return new ValueExpectation( $full_description, $value );
    }

    /// Raising errors

    public function raise_undefined_named_expression($expression_name)
    {
        throw new UndefinedNamedExpressionError(
            "Undefined expression named '{$expression_name}.",
            $expression_name
        );
    }
}