<?php
declare(strict_types=1);

use Haijin\Specs\Errors\ExpectationFailureSignal;

$spec->describe( "When expecting a string value to begin with a substring", function() {

    $this->it( "the spec passes if the value begins with the substring", function() {
        
        $this->expect( "1234" ) ->to() ->beginWith( "" );
        $this->expect( "1234" ) ->to() ->beginWith( "1" );
        $this->expect( "1234" ) ->to() ->beginWith( "123" );
        $this->expect( "1234" ) ->to() ->beginWith( "1234" );

    });

    $this->it( "the spec fails if the value does not begin with the substring", function() {
        
        $this->expect( function() {

            $this->expect( "1234" ) ->to() ->beginWith( "4" );

        }) ->to() ->raise( ExpectationFailureSignal::class, function($e) {

            $this->expect( $e->getMessage() ) ->to()
                ->equal( "Expected \"1234\" to begin with \"4\"." );

        });

    });

    $this->it( "the spec fails if the value does not begin with the substring", function() {
        
        $this->expect( function() {

            $this->expect( "1234" ) ->to() ->beginWith( "12345" );

        }) ->to() ->raise( ExpectationFailureSignal::class, function($e) {

            $this->expect( $e->getMessage() ) ->to()
                ->equal( "Expected \"1234\" to begin with \"12345\"." );

        });

    });

    $this->describe( "when negating the expectation", function() {

        $this->it( "the spec passes if the value does not begin with the substring", function() {
            
            $this->expect( "1234" ) ->not() ->to() ->beginWith( "4" );
            $this->expect( "1234" ) ->not() ->to() ->beginWith( "12345" );

        });

        $this->it( "the spec fails if the value begins with the substring", function() {
            
            $this->expect( function() {

                $this->expect( "1234" ) ->not() ->to() ->beginWith( "" );

            }) ->to() ->raise( ExpectationFailureSignal::class, function($e) {

                $this->expect( $e->getMessage() ) ->to()
                    ->equal( "Expected \"1234\" not to begin with \"\"." );

            });

        });

        $this->it( "the spec fails if the value begins with the substring", function() {
            
            $this->expect( function() {

                $this->expect( "1234" ) ->not() ->to() ->beginWith( "1" );

            }) ->to() ->raise( ExpectationFailureSignal::class, function($e) {

                $this->expect( $e->getMessage() ) ->to()
                    ->equal( "Expected \"1234\" not to begin with \"1\"." );

            });

        });

        $this->it( "the spec fails if the value begins with the substring", function() {
            
            $this->expect( function() {

                $this->expect( "1234" ) ->not() ->to() ->beginWith( "123" );

            }) ->to() ->raise( ExpectationFailureSignal::class, function($e) {

                $this->expect( $e->getMessage() ) ->to()
                    ->equal( "Expected \"1234\" not to begin with \"123\"." );

            });

        });

        $this->it( "the spec fails if the value begins with the substring", function() {
            
            $this->expect( function() {

                $this->expect( "1234" ) ->not() ->to() ->beginWith( "1234" );

            }) ->to() ->raise( ExpectationFailureSignal::class, function($e) {

                $this->expect( $e->getMessage() ) ->to()
                    ->equal( "Expected \"1234\" not to begin with \"1234\"." );

            });

        });

    });

});