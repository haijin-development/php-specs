<?php
declare(strict_types=1);

namespace Haijin\Specs\Mocks;

/**
 * A Dummy is an object that does nothing but allows to define its behaviour with closures.
 *
 *
 * This object models an unexpected error thrown during the execution of a spec.
 */
class Dummy
{
    protected $name;
    protected $methods;
    protected $evaluationsCounter = 0;

    public function __construct($name = 'dummyObject')
    {
        $this->name = $name;
        $this->methods = [];
    }

    public function on(string $methodName, $callable)
    {
        $this->methods[$methodName] = new DummyMethod($methodName, $callable);

        return $this;
    }

    public function __call(string $methodName, array $arguments)
    {
        if( !isset($this->methods[$methodName])) {
            throw new \RuntimeException("The method '$methodName' was not defined in $this->name.");
        }

        return $this->methods[$methodName](...$arguments);
    }
}