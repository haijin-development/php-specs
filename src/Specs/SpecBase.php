<?php
declare(strict_types=1);

namespace Haijin\Specs\Specs;

use Haijin\Specs\Evaluator\SpecEvaluator;

/**
 * Base class for Specs and SpecDescriptions.
 *
 * @package Haijin\Specs\Specs
 */
abstract class SpecBase
{
    /**
     * @var string The description of the Spec or SpecDescription. This description is a human readable text
     *  defining the scope of the use case having expectations.
     */
    protected $description;
    /**
     * @var bool True if this Spec or SpecDescription was flagged to be skipped when running evaluations.
     */
    protected $skipping;
    /**
     * @var string Path to the file where this Spec was defined using the specs DSL.
     */
    protected $fileName;
    /**
     * @var int The number of the line where the Spec was defined using the specs DSL.
     */
    protected $lineNumber;

    /// Initializing

    /**
     * SpecBase constructor.
     * @param string $description The description of the Spec or SpecDescription. This description is a human readable
     * text defining the scope of the use case having expectations.
     * @param string $fileName Path to the file where this spec was defined using the specs DSL.
     * @param int $lineNumber The number of the line where the spec was defined using the specs DSL.
     */
    public function __construct(string $description, ?string $fileName, ?int $lineNumber)
    {
        $this->description = $description;
        $this->skipping = false;
        $this->fileName = $fileName;
        $this->lineNumber = $lineNumber;
    }

    /// Accessors

    /**
     * Returns the description of the Spec or SpecDescription. This description is a human readable
     * text defining the scope of the use case having expectations.
     *
     * @return string The description of the Spec or SpecDescription.
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * Returns true if this Spec or SpecDescription was flagged to be skipped when running evaluations, false otherwise.
     * @return bool true if this Spec or SpecDescription was flagged to be skipped when running evaluations, false
     *  otherwise.
     */
    public function isSkipping(): bool
    {
        return $this->skipping;
    }

    /**
     * Flags this Spec to be skipped during the evaluation of Specs.
     *
     * @param bool $bool True to skip this Spec, false to evaluate it.
     */
    public function beSkipping(bool $bool): void
    {
        $this->skipping = $bool;
    }

    /**
     * Returns the path of the file where the Spec was defined using the specs DSL.
     * The filename and line number are used when reporting failed specs.
     *
     * @return string The path of the file where the Spec was defined using the specs DSL.
     */
    public function getFileName(): string
    {
        return $this->fileName;
    }

    /**
     * Returns the number of the line where the Spec was defined using the specs DSL.
     * The filename and line number are used when reporting failed specs.
     *
     * @return int The number of the line where the Spec was defined using the specs DSL.
     */
    public function getLineNumber(): int
    {
        return $this->lineNumber;
    }

    /// Definition

    /**
     * Evaluates the $specFile to define this Spec.
     *
     * Example of a $specFile:
     *
     *          $spec->describe( 'When some condition ... ', function() {
     *              $this->it( ' returns a value ... ', function() {
     *                  $this->expect( $this->value ) ->to() ->equal( 1 );
     *              });
     *
     *          });
     *
     * @param string $specFile
     */
    public function defineInFile(string $specFile): void
    {
        $spec = $this;

        require($specFile);
    }

    /// Double dispatch evaluations

    /**
     * Evaluate this Spec or SpecDescription.
     * The evaluation is done in the context of a SpecEvaluator object which has the full evaluation context.
     * This method is a double dispatch implementation to tell the SpecEvaluator that is evaluating a Spec or
     * SpecDescription type.
     *
     * @param SpecEvaluator $specEvaluator
     */
    abstract public function evaluateWith(SpecEvaluator $specEvaluator): void;

    /**
     * Returns true if this Spec includes the given $lineNumber in the file where it is defined, false otherwise.
     *
     * @param int $lineNumber
     * @return bool True if this Spec includes the given $lineNumber in the file where it is defined, false otherwise.
     */
    abstract public function isInLineNumber(int $lineNumber): bool;

    /**
     * Restricts the specs to evaluate to the one containing the given $lineNumber.
     * This method only makes sense on SpecDescriptions with nested specs. This is just a null implementation.
     *
     * @param int $lineNumber The line number contained by he Spec to run.
     */
    abstract public function restrictToLineNumber(int $lineNumber): void;
}