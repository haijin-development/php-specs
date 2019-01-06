<?php

namespace Haijin\Specs;

class ValueExpectationEvaluation
{
    protected $spec_binding;
    protected $description;
    public $actual_value;

    public function __construct($spec_binding, $description, $actual_value)
    {
        $this->spec_binding = $spec_binding;
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

    public function evaluate_closure($closure, ...$params)
    {
        return $closure->call( $this->spec_binding, ...$params );
    }
}