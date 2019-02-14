<?php

namespace Haijin\Specs;

class Expectation_Definition_Error extends \RuntimeException
{
    public function get_message()
    {
        return $this->getMessage();
    }
}