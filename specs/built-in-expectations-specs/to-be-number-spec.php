<?php

$spec->describe( "When expecting a value to be a number", function() {

    $this->it( "the spec passes if the value is a number", function() {
        
        $this->expect( 1 ) ->to() ->be() ->number();
        $this->expect( 1.1 ) ->to() ->be() ->number();

    });

    $this->it( "the spec fails if the value is not a number", function() {

        $this->expect( function() {

            $this->expect( "1" ) ->to() ->be() ->number();

        }) ->to() ->raise( \Haijin\Specs\Expectation_Failure_Signal::class, function($e) {

            $this->expect( $e->get_message() ) ->to()
                ->equal( "Expected value to be a number, got \"1\"." );

        });

    });

    $this->describe( "when negating the expectation", function() {

        $this->it( "the spec passes if the value is not a number", function() {
            
            $this->expect( "1" ) ->not() ->to() ->be() ->number();

        });

        $this->it( "the spec fails if the value is a number", function() {

            $this->expect( function() {

                $this->expect( 1 ) ->not() ->to() ->be() ->number();

            }) ->to() ->raise( \Haijin\Specs\Expectation_Failure_Signal::class, function($e) {

                $this->expect( $e->get_message() ) ->to()
                    ->equal( "Expected value not to be a number, got 1." );

            });

        });

        $this->it( "the spec fails if the value is a number", function() {

            $this->expect( function() {

                $this->expect( 1.1 ) ->not() ->to() ->be() ->number();

            }) ->to() ->raise( \Haijin\Specs\Expectation_Failure_Signal::class, function($e) {

                $this->expect( $e->get_message() ) ->to()
                    ->equal( "Expected value not to be a number, got 1.1." );

            });

        });

    });
});