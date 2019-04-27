<?php
declare(strict_types=1);

use Haijin\Specs\Runners\SpecsRunner;

$spec->describe( "When running specs from a file or folder", function() {

    $this->let( "specRunner", function() {
        return new SpecsRunner();
    });

    $this->describe( "each failed expectation", function(){

        $this->let( "specFile", function() {
            return __DIR__ . "/../../specsSamples/singleSpecFailure.php";
        });

        $this->it( "has the failure message", function() {

            $this->specRunner->runOn( $this->specFile );

            $failedExpectations = $this->specRunner->getInvalidExpectations();

            $this->expect( $failedExpectations[0]->getMessage() )
                ->to() ->equal( "Expected value to equal 2, got 1." );

        });

        $this->it( "has the nested description", function() {

            $this->specRunner->runOn( $this->specFile );

            $failedExpectations = $this->specRunner->getInvalidExpectations();

            $this->expect( $failedExpectations[0]->getDescription() )
                ->to() ->equal( "A single spec fails" );

        });

        $this->it( "has the file name", function() {

            $this->specRunner->runOn( $this->specFile );

            $failedExpectations = $this->specRunner->getInvalidExpectations();

            $this->expect( $failedExpectations[0]->getSpecFileName() )
                ->to() ->endWith( "specsSamples/singleSpecFailure.php" );

        });

        $this->it( "has the number of line of the failed expectation in the file", function() {

            $this->specRunner->runOn( $this->specFile );

            $failedExpectations = $this->specRunner->getInvalidExpectations();

            $this->expect( $failedExpectations[0]->getExpectationLine() ) ->to() ->equal( 8 );

        });

    });

    $this->describe( "the expectation runner statistics", function(){

        $this->let( "specFile", function() {
            return __DIR__ .
                "/../../specsSamples/specWithOneFailureOneErrorAndTwoSuccessOneSkip.php";
        });

        $this->it( "has the run specs count", function() {

            $this->specRunner->runOn( $this->specFile );

            $count = $this->specRunner->getStatistics()->runSpecsCount();

            $this->expect( $count ) ->to() ->equal( 4 );
        });

        $this->it( "has the skipped specs count", function() {

            $this->specRunner->runOn( $this->specFile );

            $count = $this->specRunner->getStatistics()->skippedSpecsCount();

            $this->expect( $count ) ->to() ->equal( 1 );
        });

        $this->it( "has the failures count", function() {

            $this->specRunner->runOn( $this->specFile );

            $count = $this->specRunner->getStatistics()->failedSpecsCount();

            $this->expect( $count ) ->to() ->equal( 1 );
        });

        $this->it( "has the errors count", function() {

            $this->specRunner->runOn( $this->specFile );

            $count = $this->specRunner->getStatistics()->erroredSpecsCount();

            $this->expect( $count ) ->to() ->equal( 1 );
        });

        $this->it( "has the run expectations count", function() {

            $this->specRunner->runOn( $this->specFile );

            $count = $this->specRunner->getStatistics()->runExpectationsCount();

            $this->expect( $count ) ->to() ->equal( 3 );
        });
    });

    $this->describe( "with a onSpecRunDo closure defined", function() {

        $this->let( "specFile", function() {
            return __DIR__ .
                "/../../specsSamples/specWithOneFailureOneErrorAndTwoSuccessOneSkip.php";
        });

        $this->it( "evaluates the onSpecRunDo after each spec run", function() {

            $this->specsEvaluations = 0;

            $context = $this;

            $this->specRunner->setOnSpecRunCallable( function($spec, $status) use($context) {

                $context->specsEvaluations += 1;

            });

            $this->specRunner->runOn( $this->specFile );

            $this->expect( $this->specsEvaluations ) ->to() ->equal( 5 );

        });

    });

    $this->describe( "with a line number defined", function() {

        $this->let( "specFile", function() {
            return __DIR__ .
                "/../../specsSamples/specWithOneFailureOneErrorAndTwoSuccessOneSkip.php";
        });

        $this->let( "lineNumber", function() {
            return 14;
        });

        $this->it( "evaluates the onSpecRunDo after each spec run", function() {

            $this->specRunner->runOn( $this->specFile, $this->lineNumber );

            $statistics = $this->specRunner->getStatistics();

            $this->expect( $statistics->runSpecsCount() ) ->to() ->equal( 1 );
            $this->expect( $statistics->failedSpecsCount() ) ->to() ->equal( 1 );

        });

    });

    $this->describe( "if its a folder", function() {

        $this->let( "specsFolder", function() {
            return __DIR__ .
                "/../../specsSamples/folder";
        });

        $this->it( "evaluates the onSpecRunDo after each spec run", function() {

            $this->specRunner->runOn( $this->specsFolder );

            $statistics = $this->specRunner->getStatistics();

            $this->expect( $statistics->runSpecsCount() ) ->to() ->equal( 1 );

        });

    });

});