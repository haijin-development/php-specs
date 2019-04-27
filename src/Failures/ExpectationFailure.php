<?php
declare(strict_types=1);

namespace Haijin\Specs\Failures;

/**
 * @package Haijin\Specs\Failures
 *
 * This object models an expectation that was not met during the execution of a spec.
 */
class ExpectationFailure extends InvalidExpectation
{
    /**
     * Returns whether this InvalidExpectation is a failed expectation or not.
     *
     * @return bool Returns true.
     */
    public function isFailure(): bool
    {
        return true;
    }
}