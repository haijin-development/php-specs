<?php

namespace Haijin\Specs;

class Spec_Evaluator
{
    protected $___specs_config;
    protected $___statistics;
    protected $___scope_variables;
    protected $___before_each_closures;
    protected $___after_each_closures;

    protected $___current_description;
    protected $___current_context;

    protected $___is_skipping;

    // An external handle to evaluate after each spec run, not part the DSL
    protected $___on_spec_run_closure;

    /// Initializing

    public function __construct()
    {
        $this->___specs_config = new Specs_Configuration();
        $this->___statistics = $this->___new_specs_statistics();
        $this->___scope_variables = [];
        $this->___before_each_closures = [];
        $this->___after_each_closures = [];
        $this->___on_spec_run_closure = null;

        $this->___current_description = "";
        $this->___current_context = null;

        $this->___is_skipping = false;
    }

    public function ___reset()
    {
        $this->___statistics = $this->___new_specs_statistics();
        $this->___scope_variables = [];
        $this->___current_description = "";
        $this->___current_context = null;
    }

    public function ___configure($specs_configuration)
    {
        $this->___specs_config = $specs_configuration;
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

    /// Running

    public function ___run_all($specs_collection)
    {
        $this->___current_context = $this->___specs_config->get_specs_context();

        if( $this->___specs_config->get_before_each_closure() !== null ) {
            $this->___before_each_closures[] = $this->___specs_config->get_before_each_closure();
        }

        if( $this->___specs_config->get_after_each_closure() !== null ) {
            $this->___after_each_closures[] = $this->___specs_config->get_after_each_closure();
        }

        if( $this->___specs_config->get_before_all_closure() !== null ) {
            $this->___specs_config->get_before_all_closure()->call( $this );
        }

        foreach( $specs_collection as $spec ) {
            $spec->evaluate_with( $this );
        }

        if( $this->___specs_config->get_after_all_closure() !== null ) {
            $this->___specs_config->get_after_all_closure()->call( $this );
        }
    }

    /// Evaluating

    public function ___evaluate_spec_description($spec_description)
    {
        $this->___with_context_do( $spec_description, function() use($spec_description) {

            $this->___is_skipping = $this->___is_skipping || $spec_description->is_skipping();

            if( !$this->___is_skipping && $spec_description->get_before_all_closure() !== null ) {
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

                if( !$this->___is_skipping && $spec_description->get_after_all_closure() !== null ) {
                    $spec_description->get_after_all_closure()->call( $this );
                }

            }

        });

    }

    public function ___evaluate_spec($spec)
    {
        $this->___with_context_do( $spec, function() use($spec) {

            $this->___is_skipping = $this->___is_skipping || $spec->is_skipping();

            if( $this->___is_skipping ) {

                $this->___statistics->inc_skipped_specs_count();
                $this->___on_spec_skipped( $spec );

                return;
            }

            try {

                $this->___statistics->inc_run_specs_count();

                $this->___evaluate_before_each_closures();

                $spec->get_closure()->call( $this );

                $this->___on_spec_passed( $spec );

            } finally {

                $this->___evaluate_after_each_closures();

            }

        });
    }

    protected function ___with_context_do($spec, $closure)
    {
        $previous_description = $this->___current_description;
        $previous_context = $this->___current_context;

        $previous_scope = $this->___scope_variables;

        $previous_before_each_closures = $this->___before_each_closures;
        $previous_after_each_closures = $this->___after_each_closures;

        $previous_is_skipping = $this->___is_skipping;

        try {

            $this->___current_description = $spec->get_full_description();
            $this->___current_context = $spec->get_context();

            $closure->call( $this );

        } catch( Expectation_Failure_Signal $failure_signal ) {

            $this->___on_spec_failure( $spec, $failure_signal );

        } catch( \Exception $e ) {

            $this->___on_spec_error( $spec, $e );

        } finally {

            $this->___unbind_scope_variables( $previous_scope );

            $this->___is_skipping = $previous_is_skipping;

            $this->___before_each_closures = $previous_before_each_closures;
            $this->___after_each_closures = $previous_after_each_closures;

            $this->___scope_variables = $previous_scope;

            $this->___current_description = $previous_description;
            $this->___current_context = $previous_context;

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

    protected function ___on_spec_skipped($spec)
    {
        if( $this->___on_spec_run_closure !== null ) {
            $this->___on_spec_run_closure->call( $this, $spec, "skipped" );
        }
    }

    protected function ___on_spec_failure($spec, $failure_signal)
    {
        $this->___statistics->add_invalid_expectation(
            new Expectation_Failure(
                $this->___current_description,
                $failure_signal->get_message(),
                $spec->get_file_name(),
                $spec->get_line_number(),
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
                $this->___current_description,
                $error->getMessage(),
                $spec->get_file_name(),
                $spec->get_line_number(),
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
            $this->___current_description,
            $value
        );
    }

    public function fail($message)
    {
        throw new Expectation_Failure_Signal( $message, $this->___current_description );
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
        return $this->___current_context->has_named_expression( $expression_name );
    }

    public function ___evaluate_named_expression($expression_name)
    {
        $closure = $this->___get_named_expression( $expression_name );

        return $closure->call( $this );
    }

    public function ___get_named_expression($expression_name)
    {
        return $this->___current_context->get_named_expression( $expression_name );
    }

    /// Methods

    public function __call($method_name, $parameters)
    {
        if( $this->___has_method( $method_name ) ) {

            return $this->___call_method( $method_name, $parameters );
        }

        $this->___raise_undefined_method( $method_name );
    }

    public function ___call_method($method_name, $parameters)
    {
        $closure = $this->___get_method( $method_name );

        return $closure->call( $this, ...$parameters );
    }

    public function ___has_method($method_name)
    {
        return $this->___current_context->has_method( $method_name );
    }

    public function ___get_method($method_name)
    {
        return $this->___current_context->get_method( $method_name );
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

    public function ___raise_undefined_method($method_name)
    {
        throw new Undefined_Method_Error(
            "Undefined method named '{$method_name}'.",
            $method_name
        );
    }
}