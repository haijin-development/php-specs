<?php
declare(strict_types=1);

namespace To_Be_Instance_Of_Spec;

use Haijin\Specs\Errors\ExpectationFailureSignal;

$spec->describe( "When expecting a value to be an instance of a class", function() {

    $this->it( "the spec passes if the value is an instance of that class", function() {
        
        $this->expect( new SomeClass() ) ->to() ->be() ->instanceOf( SomeClass::class );

    });

    $this->it( "the spec fails if the value is not an instance of that class", function() {

        $this->expect( function() {

            $this->expect( 1 ) ->to() ->be() ->instanceOf( SomeClass::class );

        }) ->to() ->raise( ExpectationFailureSignal::class, function($e) {

            $this->expect( $e->getMessage() ) ->to()
                ->equal( "Expected value to be an instance of To_Be_Instance_Of_Spec\SomeClass, got 1." );

        });

    });

    $this->describe( "when negating the expectation", function() {

        $this->it( "the spec passes if the value is not an instance of that class", function() {
            
            $this->expect( 1 ) ->not() ->to() ->be() ->instanceOf( SomeClass::class );

        });

        $this->it( "the spec fails if the value is an instance of that class", function() {

            $this->expect( function() {

                $this->expect( new SomeClass() ) ->not() ->to() ->be()
                    ->instanceOf( SomeClass::class );

            }) ->to() ->raise( ExpectationFailureSignal::class, function($e) {

                $this->expect( $e->getMessage() ) ->to()
                    ->equal( "Expected value not to be an instance of To_Be_Instance_Of_Spec\SomeClass, got an instance of To_Be_Instance_Of_Spec\SomeClass." );

            });

        });

    });
});

class SomeClass
{

}