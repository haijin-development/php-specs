<?php
declare(strict_types=1);

namespace Haijin\Specs\Errors;

use RuntimeException;

/**
 * Exception raised when a failed expectation is met.
 * This exception is intended to signal failures to the specs runner, it is not an actual execution error.
 *
 * @package Haijin\Specs\Errors
 */
class ExpectationFailureSignal extends RuntimeException
{
    /**
     * @var string The description of the failed spec.
     */
    protected $description;

    /// Initializing

    /**
     * ExpectationFailureSignal constructor.
     * @param string $message The failure message.
     * @param string $description The description if the failed spec.
     *
     */
    public function __construct(string $message, string $description)
    {
        parent::__construct($message);

        $this->description = $description;
    }

    /// Accessing

    /**
     * Returns the description of the failed spec.
     *
     * @return string Returns the description of the failed spec.
     */
    public function getDescription(): string
    {
        return $this->description;
    }
}