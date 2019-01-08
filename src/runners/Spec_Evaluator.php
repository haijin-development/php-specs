<?php

namespace Haijin\Specs;

class Spec_Evaluator
{
    protected $spec_closures;
    protected $statistics;
    protected $current_spec;
    protected $resolved_named_expressions;
    protected $before_each_closures;
    protected $after_each_closures;

    // An external handle to evaluate after each spec run, not part the DSL
    protected $on_spec_run_closure;

    /// Initializing

    public function __construct()
    {
        $this->spec_closures = new Spec_Closures();
        $this->statistics = $this->___new_specs_statistics();
        $this->current_spec = null;
        $this->resolved_named_expressions = [];
        $this->before_each_closures = [];
        $this->after_each_closures = [];
        $this->on_spec_run_closure = null;
    }

    public function ___reset()
    {
        $this->statistics = $this->___new_specs_statistics();
        $this->current_spec = null;
        $this->resolved_named_expressions = [];
    }

    public function ___configure($configuration_closure, $binding = null)
    {
        if( $binding === null ) {
            $binding = $this;
        }

        $configuration_closure->call( $binding, $this );
    }

    /// Accessing

    public function ___get_statistics()
    {
        return $this->statistics;
    }

    public function ___get_invalid_expectations()
    {
        return $this->statistics->get_invalid_expectations();
    }

    public function ___on_spec_run_do($closure)
    {
        $this->on_spec_run_closure = $closure;
    }

    /// DSL


    public function before_all($closure)
    {
        $this->spec_closures->before_all_closure = $closure;
    }

    public function after_all($closure)
    {
        $this->spec_closures->after_all_closure = $closure;
    }

    public function before_each($closure)
    {
        $this->spec_closures->before_each_closure = $closure;
    }

    public function after_each($closure)
    {
        $this->spec_closures->after_each_closure = $closure;
    }

    /// Running

    public function ___run_all($specs_collection)
    {
        if( $this->spec_closures->before_each_closure !== null ) {
            $this->before_each_closures[] = $this->spec_closures->before_each_closure;
        }

        if( $this->spec_closures->after_each_closure !== null ) {
            $this->after_each_closures[] = $this->spec_closures->after_each_closure;
        }

        if( $this->spec_closures->before_all_closure !== null ) {
            $this->spec_closures->before_all_closure->call( $this );
        }

        foreach( $specs_collection as $spec ) {
            $spec->evaluate_with( $this );
        }

        if( $this->spec_closures->after_all_closure !== null ) {
            $this->spec_closures->after_all_closure->call( $this );
        }
    }

    /// Evaluating

    public function ___evaluate_spec_description($spec_description)
    {
        $current_before_each_closures = $this->before_each_closures;
        $current_after_each_closures = $this->after_each_closures;

        if( $spec_description->get_before_all_closure() !== null ) {
            $spec_description->get_before_all_closure()->call( $this );
        }

        if( $spec_description->get_before_each_closure() !== null ) {
            $this->before_each_closures[] = $spec_description->get_before_each_closure();
        }

        if( $spec_description->get_after_each_closure() !== null ) {
            $this->after_each_closures =
                array_merge(
                    [ $spec_description->get_after_each_closure() ],
                    $this->after_each_closures
                );
        }

        try {

            foreach( $spec_description->get_nested_specs() as $spec ) {
                $spec->evaluate_with( $this );
            }

        } finally {

            if( $spec_description->get_after_all_closure() !== null ) {
                $spec_description->get_after_all_closure()->call( $this );
            }

            $this->before_each_closures = $current_before_each_closures;
            $this->after_each_closures = $current_after_each_closures;

        }

    }

    public function ___evaluate_spec($spec)
    {
        $this->statistics->inc_run_specs_count();

        $this->current_spec = $spec;

        $current_resolved_named_expressions = $this->resolved_named_expressions;

        try {

            $this->___evaluate_before_each_closures();

            $spec->get_closure()->call( $this );

            $this->___on_spec_passed( $spec );

        } catch( Expectation_Failure_Signal $failure_signal ) {

            $this->___on_spec_failure( $spec, $failure_signal );

        } catch( \Exception $e ) {

            $this->___on_spec_error( $spec, $e );

        } finally {

            $this->___evaluate_after_each_closures();

            $this->resolved_named_expressions = $current_resolved_named_expressions;

        }
    }

    protected function ___evaluate_before_each_closures()
    {
        foreach( $this->before_each_closures as $before_closure ) {
            $before_closure->call( $this );
        }
    }

    protected function ___evaluate_after_each_closures()
    {
        foreach( $this->after_each_closures as $after_closure ) {
            $after_closure->call( $this );
        }
    }

    protected function ___on_spec_passed($spec)
    {
        if( $this->on_spec_run_closure !== null ) {
            $this->on_spec_run_closure->call( $this, $spec, "passed" );
        }
    }

    protected function ___on_spec_failure($spec, $failure_signal)
    {
        $this->statistics->add_invalid_expectation(
            new Expectation_Failure(
                $this->current_spec->get_full_description(),
                $failure_signal->get_message(),
                $failure_signal->get_trace()
            )
        );

        if( $this->on_spec_run_closure !== null ) {
            $this->on_spec_run_closure->call( $this, $spec, "failed" );
        }
    }

    protected function ___on_spec_error($spec, $error)
    {
        $this->statistics->add_invalid_expectation(
            new Expectation_Error(
                $this->current_spec->get_full_description(),
                $error->getMessage(),
                $error->getTrace()
            )
        );

        if( $this->on_spec_run_closure !== null ) {
            $this->on_spec_run_closure->call( $this, $spec, "error" );
        }
    }

    /// Expectations

    public function expect($value)
    {
        $this->statistics->inc_expectations_count();

        return $this->new_value_expectation(
            $this->current_spec->get_full_description(),
            $value
        );
    }

    public function __get($property)
    {
        if( $this->___has_named_expression( $property ) ) {
            return $this->___evaluate_named_expression( $property );
        }

        $this->___raise_undefined_named_expression( $property );
    }

    /// Named expressions

    public function ___has_named_expression($expression_name)
    {
        return $this->current_spec->get_context()->has_named_expression( $expression_name );
    }

    public function ___evaluate_named_expression($expression_name)
    {
        if( array_key_exists( $expression_name, $this->resolved_named_expressions ) ) {
            return $this->resolved_named_expressions[ $expression_name ];
        }

        $closure = $this->___get_named_expression( $expression_name );

        $resolved_expression_value = $closure->call( $this );

        $this->resolved_named_expressions[ $expression_name ] = $resolved_expression_value;

        return $resolved_expression_value;
    }

    public function ___get_named_expression($expression_name)
    {
        return $this->current_spec->get_context()->get_named_expression( $expression_name );
    }

    /// Creating instances

    protected function ___new_specs_statistics()
    {
        return new Specs_Statistics();
    }

    protected function new_value_expectation($full_description, $value)
    {
        return new Value_Expectation( $this, $full_description, $value );
    }

    /// Raising errors

    public function ___raise_undefined_named_expression($expression_name)
    {
        throw new Undefined_Named_Expression_Error(
            "Undefined expression named '{$expression_name}'.",
            $expression_name
        );
    }
}