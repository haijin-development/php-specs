<?php

namespace Haijin\Specs;

class Expectation_Definition_Error extends \Exception
{
    public function get_message()
    {
        return $this->getMessage();
    }
}