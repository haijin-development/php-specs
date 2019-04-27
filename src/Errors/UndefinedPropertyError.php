<?php
declare(strict_types=1);

namespace Haijin\Specs\Errors;

use RuntimeException;

/**
 * Error raised when a $this->$property_name is used in the context of a Spec or a SpecDescription without
 * a previous $specDescription->let($property_name, $closure) definition.
 *
 * @package Haijin\Specs\Errors
 */
class UndefinedPropertyError extends RuntimeException
{
    protected $propertyName;

    /// Initializing

    /**
     * UndefinedPropertyError constructor.
     *
     * @param string The error message.
     * @param string The name of the undefined property.
     */
    public function __construct(string $message, string $propertyName)
    {
        parent::__construct($message);

        $this->propertyName = $propertyName;
    }

    /// Accessing

    /**
     * Returns the name of the undefined let() property.
     *
     * @return string The name of the undefined let() property.
     */
    public function getPropertyName(): string
    {
        return $this->propertyName;
    }
}