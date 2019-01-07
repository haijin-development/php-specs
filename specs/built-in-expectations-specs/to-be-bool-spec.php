<?php

$spec->describe( "When expecting a value to be a bool", function() {

    $this->it( "the spec passes if the value is a bool", function() {
        
        $this->expect( true ) ->to() ->be() ->bool();

    });

    $this->it( "the spec fails if the value is not a bool", function() {

        $this->expect( function() {

            $this->expect( 1 ) ->to() ->be() ->bool();

        }) ->to() ->raise( \Haijin\Specs\ExpectationFailureSignal::class, function($e) {

            $this->expect( $e->get_message() ) ->to()
                ->equal( "Expected value to be a bool, got 1." );

        });

    });

    $this->describe( "when negating the expectation", function() {

        $this->it( "the spec passes if the value is not a bool", function() {
            
            $this->expect( 1 ) ->not() ->to() ->be() ->bool();

        });

        $this->it( "the spec fails if the value is a bool", function() {

            $this->expect( function() {

                $this->expect( true ) ->not() ->to() ->be() ->bool();

            }) ->to() ->raise( \Haijin\Specs\ExpectationFailureSignal::class, function($e) {

                $this->expect( $e->get_message() ) ->to()
                    ->equal( "Expected value not to be a bool, got true." );

            });

        });

        $this->it( "the spec fails if the value is a bool", function() {

            $this->expect( function() {

                $this->expect( false ) ->not() ->to() ->be() ->bool();

            }) ->to() ->raise( \Haijin\Specs\ExpectationFailureSignal::class, function($e) {

                $this->expect( $e->get_message() ) ->to()
                    ->equal( "Expected value not to be a bool, got false." );

            });

        });

    });
});