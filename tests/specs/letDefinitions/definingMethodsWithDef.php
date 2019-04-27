<?php
declare(strict_types=1);

use Haijin\Specs\Errors\UndefinedMethodError;
use Haijin\Specs\Runners\SpecsGlobalContextConfiguration;
use Haijin\Specs\Runners\SpecsRunner;

$spec->describe( "When defining a method with def", function() {

    $this->def( "sum", function($n, $m) {
        return $n + $m;
    });


    $this->it( "evaluates the method when its called", function(){

        $this->expect( $this->sum( 3, 4 ) ) ->to() ->equal( 7 );

    });

    $this->describe( "When defining a method in an inner scope", function() {

        $this->def( "inc", function($n) {
            return $n + 1;
        });

        $this->it( "evaluates the method", function(){

            $this->expect( $this->inc( 3 ) ) ->to() ->equal( 4 );

        });

    });

    $this->it( "raises an error with a method defined in an inner scope called from an outer scope", function(){

        $this->expect( function() {

            $this->expect( $this->inc( 3 ) ) ->to() ->equal( 4 );

        }) ->to() ->raise( UndefinedMethodError::class, function($error) {

            $this->expect( $error->getMessage() ) ->to()
                ->equal( "Undefined method named 'inc'." );

            $this->expect( $error->getMethodName() ) ->to()
                ->equal( "inc" );

        });

    });

    $this->describe( "that calls another defined method", function() {

        $this->def( "sumAndInc", function($n, $m) {
            return $this->sum( $n, $m ) + 3;
        });

        $this->it( "evaluates the method", function(){

            $this->expect( $this->sumAndInc( 3, 4 ) ) ->to() ->equal( 10 );

        });

    });

    $this->describe( "in the Specs_Runner config", function() {

        $this->let( 'initialSpecsContextDefinitions', function() {
            return SpecsGlobalContextConfiguration::configure( function($specs) {

                $this->def( "sum", function($n, $m) {
                    return $n + $m;
                });

            });

        });

        $this->let( "specRunner", function() {
            return new SpecsRunner();
        });

        $this->let( "specFile", function() {
            return __DIR__ .
                "/../../specsSamples/specWithGlobalMethodReference.php";
        });

        $this->it( "evaluates the expression when its referenced", function(){

            $this->specRunner->runSpecFile( $this->specFile, null, $this->initialSpecsContextDefinitions );

            $this->expect( $this->specRunner->getInvalidExpectations() ) ->to() ->equal( [] );

        });

    });

    $this->describe( "in the specs file", function() {

        $this->let( "specRunner", function() {
            return new SpecsRunner();
        });

        $this->let( "specFile", function() {
            return __DIR__ .
                "/../../specsSamples/specWithDefMethods.php";
        });

        $this->it( "evaluates the expression when its referenced", function(){

            $this->specRunner->runSpecFile($this->specFile);

            $this->expect( $this->specRunner->getInvalidExpectations() ) ->to() ->equal( [] );

        });

    });

});