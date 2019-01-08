<?php

$spec->describe( "When expecting a value to be true", function() {

    $this->it( "the spec passes if the value is true", function() {
        
        $this->expect( true ) ->to() ->be() ->true();

    });

    $this->it( "the spec fails if the value is not true", function() {

        $this->expect( function() {

            $this->expect( false ) ->to() ->be() ->true();

        }) ->to() ->raise( \Haijin\Specs\Expectation_Failure_Signal::class, function($e) {

            $this->expect( $e->get_message() ) ->to()
                ->equal( "Expected value to be true, got false." );

        });

    });

    $this->describe( "when negating the expectation", function() {

        $this->it( "the spec passes if the value is not true", function() {
            
            $this->expect( false ) ->not() ->to() ->be() ->true();

        });

        $this->it( "the spec fails if the value is true", function() {

            $this->expect( function() {

                $this->expect( true ) ->not() ->to() ->be() ->true();

            }) ->to() ->raise( \Haijin\Specs\Expectation_Failure_Signal::class, function($e) {

                $this->expect( $e->get_message() ) ->to()
                    ->equal( "Expected value not to be true, got true." );

            });

        });

    });
});