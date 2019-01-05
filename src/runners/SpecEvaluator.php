<?php

namespace Haijin\Specs;

class SpecEvaluator
{
    protected $statistics;
    protected $current_spec;

    /// Initializing

    public function __construct()
    {
        $this->statistics = $this->new_specs_statistics();
        $this->current_spec = null;
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

    /// Evaluating

    public function evaluate($spec)
    {
        return $spec->evaluate_with( $this );
    }

    public function evaluate_spec_description($spec_description)
    {
        foreach( $spec_description->get_nested_specs() as $spec ) {
            $spec->evaluate_with( $this );
        }
    }

    public function evaluate_spec($spec)
    {
        $this->current_spec = $spec;

        try {

            $this->evaluate_collecting_failures( $spec->get_closure() );

        } finally {

            $this->current_spec = null;
        }
    }

    public function evaluate_collecting_failures($closure )
    {
        try {

            $closure->call( $this );

        } catch( ExpectationFailureSignal $signal ) {

            $this->statistics->add_invalid_expectation(
                new ExpectationFailure(
                    $signal->get_description(),
                    $signal->get_message(),
                    $signal->get_trace()
                )
            );

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
        $closure = $this->get_named_expression( $expression_name );

        return $closure->call( $this );
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