<?php
declare(strict_types=1);

use Haijin\Specs\Errors\ExpectationFailureSignal;

$spec->describe( "When expecting a value to be a string", function() {

    $this->it( "the spec passes if the value is a string", function() {
        
        $this->expect( "" ) ->to() ->be() ->string();

    });

    $this->it( "the spec fails if the value is not a string", function() {

        $this->expect( function() {

            $this->expect( 1 ) ->to() ->be() ->string();

        }) ->to() ->raise( ExpectationFailureSignal::class, function($e) {

            $this->expect( $e->getMessage() ) ->to()
                ->equal( "Expected value to be a string, got 1." );

        });

    });

    $this->describe( "when negating the expectation", function() {

        $this->it( "the spec passes if the value is not a string", function() {
            
            $this->expect( 1 ) ->not() ->to() ->be() ->string();

        });

        $this->it( "the spec fails if the value is a string", function() {

            $this->expect( function() {

                $this->expect( "" ) ->not() ->to() ->be() ->string();

            }) ->to() ->raise( ExpectationFailureSignal::class, function($e) {

                $this->expect( $e->getMessage() ) ->to()
                    ->equal( "Expected value not to be a string, got \"\"." );

            });

        });

    });
});