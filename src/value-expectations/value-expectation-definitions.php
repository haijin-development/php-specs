<?php

use Haijin\Specs\Value_Expectations;

/// Particles definitions

Value_Expectations::define_particle( "be", function($operator = null) {

    $this->store_param_at( "operator", $operator );

});

/// Comparison expectations

Value_Expectations::define_expectation( "equal", function() {

    $this->before( function($expected_value) {
        $this->actual_comparison = $expected_value ==$this->actual_value;
    });

    $this->assert_with( function($expected_value) {

        if( $this->actual_comparison ) {
            return;
        }

        $this->raise_failure(
            "Expected value to equal {$this->value_string($expected_value)}, got {$this->value_string($this->actual_value)}."
        );
    });

    $this->negate_with( function($expected_value) {

        if( ! $this->actual_comparison ) {
            return;
        }

        $this->raise_failure(
            "Expected value not to equal {$this->value_string($expected_value)}, got {$this->value_string($this->actual_value)}."
        );
    });
});

Value_Expectations::define_expectation( "than", function() {

    $this->before( function($expected_value) {
        $this->operator = $this->stored_params[ "operator" ];

        switch( $this->operator ) {
            case '==':
                $this->actual_comparison = $this->actual_value ==$expected_value;
                break;
            case '===':
                $this->actual_comparison = $this->actual_value ===$expected_value;
                break;
            case '!=':
                $this->actual_comparison = $this->actual_value != $expected_value;
                break;
            case '!==':
                $this->actual_comparison = $this->actual_value !==$expected_value;
                break;
            case '>':
                $this->actual_comparison = $this->actual_value > $expected_value;
                break;
            case '>=':
                $this->actual_comparison = $this->actual_value >= $expected_value;
                break;
            case '<':
                $this->actual_comparison = $this->actual_value < $expected_value;
                break;
            case '<=':
                $this->actual_comparison = $this->actual_value <= $expected_value;
                break;

            default:
                throw new Exception( "Unkown operator {$operator}" );
                break;
        }
    });

    $this->assert_with( function($expected_value) {

        if( $this->actual_comparison ) {
            return;
        }

        $this->raise_failure(
            "Expected value {$this->value_string($this->actual_value)} to be {$this->operator} than {$this->value_string($expected_value)}."
        );
    });

    $this->negate_with( function($expected_value) {

        if( ! $this->actual_comparison ) {
            return;
        }

        $this->raise_failure(
            "Expected value {$this->value_string($this->actual_value)} not to be {$this->operator} than {$this->value_string($expected_value)}."
        );
    });
});

Value_Expectations::define_expectation( "null", function() {

    $this->before( function() {

        $this->actual_comparison = $this->actual_value ===null;

    });

    $this->assert_with( function() {

        if( $this->actual_comparison ) {
            return;
        }

        $this->raise_failure(
            "Expected value to be null, got {$this->value_string($this->actual_value)}."
        );
    });

    $this->negate_with( function() {

        if( ! $this->actual_comparison ) {
            return;
        }

        $this->raise_failure(
            "Expected value not to be null, got null."

        );
    });
});

Value_Expectations::define_expectation( "true", function() {

    $this->before( function() {

        $this->actual_comparison = $this->actual_value ===true;

    });

    $this->assert_with( function() {

        if( $this->actual_comparison ) {
            return;
        }

        $this->raise_failure(
            "Expected value to be true, got {$this->value_string($this->actual_value)}."
        );
    });

    $this->negate_with( function() {

        if( ! $this->actual_comparison ) {
            return;
        }

        $this->raise_failure(
            "Expected value not to be true, got true."

        );
    });
});

Value_Expectations::define_expectation( "false", function() {

    $this->before( function() {

        $this->actual_comparison = $this->actual_value ===false;

    });

    $this->assert_with( function() {

        if( $this->actual_comparison ) {
            return;
        }

        $this->raise_failure(
            "Expected value to be false, got {$this->value_string($this->actual_value)}."
        );
    });

    $this->negate_with( function() {

        if( ! $this->actual_comparison ) {
            return;
        }

        $this->raise_failure(
            "Expected value not to be false, got false."

        );
    });
});

/// Type expectations

Value_Expectations::define_expectation( "string", function() {

    $this->before( function() {

        $this->actual_comparison = is_string( $this->actual_value );

    });

    $this->assert_with( function() {

        if( $this->actual_comparison ) {
            return;
        }

        $this->raise_failure(
            "Expected value to be a string, got {$this->value_string($this->actual_value)}."
        );
    });

    $this->negate_with( function() {

        if( ! $this->actual_comparison ) {
            return;
        }

        $this->raise_failure(
            "Expected value not to be a string, got {$this->value_string($this->actual_value)}."

        );
    });
});

Value_Expectations::define_expectation( "int", function() {

    $this->before( function() {

        $this->actual_comparison = is_int( $this->actual_value );

    });

    $this->assert_with( function() {

        if( $this->actual_comparison ) {
            return;
        }

        $this->raise_failure(
            "Expected value to be an int, got {$this->value_string($this->actual_value)}."
        );
    });

    $this->negate_with( function() {

        if( ! $this->actual_comparison ) {
            return;
        }

        $this->raise_failure(
            "Expected value not to be an int, got {$this->value_string($this->actual_value)}."

        );
    });
});

Value_Expectations::define_expectation( "double", function() {

    $this->before( function() {

        $this->actual_comparison = is_double( $this->actual_value );

    });

    $this->assert_with( function() {

        if( $this->actual_comparison ) {
            return;
        }

        $this->raise_failure(
            "Expected value to be a double, got {$this->value_string($this->actual_value)}."
        );
    });

    $this->negate_with( function() {

        if( ! $this->actual_comparison ) {
            return;
        }

        $this->raise_failure(
            "Expected value not to be a double, got {$this->value_string($this->actual_value)}."

        );
    });
});

Value_Expectations::define_expectation( "number", function() {

    $this->before( function() {

        $this->actual_comparison =
            is_int( $this->actual_value ) || is_double( $this->actual_value );

    });

    $this->assert_with( function() {

        if( $this->actual_comparison ) {
            return;
        }

        $this->raise_failure(
            "Expected value to be a number, got {$this->value_string($this->actual_value)}."
        );
    });

    $this->negate_with( function() {

        if( ! $this->actual_comparison ) {
            return;
        }

        $this->raise_failure(
            "Expected value not to be a number, got {$this->value_string($this->actual_value)}."

        );
    });
});

Value_Expectations::define_expectation( "bool", function() {

    $this->before( function() {

        $this->actual_comparison = is_bool( $this->actual_value );

    });

    $this->assert_with( function() {

        if( $this->actual_comparison ) {
            return;
        }

        $this->raise_failure(
            "Expected value to be a bool, got {$this->value_string($this->actual_value)}."
        );
    });

    $this->negate_with( function() {

        if( ! $this->actual_comparison ) {
            return;
        }

        $this->raise_failure(
            "Expected value not to be a bool, got {$this->value_string($this->actual_value)}."

        );
    });
});

Value_Expectations::define_expectation( "array", function() {

    $this->before( function() {

        $this->actual_comparison = is_array( $this->actual_value );

    });

    $this->assert_with( function() {

        if( $this->actual_comparison ) {
            return;
        }

        $this->raise_failure(
            "Expected value to be an array, got {$this->value_string($this->actual_value)}."
        );
    });

    $this->negate_with( function() {

        if( ! $this->actual_comparison ) {
            return;
        }

        $this->raise_failure(
            "Expected value not to be an array, got an array."

        );
    });
});

Value_Expectations::define_expectation( "a", function() {

    $this->before( function($class_name) {

        $this->actual_comparison = is_a( $this->actual_value, $class_name );

    });

    $this->assert_with( function($class_name) {

        if( $this->actual_comparison ) {
            return;
        }

        $this->raise_failure(
            "Expected value to be a kind of {$class_name}, got {$this->value_string($this->actual_value)}."
        );
    });

    $this->negate_with( function($class_name) {

        if( ! $this->actual_comparison ) {
            return;
        }

        $this->raise_failure(
            "Expected value not to be a kind of {$class_name}, got a kind of {$class_name}."

        );
    });
});

Value_Expectations::define_expectation( "instance_of", function() {

    $this->before( function($class_name) {

        $this->actual_comparison = $this->actual_value instanceof $class_name;

    });

    $this->assert_with( function($class_name) {

        if( $this->actual_comparison ) {
            return;
        }

        $this->raise_failure(
            "Expected value to be an instance of {$class_name}, got {$this->value_string($this->actual_value)}."
        );
    });

    $this->negate_with( function($class_name) {

        if( ! $this->actual_comparison ) {
            return;
        }

        $this->raise_failure(
            "Expected value not to be an instance of {$class_name}, got an instance of {$class_name}."

        );
    });
});

/// Strings expectations

Value_Expectations::define_expectation( "begin_with", function() {

    $this->before( function($expected_value) {

        if( $expected_value ==="" ) {
            $this->actual_comparison = true;

            return;
        }

        $this->actual_comparison =
            strpos( $this->actual_value, $expected_value ) ===0;

    });

    $this->assert_with( function($expected_value) {

        if( $this->actual_comparison ) {
            return;
        }

        $this->raise_failure(
            "Expected {$this->value_string($this->actual_value)} to begin with {$this->value_string($expected_value)}."
        );
    });

    $this->negate_with( function($expected_value) {

        if( ! $this->actual_comparison ) {
            return;
        }

        $this->raise_failure(
            "Expected {$this->value_string($this->actual_value)} not to begin with {$this->value_string($expected_value)}."
        );
    });
});


Value_Expectations::define_expectation( "end_with", function() {

    $this->before( function($expected_value) {

        if( $expected_value ==="" ) {
            $this->actual_comparison = true;

            return;
        }

        $this->actual_comparison =
            strrpos( $this->actual_value, $expected_value )
            ==
            strlen( $this->actual_value ) - strlen( $expected_value );

    });

    $this->assert_with( function($expected_value) {

        if( $this->actual_comparison ) {
            return;
        }

        $this->raise_failure(
            "Expected {$this->value_string($this->actual_value)} to end with {$this->value_string($expected_value)}."
        );
    });

    $this->negate_with( function($expected_value) {

        if( ! $this->actual_comparison ) {
            return;
        }

        $this->raise_failure(
            "Expected {$this->value_string($this->actual_value)} not to end with {$this->value_string($expected_value)}."
        );
    });
});

Value_Expectations::define_expectation( "contain", function() {

    $this->before( function($expected_value) {

        if( $expected_value ==="" ) {
            $this->actual_comparison = true;

            return;
        }

        $this->actual_comparison =
            strpos( $this->actual_value, $expected_value ) !== false;

    });

    $this->assert_with( function($expected_value) {

        if( $this->actual_comparison ) {
            return;
        }

        $this->raise_failure(
            "Expected {$this->value_string($this->actual_value)} to contain {$this->value_string($expected_value)}."
        );
    });

    $this->negate_with( function($expected_value) {

        if( ! $this->actual_comparison ) {
            return;
        }

        $this->raise_failure(
            "Expected {$this->value_string($this->actual_value)} not to contain {$this->value_string($expected_value)}."
        );
    });
});

Value_Expectations::define_expectation( "match", function() {

    $this->before( function($expected_regexp, $matching_closure = null) {

        $this->matches = [];
        $this->actual_comparison =
            preg_match( $expected_regexp, $this->actual_value, $this->matches ) !== 0;

    });

    $this->assert_with( function($expected_regexp, $matching_closure = null) {

        if( $this->actual_comparison ) {

            if( $matching_closure !== null ) {
                $this->evaluate_closure( $matching_closure, $this->matches );
            }

            return;
        }

        $this->raise_failure(
            "Expected {$this->value_string($this->actual_value)} to match {$this->value_string($expected_regexp)}."
        );

    });

    $this->negate_with( function($expected_regexp) {

        if( ! $this->actual_comparison ) {
            return;
        }

        $this->raise_failure(
            "Expected {$this->value_string($this->actual_value)} not to match {$this->value_string($expected_regexp)}."
        );
    });
});

/// Array expectations

Value_Expectations::define_expectation( "include", function() {

    $this->before( function($expected_value) {

        $this->actual_comparison = in_array( $expected_value, $this->actual_value );

    });

    $this->assert_with( function($expected_regexp, $matching_closure = null) {

        if( $this->actual_comparison ) {
            return;
        }

        $this->raise_failure(
            "Expected array to include {$this->value_string($expected_regexp)}."
        );

    });

    $this->negate_with( function($expected_regexp) {

        if( ! $this->actual_comparison ) {
            return;
        }

        $this->raise_failure(
            "Expected array not to include {$this->value_string($expected_regexp)}."
        );
    });
});

Value_Expectations::define_expectation( "include_all", function() {

    $this->before( function($expected_values) {

        $this->actual_comparison =
            array_diff( $expected_values, $this->actual_value ) == [];

    });

    $this->assert_with( function($expected_values) {

        if( $this->actual_comparison ) {
            return;
        }

        $this->raise_failure(
            "Expected array to include all the expected values."
        );

    });

    $this->negate_with( function($expected_values) {

        if( ! $this->actual_comparison ) {
            return;
        }

        $this->raise_failure(
            "Expected array not to include all the expected values."
        );
    });
});

Value_Expectations::define_expectation( "include_any", function() {

    $this->before( function($expected_values) {

        $this->actual_comparison =
            array_diff( $this->actual_value, $expected_values ) != $this->actual_value;

    });

    $this->assert_with( function($expected_values) {

        if( $this->actual_comparison ) {
            return;
        }

        $this->raise_failure(
            "Expected array to include any of the expected values."
        );

    });

    $this->negate_with( function($expected_values) {

        if( ! $this->actual_comparison ) {
            return;
        }

        $this->raise_failure(
            "Expected array not to include any of the expected values."
        );
    });
});

Value_Expectations::define_expectation( "include_none", function() {

    $this->before( function($expected_values) {

        $this->actual_comparison =
            array_diff( $this->actual_value, $expected_values ) == $this->actual_value;

    });

    $this->assert_with( function($expected_values) {

        if( $this->actual_comparison ) {
            return;
        }

        $this->raise_failure(
            "Expected array to include none of the expected values."
        );

    });

    $this->negate_with( function($expected_values) {

        if( ! $this->actual_comparison ) {
            return;
        }

        $this->raise_failure(
            "Expected array not to include none of the expected values."
        );
    });
});

Value_Expectations::define_expectation( "include_key", function() {

    $this->before( function($expected_key, $value_closure = null) {

        $this->actual_comparison = array_key_exists( $expected_key, $this->actual_value );

    });

    $this->assert_with( function($expected_key, $value_closure = null) {

        if( $this->actual_comparison ) {

            if( $value_closure !== null ) {
                $this->evaluate_closure( $value_closure, $this->actual_value[ $expected_key ] );
            }

            return;
        }

        $this->raise_failure(
            "Expected array to include key {$this->value_string($expected_key)}."
        );

    });

    $this->negate_with( function($expected_key, $value_closure = null) {

        if( ! $this->actual_comparison ) {
            return;
        }

        $this->raise_failure(
            "Expected array not to include key {$this->value_string($expected_key)}."
        );
    });
});

Value_Expectations::define_expectation( "include_value", function() {

    $this->before( function($expected_value) {

        $this->actual_comparison = in_array( $expected_value, $this->actual_value );

    });

    $this->assert_with( function($expected_value) {

        if( $this->actual_comparison ) {
            return;
        }

        $this->raise_failure(
            "Expected array to include value {$this->value_string($expected_value)}."
        );

    });

    $this->negate_with( function($expected_value) {

        if( ! $this->actual_comparison ) {
            return;
        }

        $this->raise_failure(
            "Expected array not to include value {$this->value_string($expected_value)}."
        );
    });
});

/// File expectations

Value_Expectations::define_expectation( "a_file", function() {

    $this->before( function() {

        $this->actual_comparison =
            file_exists( $this->actual_value ) && is_file( $this->actual_value );

    });

    $this->assert_with( function() {

        if( $this->actual_comparison ) {
            return;
        }

        $this->raise_failure(
            "Expected the file {$this->value_string($this->actual_value)} to exist."
        );

    });

    $this->negate_with( function() {

        if( ! $this->actual_comparison ) {
            return;
        }

        $this->raise_failure(
            "Expected the file {$this->value_string($this->actual_value)} not to exist."
        );
    });
});

Value_Expectations::define_expectation( "have_file_contents", function() {

    $this->assert_with( function($contents_closure) {

        if( ! file_exists( $this->actual_value ) ) {

            $this->raise_failure(
                "Expected the file {$this->value_string($this->actual_value)} to have contents, but is does not exist."
            );

        }

        $file_contents = file_get_contents( $this->actual_value );

        if( $file_contents === false ) {

            $this->raise_failure(
                "Expected the file {$this->value_string($this->actual_value)} to have contents, but could not read its contents."
            );

        }

        $this->evaluate_closure( $contents_closure, $file_contents );
    });
});

Value_Expectations::define_expectation( "a_folder", function() {

    $this->before( function() {

        $this->actual_comparison =
            file_exists( $this->actual_value ) && ! is_file( $this->actual_value );

    });

    $this->assert_with( function() {

        if( $this->actual_comparison ) {
            return;
        }

        $this->raise_failure(
            "Expected the folder {$this->value_string($this->actual_value)} to exist."
        );

    });

    $this->negate_with( function() {

        if( ! $this->actual_comparison ) {
            return;
        }

        $this->raise_failure(
            "Expected the folder {$this->value_string($this->actual_value)} not to exist."
        );
    });
});

Value_Expectations::define_expectation( "have_folder_contents", function() {

    $this->assert_with( function($contents_closure) {

        if( ! file_exists( $this->actual_value ) ) {

            $this->raise_failure(
                "Expected the folder {$this->value_string($this->actual_value)} to have contents, but is does not exist."
            );

        }

        if( is_file( $this->actual_value ) ) {

            $this->raise_failure(
                "Expected {$this->value_string($this->actual_value)} to be a folder, but it is a file."
            );

        }

        $files_in_folder = \scandir( $this->actual_value );

        $this->evaluate_closure( $contents_closure, $files_in_folder, $this->actual_value );
    });

});

/// Exception expectations

Value_Expectations::define_expectation( "raise", function() {

    $this->assert_with( function($expected_exception_class_name, $expected_exception_closure = null) {

        $raised_exception = null;

        try {

            $this->evaluate_closure( $this->actual_value );

        } catch( \Exception $e ) {

            $raised_exception = $e;

        }

        if( $raised_exception ===null ) {

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

        if( $expected_exception_closure !==null ) {

            $this->evaluate_closure( $expected_exception_closure, $raised_exception );

        }
    });

    $this->negate_with( function($expected_exception_class_name) {

        $raised_exception_class_name = null;

        try {

            $this->evaluate_closure( $this->actual_value );

        } catch( \Exception $e ) {

            $raised_exception_class_name = get_class( $e );

        }

        if( $raised_exception_class_name ==$expected_exception_class_name ) {

            $this->raise_failure(
                "Expected the closure not to raise a {$expected_exception_class_name}, but a {$raised_exception_class_name} was raised."
            );

        }

    });

});
