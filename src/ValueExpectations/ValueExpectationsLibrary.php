<?php
declare(strict_types=1);

namespace Haijin\Specs\ValueExpectations;

use Haijin\Specs\Errors\ExpectationDefinitionError;

/**
 * This static class is a global library of the defined expectations that can be used in Specs to make assertions
 * on the code.
 * A Spec assertion looks like this:
 *
 *      $this->expect( $value ) ->to() ->equal( 1 );
 *
 * The ->equal( 1 ) part is called the expectation on the value and is defined with this class defineExpectation.
 * Example:
 *
 *      ValueExpectationsLibrary::defineExpectation('equal', function () {
 *          $this->before(function ($expectedValue) {
 *              $this->actualComparison = $expectedValue == $this->actualValue;
 *          });
 *
 *          $this->assertWith(function ($expectedValue) {
 *
 *              if ($this->actualComparison) {
 *                  return;
 *              }
 *
 *              $this->raiseFailure(
 *                  "Expected value to equal {$this->valueString($expectedValue)}, got {$this->valueString($this->actualValue)}."
 *              );
 *          });
 *
 *          $this->negateWith(function ($expectedValue) {
 *
 *            if (!$this->actualComparison) {
 *                  return;
 *              }
 *
 *              $this->raiseFailure(
 *                  "Expected value not to equal {$this->valueString($expectedValue)}, got {$this->valueString($this->actualValue)}."
 *              );
 *          });
 *      });
 *
 * The intermediate ->to() part is called a particle. A particle can store information to be passed along to the
 * expectation.
 * Example:
 *          // The particle be()
 *          //    $this->expect($value) ->to() ->be('>') ->than(1);
 *          // stores the operator '>' and passes it along to the expectation:
 *
 *          ValueExpectationsLibrary::defineParticle('be', function ($operator = null) {
 *              $this->storeParamAt('operator', $operator);
 *          });
 *
 * @package Haijin\Specs\ValueExpectations
 */
class ValueExpectationsLibrary
{
    /**
     * @var array The defined particles in this library.
     */
    static protected $particleDefinitions = [];
    /**
     * @var array The defined ValueExpectationDefinition in this library.
     */
    static protected $expectationDefinitions = [];

    /// Particles

    /**
     * Defines a particle.
     * A particle accepts optional parameters.
     * These parameters can be stored to make them available to the final expectation with
     *      $this->storeParamAt($paramName, $paramValue);
     *
     * Example:
     *          ValueExpectationsLibrary::defineParticle('be', function ($operator = null) {
     *              $this->storeParamAt('operator', $operator);
     *          });
     *
     * @param string $methodName The particle name. For instance 'be'.
     * @param \Closure $closure The closure that defines the particle. It has the following signature:
     *  function ($optionalParameters) {}.
     */
    static public function defineParticle(string $methodName, \Closure $closure): void
    {
        self::$particleDefinitions[$methodName] = $closure;
    }

    /**
     * Returns the particle named $particleName or null if the particle was not defined.
     *
     * @param $methodName The particle name to look for.
     * @return \Closure|null The particle closure or null if it is not defined.
     */
    static public function particleAt(string $methodName): ?\Closure
    {
        if (!array_key_exists($methodName, self::$particleDefinitions)) {
            return null;
        }

        return self::$particleDefinitions[$methodName];
    }

    /// Expectations

    /**
     * Creates a ValueExpectationDefinition and stores it in the $expectationDefinitions array.
     *
     * @param string $methodName The name of the expectation method. For instance 'equals'.
     * @param \Closure $closure The definition closure. Example of a definition closure:
     *
     *      ValueExpectationsLibrary::defineExpectation('equal', function () {
     *          $this->before(function ($expectedValue) {
     *              ...
     *          });
     *
     *          $this->after(function ($expectedValue) {
     *              ...
     *          });

     *          $this->assertWith(function ($expectedValue) {
     *              ...
     *          });
     *
     *          $this->negateWith(function ($expectedValue) {
     *              ...
     *          });
     *      });
     *
     *  See ValueExpectationDefinition for more details.
     */
    static public function defineExpectation(string $methodName, \Closure $closure): void
    {
        $definition = new ValueExpectation($methodName);
        $definition->define($closure);

        self::$expectationDefinitions[$methodName] = $definition;
    }

    /**
     * Returns the ValueExpectationDefinition named $expectationName or raises an ExpectationDefinitionError
     * if it is not defined.
     * @param $expectationName The name of the ValueExpectationDefinition to look for.
     * @return ValueExpectation|null The ValueExpectationDefinition at the searched name.
     */
    static public function expectationAt(string $expectationName): ?ValueExpectation
    {
        if (!array_key_exists($expectationName, self::$expectationDefinitions)) {
            self::raiseMissingExpectationDefinitionError($expectationName);
        }

        return self::$expectationDefinitions[$expectationName];
    }

    /**
     * Raises an ExpectationDefinitionError stating that the expectation named $expectationName is missing.
     *
     * @param string $expectationName The name of the missing ValueExpectationDefinition
     */
    static public function raiseMissingExpectationDefinitionError(string $expectationName): void
    {
        throw new ExpectationDefinitionError(
            "The expectation '->$expectationName(...)' is not defined."
        );
    }
}

// Load the built-in expectations.
require_once __DIR__ . '/valueExpectationDefinitions.php';