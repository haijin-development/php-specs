<?php
declare(strict_types=1);

namespace Haijin\Specs\Specs;

/**
 * Class SpecContextDefinitions.
 * This object holds the definitions made in the context of a SpecDescription.
 * The definitions are:
 *      - beforeAll() closure
 *      - beforeEach() closure
 *      - afterEach() closure
 *      - afterAll() closure
 *      - properties defined with let()
 *      - custom methods defined with def()
 *
 * @package Haijin\Specs\Specs
 */
class SpecContextDefinitions
{
    /**
     * @var \Closure
     */
    protected $beforeAllClosure;
    /**
     * @var \Closure
     */
    protected $beforeEachClosure;
    /**
     * @var \Closure
     */
    protected $afterAllClosure;
    /**
     * @var \Closure
     */
    protected $afterEachClosure;
    /**
     * @var array
     */
    protected $properties;
    /**
     * @var array
     */
    protected $methods;

    /// Initializing

    public function __construct()
    {
        $this->beforeAllClosure = null;
        $this->beforeEachClosure = null;
        $this->afterAllClosure = null;
        $this->afterEachClosure = null;
        $this->properties = [];
        $this->methods = [];
    }

    /// Callbacks

    /**
     * @return \Closure
     */
    public function getBeforeAllClosure(): ?\Closure
    {
        return $this->beforeAllClosure;
    }

    /**
     * @param \Closure $beforeAllClosure
     */
    public function setBeforeAllClosure(?\Closure $beforeAllClosure): void
    {
        $this->beforeAllClosure = $beforeAllClosure;
    }

    /**
     * @return \Closure
     */
    public function getBeforeEachClosure(): ?\Closure
    {
        return $this->beforeEachClosure;
    }

    /**
     * @param \Closure $beforeEachClosure
     */
    public function setBeforeEachClosure(?\Closure $beforeEachClosure): void
    {
        $this->beforeEachClosure = $beforeEachClosure;
    }

    /**
     * @return \Closure
     */
    public function getAfterAllClosure(): ?\Closure
    {
        return $this->afterAllClosure;
    }

    /**
     * @param \Closure $afterAllClosure
     */
    public function setAfterAllClosure(?\Closure $afterAllClosure): void
    {
        $this->afterAllClosure = $afterAllClosure;
    }

    /**
     * @return \Closure
     */
    public function getAfterEachClosure(): ?\Closure
    {
        return $this->afterEachClosure;
    }

    /**
     * @param \Closure $afterEachClosure
     */
    public function setAfterEachClosure(?\Closure $afterEachClosure): void
    {
        $this->afterEachClosure = $afterEachClosure;
    }

    /// Named expressions

    public function atPropertyPut($propertyName, $closure)
    {
        $this->properties[$propertyName] = $closure;
    }

    public function getProperties()
    {
        return $this->properties;
    }

    /// Methods

    public function atMethodPut($methodName, $closure)
    {
        $this->methods[$methodName] = $closure;
    }

    public function getMethods()
    {
        return $this->methods;
    }
}