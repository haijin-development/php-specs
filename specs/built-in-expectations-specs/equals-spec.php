<?php

$spec->describe( "When expecting a value for equality", function() {

    $this->it( "the spec passes if values are equal", function() {
        
        $this->expect( 1 ) ->to() ->equal( 1 );

    });

    $this->it( "the spec fails if values are not equal", function() {

        $error_raised = false;

        try {
            $this->expect( 1 ) ->to() ->equal( 2 );
        } catch( \Haijin\Specs\ExpectationFailureSignal $e ) {
            $error_raised = true;

            $this->expect( $e->get_message() )
                ->to() ->equal( "Expected value to equal 2, got 1." );
        }

        if( $error_raised === false ) {
            throw new Exception( "->equal(\$value) failed in raising an error." );
        }
    });

    $this->describe( "when negating the expectation", function() {

        $this->it( "the spec passes if values are not equal", function() {

            $this->expect( 1 ) ->not() ->to() ->equal( 2 );

        });

        $this->it( "the spec fails if values are equal", function() {

            $error_raised = false;

            try {
                $this->expect( 1 ) ->not() ->to() ->equal( 1 );
            } catch( \Haijin\Specs\ExpectationFailureSignal $e ) {
                $error_raised = true;

                $this->expect( $e->get_message() )
                    ->to() ->equal( "Expected value not to equal 1, got 1." );
            }

            if( $error_raised === false ) {
                throw new Exception( "->equal(\$value) failed in raising an error." );
            }
        });

    });
});