<?php

use Haijin\Specs\ValueExpectations;

/// Comparisson expectations

ValueExpectations::define_expectation( "equal", function() {

    $this->before( function($expected_value) {
        $this->got_expected_value = $expected_value == $this->actual_value;
    });

    $this->assert_with( function($expected_value) {

        if( $this->got_expected_value ) {
            return;
        }

        $this->raise_failure(
            "Expected value to equal {$this->value_string($expected_value)}, got {$this->value_string($this->actual_value)}."
        );
    });

    $this->negate_with( function($expected_value) {

        if( ! $this->got_expected_value ) {
            return;
        }

        $this->raise_failure(
            "Expected value not to equal {$this->value_string($expected_value)}, got {$this->value_string($this->actual_value)}."
        );
    });
});

/// Strings expectations

ValueExpectations::define_expectation( "end_with", function() {

    $this->before( function($expected_value) {

        $this->got_expected_value =
            strrpos( $this->actual_value, $expected_value )
            == 
            strlen( $this->actual_value ) - strlen( $expected_value );

    });

    $this->assert_with( function($expected_value) {

        if( $this->got_expected_value ) {
            return;
        }

        $this->raise_failure(
            "Expected {$this->value_string($expected_value)} to end with {$this->value_string($expected_value)}."
        );
    });

    $this->negate_with( function($expected_value) {

        if( ! $this->got_expected_value ) {
            return;
        }

        $this->raise_failure(
            "Expected {$this->value_string($expected_value)} not to end with {$this->value_string($expected_value)}."
        );
    });
});

/// Exception expectations

ValueExpectations::define_expectation( "raise", function() {

    $this->assert_with( function($expected_exception_class_name, $expected_exception_closure = null) {

        $raised_exception = null;

        try {

            $this->evaluate_closure( $this->actual_value );

        } catch( \Exception $e ) {

            $raised_exception = $e;

        }

        if( $raised_exception === null ) {

            $this->raise_failure(
                "Expected the closure to raise a {$expected_exception_class_name}, but no Exception was raised."
            );

        }

        $raised_exception_class_name = get_class( $raised_exception );

        if( $raised_exception_class_name != $expected_exception_class_name ) {

            $this->raise_failure(
                "Expected the closure to raise a {$expected_exception_class_name}, but a {$raised_exception_class_name} was raised instead."
            );

        }

        if( $expected_exception_closure !== null ) {

            $this->evaluate_closure( $expected_exception_closure, $raised_exception );

        }
    });

    $this->negate_with( function($expected_exception_class_name) {
    });
});
