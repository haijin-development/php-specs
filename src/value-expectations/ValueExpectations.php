<?php

namespace Haijin\Specs;

class ValueExpectations
{
    static protected $expectation_definitions = [];

    /// Class methods

    static public function define_expectation($method_name, $closure)
    {
        $definition = new ValueExpectationDefinition($method_name);
        $definition->define( $closure );

        self::$expectation_definitions[ $method_name ] = $definition;
    }

    static public function definition_at($expectation_name)
    {
        if( ! array_key_exists( $expectation_name, self::$expectation_definitions ) ) {
            self::raise_missing_expectation_definition_error( $expectation_name );
        }

        return self::$expectation_definitions[ $expectation_name ];
    }

    static public function raise_missing_expectation_definition_error($expectation_name)
    {
        throw new ExpectationDefinitionError(
            "The expectation '->{$expectation_name}(...)' is not defined."
        );
    }
}

require_once __DIR__ . "/value-expectation-definitions.php";