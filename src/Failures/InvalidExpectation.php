<?php
declare(strict_types=1);

namespace Haijin\Specs\Failures;

use Haijin\Specs\ValueExpectations\ValueExpectation;

/**
 * @package Haijin\Specs\Failures
 *
 * Base class for classes modeling that a spec was not met either by a logical error or by an unexpected error.
 */
class InvalidExpectation
{
    /**
     * @var string $description The full description of the Spec where the error was found.
     */
    protected $description;
    /**
     * @var string $message The error message.
     */
    protected $message;
    /**
     * @var string $specFileName The file name of the spec where the error was found.
     */
    protected $specFileName;
    /**
     * @var int $specLineNumber The line number of the spec definition (not where the failure or error was found)
     *  where the error was found.
     */
    protected $specLineNumber;
    /**
     * @var array $stackTrace The stack trace at the point of the found failure or error.
     */
    protected $stackTrace;

    /// Initializing

    /**
     * InvalidExpectation constructor.
     * @param string $description The description of the spec where the error was found.
     * @param string $message The error message.
     * @param string $specFileName The file name of the spec where the error was found.
     * @param int $specLineNumber The line number of the spec definition (not where the failure or error was found)
     *  where the error was found.
     * @param array $stackTrace The stack trace at the point of the found failure or error.
     */
    public function __construct(
        string $description, string $message, string $specFileName, int $specLineNumber, array $stackTrace
    )
    {
        $this->description = $description;
        $this->message = $message;
        $this->specFileName = $specFileName;
        $this->specLineNumber = $specLineNumber;
        $this->stackTrace = $stackTrace;
    }

    /// Accessing

    /**
     * Returns the description of the spec where the error was found.
     *
     * @return string The description of the spec where the error was found.
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * Returns the error message.
     *
     * @return string The error message.
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * Returns the file name of the spec where the error was found.
     *
     * @return string The file name of the spec where the error was found.
     */
    public function getSpecFileName(): string
    {
        return $this->specFileName;
    }

    /**
     * Returns the line number of the spec definition (not where the error was found) where the error was found.
     *
     * @return int The line number of the spec definition (not where the error was found).
     */
    public function getSpecLineNumber(): int
    {
        return $this->specLineNumber;
    }

    /**
     * Returns whether this InvalidExpectation is a failed expectation or not.
     *
     * @return bool Returns false. Subclasses modelling failures should override this method to return true.
     */
    public function isFailure(): bool
    {
        return false;
    }

    /**
     * Returns whether this InvalidExpectation is an unexpected error or not.
     *
     * @return bool Returns false. Subclasses modeling failures should override this method to return true.
     */
    public function isError(): bool
    {
        return false;
    }

    /**
     * Returns the stack trace at the point of the found error.
     *
     * @return array The stack trace array at the point of the found error.
     */
    public function getStackTrace(): array
    {
        return $this->stackTrace;
    }

    /**
     * Returns the line number of the failed expectation or error.
     *
     * @return int The line number of the failed expectation.
     */
    public function getExpectationLine(): ?int
    {
        $stackFrame = $this->findFailedSpecStackFrame();

        return $stackFrame["line"];
    }

    /**
     * Returns the stack frame where the spec failed.
     *
     * @return array|null The frame in the execution stack trace where the failure was found.
     */
    public function findFailedSpecStackFrame(): ?array
    {
        foreach ($this->stackTrace as $i => $stackFrame) {
            if ($stackFrame['function'] == 'evaluateOn'
                &&
                $stackFrame['class'] == ValueExpectation::class
            ) {
                return $this->stackTrace[$i + 1];
            }
        }

        return null;
    }
}