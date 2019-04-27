<?php
declare(strict_types=1);

namespace Haijin\Specs\Specs;

use Haijin\Specs\Evaluator\SpecEvaluator;
use RuntimeException;
use Closure;

/**
 * This object is a single Spec that, when evaluated, runs expectations on a piece of code.
 * This class is not instantiated directly but through the ->it() expression of the Specs DSL:
 *
 *          $spec->describe( 'When some condition ... ', function() {
 *              // The following ->it(...) expression instantiates a Spec object
 *              $this->it( ' returns a value ... ', function() {
 *                  $this->expect( $this->value ) ->to() ->equal( 1 );
 *              });
 *
 *          });
 *
 * A Spec object has a description, a closure and debugging information.
 * The description is a human readable description of the expected behaviour of a feature.
 * The closure defines the code that performs expectations on some value.
 *
 * @package Haijin\Specs\Specs
 */
class Spec extends SpecBase
{
    /**
     * @var Closure The Closure with the code that, when evaluated, runs the expectations on a feature.
     */
    protected $closure;

    /**
     * SpecBase constructor.
     * @param string $description The description of the Spec or SpecDescription. This description is a human readable
     * text defining the scope of the use case having expectations.
     * @param Closure $closure The Closure with the code that, when evaluated, runs the expectations on a feature.
     * @param string $fileName Path to the file where this spec was defined using the specs DSL.
     * @param int $lineNumber The number of the line where the spec was defined using the specs DSL.
     */
    public function __construct(string $description, Closure $closure, ?string $fileName, ?int $lineNumber)
    {
        parent::__construct($description, $fileName, $lineNumber);

        $this->closure = $closure;
    }

    /// Accessing

    /**
     * Returns the Spec closure.
     *
     * @return Closure The Closure with the code that, when evaluated, runs the expectations on a feature.
     */
    public function getClosure(): Closure
    {
        return $this->closure;
    }

    /**
     * Restricts the specs to evaluate to the one containing the given $lineNumber.
     * This method only makes sense on SpecDescriptions with nested specs. This is just a null implementation.
     *
     * @param int $lineNumber The line number contained by he Spec to run.
     */
    public function restrictToLineNumber(int $lineNumber): void
    {
    }

    /**
     * Returns true if this Spec includes the given $lineNumber in the file where it is defined, false otherwise.
     *
     * @param int $lineNumber
     * @return bool True if this Spec includes the given $lineNumber in the file where it is defined, false otherwise.
     */
    public function isInLineNumber(int $lineNumber): bool
    {
        return $this->lineNumber > $lineNumber;
    }

    /// Evaluating

    /**
     * Evaluate this Spec.
     * The evaluation is done in the context of a SpecEvaluator object which has the full evaluation context.
     * This method is a double dispatch implementation to tell the SpecEvaluator that is evaluating a Spec type.
     *
     * @param SpecEvaluator $specEvaluator
     */
    public function evaluateWith(SpecEvaluator $specEvaluator): void
    {
        $specEvaluator->___evaluateSpec($this);
    }

    /// DSL

    /**

    // The evaluation of a Spec is done in the context of a SpecEvaluator, not in this class.
    // That is why these methods are never called but still document the Spec protocol during its evaluation.

    public function expect($value)
    {
        throw new RuntimeException("See class SpecEvaluator for this method implementation.");
    }

    public function fail($message)
    {
        throw new RuntimeException("See class SpecEvaluator for this method implementation.");
    }

     */
}