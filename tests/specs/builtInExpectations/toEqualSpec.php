<?php
declare(strict_types=1);

use Haijin\Specs\Errors\ExpectationFailureSignal;

$spec->describe( "When expecting a value for equality", function() {

    $this->it( "the spec passes if values are equal", function() {
        
        $this->expect( 1 ) ->to() ->equal( 1 );

    });

    $this->it( "the spec fails if values are not equal", function() {

        $this->expect( function() {

            $this->expect( 1 ) ->to() ->equal( 2 );

        }) ->to() ->raise( ExpectationFailureSignal::class, function($e) {

            $this->expect( $e->getMessage() ) ->to()
                ->equal( "Expected value to equal 2, got 1." );

        });

    });

    $this->describe( "when negating the expectation", function() {

        $this->it( "the spec passes if values are not equal", function() {

            $this->expect( 1 ) ->not() ->to() ->equal( 2 );

        });

        $this->it( "the spec fails if values are equal", function() {

            $this->expect( function() {

                $this->expect( 1 ) ->not() ->to() ->equal( 1 );

            }) ->to() ->raise( ExpectationFailureSignal::class, function($e) {

                $this->expect( $e->getMessage() ) ->to()
                    ->equal( "Expected value not to equal 1, got 1." );

            });

        });

    });
});