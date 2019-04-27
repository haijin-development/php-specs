<?php
declare(strict_types=1);

namespace Haijin\Specs\Tools;

use RuntimeException;

/**
 * Private.
 * Object to read an attribute using an array index, an object property or an object getter with a common protocol.
 *
 * @package Haijin\Specs\Tools
 */
class AttributeReader
{
    /**
     * Reads and returns an attribute value from an array or an object.
     * Examples:
     *
     *      AttributeReader::readAttribute( $array, 0 );
     *      AttributeReader::readAttribute( $object, 'name' );
     *      AttributeReader::readAttribute( $object, 'getName' );
     *
     * @param any $objectOrArray The object or array to read attribute from.
     * @param string|int $attribute If the $objectOrArray is an array the index in the array to read from.
     *  If the $objectOrArray is an object a property name or the name of a getter method to read the attribute from.
     * @param $absentClosure A closure to evaluate in case the attribute is not defined.
     * @return any The attribute value. If the attribute is missing and an $absentClosure is given returns
     *  the result of evaluating the $absentClosure or the default value. If the attribute is missing an no closure is
     *  given raises an error.
     */
    static public function readAttribute($objectOrArray, $attribute, $absentClosure = null)
    {
        return (new self($objectOrArray, $attribute))->read($absentClosure);
    }

    /**
     * AttributeReader constructor.
     *
     * @param any $objectOrArray The object or array to read attribute from.
     * @param string|int $attribute If the $objectOrArray is an array the index in the array to read from.
     *  If the $objectOrArray is an object a property name or the name of a getter method to read the attribute from.
     */
    public function __construct($objectOrArray, $attribute)
    {
        $this->object = $objectOrArray;
        $this->attribute = $attribute;
    }

    /**
     * Returns the value of the attribute read from the object.
     * If the attribute is missing and an $absentClosure is given returns the result of the evaluation of the
     * $absentClosure.
     * If the attribute is missing and no closure is given raises an error.
     *
     * @return any The value of the attribute read from the object.
     */
    public function read($absentClosure = null)
    {
        if (!$this->hasAttributeDefined()) {
            if($absentClosure === null) {
                $absentClosure = function () {
                    throw new \RuntimeException("Missing attribute {$this->attribute}.");
                };
            }

            return $absentClosure();
        }

        return $this->readValue();
    }

    /**
     * Returns true if the object or array has the attribute defined even with a null value, false otherwise.
     *
     * @return bool True if the object or array has the attribute defined even with a null value, false if not.
     */
    public function hasAttributeDefined(): bool
    {
        if ($this->isReadingArrayAttribute()) {
            return array_key_exists($this->attribute, $this->object);
        }

        if ($this->isReadingObjectProperty()) {
            return property_exists($this->object, $this->attribute);
        }

        if ($this->isReadingObjectGetter()) {
            return method_exists($this->object, substr($this->attribute, 0, -2));
        }

        return false;
    }

    /**
     * Returns true if this object is reading an indexed attribute from an array, false otherwise.
     *
     * @return bool true if this object is reading an indexed attribute from an array.
     */
    protected function isReadingArrayAttribute(): bool
    {
        return is_array($this->object);
    }

    /**
     * Returns true if this object is reading a property from an object, false otherwise.
     *
     * @return bool true if this object is reading a property from an object, false otherwise.
     */
    protected function isReadingObjectProperty(): bool
    {
        return is_object($this->object) && !$this->isReadingObjectGetter();
    }

    /**
     * Returns true if this object is reading a getter method from an object, false otherwise.
     *
     * @return bool true if this object is reading a getter method from an object, false otherwise.
     */
    protected function isReadingObjectGetter(): bool
    {
        return is_object($this->object) && substr($this->attribute, -2) == "()";
    }

    /**
     * Read and returns the attribute value.
     * Assumes that the attribute does exist.
     *
     * @return bool true if this object is reading a getter method from an object, false otherwise.
     */

    protected function readValue()
    {
        if ($this->isReadingArrayAttribute()) {
            return $this->object[$this->attribute];
        }

        if ($this->isReadingObjectGetter()) {
            $getter = substr($this->attribute, 0, -2);

            return $this->object->$getter();
        }

        $property = $this->attribute;

        return $this->object->$property;
    }
}