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

    public function raise_error($error_message)
    {
        throw new ExpectationFailureSignal( $error_message, $this->description );
    }
}