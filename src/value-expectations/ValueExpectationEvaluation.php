<?php

namespace Haijin\Specs;

class ValueExpectationEvaluation
{
    protected $spec_binding;
    protected $description;
    protected $actual_value;
    protected $stored_params;

    public function __construct($spec_binding, $description, $actual_value, $stored_params)
    {
        $this->spec_binding = $spec_binding;
        $this->description = $description;
        $this->actual_value = $actual_value;
        $this->stored_params = $stored_params;
    }

    public function raise_failure($failure_message)
    {
        throw new ExpectationFailureSignal( $failure_message, $this->description );
    }

    public function value_string($value)
    {
        if( is_string( $value ) ) {
            return "\"" . (string) $value . "\"";
        }

        if( $value === true ) {
            return "true";
        }

        if( $value === false ) {
            return "false";
        }

        return (string) $value;
    }

    public function evaluate_closure($closure, ...$params)
    {
        return $closure->call( $this->spec_binding, ...$params );
    }
}