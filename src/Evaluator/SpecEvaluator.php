<?php
declare(strict_types=1);

namespace Haijin\Specs\Evaluator;

use Closure;
use Exception;
use Haijin\Specs\Errors\ExpectationFailureSignal;
use Haijin\Specs\Errors\UndefinedMethodError;
use Haijin\Specs\Errors\UndefinedPropertyError;
use Haijin\Specs\Failures\ExpectationError;
use Haijin\Specs\Failures\ExpectationFailure;
use Haijin\Specs\Specs\Spec;
use Haijin\Specs\Specs\SpecBase;
use Haijin\Specs\Specs\SpecDescription;
use Haijin\Specs\ValueExpectations\ValueExpectationBuilder;
use Haijin\Specs\Specs\SpecContextDefinitions;

/**
 * This object recursively evaluates a collection of SpecDescriptions.
 * The evaluation includes the evaluation of the before and after blocks of each Spec and the generation of the run
 * Specs statistics.
 *
 * Implementation note: this class uses the __call() mechanism to dynamically invoke functions defined in Specs and
 * SpecDescriptions. In order to avoid functions name clashes with user defined methods all the class methods begin
 * with the "___" prefix.
 *
 * @package Haijin\Specs\Runners
 */
class SpecEvaluator
{
    /**
     * @var SpecsStatistics Object to collect statistics on the run specs. This SpecStatistics keeps track of the
     *  total specs run, the ones that succeeded, the ones that failed, the failed expectations, etc.
     */
    protected $___statistics;
    /**
     * @var SpecsEvaluationContext The context to of the execution of a Spec. Keeps track of the before and after
     *  closures, the custom variables and methods defined, etc. This SpecEvaluator keeps the context updated when
     *  it evaluates a Spec and its children.
     */
    protected $___evaluationContext;
    /**
     * @var Closure A closure to call after the evaluation of each single Spec. Can be used by the owner of the
     *  closure to give feedback or keep track of the specs run.
     */
    protected $___onSpecRunClosure;

    /// Initializing

    /**
     * SpecEvaluator constructor.
     */
    public function __construct()
    {
        $this->___onSpecRunClosure = null;

        $this->___reset();
    }

    /**
     * Resets the context to a clean state, dropping all the previous context.
     * Call this function to run more than one __runAll executions reusing this SpecEvaluator.
     */
    public function ___reset(): void
    {
        $this->___statistics = new SpecsStatistics();
        $this->___evaluationContext = new SpecsEvaluationContext();
    }

    /// Accessing

    /**
     * Configure this SpecsEvaluator before running the Specs.
     *
     * @param SpecsGlobalConfigurationDSL $specsInitialConfiguration
     */
    public function ___configureFrom(SpecContextDefinitions $specsInitialConfiguration): void
    {
        $this->___evaluationContext->addProperties($specsInitialConfiguration->getProperties());

        $this->___evaluationContext->addMethods($specsInitialConfiguration->getMethods());

        $this->___evaluationContext->setBeforeAllClosure($specsInitialConfiguration->getBeforeAllClosure());

        $this->___evaluationContext->appendBeforeEachClosure($specsInitialConfiguration->getBeforeEachClosure());

        $this->___evaluationContext->prependAfterEachClosure($specsInitialConfiguration->getAfterEachClosure());

        $this->___evaluationContext->setAfterAllClosure($specsInitialConfiguration->getAfterAllClosure());
    }

    /**
     * Returns the SpecsStatistics collected during the executions of Specs.
     * To reset the collected SpecsStatistics between consecutive calls to __runAll() call
     * __reset().
     *
     * @return SpecsStatistics The SpecsStatistics collected during the executions of Specs.
     */
    public function ___getStatistics(): SpecsStatistics
    {
        return $this->___statistics;
    }

    /**
     * Returns the InvalidExpectations collected during the executions of Specs.
     * To reset the collected InvalidExpectations between consecutive calls to __runAll() call
     * __reset().
     *
     * @return array The InvalidExpectations collected during the execution of Specs.
     */
    public function ___getInvalidExpectations(): array
    {
        return $this->___statistics->getInvalidExpectations();
    }

    /// Running

    /**
     * Sets a callable to invoke after each Spec run.
     * This callable can be used to give feedback on the status of the execution of each Spec.
     *
     * @param $closure The callable signature is $callable(SpecEvaluator, Spec, $status).
     */
    public function ___setOnSpecRunCallable($callable): void
    {
        $this->___onSpecRunClosure = $callable;
    }

    /**
     * Runs each Spec in the specsCollection.
     * Before running each Spec invokes the before and after closures in this order:
     *      - beforeAll()
     *      - beforeEach()
     *      - run each Spec
     *      - onSpecRun()
     *      - afterEach()
     *      - afterAll()
     *
     * @param array $specsCollection The collection of Specs to run.
     */
    public function ___runAll(array $specsCollection): void
    {
        if ($this->___evaluationContext->getBeforeAllClosure() !== null) {
            $this->___evaluationContext->getBeforeAllClosure()->call($this);
        }

        $this->___evaluateAll($specsCollection);

        if ($this->___evaluationContext->getAfterAllClosure() !== null) {
            $this->___evaluationContext->getAfterAllClosure()->call($this);
        }
    }

    /// Evaluating

    /**
     * Evaluate each Spec without calling the before and after closures for the current Spec.
     *
     * @param array $specsCollection The collection of Specs to evaluate.
     */
    public function ___evaluateAll(array $specsCollection): void
    {
        foreach ($specsCollection as $spec) {
            $this->___evaluate($spec);
        }
    }

    /**
     * Evaluates the Spec or SpecDescription preserving the state of the current context.
     * Before evaluating the the Spec or SpecDescription the current context is saved and it is restored after
     * the evaluation.
     *
     * Implementation note: uses double dispatch to evaluate the Spec or SpecDescription.
     * See ___evaluateSpecDescription() and ___evaluateSpec() for the actual evaluations of each type.
     *
     * @param SpecBase $spec The Spec or SpecDescription to evaluate.
     */
    protected function ___evaluate(SpecBase $spec): void
    {
        $previousEvaluationContext = $this->___evaluationContext;

        $this->___evaluationContext = clone $this->___evaluationContext;

        $this->___evaluationContext->setBeforeAllClosure(null);
        $this->___evaluationContext->setAfterAllClosure(null);

        try {

            $this->___evaluationContext->appendSpecDescription($spec->getDescription());
            $this->___evaluationContext->skipEvaluations($spec->isSkipping());

            $spec->evaluateWith($this);

        } catch (ExpectationFailureSignal $failureSignal) {

            $this->___onSpecFailure($spec, $failureSignal);

        } catch (Exception $e) {

            $this->___onSpecError($spec, $e);

        } finally {

            $this->___unsetScopedVariables($previousEvaluationContext->scopeVariables);

            $this->___evaluationContext = $previousEvaluationContext;

        }
    }

    /**
     * Creates an InvalidExpectation from the failed Spec and adds it to the collected statistics. Also calls
     * the onSpecRunClosure(), if present, with the 'failed' status.
     *
     * @param Spec $spec The Spec that failed some expectation.
     * @param ExpectationFailureSignal $failureSignal The thrown ExpectationFailureSignal holding the information
     *  of the failed expectation.
     */
    protected function ___onSpecFailure(Spec $spec, ExpectationFailureSignal $failureSignal): void
    {
        $invalidExpectation = new ExpectationFailure(
            $this->___evaluationContext->getSpecDescription(),
            $failureSignal->getMessage(),
            $spec->getFileName(),
            $spec->getLineNumber(),
            $failureSignal->getTrace()
        );

        $this->___statistics->addInvalidExpectation($invalidExpectation);

        if ($this->___onSpecRunClosure !== null) {
            ($this->___onSpecRunClosure)($spec, 'failed');
        }
    }

    /**
     * Creates an ExpectationError from the unexpected error thrown during the execution of a Spec
     * and adds it to collected statistics. Also calls the onSpecRunClosure(), if present, with the 'error'
     * status.
     *
     * @param Spec $spec The Spec that threw an unexpected Exception during its execution.
     * @param Exception $error The unexpected Exception thrown during the Spec execution.
     */
    protected function ___onSpecError(Spec $spec, Exception $error): void
    {
        $invalidExpectation = new ExpectationError(
            $this->___evaluationContext->getSpecDescription(),
            $error->getMessage(),
            $spec->getFileName(),
            $spec->getLineNumber(),
            $error->getTrace()
        );

        $this->___statistics->addInvalidExpectation($invalidExpectation);

        if ($this->___onSpecRunClosure !== null) {
            ($this->___onSpecRunClosure)($spec, 'error');
        }
    }

    /**
     * This function compares the variables defined in the current scope against the variables defined in the
     * previous scope and unsets the ones defines only in the current Spec or SpecDescription.
     * Call this function after the evaluation of a Spec or SpecDescription to clean up the variables defined with
     * let() within the scope of that Spec or SpecDescription.
     *
     * @param array $previousScope The scope previous to the evaluation of a Spec or SpecBase.
     */
    protected function ___unsetScopedVariables(array $previousScope): void
    {
        foreach (array_diff($this->___evaluationContext->scopeVariables, $previousScope) as $instVarName) {
            unset($this->$instVarName);
        }
    }

    /**
     * Private - Evaluates the SpecDescription.
     * The evaluation performs the following actions:
     *      - evaluate the SpecDescription beforeAll() callable if present
     *      - collect the beforeEach() callable, if present, for the further evaluation of each of its nested Specs
     *      - collect the afterEach() callable, if present, for the further evaluation of each of its nested Specs
     *      - evaluate each of its nested Specs and SpecDescription
     *      - evaluate the SpecDescription afterAll() callable if present
     *
     * @param SpecDescription $specDescription The SpecDescription to evaluate.
     */
    public function ___evaluateSpecDescription(SpecDescription $specDescription): void
    {
        $this->___evaluationContext->addProperties($specDescription->getProperties());

        $this->___evaluationContext->addMethods($specDescription->getMethods());

        $this->___evaluationContext->setBeforeAllClosure($specDescription->getBeforeAllClosure());

        $this->___evaluationContext->appendBeforeEachClosure($specDescription->getBeforeEachClosure());

        $this->___evaluationContext->prependAfterEachClosure($specDescription->getAfterEachClosure());

        $this->___evaluationContext->setAfterAllClosure($specDescription->getAfterAllClosure());

        if ($this->___evaluationContext->evaluatesBeforeAllClosure()) {
            $this->___evaluationContext->getBeforeAllClosure()->call($this);
        }

        try {

            $this->___evaluateAll($specDescription->getNestedSpecs());

        } finally {

            if ($this->___evaluationContext->evaluatesAfterAllClosure()) {
                $this->___evaluationContext->getAfterAllClosure()->call($this);
            }

        }
    }

    /**
     * Private - Evaluates the Spec.
     * The evaluation of the spec performs the following actions:
     *      - evaluate all of the beforeEach() closures collected from all of the Spec ancestors.
     *      - evaluate the Spec closure with the expectations.
     *      - evaluate the onSpecRun() callable, if present.
     *      - evaluate all of the afterEach() closures collected from all of the Spec ancestors.
     *
     * @param Spec $spec The Spec to evaluate.
     */
    public function ___evaluateSpec(Spec $spec): void
    {
        if ($this->___evaluationContext->isSkipping()) {

            $this->___statistics->incSkippedSpecsCount();
            $this->___onSpecSkipped($spec);

            return;
        }

        try {

            $this->___statistics->incRunSpecsCount();

            $this->___evaluateBeforeEachClosures();

            $spec->getClosure()->call($this);

            $this->___onSpecPassed($spec);

        } finally {

            $this->___evaluateAfterEachClosures();

        }
    }

    /**
     * Calls the onSpecRunClosure(), if present, with the 'skipped' status.
     *
     * @param Spec $spec The Spec that was skipped.
     */
    protected function ___onSpecSkipped(Spec $spec): void
    {
        if ($this->___onSpecRunClosure !== null) {
            ($this->___onSpecRunClosure)($spec, 'skipped');
        }
    }

    /**
     * Evaluates all of the beforeEach() closures collected from the visited SpecDescriptions.
     */
    protected function ___evaluateBeforeEachClosures(): void
    {
        foreach ($this->___evaluationContext->getBeforeEachClosures() as $beforeClosure) {
            $beforeClosure->call($this);
        }
    }

    /**
     * Calls the onSpecRunClosure(), if present, with the 'passed' status.
     *
     * @param Spec $spec The Spec that passed all of its expectations.
     */
    protected function ___onSpecPassed(Spec $spec): void
    {
        if ($this->___onSpecRunClosure !== null) {
            ($this->___onSpecRunClosure)($spec, 'passed');
        }
    }

    /// Expectations

    /**
     * Evaluates all of the afterEach() closures collected from the visited SpecDescriptions.
     */
    protected function ___evaluateAfterEachClosures(): void
    {
        foreach ($this->___evaluationContext->getAfterEachClosures() as $afterClosure) {
            $afterClosure->call($this);
        }
    }

    /**
     * Prepare the context to run expectations on the given value.
     * Returns a new ValueExpectation that will receive the following expectation on the value.
     *
     * @param mixed $value The value on which the expectation will be run.
     * @return ValueExpectationBuilder The object that will receive the following expectation on the value it holds.
     */
    public function expect($value): ValueExpectationBuilder
    {
        $this->___statistics->incExpectationsCount();

        return $this->newValueExpectation(
            $this->___evaluationContext->getSpecDescription(),
            $value
        );
    }

    /**
     * Creates and returns a new ValueExpectation object with the given Spec description and value.
     * The ValueExpectation can receive further expectations on the object it holds.
     *
     * @param string $fullDescription The complete description (the concatenation of each description from the root
     *  SpecDescription to the final Spec) of the Spec.
     * @param $value The value to run expectations on.
     * @return ValueExpectationBuilder The created ValueExpectation object.
     */
    protected function newValueExpectation(string $fullDescription, $value): ValueExpectationBuilder
    {
        return new ValueExpectationBuilder($this, $fullDescription, $value);
    }

    /**
     * Raises an ExpectationFailureSignal to flag that the Spec failed.
     * This function makes a Spec to fail with a given failure message.
     * No further code is executed in the current Spec after calling this function.
     *
     * @param string $message The failure message.
     */
    public function fail(string $message): void
    {
        throw new ExpectationFailureSignal($message, $this->___evaluationContext->getSpecDescription());
    }

    /// Properties

    /**
     * This method is called the first time that a property is read in the context of the execution of a Spec.
     * If in the current context scope there is a property previously defined with ->let() with the given property
     *  name then evaluates the let() closure and assigns the result of the evaluation to this object as an object
     * property.
     * If there is not a variable defined with the given property name raise an UndefinedPropertyError.
     *
     * @param string $propertyName The property being accessed.
     * @return mixed The result of the property closure evaluation.
     */
    public function __get(string $propertyName)
    {
        if (!$this->___hasProperty($propertyName)) {
            return $this->___raiseUndefinedPropertyError($propertyName);
        }

        $value = $this->___evaluatePropertyClosure($propertyName);

        $this->$propertyName = $value;

        return $value;
    }

    /**
     * This function is called the first time a property is assigned in the context of the execution of a Spec.
     * Assigns the given value to the object property and also registers the property in the current context.
     * This registration is used after the evaluation of the current Spec or SpecDescription to perform the clean up
     * of the properties defined within the current context scope.
     *
     * @param string $propertyName The name of the property being assigned.
     * @param mixed $value The value being assigned.
     */
    public function __set(string $propertyName, $value): void
    {
        $this->___evaluationContext->scopeVariables[] = $propertyName;

        $this->$propertyName = $value;
    }

    /**
     * Returns true if the current context has a property defined with the given name, false otherwise.
     *
     * @param string $propertyName The property name.
     * @return bool True if the current context has a closure defined at the given propertyName, false otherwise.
     */
    protected function ___hasProperty(string $propertyName): bool
    {
        return $this->___evaluationContext->hasPropertyAt($propertyName);
    }

    /// Methods

    /**
     * Raises an UndefinedPropertyError for the given property name.
     *
     * @param string $propertyName The undefined property name.
     */
    protected function ___raiseUndefinedPropertyError(string $propertyName): void
    {
        throw new UndefinedPropertyError(
            "Undefined property named '$propertyName'.",
            $propertyName
        );
    }

    /**
     * Evaluates the closure previously defined at the property name with let().
     *
     * @param string $propertyName The name of the closure to evaluate.
     * @return mixed The result of the evaluation of the closure defined at the given property name.
     */
    protected function ___evaluatePropertyClosure(string $propertyName)
    {
        $closure = $this->___getPropertyAt($propertyName);

        return $closure->call($this);
    }

    /**
     * Returns the closure previously defined at the property name with let().
     *
     * @param string $propertyName The name of the closure to evaluate.
     * @return Closure The closure defined at the given property name.
     */
    protected function ___getPropertyAt(string $propertyName): Closure
    {
        return $this->___evaluationContext->getPropertyAt($propertyName);
    }

    /**
     * This function is called when a function is called in the context of the evaluation of a Spec or
     * SpecDescription.
     * If there is a custom method previously defined with def() evaluates the closure on that definition.
     * If there is no custom method at that name raises an UndefinedMethodError.
     *
     * @param string $methodName The name of method called.
     * @param array $parameters The parameters passed to the method called.
     * @return mixed The result of the evaluation of the custom method defined by the user.
     */
    public function __call(string $methodName, array $parameters)
    {
        if (!$this->___hasMethod($methodName)) {
            return $this->___raiseUndefinedMethodError($methodName);
        }


        return $this->___callMethod($methodName, $parameters);
    }

    /**
     * Returns true if the current context has a custom method previously defined with def(), false otherwise.
     *
     * @param string $methodName The custom method name to check for a definition.
     * @return bool True if the current context has a custom method defined with the given name, false otherwise.
     */
    protected function ___hasMethod(string $methodName): bool
    {
        return $this->___evaluationContext->hasMethodAt($methodName);
    }

        /// Raising errors

    /**
     * Raises an UndefinedMethodError for the given methodName.
     *
     * @param string $methodName The undefined custom method name.
     */
    protected function ___raiseUndefinedMethodError(string $methodName): void
    {
        throw new UndefinedMethodError(
            "Undefined method named '$methodName'.",
            $methodName
        );
    }

    /**
     * Evaluates the closure at the given methodName previously defined with def().
     *
     * @param string $methodName The method name.
     * @param array $parameters The parameters to passed along to the custom method call.
     * @return mixed The result of the evaluation of the custom method previously defined with def().
     */
    protected function ___callMethod(string $methodName, array $parameters)
    {
        $closure = $this->___getMethod($methodName);

        return $closure->call($this, ...$parameters);
    }

    /**
     * Returns the closure at the methodName previously defined with def().
     *
     * @param string $methodName The name of the custom method.
     * @return Closure The closure previously defined at the methodName.
     */
    protected function ___getMethod(string $methodName): Closure
    {
        return $this->___evaluationContext->getMethodAt($methodName);
    }
}