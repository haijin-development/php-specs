<?php

namespace To_Be_A_Spec;

$spec->describe( "When expecting a value to be a kind of a class", function() {

    $this->it( "the spec passes if the value is of that kind", function() {
        
        $this->expect( new SomeClass() ) ->to() ->be() ->a( SomeClass::class );

    });

    $this->it( "the spec fails if the value is not of that kind", function() {

        $this->expect( function() {

            $this->expect( 1 ) ->to() ->be() ->a( SomeClass::class );

        }) ->to() ->raise( \Haijin\Specs\Expectation_Failure_Signal::class, function($e) {

            $this->expect( $e->get_message() ) ->to()
                ->equal( "Expected value to be a kind of To_Be_A_Spec\SomeClass, got 1." );

        });

    });

    $this->describe( "when negating the expectation", function() {

        $this->it( "the spec passes if the value is not of that kind", function() {
            
            $this->expect( 1 ) ->not() ->to() ->be() ->a( SomeClass::class );

        });

        $this->it( "the spec fails if the value is of that kind", function() {

            $this->expect( function() {

                $this->expect( new SomeClass() ) ->not() ->to() ->be() ->a( SomeClass::class );

            }) ->to() ->raise( \Haijin\Specs\Expectation_Failure_Signal::class, function($e) {

                $this->expect( $e->get_message() ) ->to()
                    ->equal( "Expected value not to be a kind of To_Be_A_Spec\SomeClass, got a kind of To_Be_A_Spec\SomeClass." );

            });

        });

    });
});

class SomeClass
{

}