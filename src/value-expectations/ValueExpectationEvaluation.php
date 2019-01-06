<?php

namespace Haijin\Specs;

class ValueExpectationEvaluation
{
    protected $description;
    public $actual_value;

    public function __construct($description, $actual_value)
    {
        $this->description = $description;
        $this->actual_value = $actual_value;
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

        return (string) $value;
    }
}