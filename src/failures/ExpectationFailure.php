<?php

namespace Haijin\Specs;

class ExpectationFailure
{
    protected $description;
    protected $message;
    protected $stack_trace;

    /// Initializing

    public function __construct($description, $message, $stack_trace)
    {
        $this->description = $description;
        $this->message = $message;
        $this->stack_trace = $stack_trace;
    }

    /// Accessing

    public function get_description()
    {
        return $this->description;
    }

    public function get_message()
    {
        return $this->message;
    }

    public function get_stack_trace()
    {
        return $this->stack_trace;
    }

    public function get_file_name()
    {
        $stack_frame = $this->find_source_stack_frame();

        return $stack_frame[ "file" ];
    }

    public function get_line()
    {
        $stack_frame = $this->find_source_stack_frame();

        return $stack_frame[ "line" ];
    }

    public function find_source_stack_frame()
    {
        foreach( $this->stack_trace as $i => $stack_frame) {
            if( $stack_frame[ "function" ] == "evaluate_expectation_definition_with"
                && 
                $stack_frame[ "class" ] == "Haijin\Specs\ValueExpectation"
            ) 
            {
                return $this->stack_trace[ $i + 1 ];
            }
        }

        return null;
    }
}