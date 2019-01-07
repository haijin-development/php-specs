<?php

$spec->describe( "When expecting a string value to end with a substring", function() {

    $this->it( "the spec passes if the value ends with the substring", function() {
        
        $this->expect( "1234" ) ->to() ->end_with( "" );
        $this->expect( "1234" ) ->to() ->end_with( "4" );
        $this->expect( "1234" ) ->to() ->end_with( "34" );
        $this->expect( "1234" ) ->to() ->end_with( "1234" );

    });

    $this->it( "the spec fails if the value does not end with the substring", function() {
        
        $this->expect( function() {

            $this->expect( "1234" ) ->to() ->end_with( "1" );

        }) ->to() ->raise( \Haijin\Specs\ExpectationFailureSignal::class, function($e) {

            $this->expect( $e->get_message() ) ->to()
                ->equal( "Expected \"1234\" to end with \"1\"." );

        });

    });

    $this->it( "the spec fails if the value does not end with the substring", function() {
        
        $this->expect( function() {

            $this->expect( "1234" ) ->to() ->end_with( "01234" );

        }) ->to() ->raise( \Haijin\Specs\ExpectationFailureSignal::class, function($e) {

            $this->expect( $e->get_message() ) ->to()
                ->equal( "Expected \"1234\" to end with \"01234\"." );

        });

    });

    $this->describe( "when negating the expectation", function() {

        $this->it( "the spec passes if the value does not end with the substring", function() {
            
            $this->expect( "1234" ) ->not() ->to() ->end_with( "1" );
            $this->expect( "1234" ) ->not() ->to() ->end_with( "01234" );

        });

        $this->it( "the spec fails if the value ends with the substring", function() {
            
            $this->expect( function() {

                $this->expect( "1234" ) ->not() ->to() ->end_with( "" );

            }) ->to() ->raise( \Haijin\Specs\ExpectationFailureSignal::class, function($e) {

                $this->expect( $e->get_message() ) ->to()
                    ->equal( "Expected \"1234\" not to end with \"\"." );

            });

        });

        $this->it( "the spec fails if the value ends with the substring", function() {
            
            $this->expect( function() {

                $this->expect( "1234" ) ->not() ->to() ->end_with( "4" );

            }) ->to() ->raise( \Haijin\Specs\ExpectationFailureSignal::class, function($e) {

                $this->expect( $e->get_message() ) ->to()
                    ->equal( "Expected \"1234\" not to end with \"4\"." );

            });

        });

        $this->it( "the spec fails if the value ends with the substring", function() {
            
            $this->expect( function() {

                $this->expect( "1234" ) ->not() ->to() ->end_with( "34" );

            }) ->to() ->raise( \Haijin\Specs\ExpectationFailureSignal::class, function($e) {

                $this->expect( $e->get_message() ) ->to()
                    ->equal( "Expected \"1234\" not to end with \"34\"." );

            });

        });

        $this->it( "the spec fails if the value ends with the substring", function() {
            
            $this->expect( function() {

                $this->expect( "1234" ) ->not() ->to() ->end_with( "1234" );

            }) ->to() ->raise( \Haijin\Specs\ExpectationFailureSignal::class, function($e) {

                $this->expect( $e->get_message() ) ->to()
                    ->equal( "Expected \"1234\" not to end with \"1234\"." );

            });

        });

    });

});