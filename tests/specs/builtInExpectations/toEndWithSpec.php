<?php
declare(strict_types=1);

use Haijin\Specs\Errors\ExpectationFailureSignal;

$spec->describe( "When expecting a string value to end with a substring", function() {

    $this->it( "the spec passes if the value ends with the substring", function() {
        
        $this->expect( "1234" ) ->to() ->endWith( "" );
        $this->expect( "1234" ) ->to() ->endWith( "4" );
        $this->expect( "1234" ) ->to() ->endWith( "34" );
        $this->expect( "1234" ) ->to() ->endWith( "1234" );

    });

    $this->it( "the spec fails if the value does not end with the substring", function() {
        
        $this->expect( function() {

            $this->expect( "1234" ) ->to() ->endWith( "1" );

        }) ->to() ->raise( ExpectationFailureSignal::class, function($e) {

            $this->expect( $e->getMessage() ) ->to()
                ->equal( "Expected \"1234\" to end with \"1\"." );

        });

    });

    $this->it( "the spec fails if the value does not end with the substring", function() {
        
        $this->expect( function() {

            $this->expect( "1234" ) ->to() ->endWith( "01234" );

        }) ->to() ->raise( ExpectationFailureSignal::class, function($e) {

            $this->expect( $e->getMessage() ) ->to()
                ->equal( "Expected \"1234\" to end with \"01234\"." );

        });

    });

    $this->describe( "when negating the expectation", function() {

        $this->it( "the spec passes if the value does not end with the substring", function() {
            
            $this->expect( "1234" ) ->not() ->to() ->endWith( "1" );
            $this->expect( "1234" ) ->not() ->to() ->endWith( "01234" );

        });

        $this->it( "the spec fails if the value ends with the substring", function() {
            
            $this->expect( function() {

                $this->expect( "1234" ) ->not() ->to() ->endWith( "" );

            }) ->to() ->raise( ExpectationFailureSignal::class, function($e) {

                $this->expect( $e->getMessage() ) ->to()
                    ->equal( "Expected \"1234\" not to end with \"\"." );

            });

        });

        $this->it( "the spec fails if the value ends with the substring", function() {
            
            $this->expect( function() {

                $this->expect( "1234" ) ->not() ->to() ->endWith( "4" );

            }) ->to() ->raise( ExpectationFailureSignal::class, function($e) {

                $this->expect( $e->getMessage() ) ->to()
                    ->equal( "Expected \"1234\" not to end with \"4\"." );

            });

        });

        $this->it( "the spec fails if the value ends with the substring", function() {
            
            $this->expect( function() {

                $this->expect( "1234" ) ->not() ->to() ->endWith( "34" );

            }) ->to() ->raise( ExpectationFailureSignal::class, function($e) {

                $this->expect( $e->getMessage() ) ->to()
                    ->equal( "Expected \"1234\" not to end with \"34\"." );

            });

        });

        $this->it( "the spec fails if the value ends with the substring", function() {
            
            $this->expect( function() {

                $this->expect( "1234" ) ->not() ->to() ->endWith( "1234" );

            }) ->to() ->raise( ExpectationFailureSignal::class, function($e) {

                $this->expect( $e->getMessage() ) ->to()
                    ->equal( "Expected \"1234\" not to end with \"1234\"." );

            });

        });

    });

});