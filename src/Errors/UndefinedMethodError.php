<?php
declare(strict_types=1);

namespace Haijin\Specs\Errors;

use RuntimeException;

/**
 * Error raised when, in the context of a spec or a spec description, a method is invoked without being
 * defined.
 *
 * @package Haijin\Specs\Errors
 */
class UndefinedMethodError extends RuntimeException
{
    /**
     * @var string The name of the undefined method.
     */
    protected $methodName;

    /// Initializing

    /**
     * UndefinedMethodError constructor.
     * @param $message
     * @param $methodName
     */
    public function __construct($message, $methodName)
    {
        parent::__construct($message);

        $this->methodName = $methodName;
    }

    /// Accessing

    /**
     * Returns the name of the undefined method.
     *
     * @return string The name of the undefined method.
     */
    public function getMethodName(): string
    {
        return $this->methodName;
    }
}