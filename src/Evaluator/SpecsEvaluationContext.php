<?php


namespace Haijin\Specs\Evaluator;

use Closure;
use Haijin\Specs\Specs\SpecContextDefinitions;

/**
 * This object holds the context needed to completely evaluate a Spec.
 * The context includes
 *      - the beforeAll() closure of the current SpecDescription.
 *      - the collection of beforeEach() closures of the current Spec and its ancestors.
 *      - the collection of afterEach() closures of the Spec and its ancestors.
 *      - the afterAll() closure of the current SpecDescription.
 *      - the properties defined with ->let() in the current Spec and its ancestors.
 *      - the methods defined with ->def() in the current Spec and its ancestors.
 *
 * @package Haijin\Specs\Runners
 */
class SpecsEvaluationContext
{
    /**
     * @var array All the properties defined in the scope of a Spec execution. Includes the variables defined
     *  in the current Spec or SpecDescription and the ones defined in its ancestors.
     */
    public $scopeVariables;
    /**
     * @var string The textual description of the current Spec or SpecDescription. It is the concatenation of
     * the partial descriptions of each ancestor of the current Spec.
     */
    protected $specDescription;
    /**
     * @var Closure|null $beforeAllClosure The closure to evaluate before the evaluation of this Spec children.
     */
    protected $beforeAllClosure;
    /**
     * @var array $beforeEachClosures The collection of closures to evaluate before running each Spec.
     */
    protected $beforeEachClosures;
    /**
     * @var array $afterEachClosures The collection of closures to evaluate after running each Spec.
     */
    protected $afterEachClosures;
    /**
     * @var Closure|null $afterAllClosure The closure to evaluate after the evaluation of this Spec children.
     */
    protected $afterAllClosure;
    /**
     * @var array The properties defined with ->let() in the current Spec and in its ancestors.
     */
    protected $properties;
    /**
     * @var array The methods defined with ->def() in the current Spec and in its ancestors.
     */
    protected $methods;
    /**
     * @var bool Boolean to flag if the execution of the current Spec and its children should be skipped.
     */
    protected $isSkipping;

    /// Initializing

    /**
     * SpecsEvaluationContext constructor.
     */
    public function __construct()
    {
        $this->specDescription = '';
        $this->beforeAllClosure = null;
        $this->beforeEachClosures = [];
        $this->afterEachClosures = [];
        $this->afterAllClosure = null;
        $this->scopeVariables = [];
        $this->properties = [];
        $this->methods = [];
        $this->isSkipping = false;
    }

    /// Spec description

    /**
     * Returns the description of the current Spec.
     *
     * @return string $specDescription The current Spec description.
     */
    public function getSpecDescription(): string
    {
        return $this->specDescription;
    }

    /**
     * Sets the description of the current Spec.
     *
     * @param string $specDescription The current Spec description.
     */
    public function setSpecDescription(string $specDescription): void
    {
        $this->specDescription = $specDescription;
    }

    /**
     * Appends the given description of the current scope to the previous descriptions.
     *
     * @param string $scopeDescription The description of the current scope.
     */
    public function appendSpecDescription(string $scopeDescription): void
    {
        if (!empty($this->specDescription)) {
            $this->specDescription .= ' ';
        }

        $this->setSpecDescription( $this->specDescription . $scopeDescription);
    }

    /// Before and after Closures

    /**
     * Returns the beforeAll() closure to evaluate before the evaluation of the current Spec children.
     *
     * @return Closure|null The beforeAll() closure to evaluate before the evaluation of the current Spec children.
     */
    public function getBeforeAllClosure(): ?Closure
    {
        return $this->beforeAllClosure;
    }

    /**
     * Sets the beforeAll() closure to evaluate before the evaluation of the current Spec children.
     *
     * @param Closure|null $closure
     */
    public function setBeforeAllClosure(?Closure $closure): void
    {
        $this->beforeAllClosure = $closure;
    }

    /**
     * Returns the beforeEach() closures to evaluate before the evaluation of the current Spec children.
     *
     * @return array The beforeEach() closures to evaluate before the evaluation of the current Spec children.
     */
    public function getBeforeEachClosures(): array
    {
        return $this->beforeEachClosures;
    }

    /**
     * Returns the afterEach() closures to evaluate after the evaluation of the current Spec children.
     *
     * @return array The afterEach() closures to evaluate after the evaluation of the current Spec children.
     */
    public function getAfterEachClosures(): array
    {
        return $this->afterEachClosures;
    }

    /**
     * Returns the afterAll() closure to evaluate after the evaluation of the current Spec children.
     *
     * @return Closure|null The afterAll() closure to evaluate before the evaluation of the current Spec children.
     */
    public function getAfterAllClosure(): ?Closure
    {
        return $this->afterAllClosure;
    }

    /**
     * Sets the afterAll() closure to evaluate after the evaluation of the current Spec children.
     *
     * @param Closure|null $closure
     */
    public function setAfterAllClosure(?Closure $closure): void
    {
        $this->afterAllClosure = $closure;
    }

    /**
     * Appends a closure to the collection of beforeEach() closures to evaluate before each Spec evaluation.
     *
     * @param Closure $closure The closure to append to the collection of beforeEach() closures.
     */
    public function appendBeforeEachClosure(?Closure $closure): void
    {
        if ($closure === null) {
            return;
        }

        $this->beforeEachClosures[] = $closure;
    }

    /**
     * Appends a closure to the collection of afterEach() closures to evaluate before each Spec evaluation.
     *
     * @param Closure $closure The closure to append to the collection of afterEach() closures.
     */
    public function prependAfterEachClosure(?Closure $closure): void
    {
        if ($closure === null) {
            return;
        }

        $this->afterEachClosures = array_merge(
            [$closure],
            $this->afterEachClosures
        );
    }

    /// Skipping Specs

    /**
     * @param bool $skipEvaluations If true the following the evaluation of the current Spec and its children
     *  will be skipped. If false the current isSkipping flag is preserved.
     */
    public function skipEvaluations(bool $skipEvaluations): void
    {
        if ($skipEvaluations === false) {
            return;
        }

        $this->isSkipping = true;
    }

    /**
     * Returns true if the current Spec context is skipping the evaluation of the Spec and its children.
     *
     * @return bool True if the current context is skipping the evaluation of the Spec and its children, false
     *  otherwise.
     */
    public function isSkipping()
    {
        return $this->isSkipping;
    }

    /// Asking

    /**
     * Answers whether the current SpecDescription must evaluate the beforeAll() closure or not.
     * The reasons not to evaluate the closure can be that it was not defined or that the
     * current context is skipping evaluations.
     *
     * @return bool True if the current SpecDescription must evaluate the beforeAll() closure, false otherwise.
     */
    public function evaluatesBeforeAllClosure(): bool
    {
        return $this->beforeAllClosure !== null && !$this->isSkipping;
    }

    /**
     * Answers whether the current SpecDescription must evaluate the afterAll() closure or not.
     * The reasons not to evaluate the closure can be that it was not defined or that the
     * current context is skipping evaluations.
     *
     * @return bool True if the current SpecDescription must evaluate the afterAll() closure, false otherwise.
     */
    public function evaluatesAfterAllClosure(): bool
    {
        return $this->afterAllClosure !== null && !$this->isSkipping;
    }

    /// Properties

    /**
     * Sets the definition closure at the property name $propertyName.
     * This property was previously defined with ->let() in a SpecDescription and this method adds it to the current
     * execution context.
     *
     * @param string $propertyName The name of the property.
     * @param Closure $closure The closure that will be evaluated on the first read of the property.
     */
    public function atPropertyPut(string $propertyName, Closure $closure): void
    {
        $this->properties[ $propertyName ] = $closure;
    }

    /**
     * Returns the Closure defined at the property named $propertyName.
     *
     * @param string $propertyName The name of the property.
     * @return Closure The closure to evaluate on the first read of the property. If the property was not
     *  defined returns null and raises a warning.
     */
    public function getPropertyAt(string $propertyName): Closure
    {
        return $this->properties[ $propertyName ];
    }

    /**
     * Returns true if the evaluation context has a property named $propertyName, false otherwise.
     *
     * @param string $propertyName The name of the property to check for existence.
     * @return bool True if the property is defined, false if not.
     */
    public function hasPropertyAt(string $propertyName): bool
    {
        return array_key_exists( $propertyName, $this->properties );
    }

    /**
     * Adds the given properties to this SpecsEvaluationContext.
     *
     * @param array $propertyDefinitions
     */
    public function addProperties(array $propertyDefinitions): void
    {
        foreach ($propertyDefinitions as $name => $closure) {
            $this->atPropertyPut($name, $closure);
        }
    }

    /// Methods

    /**
     * Sets the custom method at the give methodName. After setting this method the method is callable within
     * the context of a Spec or SpecDescription and its children.
     *
     * @param string $methodName The name of the method being defined.
     * @param Closure $closure The Closure that is evaluated when the defined method is invoked.
     */
    public function atMethodPut(string $methodName, Closure $closure): void
    {
        $this->methods[ $methodName ] = $closure;
    }

    /**
     * Returns the Closure that is evaluated when the method named $methodName is called within the context
     * of a Spec or SpecDescription.
     *
     * @param string $methodName The name of the method.
     * @return Closure The closure define for the method name. If the method is not defined returns null and raises
     *  a warning.
     */
    public function getMethodAt(string $methodName): Closure
    {
        return $this->methods[ $methodName ];
    }

    /**
     * Returns true if the method named $methodName is defined, false if not.
     *
     * @param string $methodName The name of the method.
     * @return bool True if the method is defined, false if not.
     */
    public function hasMethodAt(string $methodName):bool
    {
        return array_key_exists( $methodName, $this->methods );
    }

    /**
     * Adds the given custom methods to this SpecsEvaluationContext.
     *
     * @param array $methodDefinitions
     */
    public function addMethods(array $methodDefinitions): void
    {
        foreach ($methodDefinitions as $name => $closure) {
            $this->atMethodPut($name, $closure);
        }
    }
}