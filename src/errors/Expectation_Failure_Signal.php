<?php

namespace Haijin\Specs;

class Expectation_Failure_Signal extends \RuntimeException
{
    protected $description;

    public function __construct($message, $description)
    {
        parent::__construct( $message );

        $this->description = $description;
    }

    public function get_message()
    {
        return $this->getMessage();
    }

    public function get_description()
    {
        return $this->description;
    }

    public function get_trace()
    {
        return $this->getTrace();
    }
}