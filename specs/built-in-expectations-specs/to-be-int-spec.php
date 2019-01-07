<?php

$spec->describe( "When expecting a value to be an int", function() {

    $this->it( "the spec passes if the value is an int", function() {
        
        $this->expect( 1 ) ->to() ->be() ->int();

    });

    $this->it( "the spec fails if the value is not an int", function() {

        $this->expect( function() {

            $this->expect( "1" ) ->to() ->be() ->int();

        }) ->to() ->raise( \Haijin\Specs\ExpectationFailureSignal::class, function($e) {

            $this->expect( $e->get_message() ) ->to()
                ->equal( "Expected value to be an int, got \"1\"." );

        });

    });

    $this->describe( "when negating the expectation", function() {

        $this->it( "the spec passes if the value is not an int", function() {
            
            $this->expect( "1" ) ->not() ->to() ->be() ->int();

        });

        $this->it( "the spec fails if the value is an int", function() {

            $this->expect( function() {

                $this->expect( 1 ) ->not() ->to() ->be() ->int();

            }) ->to() ->raise( \Haijin\Specs\ExpectationFailureSignal::class, function($e) {

                $this->expect( $e->get_message() ) ->to()
                    ->equal( "Expected value not to be an int, got 1." );

            });

        });

    });
});