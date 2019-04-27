<?php
declare(strict_types=1);

use Haijin\Specs\Errors\ExpectationFailureSignal;

$spec->describe( "When expecting a closure to raise an Exception", function() {

    $this->describe( "with no expectation closure on the raised Exception", function() {

        $this->it( "the spec passes if the exception is raised", function() {

            $errorRaised = false;

            try {

                $this->expect( function() {

                    throw new RuntimeException( "RuntimeException raised" );

                }) ->to() ->raise( RuntimeException::class );

            } catch(Exception $e ) {

                $errorRaised = true;

            }

            if( $errorRaised === true ) {
                throw new RuntimeException(
                    "->raise($exception) failed in catching an expected exception."
                );
            }

        });

        $this->it( "the spec fails if no exception is raised", function() {

            $errorRaised = false;

            try {

                $this->expect( function() {

                }) ->to() ->raise( RuntimeException::class );

            } catch( ExpectationFailureSignal $e ) {

                $errorRaised = true;

                $this->expect( $e->getMessage() )
                    ->to() ->equal( "Expected the closure to raise a RuntimeException, but no Exception was raised." );

            }

            if( $errorRaised === false ) {
                throw new RuntimeException( "->raise($exception) failed in raising a failure." );
            }

        });

        $this->it( "the spec fails if a different exception is raised", function() {

            $errorRaised = false;

            try {

                $this->expect( function() {

                    throw new Exception( "Exception raised" );

                }) ->to() ->raise( RuntimeException::class );

            } catch( ExpectationFailureSignal $e ) {

                $errorRaised = true;

                $this->expect( $e->getMessage() )
                    ->to() ->equal( "Expected the closure to raise a RuntimeException, but a Exception was raised instead." );

            }

            if( $errorRaised === false ) {
                throw new RuntimeException( "->raise(\$exception) failed in raising a failure." );
            }

        });

    });

    $this->describe( "with a expectation closure on the raised Exception", function() {

        $this->it( "the spec passes if the exception is raised and the expected exception closure is evaluated", function() {

            $this->exceptionClosureExecuted = false;

            $this->expect( function() {

                throw new RuntimeException( "RuntimeException raised" );

            }) ->to() ->raise( RuntimeException::class, function($e) {

                $this->exceptionClosureExecuted = true;

            });

            if( $this->exceptionClosureExecuted === false ) {
                throw new RuntimeException(
                    "->raise(\$exception, \$closure) failed in evaluating the expected exception closure."
                );
            }

        });

        $this->it( "the spec fails if no exception is raised and the closure is not evalueted", function() {

            $errorRaised = false;
            $this->exceptionClosureExecuted = false;

            try {

                $this->expect( function() {

                }) ->to() ->raise( RuntimeException::class, function($e) {

                    $this->exceptionClosureExecuted = true;

                });

            } catch( ExpectationFailureSignal $e ) {

                $errorRaised = true;

                $this->expect( $e->getMessage() )
                    ->to() ->equal( "Expected the closure to raise a RuntimeException, but no Exception was raised." );

            }

            if( $this->exceptionClosureExecuted === true ) {
                throw new RuntimeException(
                    "->raise(\$exception, \$closure) incorrectly evaluated the expected exception closure."
                );
            }

            if( $errorRaised === false ) {
                throw new RuntimeException( "->raise(\$exception) failed in raising a failure." );
            }

        });

        $this->it( "the spec fails if a different exception is raised", function() {

            $errorRaised = false;
            $this->exceptionClosureExecuted = false;

            try {

                $this->expect( function() {

                    throw new Exception( "Exception raised" );

                }) ->to() ->raise( RuntimeException::class, function($e) {

                    $this->exceptionClosureExecuted = true;

                });

            } catch( ExpectationFailureSignal $e ) {

                $errorRaised = true;

                $this->expect( $e->getMessage() )
                    ->to() ->equal( "Expected the closure to raise a RuntimeException, but a Exception was raised instead." );

            }

            if( $this->exceptionClosureExecuted === true ) {
                throw new RuntimeException(
                    "->raise(\$exception, \$closure) incorrectly evaluated the expected exception closure."
                );
            }

            if( $errorRaised === false ) {
                throw new RuntimeException( "->raise(\$exception) failed in raising a failure." );
            }

        });

    });


    $this->describe( "when negating the expectation", function() {

        $this->it( "the spec passes if the exception is not raised", function() {

            $errorRaised = false;

            try {

                $this->expect( function() {

                    // No exception raised.

                }) ->not() ->to() ->raise( RuntimeException::class );

            } catch( ExpectationFailureSignal $e ) {

                $errorRaised = true;

            }

            if( $errorRaised === true ) {
                throw new RuntimeException(
                    "->raise($exception) failed in catching a non raised exception."
                );
            }

        });


        $this->it( "the spec passes if a different exception is raised", function() {

            $errorRaised = false;

            try {

                $this->expect( function() {

                    throw new Exception( "Exception raised" );

                }) ->not() ->to() ->raise( RuntimeException::class );

            } catch( ExpectationFailureSignal $e ) {

                $errorRaised = true;

            }

            if( $errorRaised === true ) {
                throw new RuntimeException(
                    "->raise($exception) failed in catching a non raised exception."
                );
            }

        });

        $this->it( "the spec fails if the expected exception is raised", function() {

            $errorRaised = false;

            try {

                $this->expect(function(){

                    throw new RuntimeException();

                }) ->not() ->to() ->raise( RuntimeException::class );

            } catch( ExpectationFailureSignal $e ) {

                $errorRaised = true;

                $this->expect( $e->getMessage() )
                    ->to() ->equal( "Expected the closure not to raise a RuntimeException, but a RuntimeException was raised." );
            }

            if( $errorRaised === false ) {
                throw new RuntimeException(
                    "->raise($exception) failed in not raising a failure."
                );
            }

        });

    });

});