<?php
declare(strict_types=1);

use Haijin\Specs\Runners\SpecsGlobalContextConfiguration;
use Haijin\Specs\Runners\SpecsRunner;

$spec->describe( "When defining variables in before and after closures", function() {

    $this->let( 'initialSpecsContextDefinitions', function() {
        return SpecsGlobalContextConfiguration::configure( function($specs) {

            $specs->beforeAll( function() {
                $this->beforeAll = true;
            });

            $specs->afterAll( function() {
                $this->afterAll = true;
            });

            $specs->beforeEach( function() {
                $this->beforeEach = true;
            });

            $specs->afterEach( function() {
                $this->afterEach = true;
            });

        });

    });

    $this->let( "specRunner", function() {
        return new SpecsRunner();
    });

    $this->let( "specFile", function() {
        return __DIR__ .
            "/../../specsSamples/specWithVariablesDefinedClosures.php";
    });

    $this->it( "the variables are defined during the scope of the expression in which they were defined", function() {

        $this->specRunner->runSpecFile( $this->specFile, null, $this->initialSpecsContextDefinitions );

        $this->expect( $this->specRunner->getInvalidExpectations() ) ->to() ->equal( [] );

    });

});