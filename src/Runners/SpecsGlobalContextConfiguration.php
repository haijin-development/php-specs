<?php
declare(strict_types=1);

namespace Haijin\Specs\Runners;

use Closure;
use Haijin\Specs\Specs\SpecContextDefinitions;

/**
 * Configures a SpecContextDefinitions that can be used as a global context definition when running specs.
 * The configuration includes definitions of before and after closures, ->let() properties and ->def() functions
 * to evaluate before and after running the specs.
 *
 * @package Haijin\Specs\Runners
 */
class SpecsGlobalContextConfiguration
{
    /**
     * @var SpecContextDefinitions The definition of the specs global to configure.
     */
    protected $specContextDefinitions;

    /**
     * SpecsGlobalContextConfiguration constructor.
     */
    public function __construct()
    {
        $this->specContextDefinitions = new SpecContextDefinitions();
    }

    /// Initializing

    /**
     * Creates and configures a new SpecContextDefinitions.
     * This SpecContextDefinitions can later be used in the SpecsRunner to define definitions globally to all the
     * specs run.
     *
     * Example:
     *          specsContextDefinitions = SpecsGlobalContextConfiguration::configure( function($specs) {
     *              $specs->beforeAll( function() {
     *                  $this->database = new Database();
     *              });
     *
     *              $specs->beforeEach( function() {
     *                  $this->records = $this->>database()->queryRecords();
     *              });
     *
     *              $specs->afterAll( function() {
     *                  $this->database->close();
     *              });
     *          });
     *
     *          $specRunner->runSpecFile($specFile, null, $specsContextDefinitions);
     *
     * @param Closure $configurationClosure The configuration Closure. It signature is
     *  function(SpecsGlobalContextConfiguration $specs){}
     * @return SpecContextDefinitions A new SpecContextDefinitions configured with the given configuration closure.
     */
    static public function configure(Closure $configurationClosure): SpecContextDefinitions
    {
        $specsConfigDSL = new self();

        $configurationClosure->call($specsConfigDSL, $specsConfigDSL);

        return $specsConfigDSL->getSpecContextDefinitions();
    }

    /// Accessing

    /**
     * Returns the created and configured SpecContextDefinitions.
     *
     * @return SpecContextDefinitions The created and configured SpecContextDefinitions.
     */
    public function getSpecContextDefinitions(): SpecContextDefinitions
    {
        return $this->specContextDefinitions;
    }

    /// DSL

    /**
     * Defines a beforeAll closure to evaluate before the evaluation of any spec.
     *
     * @param Closure $closure The closure to evaluate before any spec.
     */
    public function beforeAll(Closure $closure): void
    {
        $this->specContextDefinitions->setBeforeAllClosure($closure);
    }

    /**
     * Defines an afterAll closure to evaluate after the evaluation of all of the specs.
     *
     * @param Closure $closure The closure to evaluate after all the specs.
     */
    public function afterAll(Closure $closure): void
    {
        $this->specContextDefinitions->setAfterAllClosure($closure);
    }

    /**
     * Defines a beforeEach closure to evaluate before the evaluation of each single Spec.
     *
     * @param Closure $closure The closure to evaluate before each single Spec.
     */
    public function beforeEach(Closure $closure): void
    {
        $this->specContextDefinitions->setBeforeEachClosure($closure);
    }

    /**
     * Defines an afterEach closure to evaluate after the evaluation of each single Spec.
     *
     * @param Closure $closure The closure to evaluate after each single Spec.
     */
    public function afterEach(Closure $closure): void
    {
        $this->specContextDefinitions->setAfterEachClosure($closure);
    }

    /**
     * Defines a lazy spec property that is instantiated on its first use.
     *
     * @param string $propertyName The name of the spec property.
     * @param Closure $closure The closure to evaluate to instantiate the property.
     */
    public function let(string $propertyName, Closure $closure): void
    {
        $this->specContextDefinitions->atPropertyPut($propertyName, $closure);
    }

    /**
     * Defines a custom spec method.
     *
     * @param string $methodName The name of the custom method.
     * @param Closure $closure The Closure that is evaluated when the method is called.
     */
    public function def(string $methodName, Closure $closure): void
    {
        $this->specContextDefinitions->atMethodPut($methodName, $closure);
    }
}