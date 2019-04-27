<?php
declare(strict_types=1);

namespace Haijin\Specs\ValueExpectations;

/**
 * This object dynamically builds a ValueExpectation using the specs DSL.
 *
 * In the Specs DSL an expectation is declared as this:
 *
 *          $this->expect( $value ) ->to() ->be() ->string();
 *
 * When the spec receives the first message
 *
 *          $this->expect( $value )
 *
 * creates and returns an instance of this class.
 * From there on all the messages are received by that instance using the _call() mechanism and the expectation is
 * built part by part.
 *
 * If this object receives a particle message
 *
 *          ->to()
 *
 * evaluates the particle closure and keeps building the expectation.
 *
 * If it receives an expectation message
 *
 *          ->string();
 *
 * ends the building, creates a ValueExpectationEvaluationContext and evaluates
 * the expectation on that context.
 *
 * @package Haijin\Specs\ValueExpectations
 */
class ValueExpectationBuilder
{
    /// Instance methods

    /**
     * @var Spec The Spec where the expectation was declared. This Spec is kept by this ValueExpectationBuilder
     * to evaluate closures passed as parameters in the context of the Spec and not the
     * ValueExpectationEvaluationContext.
     */
    protected $specBinding;
    /**
     * @var string The full description of the Spec where the expectation was declared.
     */
    protected $description;
    /**
     * @var mixed The value on which to perform the expectations.
     */
    protected $value;
    /**
     * @var bool True if the expectation is negated with the particle ->not(). In that case the negationClosure is
     *  used instead of the assertionClosure.
     */
    protected $negated;
    /**
     * @var array An associative array of expectation parameters populated by the intermediate particles and used
     *  in the final expectation.
     */
    protected $storedParams;

    /// Accessors

    /**
     * ValueExpectationBuilder constructor.
     *
     * @param $specBinding The Spec where the expectation was declared. This Spec is kept by this ValueExpectationBuilder
     * to evaluate closures passed as parameters in the context of the Spec and not the
     * ValueExpectationEvaluationContext.
     * @param $description The full description of the Spec where the expectation was declared.
     * @param $value The value on which to perform the expectations.
     */
    public function __construct($specBinding, string $description, $value)
    {
        $this->specBinding = $specBinding;
        $this->description = $description;
        $this->value = $value;
        $this->negated = false;
        $this->storedParams = [];
    }

    ///  DSL

    /**
     * Decorative particle that returns $this object.
     * It just improves the readability of the expectation.
     *
     * @return ValueExpectationBuilder $this Returns this object to allow further configuration on the expectation.
     */
    public function to(): ValueExpectationBuilder
    {
        return $this;
    }

    /**
     * Configures the expectation to use the negationClosure instead of the assertionClosure.
     * Example:
     *
     *      $this->>expect( $value ) ->not() ->to() ->equal(1);
     *
     * @return ValueExpectationBuilder $this Returns this object to allow further configuration on the expectation.
     */
    public function not(): ValueExpectationBuilder
    {
        $this->negated = true;

        return $this;
    }

    /**
     * Stores an expectation parameter to pass it along to the final expectation context.
     * This method is used from within the evaluation of a particle closure.
     * For instance in the expression
     *
     *      $this->>expect($value) ->to() ->be('>') ->than(1);
     *
     * the particle
     *
     *      ->be('>')
     *
     *  is defined as
     *
     *      ValueExpectationsLibrary::defineParticle('be', function ($operator = null) {
     *          $this->storeParamAt('operator', $operator);
     *      });
     *
     *  The particle stores the operator string used ('>') and later the expectation ->than() reads it to perform
     *  the proper assertion on the value:
     *
     *      ValueExpectationsLibrary::defineExpectation('than', function () {
     *          $this->assertWith(function ($expectedValue) {
     *              // This parameter is the one set by the particle ->be('>')
     *              $operator = $this->storedParams['operator'];
     *
     *              switch ($this->operator) {
     *                  // ... etc
     *
     *                  case '>':
     *                      if($this->actualValue > $expectedValue) {
     *                          return:
     *                      }
     *                  break;
     *
     *                  // ... etc
     *              }
     *
     *              $this->raiseFailure(
     *                  "Expected value {$this->valueString($this->actualValue)} to be {$operator} than {$this->valueString($expectedValue)}."
     *              );
     *          });
     *      });
     *
     * @param string $paramName The name of the parameter stored.
     * @param mixed $value The parameter value.
     */
    public function storeParamAt(string $paramName, $value): void
    {
        $this->storedParams[$paramName] = $value;
    }

    /**
     * Looks for a particle or an expectation defined in the ValueExpectationsLibrary with the given $methodName and
     * evaluates it.
     * Raises an error if no expectation is found under that $methodName.
     *
     * @param string $methodName The name of the particle or expectation to search in the ValueExpectationsLibrary for.
     * @param array $params The parameters received by the particle or expectation.
     * @return ValueExpectationBuilder Returns
     */
    public function __call(string $methodName, array $params): ValueExpectationBuilder
    {
        $particleClosure = ValueExpectationsLibrary::particleAt($methodName);

        if ($particleClosure !== null) {

            $particleClosure->call($this, ...$params);

            return $this;
        }

        $evaluationContext = new ValueExpectationEvaluationContext(
            $this->specBinding,
            $this->description,
            $this->value,
            $this->storedParams
        );

        $valueExpectation = ValueExpectationsLibrary::expectationAt($methodName);

        $valueExpectation->evaluateOn($evaluationContext, $params, $this->negated);

        return $this;
    }
}