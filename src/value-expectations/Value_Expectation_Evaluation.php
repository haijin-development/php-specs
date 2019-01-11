<?php

namespace Haijin\Specs;

class Value_Expectation_Evaluation
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
        throw new Expectation_Failure_Signal( $failure_message, $this->description );
    }

    public function value_string($value)
    {
        return Value_Printer::print_string_of( $value );
    }

    public function evaluate_closure($closure, ...$params)
    {
        return $closure->call( $this->spec_binding, ...$params );
    }
}