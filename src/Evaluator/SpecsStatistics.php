<?php
declare(strict_types=1);

namespace Haijin\Specs\Evaluator;

use Haijin\Specs\Failures\ExpectationFailure;
use Haijin\Specs\Failures\ExpectationError;
use Haijin\Specs\Failures\InvalidExpectation;

/**
 * This object keeps track of some basic statistics on the run Specs.
 * It collects the following information:
 *      - number of Specs run
 *      - number of expectations evaluated
 *      - number of failed specs
 *      - number of specs with unexpected errors
 *      - number of specs skipped
 *
 * It also collects the specs failures.
 *
 * @package Haijin\Specs\Evaluator
 */
class SpecsStatistics
{
    /**
     * @var int The number of total specs run.
     */
    protected $runSpecsCount;
    /**
     * @var int The number of total expectations evaluated.
     */
    protected $runExpectationsCount;
    /**
     * @var int The number of specs skipped.
     */
    protected $skippedSpecsCount;
    /**
     * @var array The number of total invalid expectations, including both expectations failures and unexpected errors.
     */
    protected $invalidExpectations;

    /// Initializing

    /**
     * SpecsStatistics constructor.
     */
    public function __construct()
    {
        $this->runSpecsCount = 0;
        $this->runExpectationsCount = 0;
        $this->skippedSpecsCount = 0;
        $this->invalidExpectations = [];
    }

    /// Accessing

    /**
     * Increments the number of specs run by 1.
     */
    public function incRunSpecsCount(): void
    {
        $this->runSpecsCount += 1;
    }

    /**
     * Increments the number of expectations evaluated by 1.
     */
    public function incExpectationsCount(): void
    {
        $this->runExpectationsCount += 1;
    }

    /**
     * Increments the number of specs skipped by 1.
     */
    public function incSkippedSpecsCount()
    {
        $this->skippedSpecsCount += 1;
    }

    /**
     * Returns all the InvalidExpectation collected during the execution of the Specs, including both
     *  ExpectationFailure and ExpectationError.
     * @return array
     */
    public function getInvalidExpectations(): array
    {
        return $this->invalidExpectations;
    }

    /**
     * Adds an InvalidExpectation to the collection of invalid expectations.
     *
     * @param InvalidExpectation $invalidExpectation The InvalidExpectation to collect.
     */
    public function addInvalidExpectation(InvalidExpectation $invalidExpectation): void
    {
        $this->invalidExpectations[] = $invalidExpectation;
    }

    /// Querying

    /**
     * Returns the number of total invalid expectation counting both failures and errors.
     *
     * @return int The number of total invalid expectation counting both failures and errors.
     */
    public function invalidExpectationsCount(): int
    {
        return count( $this->invalidExpectations );
    }

    /**
     * Returns the number of failed expectations collected during the execution of the Specs.
     *
     * @return int The number of failed expectations.
     */
    public function failedSpecsCount(): int
    {
        $count = 0;

        foreach( $this->invalidExpectations as $invalidSpec ) {
            if( $invalidSpec->isFailure() ) {
                $count += 1;
            }
        }

        return $count;
    }

    /**
     * Returns the number of unexpected errors collected during the execution of the Specs.
     *
     * @return int The number of unexpected errors.
     */
    public function erroredSpecsCount(): int
    {
        $count = 0;

        foreach( $this->invalidExpectations as $invalidSpec ) {
            if( $invalidSpec->isError() ) {
                $count += 1;
            }
        }

        return $count;
    }

    /**
     * Returns the total number of Specs run.
     *
     * @return int The total number of Specs run.
     */
    public function runSpecsCount()
    {
        return $this->runSpecsCount;
    }

    /**
     * Returns the number of expectations run.
     *
     * @return int The number of expectations run.
     */
    public function runExpectationsCount()
    {
        return $this->runExpectationsCount;
    }

    /**
     * Returns the number of expectations skipped.
     *
     * @return int The number of expectations skipped.
     */
    public function skippedSpecsCount()
    {
        return $this->skippedSpecsCount;
    }
}