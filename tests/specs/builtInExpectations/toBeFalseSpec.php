<?php
declare(strict_types=1);

use Haijin\Specs\Errors\ExpectationFailureSignal;

$spec->describe( "When expecting a value to be false", function() {

    $this->it( "the spec passes if the value is false", function() {
        
        $this->expect( false ) ->to() ->be() ->false();

    });

    $this->it( "the spec fails if the value is not false", function() {

        $this->expect( function() {

            $this->expect( true ) ->to() ->be() ->false();

        }) ->to() ->raise( ExpectationFailureSignal::class, function($e) {

            $this->expect( $e->getMessage() ) ->to()
                ->equal( "Expected value to be false, got true." );

        });

    });

    $this->describe( "when negating the expectation", function() {

        $this->it( "the spec passes if the value is not false", function() {
            
            $this->expect( true ) ->not() ->to() ->be() ->false();

        });

        $this->it( "the spec fails if the value is false", function() {

            $this->expect( function() {

                $this->expect( false ) ->not() ->to() ->be() ->false();

            }) ->to() ->raise( ExpectationFailureSignal::class, function($e) {

                $this->expect( $e->getMessage() ) ->to()
                    ->equal( "Expected value not to be false, got false." );

            });

        });

    });
});