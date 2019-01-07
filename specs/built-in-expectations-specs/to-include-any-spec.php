<?php

$spec->describe( "When expecting an array to include some elements in a collection of values", function() {

    $this->it( "the spec passes if the array includes any of the values", function() {
        
        $this->expect( [ 1, 2, 3 ] ) ->to() ->include_any( [ 1 ] );
        $this->expect( [ 1, 2, 3 ] ) ->to() ->include_any( [ 1, 2, 4 ] );

    });

    $this->it( "the spec fails if the array does not include any of the values", function() {

        $this->expect( function() {

        $this->expect( [ 1, 2, 3 ] ) ->to() ->include_any( [ 0, 4 ] );

        }) ->to() ->raise( \Haijin\Specs\ExpectationFailureSignal::class, function($e) {

            $this->expect( $e->get_message() ) ->to()
                ->equal( "Expected array to include any of the expected values." );

        });
    });

    $this->describe( "when negating the expectation", function() {

        $this->it( "the spec passes if the array does not include any of the values", function() {
            
            $this->expect( [ 1, 2, 3 ] ) ->not() ->to() ->include_any( [ 0, 4 ] );

        });

        $this->it( "the spec fails if the array includes any of the values", function() {

            $this->expect( function() {

            $this->expect( [ 1, 2, 3 ] ) ->not() ->to() ->include_any( [ 0, 2 ] );

            }) ->to() ->raise( \Haijin\Specs\ExpectationFailureSignal::class, function($e) {

                $this->expect( $e->get_message() ) ->to()
                    ->equal( "Expected array not to include any of the expected values." );

            });
        });

    });

});