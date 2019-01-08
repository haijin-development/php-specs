<?php

$spec->describe( "When expecting a string value to contain a substring", function() {

    $this->it( "the spec passes if the value contains the substring", function() {
        
        $this->expect( "1234" ) ->to() ->contain( "" );
        $this->expect( "1234" ) ->to() ->contain( "1" );
        $this->expect( "1234" ) ->to() ->contain( "23" );
        $this->expect( "1234" ) ->to() ->contain( "4" );
        $this->expect( "1234" ) ->to() ->contain( "1234" );

    });

    $this->it( "the spec fails if the value does not contain the substring", function() {
        
        $this->expect( function() {

            $this->expect( "1234" ) ->to() ->contain( "0" );

        }) ->to() ->raise( \Haijin\Specs\Expectation_Failure_Signal::class, function($e) {

            $this->expect( $e->get_message() ) ->to()
                ->equal( "Expected \"1234\" to contain \"0\"." );

        });

    });

    $this->it( "the spec fails if the value does not contain the substring", function() {
        
        $this->expect( function() {

            $this->expect( "1234" ) ->to() ->contain( "32" );

        }) ->to() ->raise( \Haijin\Specs\Expectation_Failure_Signal::class, function($e) {

            $this->expect( $e->get_message() ) ->to()
                ->equal( "Expected \"1234\" to contain \"32\"." );

        });

    });

    $this->it( "the spec fails if the value does not contain the substring", function() {
        
        $this->expect( function() {

            $this->expect( "1234" ) ->to() ->contain( "12345" );

        }) ->to() ->raise( \Haijin\Specs\Expectation_Failure_Signal::class, function($e) {

            $this->expect( $e->get_message() ) ->to()
                ->equal( "Expected \"1234\" to contain \"12345\"." );

        });

    });

    $this->describe( "when negating the expectation", function() {

        $this->it( "the spec passes if the value does not contain the substring", function() {
            
            $this->expect( "1234" ) ->not() ->to() ->contain( "0" );
            $this->expect( "1234" ) ->not() ->to() ->contain( "32" );
            $this->expect( "1234" ) ->not() ->to() ->contain( "12345" );

        });

        $this->it( "the spec fails if the value contains the substring", function() {
            
            $this->expect( function() {

                $this->expect( "1234" ) ->not() ->to() ->contain( "" );

            }) ->to() ->raise( \Haijin\Specs\Expectation_Failure_Signal::class, function($e) {

                $this->expect( $e->get_message() ) ->to()
                    ->equal( "Expected \"1234\" not to contain \"\"." );

            });

        });

        $this->it( "the spec fails if the value contains the substring", function() {
            
            $this->expect( function() {

                $this->expect( "1234" ) ->not() ->to() ->contain( "1" );

            }) ->to() ->raise( \Haijin\Specs\Expectation_Failure_Signal::class, function($e) {

                $this->expect( $e->get_message() ) ->to()
                    ->equal( "Expected \"1234\" not to contain \"1\"." );

            });

        });

        $this->it( "the spec fails if the value contains the substring", function() {
            
            $this->expect( function() {

                $this->expect( "1234" ) ->not() ->to() ->contain( "" );

            }) ->to() ->raise( \Haijin\Specs\Expectation_Failure_Signal::class, function($e) {

                $this->expect( $e->get_message() ) ->to()
                    ->equal( "Expected \"1234\" not to contain \"\"." );

            });

        });

        $this->it( "the spec fails if the value contains the substring", function() {
            
            $this->expect( function() {

                $this->expect( "1234" ) ->not() ->to() ->contain( "1" );

            }) ->to() ->raise( \Haijin\Specs\Expectation_Failure_Signal::class, function($e) {

                $this->expect( $e->get_message() ) ->to()
                    ->equal( "Expected \"1234\" not to contain \"1\"." );

            });

        });

        $this->it( "the spec fails if the value contains the substring", function() {
            
            $this->expect( function() {

                $this->expect( "1234" ) ->not() ->to() ->contain( "4" );

            }) ->to() ->raise( \Haijin\Specs\Expectation_Failure_Signal::class, function($e) {

                $this->expect( $e->get_message() ) ->to()
                    ->equal( "Expected \"1234\" not to contain \"4\"." );

            });

        });

        $this->it( "the spec fails if the value contains the substring", function() {
            
            $this->expect( function() {

                $this->expect( "1234" ) ->not() ->to() ->contain( "1234" );

            }) ->to() ->raise( \Haijin\Specs\Expectation_Failure_Signal::class, function($e) {

                $this->expect( $e->get_message() ) ->to()
                    ->equal( "Expected \"1234\" not to contain \"1234\"." );

            });

        });

    });

});