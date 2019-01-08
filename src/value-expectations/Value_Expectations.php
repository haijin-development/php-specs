<?php

namespace Haijin\Specs;

class Value_Expectations
{
    static protected $expectation_definitions = [];
    static protected $particle_definitions = [];

    /// Expectations

    static public function define_particle($method_name, $closure)
    {
        self::$particle_definitions[ $method_name ] = $closure;
    }

    static public function particle_at($particle_name)
    {
        if( ! array_key_exists( $particle_name, self::$particle_definitions ) ) {
            return null;
        }

        return self::$particle_definitions[ $particle_name ];
    }

    /// Expectations

    static public function define_expectation($method_name, $closure)
    {
        $definition = new Value_Expectation_Definition($method_name);
        $definition->define( $closure );

        self::$expectation_definitions[ $method_name ] = $definition;
    }

    static public function expectation_at($expectation_name)
    {
        if( ! array_key_exists( $expectation_name, self::$expectation_definitions ) ) {
            self::raise_missing_expectation_definition_error( $expectation_name );
        }

        return self::$expectation_definitions[ $expectation_name ];
    }

    static public function raise_missing_expectation_definition_error($expectation_name)
    {
        throw new Expectation_Definition_Error(
            "The expectation '->{$expectation_name}(...)' is not defined."
        );
    }
}

require_once __DIR__ . "/value-expectation-definitions.php";