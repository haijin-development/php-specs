<?php
declare(strict_types=1);

namespace Haijin\Specs\Errors;

use RuntimeException;

/**
 * Error raised when a spec has an invalid definition.
 *
 * @package Haijin\Specs\Errors
 */
class ExpectationDefinitionError extends RuntimeException
{
}