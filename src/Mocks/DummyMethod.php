<?php
declare(strict_types=1);

namespace Haijin\Specs\Mocks;

class DummyMethod
{
    protected $name;
    protected $evaluationsCounter = 0;
    protected $callable;

    public function __construct(string $name, \Closure $callable)
    {
        $this->name = $name;
        $this->callable = $callable;
        $this->evaluationsCounter = 0;
    }

    public function __invoke(...$parameters)
    {
        $this->evaluationsCounter += 1;

        $parameters[] = $this->evaluationsCounter;

        return ($this->callable)(...$parameters);
    }
}