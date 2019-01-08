<?php

$spec->describe( "When expecting a closure to raise an Exception", function() {

    $this->describe( "with no expectation closure on the raised Exception", function() {

        $this->it( "the spec passes if the exception is raised", function() {

            $error_raised = false;

            try {

                $this->expect( function() {

                    throw new \RuntimeException( "RuntimeException raised" );

                }) ->to() ->raise( \RuntimeException::class );

            } catch( \Exception $e ) {

                $error_raised = true;

            }

            if( $error_raised === true ) {
                throw new Exception(
                    "->raise($exception) failed in catching an expected exception."
                );
            }

        });

        $this->it( "the spec fails if no exception is raised", function() {

            $error_raised = false;

            try {

                $this->expect( function() {

                }) ->to() ->raise( \RuntimeException::class );

            } catch( \Haijin\Specs\Expectation_Failure_Signal $e ) {

                $error_raised = true;

                $this->expect( $e->get_message() )
                    ->to() ->equal( "Expected the closure to raise a RuntimeException, but no Exception was raised." );

            }

            if( $error_raised === false ) {
                throw new Exception( "->raise($exception) failed in raising a failure." );
            }

        });

        $this->it( "the spec fails if a different exception is raised", function() {

            $error_raised = false;

            try {

                $this->expect( function() {

                    throw new \Exception( "Exception raised" );

                }) ->to() ->raise( \RuntimeException::class );

            } catch( \Haijin\Specs\Expectation_Failure_Signal $e ) {

                $error_raised = true;

                $this->expect( $e->get_message() )
                    ->to() ->equal( "Expected the closure to raise a RuntimeException, but a Exception was raised instead." );

            }

            if( $error_raised === false ) {
                throw new Exception( "->raise(\$exception) failed in raising a failure." );
            }

        });

    });

    $this->describe( "with a expectation closure on the raised Exception", function() {

        $this->it( "the spec passes if the exception is raised and the expected exception closure is evaluated", function() {

            $this->exception_closure_executed = false;

            $this->expect( function() {

                throw new \RuntimeException( "RuntimeException raised" );

            }) ->to() ->raise( \RuntimeException::class, function($e) {

                $this->exception_closure_executed = true;

            });

            if( $this->exception_closure_executed === false ) {
                throw new Exception(
                    "->raise(\$exception, \$closure) failed in evaluating the expected exception closure."
                );
            }

        });

        $this->it( "the spec fails if no exception is raised and the closure is not evalueted", function() {

            $error_raised = false;
            $this->exception_closure_executed = false;

            try {

                $this->expect( function() {

                }) ->to() ->raise( \RuntimeException::class, function($e) {

                    $this->exception_closure_executed = true;

                });

            } catch( \Haijin\Specs\Expectation_Failure_Signal $e ) {

                $error_raised = true;

                $this->expect( $e->get_message() )
                    ->to() ->equal( "Expected the closure to raise a RuntimeException, but no Exception was raised." );

            }

            if( $this->exception_closure_executed === true ) {
                throw new Exception(
                    "->raise(\$exception, \$closure) incorrectly evaluated the expected exception closure."
                );
            }

            if( $error_raised === false ) {
                throw new Exception( "->raise(\$exception) failed in raising a failure." );
            }

        });

        $this->it( "the spec fails if a different exception is raised", function() {

            $error_raised = false;
            $this->exception_closure_executed = false;

            try {

                $this->expect( function() {

                    throw new \Exception( "Exception raised" );

                }) ->to() ->raise( \RuntimeException::class, function($e) {

                    $this->exception_closure_executed = true;

                });

            } catch( \Haijin\Specs\Expectation_Failure_Signal $e ) {

                $error_raised = true;

                $this->expect( $e->get_message() )
                    ->to() ->equal( "Expected the closure to raise a RuntimeException, but a Exception was raised instead." );

            }

            if( $this->exception_closure_executed === true ) {
                throw new Exception(
                    "->raise(\$exception, \$closure) incorrectly evaluated the expected exception closure."
                );
            }

            if( $error_raised === false ) {
                throw new Exception( "->raise(\$exception) failed in raising a failure." );
            }

        });

    });


    $this->describe( "when negating the expectation", function() {

        $this->it( "the spec passes if the exception is not raised", function() {

            $error_raised = false;

            try {

                $this->expect( function() {

                    // No exception raised.

                }) ->not() ->to() ->raise( \RuntimeException::class );

            } catch( \Haijin\Specs\Expectation_Failure_Signal $e ) {

                $error_raised = true;

            }

            if( $error_raised === true ) {
                throw new Exception(
                    "->raise($exception) failed in catching a non raised exception."
                );
            }

        });


        $this->it( "the spec passes if a different exception is raised", function() {

            $error_raised = false;

            try {

                $this->expect( function() {

                    throw new \Exception( "Exception raised" );

                }) ->not() ->to() ->raise( \RuntimeException::class );

            } catch( \Haijin\Specs\Expectation_Failure_Signal $e ) {

                $error_raised = true;

            }

            if( $error_raised === true ) {
                throw new Exception(
                    "->raise($exception) failed in catching a non raised exception."
                );
            }

        });

        $this->it( "the spec fails if the expected exception is raised", function() {

            $error_raised = false;

            try {

                $this->expect(function(){

                    throw new \RuntimeException();

                }) ->not() ->to() ->raise( \RuntimeException::class );

            } catch( \Haijin\Specs\Expectation_Failure_Signal $e ) {

                $error_raised = true;

                $this->expect( $e->get_message() )
                    ->to() ->equal( "Expected the closure not to raise a RuntimeException, but a RuntimeException was raised." );
            }

            if( $error_raised === false ) {
                throw new Exception(
                    "->raise($exception) failed in not raising a failure."
                );
            }

        });

    });

});