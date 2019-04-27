<?php
declare(strict_types=1);

use Haijin\Specs\Errors\ExpectationFailureSignal;

$spec->describe( "When expecting a value to be a double", function() {

    $this->it( "the spec passes if the value is a double", function() {
        
        $this->expect( 1.1 ) ->to() ->be() ->double();

    });

    $this->it( "the spec fails if the value is not a double", function() {

        $this->expect( function() {

            $this->expect( 1 ) ->to() ->be() ->double();

        }) ->to() ->raise( ExpectationFailureSignal::class, function($e) {

            $this->expect( $e->getMessage() ) ->to()
                ->equal( "Expected value to be a double, got 1." );

        });

    });

    $this->describe( "when negating the expectation", function() {

        $this->it( "the spec passes if the value is not a double", function() {
            
            $this->expect( 1 ) ->not() ->to() ->be() ->double();

        });

        $this->it( "the spec fails if the value is a double", function() {

            $this->expect( function() {

                $this->expect( 1.1 ) ->not() ->to() ->be() ->double();

            }) ->to() ->raise( ExpectationFailureSignal::class, function($e) {

                $this->expect( $e->getMessage() ) ->to()
                    ->equal( "Expected value not to be a double, got 1.1." );

            });

        });

    });
});