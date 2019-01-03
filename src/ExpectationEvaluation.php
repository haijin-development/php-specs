<?php

namespace Haijin\Specs;

class ExpectationEvaluation
{
    public $actual_value;

    public function __construct($actual_value)
    {
        $this->actual_value = $actual_value;
    }

    public function raise_error($error_message)
    {
        throw new ExpectationError( $error_message );
    }
}