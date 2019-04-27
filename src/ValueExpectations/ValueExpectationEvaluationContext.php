<?php
declare(strict_types=1);

namespace Haijin\Specs\ValueExpectations;

use Haijin\Specs\Errors\ExpectationFailureSignal;
use Haijin\Specs\Tools\ValuePrinter;
use Closure;

/**
 * The evaluation context of a ValueExpectation closures.
 * All the closures of a ValueExpectation are evaluated binding its variable $this to an instance of
 * ValueExpectationEvaluationContext.
 *
 * @package Haijin\Specs\ValueExpectations
 */
class ValueExpectationEvaluationContext
{
    protected $specBinding;
    protected $description;
    protected $actualValue;
    protected $storedParams;

    public function __construct($specBinding, string $description, $actualValue, array $storedParams)
    {
        $this->specBinding = $specBinding;
        $this->description = $description;
        $this->actualValue = $actualValue;
        $this->storedParams = $storedParams;
    }

    /**
     * Returns the value to run expectations on.
     *
     * @return mixed The value to run expectations on.
     */
    public function getActualValue()
    {
        return $this->actualValue;
    }

    /**
     * Returns the value of a parameter named $paramName, stored during the evaluation of a particle.
     *
     * @param string $paramName The name of the parameter to retrieve.
     *
     * @return mixed The value of the param stored at $paramName during during the evaluation of a particle.
     */
    public function getStoredParamAt(string $paramName)
    {
        if (!isset($this->storedParams[$paramName])) {
            return null;
        }

        return $this->storedParams[$paramName];
    }

    /**
     * Raises an ExpectationFailureSignal.
     * Use this method from a ValueExpectation closure to signal an expectation failure on the actual value.
     *
     * @param string $failureMessage The failure message.
     */
    public function raiseFailure(string $failureMessage): void
    {
        throw new ExpectationFailureSignal($failureMessage, $this->description);
    }

    /**
     * Returns a string representation of the given $value.
     * Use this method from a ValueExpectation closure to get a printable string representation of a value, array or
     * object.
     *
     * @param mixed $value The value, array of object to get its print string.
     * @return string The string representation of the given $value.
     *
     */
    public function valueString($value): string
    {
        return ValuePrinter::printStringOf($value);
    }

    /**
     * Evaluates the given $closure in the context of the original Spec, allowing to evaluate additional expectations.
     * Returns the result of the evaluation of the closure.
     *
     * ValueExpectation closures are evaluated in an isolated ValueExpectationEvaluationContext but in some cases a
     * parameter of the expectations needs to be evaluated on the context of the original Spec object to perform
     * additional expectations.
     *
     * @param Closure $closure The closure to evaluate in the context of the original Spec.
     * @param mixed ...$params The parameters to pass along to the closure evaluation.
     * @return mixed The result of the evaluation.
     */
    public function evaluateClosure(Closure $closure, ...$params)
    {
        return $closure->call($this->specBinding, ...$params);
    }
}