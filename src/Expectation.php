<?php

namespace Haijin\Specs;

class Expectation
{
    static public function on($value)
    {
        return new self( $value );
    }

    protected $value;
    protected $expected_value;
    protected $negated;

    /// Accessors

    public function __construct($value)
    {
        return $this->value = $value;
        return $this->expected_value = null;
        return $this->negated = false;
    }

    ///  DSL

    public function to()
    {
        return $this;
    }

    public function not()
    {
        $this->negated = true;

        return $this;
    }

    public function equal($expected_value)
    {
        $this->expected_value = $expected_value;

        if( $this->negated ) {

            if( $this->expected_value  != $this->value ) {
                return;
            }

            return $this->raise_expectation_error();            

        } else {

            if( $this->expected_value  == $this->value ) {
                return;
            }

            return $this->raise_expectation_error();            

        }
    }

    public function raise_expectation_error()
    {
        throw new ExpectationError();
    }
}