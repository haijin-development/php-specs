<?php

use Haijin\Specs\ValueExpectations;

ValueExpectations::define_expectation( "equal", function() {

    $this->before( function($expected_value) {
        $this->got_expected_value = $expected_value == $this->actual_value;
    });

    $this->assert_with( function($expected_value) {

        if( $this->got_expected_value ) {
            return;
        }

        $this->raise_error(
            "Expected value to equal {$this->value_string($expected_value)}, got {$this->value_string($this->actual_value)}."
        );
    });

    $this->negate_with( function($expected_value) {

        if( ! $this->got_expected_value ) {
            return;
        }

        $this->raise_error(
            "Expected value not to equal {$this->value_string($expected_value)}, got {$this->value_string($this->actual_value)}."
        );
    });
});

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

        $this->raise_error(
            "Expected {$this->value_string($expected_value)} to end with {$this->value_string($expected_value)}."
        );
    });

    $this->negate_with( function($expected_value) {

        if( ! $this->got_expected_value ) {
            return;
        }

        $this->raise_error(
            "Expected {$this->value_string($expected_value)} not to end with {$this->value_string($expected_value)}."
        );
    });
});