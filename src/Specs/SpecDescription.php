<?php
declare(strict_types=1);

namespace Haijin\Specs\Specs;

use Closure;
use function debug_backtrace;
use Haijin\Specs\Evaluator\SpecEvaluator;

/**
 * This object is a scope definition of nested Spec and further SpecDescriptions.
 * This class is not instantiated directly but through the ->describe() expression of the Specs DSL:
 *
 *          // The following ->describe(...) expression instantiates a SpecDescription object
 *          $spec->describe( 'When some condition ... ', function() {
 *              $this->it( ' returns a value ... ', function() {
 *                  $this->expect( $this->value ) ->to() ->equal( 1 );
 *              });
 *
 *          });
 *
 * A SpecDescription object has a description of its scope, optional definitions of before and after closures,
 * properties and custom methods and a collection of nested Specs and SpecDescriptions.
 *
 * @package Haijin\Specs\Specs
 */
class SpecDescription extends SpecBase
{
    /**
     * @var SpecContextDefinitions The definitions of before and after closures, properties and custom methods
     *  for this SpecDescription and its children.
     */
    protected $specContextDefinitions;
    /**
     * @var array The child Specs and SpecDescriptions. Children specs inherit this SpecDescription context.
     */
    protected $nestedSpecs;
    /**
     * @var string Path to the file where this SpecDescription was defined using the specs DSL.
     */
    protected $fileName;
    /**
     * @var int The number of the line where the SpecDescription was defined using the specs DSL.
     */
    protected $lineNumber;

    /**
     * SpecDescription constructor.
     * @param $description
     */
    public function __construct(string $description, ?string $fileName, ?int $lineNumber)
    {
        parent::__construct($description, $fileName, $lineNumber);

        $this->specContextDefinitions = new SpecContextDefinitions();
        $this->nestedSpecs = [];
    }

    /// Accessing

    /**
     * The children Spec and SpecDescription. Children specs inherit the context of their ancestors during its
     * evaluation.
     *
     * @return array The children Spec and SpecDescription. Children specs inherit the context of their ancestors
     *  during the its evaluation.
     */
    public function getNestedSpecs(): array
    {
        return $this->nestedSpecs;
    }

    /**
     * Returns the closure to evaluate before the evaluation of this SpecDescription children.
     * Can be null.
     *
     * @return Closure|null The closure to evaluate before the evaluation of this SpecDescription children.
     */
    public function getBeforeAllClosure(): ?Closure
    {
        return $this->specContextDefinitions->getBeforeAllClosure();
    }

    /**
     * Returns the closure to evaluate after the evaluation of this SpecDescription children.
     * Can be null.
     *
     * @return Closure|null The closure to evaluate after the evaluation of this SpecDescription children.
     */
    public function getAfterAllClosure(): ?Closure
    {
        return $this->specContextDefinitions->getAfterAllClosure();
    }

    /**
     * Returns the closure to evaluate before the evaluation of each child Spec.
     * Can be null.
     *
     * @return Closure|null The closure to evaluate before the evaluation of each child Spec.
     */
    public function getBeforeEachClosure(): ?Closure
    {
        return $this->specContextDefinitions->getBeforeEachClosure();
    }

    /**
     * Returns the closure to evaluate after the evaluation of each child Spec.
     * Can be null.
     *
     * @return Closure|null The closure to evaluate after the evaluation of each child Spec.
     */
    public function getAfterEachClosure(): ?Closure
    {
        return $this->specContextDefinitions->getAfterEachClosure();
    }

    /**
     * Returns all the properties defined in the execution context. Includes the properties defined in
     * the current SpecDescription and the ones defined in it ancestors. The array keys are the properties name
     * and its values are the property closures.
     *
     * @return array The properties defined in the SpecsEvaluationContext of the current Spec. The array keys are the
     * properties name and its values are the property closures.
     */
    public function getProperties(): array
    {
        return $this->specContextDefinitions->getProperties();
    }

    /**
     * Returns an array with of all the methods defined in the context of the current SpecDescription.
     * The array keys are the method names and its values are the method closures.
     *
     * @return array The associative array with all the methods defined. The array keys are the method names and
     *  its values are the method closures.
     */
    public function getMethods(): array
    {
        return $this->specContextDefinitions->getMethods();
    }

    /**
     * Restricts the specs to evaluate to the one containing the given $lineNumber.
     * Filters the children to only the one containing the given $lineNumber and passes along the requirement
     * of restricting the line number to its child.
     *
     * @param int $lineNumber The line number contained by he Spec to run.
     */
    public function restrictToLineNumber(int $lineNumber): void
    {
        foreach ($this->nestedSpecs as $nestedSpec) {

            if ($nestedSpec->isInLineNumber($lineNumber)) {

                $this->nestedSpecs = [$nestedSpec];

                $nestedSpec->restrictToLineNumber($lineNumber);

                return;

            }

        }
    }

    /**
     * Returns true if this SpecDescription includes the given $lineNumber in the file where it is defined,
     * false otherwise.
     *
     * @param int $lineNumber
     * @return bool True if this SpecDescription includes the given $lineNumber in the file where it is defined,
     * false otherwise.
     */
    public function isInLineNumber(int $lineNumber): bool
    {
        foreach ($this->nestedSpecs as $nestedSpec) {

            if ($nestedSpec->isInLineNumber($lineNumber)) {
                return true;
            }
        }

        return false;
    }

    /// DSL

    /**
     * Defines a closure to evaluate before the evaluations of this SpecDescription children.
     *
     * @param Closure $closure The closure to evaluate before the evaluations of this SpecDescription children.
     */
    public function beforeAll(Closure $closure): void
    {
        $this->specContextDefinitions->setBeforeAllClosure($closure);
    }

    /**
     * Defines a closure to evaluate after the evaluations of this SpecDescription children.
     *
     * @param Closure $closure The closure to evaluate after the evaluations of this SpecDescription children.
     */
    public function afterAll(Closure $closure): void
    {
        $this->specContextDefinitions->setAfterAllClosure($closure);
    }

    /**
     * Defines a closure to evaluate before the evaluation of each SpecDescription child.
     *
     * @param Closure $closure The closure to evaluate before the evaluation of each SpecDescription child.
     */
    public function beforeEach(Closure $closure): void
    {
        $this->specContextDefinitions->setBeforeEachClosure($closure);
    }

    /**
     * Defines a closure to evaluate after the evaluation of each SpecDescription child.
     *
     * @param Closure $closure The closure to evaluate after the evaluation of each SpecDescription child.
     */
    public function afterEach(Closure $closure): void
    {
        $this->specContextDefinitions->setAfterEachClosure($closure);
    }

    /**
     * Creates a SpecDescription and adds it to this SpecDescription children.
     * The created SpecDescription has given descriptionText as its scope description and accepts
     * a closure parameter to configure its own child specs.
     *
     * @param string $descriptionText The description of this SpecDescription. This description is a human
     *  readable text defining the scope of the use case having expectations.
     * @param Closure $closure Closure to configure the children of the created SpecDescription.
     */
    public function describe(string $descriptionText, Closure $closure): void
    {
        $filename = debug_backtrace(0, 1)[0]["file"];
        $lineNumber = debug_backtrace(0, 1)[0]["line"];

        $nestedSpecDescription = new self($descriptionText, $filename, $lineNumber);

        $this->addNestedSpec($nestedSpecDescription, false, $closure);
    }

    /**
     * Adds a Spec or SpecDescription to this object children.
     *
     * @param SpecBase $nestedSpec The Spec or SpecDescription to add to this object children.
     * @param bool $skipping True if the added spec is flagged to be skipped from evaluations.
     * @param Closure $closure|null Optional - Optional closure to configure the children of the added spec.
     */
    protected function addNestedSpec(SpecBase $nestedSpec, bool $skipping, ?Closure $closure): void
    {
        $nestedSpec->beSkipping($skipping);

        $this->nestedSpecs[] = $nestedSpec;

        if ($closure !== null) {
            $closure->call($nestedSpec, $nestedSpec);
        }
    }

    /**
     * Creates a SpecDescription, flags it as skipped and adds it to this SpecDescription children.
     * The created SpecDescription has given descriptionText as its scope description and accepts
     * a closure parameter to configure its own child specs.
     *
     * @param string $descriptionText The description of the created SpecDescription. This description is a human
     *  readable text defining the scope of the use case having expectations.
     * @param Closure $closure Closure to configure the children of the created SpecDescription.
     */
    public function xdescribe(string $descriptionText, Closure $closure): void
    {
        $nestedSpecDescription = new self($descriptionText, null, null);

        $this->addNestedSpec($nestedSpecDescription, true, $closure);
    }

    /**
     * Defines a Spec property to instantiate on its first use from the given block.
     * The created property can be used from the Spec body, from other property block, from custom methods
     * and from any of the before and after closures of its own and of its children.
     *
     * @param string $propertyName The name of the property.
     * @param Closure $closure The closure that is evaluated to assign the property value on its first use.
     */
    public function let(string $propertyName, Closure $closure): void
    {
        $this->specContextDefinitions->atPropertyPut($propertyName, $closure);
    }

    /**
     * Defines a custom method.
     * The created method can be used from the Spec body, from property blocks, from other custom methods
     * and from any of the before and after closures of its own and of its children.
     *
     * @param string $methodName The name of the method.
     * @param Closure $closure The body of the method.
     */
    public function def(string $methodName, Closure $closure): void
    {
        $this->specContextDefinitions->atMethodPut($methodName, $closure);
    }

    /**
     * Creates a Spec and adds it to this SpecDescription children.
     *
     * @param string $descriptionText The description of the created Spec. This description is a human
     *  readable text defining the scope of the use case having expectations.
     * @param Closure $closure Closure with the code running the expectations.
     */
    public function it(string $descriptionText, Closure $closure): void
    {
        $filename = debug_backtrace(0, 1)[0]["file"];
        $lineNumber = debug_backtrace(0, 1)[0]["line"];

        $nestedSpec = new Spec($descriptionText, $closure, $filename, $lineNumber);

        $this->addNestedSpec($nestedSpec, false, null);
    }

    /**
     * Creates a Spec, flags it as skipped and adds it to this SpecDescription children.
     *
     * @param string $descriptionText The description of the created Spec. This description is a human
     *  readable text defining the scope of the use case having expectations.
     * @param Closure $closure Closure with the code running the expectations.
     */
    public function xit(string $descriptionText, Closure $closure): void
    {
        $filename = debug_backtrace(0, 1)[0]["file"];
        $lineNumber = debug_backtrace(0, 1)[0]["line"];

        $nestedSpec = new Spec($descriptionText, $closure, $filename, $lineNumber);

        $this->addNestedSpec($nestedSpec, true, null);
    }

    /// Double dispatch evaluations

    /**
     * Evaluate this SpecDescription.
     * The evaluation is done in the context of a SpecEvaluator object which has the full evaluation context.
     * This method is a double dispatch implementation to tell the SpecEvaluator that is evaluating a SpecDescription
     * type.
     *
     * @param SpecEvaluator $specEvaluator
     */
    public function evaluateWith(SpecEvaluator $specEvaluator): void
    {
        $specEvaluator->___evaluateSpecDescription($this);
    }
}