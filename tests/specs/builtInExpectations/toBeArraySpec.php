<?php
declare(strict_types=1);

use Haijin\Specs\Errors\ExpectationFailureSignal;

$spec->describe( "When expecting a value to be an array", function() {

    $this->it( "the spec passes if the value is an array", function() {
        
        $this->expect( [] ) ->to() ->be() ->array();

    });

    $this->it( "the spec fails if the value is not an array", function() {

        $this->expect( function() {

            $this->expect( 1 ) ->to() ->be() ->array();

        }) ->to() ->raise( ExpectationFailureSignal::class, function($e) {

            $this->expect( $e->getMessage() ) ->to()
                ->equal( "Expected value to be an array, got 1." );

        });

    });

    $this->describe( "when negating the expectation", function() {

        $this->it( "the spec passes if the value is not an array", function() {
            
            $this->expect( 1 ) ->not() ->to() ->be() ->array();

        });

        $this->it( "the spec fails if the value is an array", function() {

            $this->expect( function() {

                $this->expect( [] ) ->not() ->to() ->be() ->array();

            }) ->to() ->raise( ExpectationFailureSignal::class, function($e) {

                $this->expect( $e->getMessage() ) ->to()
                    ->equal( "Expected value not to be an array, got an array." );

            });

        });

    });
});