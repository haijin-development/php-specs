<?php
declare(strict_types=1);

namespace Haijin\Specs\Failures;

/**
 * @package Haijin\Specs\Failures
 *
 * This object models an unexpected error thrown during the execution of a spec.
 */
class ExpectationError extends InvalidExpectation
{
    /**
     * Returns whether this InvalidExpectation is an unexpected error or not.
     *
     * @return bool Returns true.
     */
    public function isError(): bool
    {
        return true;
    }
}