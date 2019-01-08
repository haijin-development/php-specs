<?php

$spec->describe( "When expecting an array to include a value", function() {

    $this->it( "the spec passes if the array includes the value", function() {
        
        $this->expect( [ 1, 2, 3 ] ) ->to() ->include( 1 );

    });

    $this->it( "the spec fails if the array does not include the value", function() {

        $this->expect( function() {

            $this->expect( [ 1, 2, 3 ] ) ->to() ->include( 0 );

        }) ->to() ->raise( \Haijin\Specs\Expectation_Failure_Signal::class, function($e) {

            $this->expect( $e->get_message() ) ->to()
                ->equal( "Expected array to include 0." );

        });
    });

    $this->describe( "when negating the expectation", function() {

        $this->it( "the spec passes if the array does not include the value", function() {
            
            $this->expect( [ 1, 2, 3 ] ) ->not() ->to() ->include( 0 );

        });

        $this->it( "the spec fails if the array does not include the value", function() {

            $this->expect( function() {

                $this->expect( [ 1, 2, 3 ] ) ->not() ->to() ->include( 1 );

            }) ->to() ->raise( \Haijin\Specs\Expectation_Failure_Signal::class, function($e) {

                $this->expect( $e->get_message() ) ->to()
                    ->equal( "Expected array not to include 1." );

            });
        });

    });
});