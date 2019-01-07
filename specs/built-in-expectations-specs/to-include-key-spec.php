<?php

$spec->describe( "When expecting an array to include a key", function() {

    $this->let( "array", function () {
        return [ "a" => 1, "b" => 2, "c" => 3 ];
    });

    $this->it( "the spec passes if the array includes the key", function() {
        
        $this->expect( $this->array ) ->to() ->include_key( "c" );

    });

    $this->it( "the spec fails if the array does not include the key", function() {

        $this->expect( function() {

            $this->expect( $this->array ) ->to() ->include_key( "d" );

        }) ->to() ->raise( \Haijin\Specs\ExpectationFailureSignal::class, function($e) {

            $this->expect( $e->get_message() ) ->to()
                ->equal( "Expected array to include key \"d\"." );

        });
    });

    $this->describe( "when negating the expectation", function() {

        $this->it( "the spec passes if the array does not include the key", function() {
            
            $this->expect( $this->array ) ->not() ->to() ->include_key( "d" );

        });

        $this->it( "the spec fails if the array includes the key", function() {

            $this->expect( function() {

                $this->expect( $this->array ) ->not() ->to() ->include_key( "c" );

            }) ->to() ->raise( \Haijin\Specs\ExpectationFailureSignal::class, function($e) {

                $this->expect( $e->get_message() ) ->to()
                ->equal( "Expected array not to include key \"c\"." );

            });
        });

    });

    $this->describe( "with a value closure", function() {

        $this->it( "the spec passes and the value closure if the array includes the key", function() {

            $this->value_closure_was_called = false;

            $this->expect( $this->array ) ->to() ->include_key( "c", function($value) {
                $this->value_closure_was_called = true;

                $this->expect( $value ) ->to() ->equal( 3 );
            });

            $this->expect( $this->value_closure_was_called ) ->to() ->be() ->true();
        });

        $this->it( "the spec fails if the array does not include the key, and the value closure is not evaluated", function() {

            $this->expect( function() {

                $this->expect( $this->array ) ->to() ->include_key( "d", function($value) {
                    throw new \Exception( "This closure should not be evaluated" );
                });

            }) ->to() ->raise( \Haijin\Specs\ExpectationFailureSignal::class, function($e) {

                $this->expect( $e->get_message() ) ->to()
                    ->equal( "Expected array to include key \"d\"." );

            });

        });

    });
});