<?php
declare(strict_types=1);

namespace Haijin\Specs\Tools;

/**
 * Helper class to print object values in the specs report.
 *
 * @package Haijin\Specs\Tools
 */
class ValuePrinter
{
    /**
     * Returns a human readable string representation of a value.
     *
     * @param any $value The value to print.
     * @return string The printable string of the value.
     */
    static function printStringOf($value): string
    {
        if (is_string($value)) {
            return "\"" . $value . "\"";
        }

        if ($value === null) {
            return "null";
        }

        if ($value === true) {
            return "true";
        }

        if ($value === false) {
            return "false";
        }

        if (is_object($value)) {
            $className = get_class($value);
            if (preg_match('|^[AEIOU]|i', $className)) {
                return "an " . $className;
            } else {
                return "a " . $className;
            }
        }

        if (is_array($value)) {
            $printStrings = [];

            foreach ($value as $key => $value) {
                $printStrings[] = self::printStringOf($key) . " => " . self::printStringOf($value);
            }


            return "[" . join(", ", $printStrings) . "]";
        }

        return (string)$value;
    }

}
