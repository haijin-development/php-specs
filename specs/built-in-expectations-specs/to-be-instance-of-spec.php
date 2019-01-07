<?php

namespace ToBeInstanceOfSpec;

$spec->describe( "When expecting a value to be an instance of a class", function() {

    $this->it( "the spec passes if the value is an instance of that class", function() {
        
        $this->expect( new SomeClass() ) ->to() ->be() ->instance_of( SomeClass::class );

    });

    $this->it( "the spec fails if the value is not an instance of that class", function() {

        $this->expect( function() {

            $this->expect( 1 ) ->to() ->be() ->instance_of( SomeClass::class );

        }) ->to() ->raise( \Haijin\Specs\ExpectationFailureSignal::class, function($e) {

            $this->expect( $e->get_message() ) ->to()
                ->equal( "Expected value to be an instance of ToBeInstanceOfSpec\SomeClass, got 1." );

        });

    });

    $this->describe( "when negating the expectation", function() {

        $this->it( "the spec passes if the value is not an instance of that class", function() {
            
            $this->expect( 1 ) ->not() ->to() ->be() ->instance_of( SomeClass::class );

        });

        $this->it( "the spec fails if the value is an instance of that class", function() {

            $this->expect( function() {

                $this->expect( new SomeClass() ) ->not() ->to() ->be()
                    ->instance_of( SomeClass::class );

            }) ->to() ->raise( \Haijin\Specs\ExpectationFailureSignal::class, function($e) {

                $this->expect( $e->get_message() ) ->to()
                    ->equal( "Expected value not to be an instance of ToBeInstanceOfSpec\SomeClass, got an instance of ToBeInstanceOfSpec\SomeClass." );

            });

        });

    });
});

class SomeClass
{

}