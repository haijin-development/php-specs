<?php

use Haijin\Specs\Errors\ExpectationFailureSignal;

$spec->describe( "When expecting a string value to match a regex", function() {

    $this->describe( "and no matching closure is given", function() {

        $this->it( "the spec passes if the value matches the regex", function() {
            
            $this->expect( "1234" ) ->to() ->match( "|^.23.$|" );

        });

        $this->it( "the spec fails if the value does not match the regex", function() {
            
            $this->expect( function() {

                $this->expect( "1234" ) ->to() ->match( "|^.23$|" );

            }) ->to() ->raise( ExpectationFailureSignal::class, function($e) {

                $this->expect( $e->getMessage() ) ->to()
                    ->equal( "Expected \"1234\" to match \"|^.23$|\"." );

            });

        });

        $this->describe( "when negating the expectation", function() {

            $this->it( "the spec passes if the value does not match the regex", function() {
                
                $this->expect( "1234" ) ->not() ->to() ->match( "|^.23$|" );

            });

            $this->it( "the spec fails if the value matches the regex", function() {
                
                $this->expect( function() {

                    $this->expect( "1234" ) ->not() ->to() ->match( "|^.23.$|" );

                }) ->to() ->raise( ExpectationFailureSignal::class, function($e) {

                    $this->expect( $e->getMessage() ) ->to()
                        ->equal( "Expected \"1234\" not to match \"|^.23.$|\"." );

                });

            });

        });

    });

    $this->describe( "and a matching closure is given", function() {

        $this->it( "the spec passes and the matching closure is evaluated if the value matches the regex", function() {
            
            $this->expect( "1234" ) ->to() ->match( "|^(.)23.$|", function($matches) {
                $this->expect( $matches ) ->to() ->equal( [ "1234", "1" ] );
            });

        });

        $this->it( "the spec fails if the value does not match the regex, and the matching closure is not evaluated", function() {
            
            $this->expect( function() {

                $this->expect( "1234" ) ->to() ->match( "|^(.)23$|", function($matches) {
                    throw new RuntimeException( "Should no evaluated this closure." );
                });

            }) ->to() ->raise( ExpectationFailureSignal::class, function($e) {

                $this->expect( $e->getMessage() ) ->to()
                    ->equal( "Expected \"1234\" to match \"|^(.)23$|\"." );

            });

        });

    });

});