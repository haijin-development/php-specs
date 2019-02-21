<?php

namespace Haijin\Specs;

class Invalid_Expectation
{
    protected $description;
    protected $message;
    protected $spec_file_name;
    protected $spec_line_number;
    protected $stack_trace;

    /// Initializing

    public function __construct($description, $message, $spec_file_name, $spec_line_number, $stack_trace)
    {
        $this->description = $description;
        $this->message = $message;
        $this->spec_file_name = $spec_file_name;
        $this->spec_line_number = $spec_line_number;
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

    public function get_spec_file_name()
    {
        return $this->spec_file_name;
    }

    public function get_spec_line_number()
    {
        return $this->spec_line_number;
    }

    public function get_stack_trace()
    {
        return $this->stack_trace;
    }

    public function get_expectation_line()
    {
        $stack_frame = $this->find_source_stack_frame();

        return $stack_frame[ "line" ];
    }

    public function find_source_stack_frame()
    {
        foreach( $this->stack_trace as $i => $stack_frame) {
            if( $stack_frame[ "function" ] == "evaluate_expectation_definition_with"
                && 
                $stack_frame[ "class" ] == "Haijin\Specs\Value_Expectation"
            ) 
            {
                return $this->stack_trace[ $i + 1 ];
            }
        }

        return null;
    }
}