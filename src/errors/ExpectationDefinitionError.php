<?php

namespace Haijin\Specs;

class ExpectationDefinitionError extends \Exception
{
    public function get_message()
    {
        return $this->getMessage();
    }
}