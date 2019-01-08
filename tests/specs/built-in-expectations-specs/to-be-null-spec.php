<?php

$spec->describe( "When expecting a value to be null", function() {

    $this->it( "the spec passes if the value is null", function() {
        
        $this->expect( null ) ->to() ->be() ->null();

    });

    $this->it( "the spec fails if the value is not null", function() {

        $this->expect( function() {

            $this->expect( 1 ) ->to() ->be() ->null();

        }) ->to() ->raise( \Haijin\Specs\Expectation_Failure_Signal::class, function($e) {

            $this->expect( $e->get_message() ) ->to()
                ->equal( "Expected value to be null, got 1." );

        });

    });

    $this->describe( "when negating the expectation", function() {

        $this->it( "the spec passes if the value is not null", function() {
            
            $this->expect( 1 ) ->not() ->to() ->be() ->null();

        });

        $this->it( "the spec fails if the value is null", function() {

            $this->expect( function() {

                $this->expect( null ) ->not() ->to() ->be() ->null();

            }) ->to() ->raise( \Haijin\Specs\Expectation_Failure_Signal::class, function($e) {

                $this->expect( $e->get_message() ) ->to()
                    ->equal( "Expected value not to be null, got null." );

            });

        });

    });
});