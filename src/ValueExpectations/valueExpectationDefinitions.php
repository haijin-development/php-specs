<?php
declare(strict_types=1);

namespace Haijin\Specs\ValueExpectations;

use Closure;
use Exception;
use Haijin\Announcements\Announcements_Dispatcher;
use Haijin\Specs\Errors\ExpectationDefinitionError;
use Haijin\Specs\Tools\AttributeReader;
use RuntimeException;
use function scandir;

/// Particles definitions

ValueExpectationsLibrary::defineParticle('be', function ($operator = null) {

    $this->storeParamAt('operator', $operator);

});

ValueExpectationsLibrary::defineParticle('during', function ($closure) {

    $this->storeParamAt("duringClosure", $closure);

});

/// Comparison expectations

ValueExpectationsLibrary::defineExpectation('equal', function () {

    $this->before(function ($expectedValue) {
        $this->actualComparison = $expectedValue == $this->actualValue;
    });

    $this->assertWith(function ($expectedValue) {

        if ($this->actualComparison) {
            return;
        }

        return $this->raiseFailure(
            "Expected value to equal {$this->valueString($expectedValue)}, got {$this->valueString($this->actualValue)}."
        );
    });

    $this->negateWith(function ($expectedValue) {

        if (!$this->actualComparison) {
            return;
        }

        return $this->raiseFailure(
            "Expected value not to equal {$this->valueString($expectedValue)}, got {$this->valueString($this->actualValue)}."
        );
    });
});

ValueExpectationsLibrary::defineExpectation('than', function () {

    $this->before(function ($expectedValue) {
        $this->operator = $this->getStoredParamAt('operator');

        switch ($this->operator) {
            case '==':
                $this->actualComparison = $this->actualValue == $expectedValue;
                break;
            case '===':
                $this->actualComparison = $this->actualValue === $expectedValue;
                break;
            case '!=':
                $this->actualComparison = $this->actualValue != $expectedValue;
                break;
            case '!==':
                $this->actualComparison = $this->actualValue !== $expectedValue;
                break;
            case '>':
                $this->actualComparison = $this->actualValue > $expectedValue;
                break;
            case '>=':
                $this->actualComparison = $this->actualValue >= $expectedValue;
                break;
            case '<':
                $this->actualComparison = $this->actualValue < $expectedValue;
                break;
            case '<=':
                $this->actualComparison = $this->actualValue <= $expectedValue;
                break;

            default:
                throw new ExpectationDefinitionError("Unknown operator '$this->operator'.");
        }
    });

    $this->assertWith(function ($expectedValue) {

        if ($this->actualComparison) {
            return;
        }

        return $this->raiseFailure(
            "Expected value {$this->valueString($this->actualValue)} to be {$this->operator} than {$this->valueString($expectedValue)}."
        );
    });

    $this->negateWith(function ($expectedValue) {

        if (!$this->actualComparison) {
            return;
        }

        return $this->raiseFailure(
            "Expected value {$this->valueString($this->actualValue)} not to be {$this->operator} than {$this->valueString($expectedValue)}."
        );
    });
});

ValueExpectationsLibrary::defineExpectation('null', function () {

    $this->before(function () {

        $this->actualComparison = $this->actualValue === null;

    });

    $this->assertWith(function () {

        if ($this->actualComparison) {
            return;
        }

        $this->raiseFailure(
            "Expected value to be null, got {$this->valueString($this->actualValue)}."
        );
    });

    $this->negateWith(function () {

        if (!$this->actualComparison) {
            return;
        }

        $this->raiseFailure(
            'Expected value not to be null, got null.'

        );
    });
});

ValueExpectationsLibrary::defineExpectation('true', function () {

    $this->before(function () {

        $this->actualComparison = $this->actualValue === true;

    });

    $this->assertWith(function () {

        if ($this->actualComparison) {
            return;
        }

        return $this->raiseFailure(
            "Expected value to be true, got {$this->valueString($this->actualValue)}."
        );
    });

    $this->negateWith(function () {

        if (!$this->actualComparison) {
            return;
        }

        return $this->raiseFailure(
            'Expected value not to be true, got true.'

        );
    });
});

ValueExpectationsLibrary::defineExpectation('false', function () {

    $this->before(function () {

        $this->actualComparison = $this->actualValue === false;

    });

    $this->assertWith(function () {

        if ($this->actualComparison) {
            return;
        }

        return $this->raiseFailure(
            "Expected value to be false, got {$this->valueString($this->actualValue)}."
        );
    });

    $this->negateWith(function () {

        if (!$this->actualComparison) {
            return;
        }

        return $this->raiseFailure(
            'Expected value not to be false, got false.'

        );
    });
});

ValueExpectationsLibrary::defineExpectation('like', function () {

    $this->assertWith(function ($expectedObject, $attributePath = "") {

        if ($expectedObject instanceof Closure) {

            $this->evaluateClosure($expectedObject, $this->actualValue, $attributePath);

            return;
        }

        if (!is_array($expectedObject)) {

            if ($this->actualValue != $expectedObject) {

                return $this->raiseFailure(
                    "At {$this->valueString($attributePath)} expected {$this->valueString($expectedObject)}, got {$this->valueString($this->actualValue)}."
                );

            }

            return;
        }

        foreach ($expectedObject as $expectedKey => $expectedValue) {

            if (empty($attributePath)) {
                $childAttributePath = $expectedKey;
            } else {
                $childAttributePath = $attributePath . "." . $expectedKey;
            }

            $childValue = AttributeReader::readAttribute(
                $this->actualValue,
                $expectedKey,
                function () use($childAttributePath) {
                    $this->raiseFailure(
                        "The object was expected to have an attribute defined at {$this->valueString($childAttributePath)}."
                    );
                }
            );

            $this->evaluateClosure(function () use ($childValue, $expectedValue, $childAttributePath) {

                $this->expect($childValue)->to()->be()
                    ->like($expectedValue, $childAttributePath);

            });
        }

    });
});

ValueExpectationsLibrary::defineExpectation('exactlyLike', function () {

    $this->assertWith(function ($expectedObject, $attributePath = "") {

        if ($expectedObject instanceof Closure) {

            $this->evaluateClosure($expectedObject, $this->actualValue, $attributePath);

            return;
        }

        if (!is_array($expectedObject)) {

            if ($this->actualValue != $expectedObject) {

                return $this->raiseFailure(
                    "At {$this->valueString($attributePath)} expected {$this->valueString($expectedObject)}, got {$this->valueString($this->actualValue)}."
                );

            }

            return;
        }

        if (is_array($this->actualValue)) {
            $expectedKeys = array_keys($expectedObject);
            $actualKeys = array_keys($this->actualValue);

            $missingKeys = array_diff($expectedKeys, $actualKeys);

            if (count($missingKeys) > 0) {

                return $this->raiseFailure(
                    "The object was expected to have the attributes defined at [\"" . join("\", \"", $missingKeys) . "\"]."
                );

            }

            $excedingKeys = array_diff($actualKeys, $expectedKeys);

            if (count($excedingKeys) > 0) {

                return $this->raiseFailure(
                    "The object was not expected to have the attributes defined at [\"" . join("\", \"", $excedingKeys) . "\"]."
                );

            }
        }

        foreach ($expectedObject as $expectedKey => $expectedValue) {

            if (empty($attributePath)) {
                $childAttributePath = $expectedKey;
            } else {
                $childAttributePath = $attributePath . "." . $expectedKey;
            }

            $childValue = AttributeReader::readAttribute(
                $this->actualValue,
                $expectedKey,
                function() use($childAttributePath) {
                    $this->raiseFailure(
                        "The object was expected to have an attribute defined at {$this->valueString($childAttributePath)}."
                    );
                }
            );

            $this->evaluateClosure(function () use ($childValue, $expectedValue, $childAttributePath) {

                $this->expect($childValue)->to()->be()
                    ->exactlyLike($expectedValue, $childAttributePath);

            });
        }

    });
});

/// Type expectations

ValueExpectationsLibrary::defineExpectation('string', function () {

    $this->before(function () {

        $this->actualComparison = is_string($this->actualValue);

    });

    $this->assertWith(function () {

        if ($this->actualComparison) {
            return;
        }

        return $this->raiseFailure(
            "Expected value to be a string, got {$this->valueString($this->actualValue)}."
        );
    });

    $this->negateWith(function () {

        if (!$this->actualComparison) {
            return;
        }

        return $this->raiseFailure(
            "Expected value not to be a string, got {$this->valueString($this->actualValue)}."

        );
    });
});

ValueExpectationsLibrary::defineExpectation("int", function () {

    $this->before(function () {

        $this->actualComparison = is_int($this->actualValue);

    });

    $this->assertWith(function () {

        if ($this->actualComparison) {
            return;
        }

        return $this->raiseFailure(
            "Expected value to be an int, got {$this->valueString($this->actualValue)}."
        );
    });

    $this->negateWith(function () {

        if (!$this->actualComparison) {
            return;
        }

        return $this->raiseFailure(
            "Expected value not to be an int, got {$this->valueString($this->actualValue)}."

        );
    });
});

ValueExpectationsLibrary::defineExpectation('double', function () {

    $this->before(function () {

        $this->actualComparison = is_double($this->actualValue);

    });

    $this->assertWith(function () {

        if ($this->actualComparison) {
            return;
        }

        return $this->raiseFailure(
            "Expected value to be a double, got {$this->valueString($this->actualValue)}."
        );
    });

    $this->negateWith(function () {

        if (!$this->actualComparison) {
            return;
        }

        return $this->raiseFailure(
            "Expected value not to be a double, got {$this->valueString($this->actualValue)}."

        );
    });
});

ValueExpectationsLibrary::defineExpectation('number', function () {

    $this->before(function () {

        $this->actualComparison =
            is_int($this->actualValue) || is_double($this->actualValue);

    });

    $this->assertWith(function () {

        if ($this->actualComparison) {
            return;
        }

        return $this->raiseFailure(
            "Expected value to be a number, got {$this->valueString($this->actualValue)}."
        );
    });

    $this->negateWith(function () {

        if (!$this->actualComparison) {
            return;
        }

        return $this->raiseFailure(
            "Expected value not to be a number, got {$this->valueString($this->actualValue)}."

        );
    });
});

ValueExpectationsLibrary::defineExpectation('bool', function () {

    $this->before(function () {

        $this->actualComparison = is_bool($this->actualValue);

    });

    $this->assertWith(function () {

        if ($this->actualComparison) {
            return;
        }

        return $this->raiseFailure(
            "Expected value to be a bool, got {$this->valueString($this->actualValue)}."
        );
    });

    $this->negateWith(function () {

        if (!$this->actualComparison) {
            return;
        }

        return $this->raiseFailure(
            "Expected value not to be a bool, got {$this->valueString($this->actualValue)}."

        );
    });
});

ValueExpectationsLibrary::defineExpectation('array', function () {

    $this->before(function () {

        $this->actualComparison = is_array($this->actualValue);

    });

    $this->assertWith(function () {

        if ($this->actualComparison) {
            return;
        }

        return $this->raiseFailure(
            "Expected value to be an array, got {$this->valueString($this->actualValue)}."
        );
    });

    $this->negateWith(function () {

        if (!$this->actualComparison) {
            return;
        }

        return $this->raiseFailure(
            'Expected value not to be an array, got an array.'

        );
    });
});

ValueExpectationsLibrary::defineExpectation('a', function () {

    $this->before(function ($className) {

        $this->actualComparison = is_a($this->actualValue, $className);

    });

    $this->assertWith(function ($className) {

        if ($this->actualComparison) {
            return;
        }

        return $this->raiseFailure(
            "Expected value to be a kind of {$className}, got {$this->valueString($this->actualValue)}."
        );
    });

    $this->negateWith(function ($className) {

        if (!$this->actualComparison) {
            return;
        }

        return $this->raiseFailure(
            "Expected value not to be a kind of {$className}, got a kind of {$className}."

        );
    });
});

ValueExpectationsLibrary::defineExpectation('instanceOf', function () {

    $this->before(function ($className) {

        $this->actualComparison = $this->actualValue instanceof $className;

    });

    $this->assertWith(function ($className) {

        if ($this->actualComparison) {
            return;
        }

        return $this->raiseFailure(
            "Expected value to be an instance of {$className}, got {$this->valueString($this->actualValue)}."
        );
    });

    $this->negateWith(function ($className) {

        if (!$this->actualComparison) {
            return;
        }

        return $this->raiseFailure(
            "Expected value not to be an instance of {$className}, got an instance of {$className}."

        );
    });
});

/// Strings expectations

ValueExpectationsLibrary::defineExpectation('beginWith', function () {

    $this->before(function ($expectedValue) {

        if ($expectedValue === '') {
            $this->actualComparison = true;

            return;
        }

        $this->actualComparison =
            strpos($this->actualValue, $expectedValue) === 0;

    });

    $this->assertWith(function ($expectedValue) {

        if ($this->actualComparison) {
            return;
        }

        return $this->raiseFailure(
            "Expected {$this->valueString($this->actualValue)} to begin with {$this->valueString($expectedValue)}."
        );
    });

    $this->negateWith(function ($expectedValue) {

        if (!$this->actualComparison) {
            return;
        }

        return $this->raiseFailure(
            "Expected {$this->valueString($this->actualValue)} not to begin with {$this->valueString($expectedValue)}."
        );
    });
});


ValueExpectationsLibrary::defineExpectation('endWith', function () {

    $this->before(function ($expectedValue) {

        if ($expectedValue === '') {
            $this->actualComparison = true;

            return;
        }

        $this->actualComparison =
            strrpos($this->actualValue, $expectedValue)
            ==
            strlen($this->actualValue) - strlen($expectedValue);

    });

    $this->assertWith(function ($expectedValue) {

        if ($this->actualComparison) {
            return;
        }

        return $this->raiseFailure(
            "Expected {$this->valueString($this->actualValue)} to end with {$this->valueString($expectedValue)}."
        );
    });

    $this->negateWith(function ($expectedValue) {

        if (!$this->actualComparison) {
            return;
        }

        return $this->raiseFailure(
            "Expected {$this->valueString($this->actualValue)} not to end with {$this->valueString($expectedValue)}."
        );
    });
});

ValueExpectationsLibrary::defineExpectation('contain', function () {

    $this->before(function ($expectedValue) {

        if ($expectedValue === "") {
            $this->actualComparison = true;

            return;
        }

        $this->actualComparison =
            strpos($this->actualValue, $expectedValue) !== false;

    });

    $this->assertWith(function ($expectedValue) {

        if ($this->actualComparison) {
            return;
        }

        return $this->raiseFailure(
            "Expected {$this->valueString($this->actualValue)} to contain {$this->valueString($expectedValue)}."
        );
    });

    $this->negateWith(function ($expectedValue) {

        if (!$this->actualComparison) {
            return;
        }

        return $this->raiseFailure(
            "Expected {$this->valueString($this->actualValue)} not to contain {$this->valueString($expectedValue)}."
        );
    });
});

ValueExpectationsLibrary::defineExpectation('match', function () {

    $this->before(function ($expectedRegexp, $matchingClosure = null) {

        $this->matches = [];
        $this->actualComparison =
            preg_match($expectedRegexp, $this->actualValue, $this->matches) !== 0;

    });

    $this->assertWith(function ($expectedRegexp, $matchingClosure = null) {

        if ($this->actualComparison) {

            if ($matchingClosure !== null) {
                $this->evaluateClosure($matchingClosure, $this->matches);
            }

            return;
        }

        return $this->raiseFailure(
            "Expected {$this->valueString($this->actualValue)} to match {$this->valueString($expectedRegexp)}."
        );

    });

    $this->negateWith(function ($expectedRegexp) {

        if (!$this->actualComparison) {
            return;
        }

        return $this->raiseFailure(
            "Expected {$this->valueString($this->actualValue)} not to match {$this->valueString($expectedRegexp)}."
        );
    });
});

/// Array expectations

ValueExpectationsLibrary::defineExpectation('include', function () {

    $this->before(function ($expectedValue) {

        $this->actualComparison = in_array($expectedValue, $this->actualValue);

    });

    $this->assertWith(function ($expectedRegexp, $matchingClosure = null) {

        if ($this->actualComparison) {
            return;
        }

        return $this->raiseFailure(
            "Expected array to include {$this->valueString($expectedRegexp)}."
        );

    });

    $this->negateWith(function ($expectedRegexp) {

        if (!$this->actualComparison) {
            return;
        }

        return $this->raiseFailure(
            "Expected array not to include {$this->valueString($expectedRegexp)}."
        );
    });
});

ValueExpectationsLibrary::defineExpectation('includeAll', function () {

    $this->before(function ($expectedValues) {

        $this->actualComparison =
            array_diff($expectedValues, $this->actualValue) == [];

    });

    $this->assertWith(function ($expectedValues) {

        if ($this->actualComparison) {
            return;
        }

        return $this->raiseFailure(
            'Expected array to include all the expected values.'
        );

    });

    $this->negateWith(function ($expectedValues) {

        if (!$this->actualComparison) {
            return;
        }

        return $this->raiseFailure(
            'Expected array not to include all the expected values.'
        );
    });
});

ValueExpectationsLibrary::defineExpectation('includeAny', function () {

    $this->before(function ($expectedValues) {

        $this->actualComparison =
            array_diff($this->actualValue, $expectedValues) != $this->actualValue;

    });

    $this->assertWith(function ($expectedValues) {

        if ($this->actualComparison) {
            return;
        }

        return $this->raiseFailure(
            'Expected array to include any of the expected values.'
        );

    });

    $this->negateWith(function ($expectedValues) {

        if (!$this->actualComparison) {
            return;
        }

        return $this->raiseFailure(
            'Expected array not to include any of the expected values.'
        );
    });
});

ValueExpectationsLibrary::defineExpectation('includeNone', function () {

    $this->before(function ($expectedValues) {

        $this->actualComparison =
            array_diff($this->actualValue, $expectedValues) == $this->actualValue;

    });

    $this->assertWith(function ($expectedValues) {

        if ($this->actualComparison) {
            return;
        }

        return $this->raiseFailure(
            'Expected array to include none of the expected values.'
        );

    });

    $this->negateWith(function ($expectedValues) {

        if (!$this->actualComparison) {
            return;
        }

        return $this->raiseFailure(
            'Expected array not to include none of the expected values.'
        );
    });
});

ValueExpectationsLibrary::defineExpectation('includeKey', function () {

    $this->before(function ($expectedKey, $valueClosure = null) {

        $this->actualComparison = array_key_exists($expectedKey, $this->actualValue);

    });

    $this->assertWith(function ($expectedKey, $valueClosure = null) {

        if ($this->actualComparison) {

            if ($valueClosure !== null) {
                $this->evaluateClosure($valueClosure, $this->actualValue[$expectedKey]);
            }

            return;
        }

        return $this->raiseFailure(
            "Expected array to include key {$this->valueString($expectedKey)}."
        );

    });

    $this->negateWith(function ($expectedKey, $valueClosure = null) {

        if (!$this->actualComparison) {
            return;
        }

        return $this->raiseFailure(
            "Expected array not to include key {$this->valueString($expectedKey)}."
        );
    });
});

ValueExpectationsLibrary::defineExpectation('includeValue', function () {

    $this->before(function ($expectedValue) {

        $this->actualComparison = in_array($expectedValue, $this->actualValue);

    });

    $this->assertWith(function ($expectedValue) {

        if ($this->actualComparison) {
            return;
        }

        return $this->raiseFailure(
            "Expected array to include value {$this->valueString($expectedValue)}."
        );

    });

    $this->negateWith(function ($expectedValue) {

        if (!$this->actualComparison) {
            return;
        }

        return $this->raiseFailure(
            "Expected array not to include value {$this->valueString($expectedValue)}."
        );
    });
});

/// File expectations

ValueExpectationsLibrary::defineExpectation("aFile", function () {

    $this->before(function () {

        $this->actualComparison =
            file_exists($this->actualValue) && is_file($this->actualValue);

    });

    $this->assertWith(function () {

        if ($this->actualComparison) {
            return;
        }

        return $this->raiseFailure(
            "Expected the file {$this->valueString($this->actualValue)} to exist."
        );

    });

    $this->negateWith(function () {

        if (!$this->actualComparison) {
            return;
        }

        return $this->raiseFailure(
            "Expected the file {$this->valueString($this->actualValue)} not to exist."
        );
    });
});

ValueExpectationsLibrary::defineExpectation('haveFileContents', function () {

    $this->assertWith(function ($contentsClosure) {

        if (!file_exists($this->actualValue)) {

            return $this->raiseFailure(
                "Expected the file {$this->valueString($this->actualValue)} to have contents, but is does not exist."
            );

        }

        $fileContents = file_get_contents($this->actualValue);

        if ($fileContents === false) {

            return $this->raiseFailure(
                "Expected the file {$this->valueString($this->actualValue)} to have contents, but could not read its contents."
            );

        }

        $this->evaluateClosure($contentsClosure, $fileContents);
    });
});

ValueExpectationsLibrary::defineExpectation('aDirectory', function () {

    $this->before(function () {

        $this->actualComparison =
            file_exists($this->actualValue) && !is_file($this->actualValue);

    });

    $this->assertWith(function () {

        if ($this->actualComparison) {
            return;
        }

        return $this->raiseFailure(
            "Expected the directory {$this->valueString($this->actualValue)} to exist."
        );

    });

    $this->negateWith(function () {

        if (!$this->actualComparison) {
            return;
        }

        return $this->raiseFailure(
            "Expected the directory {$this->valueString($this->actualValue)} not to exist."
        );
    });
});

ValueExpectationsLibrary::defineExpectation('haveDirectoryContents', function () {

    $this->assertWith(function ($contentsClosure) {

        if (!file_exists($this->actualValue)) {

            return $this->raiseFailure(
                "Expected the directory {$this->valueString($this->actualValue)} to have contents, but is does not exist."
            );

        }

        if (is_file($this->actualValue)) {

            return $this->raiseFailure(
                "Expected {$this->valueString($this->actualValue)} to be a directory, but it is a file."
            );

        }

        $filesInDirectory = scandir($this->actualValue);

        $this->evaluateClosure($contentsClosure, $filesInDirectory, $this->actualValue);
    });

});

/// Exception expectations

ValueExpectationsLibrary::defineExpectation('raise', function () {

    $this->assertWith(function (
        $expectedExceptionClassName, $expectedExceptionClosure = null) {

        $raisedException = null;

        try {

            $this->evaluateClosure($this->actualValue);

        } catch (Exception $e) {

            $raisedException = $e;

        }

        if ($raisedException === null) {

            return $this->raiseFailure(
                "Expected the closure to raise a {$expectedExceptionClassName}, but no Exception was raised."
            );

        }

        $raisedExceptionClassName = get_class($raisedException);

        if ($raisedExceptionClassName != $expectedExceptionClassName) {

            return $this->raiseFailure(
                "Expected the closure to raise a {$expectedExceptionClassName}, but a {$raisedExceptionClassName} was raised instead."
            );

        }

        if ($expectedExceptionClosure !== null) {

            $this->evaluateClosure($expectedExceptionClosure, $raisedException);

        }
    });

    $this->negateWith(function ($expectedExceptionClassName) {

        $raisedExceptionClassName = null;

        try {

            $this->evaluateClosure($this->actualValue);

        } catch (Exception $e) {

            $raisedExceptionClassName = get_class($e);

        }

        if ($raisedExceptionClassName == $expectedExceptionClassName) {

            return $this->raiseFailure(
                "Expected the closure not to raise a {$expectedExceptionClassName}, but a {$raisedExceptionClassName} was raised."
            );

        }

    });

});