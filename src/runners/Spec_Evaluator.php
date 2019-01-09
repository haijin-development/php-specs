<?php

namespace Haijin\Specs;

class Spec_Evaluator
{
    protected $___spec_closures;
    protected $___statistics;
    protected $___current_spec;
    protected $___scope_variables;
    protected $___before_each_closures;
    protected $___after_each_closures;

    // An external handle to evaluate after each spec run, not part the DSL
    protected $___on_spec_run_closure;

    /// Initializing

    public function __construct()
    {
        $this->___spec_closures = new Spec_Closures();
        $this->___statistics = $this->___new_specs_statistics();
        $this->___current_spec = null;
        $this->___scope_variables = [];
        $this->___before_each_closures = [];
        $this->___after_each_closures = [];
        $this->___on_spec_run_closure = null;
    }

    public function ___reset()
    {
        $this->___statistics = $this->___new_specs_statistics();
        $this->___current_spec = null;
        $this->___scope_variables = [];
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
        return $this->___statistics;
    }

    public function ___get_invalid_expectations()
    {
        return $this->___statistics->get_invalid_expectations();
    }

    public function ___on_spec_run_do($closure)
    {
        $this->___on_spec_run_closure = $closure;
    }

    /// DSL


    public function before_all($closure)
    {
        $this->___spec_closures->before_all_closure = $closure;
    }

    public function after_all($closure)
    {
        $this->___spec_closures->after_all_closure = $closure;
    }

    public function before_each($closure)
    {
        $this->___spec_closures->before_each_closure = $closure;
    }

    public function after_each($closure)
    {
        $this->___spec_closures->after_each_closure = $closure;
    }

    /// Running

    public function ___run_all($specs_collection)
    {
        if( $this->___spec_closures->before_each_closure !== null ) {
            $this->___before_each_closures[] = $this->___spec_closures->before_each_closure;
        }

        if( $this->___spec_closures->after_each_closure !== null ) {
            $this->___after_each_closures[] = $this->___spec_closures->after_each_closure;
        }

        if( $this->___spec_closures->before_all_closure !== null ) {
            $this->___spec_closures->before_all_closure->call( $this );
        }

        foreach( $specs_collection as $spec ) {
            $spec->evaluate_with( $this );
        }

        if( $this->___spec_closures->after_all_closure !== null ) {
            $this->___spec_closures->after_all_closure->call( $this );
        }
    }

    /// Evaluating

    public function ___evaluate_spec_description($spec_description)
    {
        $current_before_each_closures = $this->___before_each_closures;
        $current_after_each_closures = $this->___after_each_closures;

        if( $spec_description->get_before_all_closure() !== null ) {
            $spec_description->get_before_all_closure()->call( $this );
        }

        if( $spec_description->get_before_each_closure() !== null ) {
            $this->___before_each_closures[] = $spec_description->get_before_each_closure();
        }

        if( $spec_description->get_after_each_closure() !== null ) {
            $this->___after_each_closures =
                array_merge(
                    [ $spec_description->get_after_each_closure() ],
                    $this->___after_each_closures
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

            $this->___before_each_closures = $current_before_each_closures;
            $this->___after_each_closures = $current_after_each_closures;

        }

    }

    public function ___evaluate_spec($spec)
    {
        $this->___statistics->inc_run_specs_count();

        $this->___current_spec = $spec;

        $previous_scope = $this->___scope_variables;

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

            $this->___unbind_scope_variables( $previous_scope );

            $this->___scope_variables = $previous_scope;

            $this->___current_spec = null;

        }
    }

    protected function ___unbind_scope_variables($previous_scope)
    {
        foreach( array_diff( $this->___scope_variables, $previous_scope ) as $inst_var_name ) {
            unset( $this->$inst_var_name );
        }
    }

    protected function ___evaluate_before_each_closures()
    {
        foreach( $this->___before_each_closures as $before_closure ) {
            $before_closure->call( $this );
        }
    }

    protected function ___evaluate_after_each_closures()
    {
        foreach( $this->___after_each_closures as $after_closure ) {
            $after_closure->call( $this );
        }
    }

    protected function ___on_spec_passed($spec)
    {
        if( $this->___on_spec_run_closure !== null ) {
            $this->___on_spec_run_closure->call( $this, $spec, "passed" );
        }
    }

    protected function ___on_spec_failure($spec, $failure_signal)
    {
        $this->___statistics->add_invalid_expectation(
            new Expectation_Failure(
                $this->___current_spec->get_full_description(),
                $failure_signal->get_message(),
                $failure_signal->get_trace()
            )
        );

        if( $this->___on_spec_run_closure !== null ) {
            $this->___on_spec_run_closure->call( $this, $spec, "failed" );
        }
    }

    protected function ___on_spec_error($spec, $error)
    {
        $this->___statistics->add_invalid_expectation(
            new Expectation_Error(
                $this->___current_spec->get_full_description(),
                $error->getMessage(),
                $error->getTrace()
            )
        );

        if( $this->___on_spec_run_closure !== null ) {
            $this->___on_spec_run_closure->call( $this, $spec, "error" );
        }
    }

    /// Expectations

    public function expect($value)
    {
        $this->___statistics->inc_expectations_count();

        return $this->new_value_expectation(
            $this->___current_spec->get_full_description(),
            $value
        );
    }

    public function __get($property)
    {
        if( $this->___has_named_expression( $property ) ) {

            $value = $this->___evaluate_named_expression( $property );

            $this->$property = $value;

            return $value;

        }

        $this->___raise_undefined_named_expression( $property );
    }

    public function __set($property, $value)
    {
        $this->___scope_variables[] = $property;
        $this->$property = $value;
    }

    /// Named expressions

    public function ___has_named_expression($expression_name)
    {
        return $this->___current_spec->get_context()->has_named_expression( $expression_name );
    }

    public function ___evaluate_named_expression($expression_name)
    {
        $closure = $this->___get_named_expression( $expression_name );

        return $closure->call( $this );
    }

    public function ___get_named_expression($expression_name)
    {
        return $this->___current_spec->get_context()->get_named_expression( $expression_name );
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