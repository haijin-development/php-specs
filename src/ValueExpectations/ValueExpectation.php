<?php
declare(strict_types=1);

namespace Haijin\Specs\ValueExpectations;

use Closure;
use Haijin\Specs\Errors\ExpectationDefinitionError;

/**
 * Defines an expectation on a value.
 *
 * A ValueExpectation has the following parts:
 *      - a before($expectedValue) closure: an optional closure that is evaluated before evaluating the actual assertions.
 *      - an after($expectedValue) closure: an optional closure that is evaluated after evaluating the actual assertions.
 *      - an assertWith($expectedValue) closure: a mandatory closure that is evaluated to assert the expected value.
 *      - an negateWith($expectedValue) closure: an optional closure that is evaluated to negated the expected value
 *          when using the ->not() particle.
 *
 * Within all of these closure the following properties are available:
 *      - $this->actualValue The actual value being validated.
 *      - $this->storedParams An array of params stored by the particles previous to the expectation definition.
 *
 * Within all of these closure the following properties are available:
 *      - $this->raiseFailure($failureMessage) Raises an expectation failure with the given message. Call this method
 *          when the expectation on the actual value is not met.
 *      - $this->valueString($value) Returns a string representation of the $value. Use this method to represent values
 *          on the failed expectation messages.
 *      - $this->evaluateClosure($closure, ...$params) Some of the expectation parameters or expected values may be
 *          closures, for instance when expecting a piece of code to raise an Exception. To evaluate these closures
 *          use the method $this->evaluateClosure($closure, ...$params). This method is necessary to correctly bind
 *          the $this variable to the proper evaluation context during the expectation.
 *
 * @package Haijin\Specs\ValueExpectations
 */
class ValueExpectation
{
    /**
     * @var The name of the expectation
     */
    public $expectationName;
    /**
     * @var Closure|null An optional closure to evaluate before the evaluation of the assertionClosure or
     * negationClosure. Useful to implement shared behaviour among assertion and negation closures.
     */
    public $beforeClosure;
    /**
     * @var Closure The closure that makes the assertions on the actual value.
     */
    public $assertionClosure;
    /**
     * @var Closure|null An optional closure that is evaluated to negated the expected value when using the ->not()
     * particle.
     */
    public $negationClosure;
    /**
     * @var Closure|null An optional closure to evaluate after the evaluation of the assertionClosure or
     * negationClosure. Useful to implement shared behaviour among assertion and negation closures.
     */
    public $afterClosure;

    /**
     * ValueExpectationDefinition constructor.
     *
     * @param string $expectationName The name of the expectation.
     */
    public function __construct(string $expectationName)
    {
        $this->expectationName = $expectationName;
        $this->beforeClosure = null;
        $this->assertionClosure = null;
        $this->negationClosure = null;
        $this->afterClosure = null;
    }

    /// DSL

    /**
     * Evaluates the given Closure to configure this ValueExpectationDefinition.
     *
     * @param Closure $closure The definition closure.
     */
    public function define(Closure $closure): void
    {
        $closure->call($this);
    }

    /**
     * Sets a before() closure to evaluate before the evaluation of the assertion or negation closures.
     *
     * @param Closure $closure The before Closure.
     */
    public function before(Closure $closure): void
    {
        $this->beforeClosure = $closure;
    }

    /**
     * Sets the assertion closure. The assertion closure has the code that performs the assertions on the actual and
     * the expected values.
     *
     * @param Closure $closure
     */
    public function assertWith(Closure $closure): void
    {
        $this->assertionClosure = $closure;
    }

    /**
     * Sets the negation closure. The negation closure has the code that performs the assertions on the actual and
     * the not expected values.
     *
     * @param Closure $closure
     */
    public function negateWith(Closure $closure): void
    {
        $this->negationClosure = $closure;
    }

    /**
     * Sets an after() closure to evaluate after the evaluation of the assertion or negation closures.
     *
     * @param Closure $closure The after Closure.
     */
    public function after(Closure $closure): void
    {
        $this->afterClosure = $closure;
    }

    /// Evaluating

    /**
     * Evaluates this ValueExpectation in the context of the given $evaluationContext and with
     * the given $params. If $negated is true evaluates the negationClosure, if false evaluates the assertionClosure.
     * In all the closure evaluations the variables $this is bound to the given $evaluationContext.
     *
     * @param ValueExpectationEvaluationContext $evaluationContext The object bound to the variable $this during
     *  the evaluation of the expectation closures.
     * @param array $params The params passed along to all the expectation closures.
     * @param bool $negated If true evaluates the negationClosure, if false evaluates the assertionClosure.
     */
    public function evaluateOn(ValueExpectationEvaluationContext $evaluationContext, array $params, bool $negated): void
    {
        if ($this->beforeClosure !== null ) {
            $this->beforeClosure->call($evaluationContext, ...$params);
        }

        try {
            if($negated) {
                if ($this->negationClosure === null) {
                    $this->raiseMissingNegationClosureError();
                }

                $this->negationClosure->call($evaluationContext, ...$params);
            } else {
                if ($this->assertionClosure === null) {
                    $this->raiseMissingAssertionClosureError();
                }

                $this->assertionClosure->call($evaluationContext, ...$params);
            }
        } finally {
            if ($this->afterClosure !== null ) {
                $this->afterClosure->call($evaluationContext, ...$params);
            }
        }
    }

    /// Raising errors

    protected function raiseMissingAssertionClosureError(): void
    {
        throw new ExpectationDefinitionError(
            "Expectation definition '{$this->expectationName}' is missing the 'assertWith()' closure."
        );
    }

    protected function raiseMissingNegationClosureError(): void
    {
        throw new ExpectationDefinitionError(
            "Expectation definition '{$this->expectationName}' is missing the 'negateWith()' closure."
        );
    }
}