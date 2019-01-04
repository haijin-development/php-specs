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
            "Expected value to equal '{$expected_value}', got '{$this->actual_value}'."
        );
    });

    $this->negate_with( function($expected_value) {

        if( ! $this->got_expected_value ) {
            return;
        }

        $this->raise_error(
            "Expected value not to equal '{$expected_value}', got '{$this->actual_value}'."
        );
    });
});