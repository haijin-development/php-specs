<?php

namespace Haijin\Specs;

class ExpectationError extends \Exception
{
    public function get_message()
    {
        return $this->getMessage();
    }
}