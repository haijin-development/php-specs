<?php

namespace Haijin\Specs;

class Value_Expectation_Definition
{
    public $expectation_name;
    public $before_closure;
    public $assertion_closure;
    public $negation_closure;
    public $after_closure;

    public function __construct($expectation_name = null)
    {
        $this->expectation_name = $expectation_name;
        $this->before_closure = null;
        $this->assertion_closure = null;
        $this->negation_closure = null;
        $this->after_closure = null;
    }

    /// DSL

    public function define($closure)
    {
        $closure->call( $this );
    }

    public function before($closure)
    {
        $this->before_closure = $closure;
    }

    public function assert_with($closure)
    {
        $this->assertion_closure = $closure;
    }

    public function negate_with($closure)
    {
        $this->negation_closure = $closure;
    }

    public function after($closure)
    {
        $this->after_closure = $closure;
    }

    /// Evaluating

    public function evaluate_before_closure_with($expectation_evaluation, $params)
    {
        if( $this->before_closure === null ) {
            return;
        }

        $this->before_closure->call( $expectation_evaluation, ...$params );
    }

    public function evaluate_assertion_closure_with($expectation_evaluation, $params)
    {
        if( $this->assertion_closure === null ) {
            $this->raise_missing_assertion_closure_error();
        }

        $this->assertion_closure->call( $expectation_evaluation, ...$params );
    }

    public function evaluate_negation_closure_with($expectation_evaluation, $params)
    {
        if( $this->negation_closure === null ) {
            $this->raise_missing_negation_closure_error();
        }

        $this->negation_closure->call( $expectation_evaluation, ...$params );
    }

    public function evaluate_after_closure_with($expectation_evaluation, $params)
    {
        if( $this->after_closure === null ) {
            return;
        }

        $this->after_closure->call( $expectation_evaluation, ...$params );
    }

    /// Raising errors

    public function raise_missing_assertion_closure_error()
    {
        throw new Expectation_Definition_Error(
            "Expectation definition '{$this->expectation_name}' is missing the 'assert_with()' closure."
        );
    }

    public function raise_missing_negation_closure_error()
    {
        throw new Expectation_Definition_Error(
            "Expectation definition '{$this->expectation_name}' is missing the 'negate_with()' closure."
        );
    }
}