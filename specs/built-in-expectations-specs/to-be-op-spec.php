<?php

$spec->describe( "When expecting a value to compare to anothe value", function() {

    $this->describe( "with the == operator", function() {

        $this->it( "the spec passes if the comparison is true", function() {
            
            $this->expect( 1 ) ->to() ->be( "==" ) ->than( 1 );

        });

        $this->it( "the spec fails if the comparison is false", function() {

            $this->expect( function() {

                $this->expect( 1 ) ->to() ->be( "==" ) ->than( 0 );

            }) ->to() ->raise( \Haijin\Specs\Expectation_Failure_Signal::class, function($e) {

                $this->expect( $e->get_message() ) ->to()
                    ->equal( "Expected value 1 to be == than 0." );

            });

        });

        $this->describe( "when negating the expectation", function() {

            $this->it( "the spec passes if the comparison is true", function() {
                
                $this->expect( 1 ) ->not() ->to() ->be( "==" ) ->than( 0 );

            });

            $this->it( "the spec fails if the comparison is false", function() {

                $this->expect( function() {

                    $this->expect( 1 ) ->not() ->to() ->be( "==" ) ->than( 1 );

                }) ->to() ->raise( \Haijin\Specs\Expectation_Failure_Signal::class, function($e) {

                    $this->expect( $e->get_message() ) ->to()
                        ->equal( "Expected value 1 not to be == than 1." );

                });

            });

        });

    });

    $this->describe( "with the === operator", function() {

        $this->it( "the spec passes if the comparison is true", function() {
            
            $this->expect( 1 ) ->to() ->be( "===" ) ->than( 1 );

        });

        $this->it( "the spec fails if the comparison is false", function() {

            $this->expect( function() {

                $this->expect( 1 ) ->to() ->be( "===" ) ->than( "1" );

            }) ->to() ->raise( \Haijin\Specs\Expectation_Failure_Signal::class, function($e) {

                $this->expect( $e->get_message() ) ->to()
                    ->equal( "Expected value 1 to be === than \"1\"." );

            });

        });

        $this->describe( "when negating the expectation", function() {

            $this->it( "the spec passes if the comparison is true", function() {
                
                $this->expect( 1 ) ->not() ->to() ->be( "===" ) ->than( "1" );

            });

            $this->it( "the spec fails if the comparison is false", function() {

                $this->expect( function() {

                    $this->expect( 1 ) ->not() ->to() ->be( "===" ) ->than( 1 );

                }) ->to() ->raise( \Haijin\Specs\Expectation_Failure_Signal::class, function($e) {

                    $this->expect( $e->get_message() ) ->to()
                        ->equal( "Expected value 1 not to be === than 1." );

                });

            });

        });

    });

    $this->describe( "with the != operator", function() {

        $this->it( "the spec passes if the comparison is true", function() {
            
            $this->expect( 1 ) ->to() ->be( "!=" ) ->than( 0 );

        });

        $this->it( "the spec fails if the comparison is false", function() {

            $this->expect( function() {

                $this->expect( 1 ) ->to() ->be( "!=" ) ->than( 1 );

            }) ->to() ->raise( \Haijin\Specs\Expectation_Failure_Signal::class, function($e) {

                $this->expect( $e->get_message() ) ->to()
                    ->equal( "Expected value 1 to be != than 1." );

            });

        });

        $this->describe( "when negating the expectation", function() {

            $this->it( "the spec passes if the comparison is true", function() {
                
                $this->expect( 1 ) ->not() ->to() ->be( "!=" ) ->than( 1 );

            });

            $this->it( "the spec fails if the comparison is false", function() {

                $this->expect( function() {

                    $this->expect( 1 ) ->not() ->to() ->be( "!=" ) ->than( 0 );

                }) ->to() ->raise( \Haijin\Specs\Expectation_Failure_Signal::class, function($e) {

                    $this->expect( $e->get_message() ) ->to()
                        ->equal( "Expected value 1 not to be != than 0." );

                });

            });

        });

    });

    $this->describe( "with the !== operator", function() {

        $this->it( "the spec passes if the comparison is true", function() {
            
            $this->expect( 1 ) ->to() ->be( "!==" ) ->than( "1" );

        });

        $this->it( "the spec fails if the comparison is false", function() {

            $this->expect( function() {

                $this->expect( 1 ) ->to() ->be( "!==" ) ->than( 1 );

            }) ->to() ->raise( \Haijin\Specs\Expectation_Failure_Signal::class, function($e) {

                $this->expect( $e->get_message() ) ->to()
                    ->equal( "Expected value 1 to be !== than 1." );

            });

        });

        $this->describe( "when negating the expectation", function() {

            $this->it( "the spec passes if the comparison is true", function() {
                
                $this->expect( 1 ) ->not() ->to() ->be( "!==" ) ->than( 1 );

            });

            $this->it( "the spec fails if the comparison is false", function() {

                $this->expect( function() {

                    $this->expect( 1 ) ->not() ->to() ->be( "!==" ) ->than( "1" );

                }) ->to() ->raise( \Haijin\Specs\Expectation_Failure_Signal::class, function($e) {

                    $this->expect( $e->get_message() ) ->to()
                        ->equal( "Expected value 1 not to be !== than \"1\"." );

                });

            });

        });

    });

    $this->describe( "with the > operator", function() {

        $this->it( "the spec passes if the comparison is true", function() {
            
            $this->expect( 1 ) ->to() ->be( ">" ) ->than( 0 );

        });

        $this->it( "the spec fails if the comparison is false", function() {

            $this->expect( function() {

                $this->expect( 1 ) ->to() ->be( ">" ) ->than( 2 );

            }) ->to() ->raise( \Haijin\Specs\Expectation_Failure_Signal::class, function($e) {

                $this->expect( $e->get_message() ) ->to()
                    ->equal( "Expected value 1 to be > than 2." );

            });

        });

        $this->describe( "when negating the expectation", function() {

            $this->it( "the spec passes if the comparison is true", function() {
                
                $this->expect( 1 ) ->not() ->to() ->be( ">" ) ->than( 2 );

            });

            $this->it( "the spec fails if the comparison is false", function() {

                $this->expect( function() {

                    $this->expect( 1 ) ->not() ->to() ->be( ">" ) ->than( 0 );

                }) ->to() ->raise( \Haijin\Specs\Expectation_Failure_Signal::class, function($e) {

                    $this->expect( $e->get_message() ) ->to()
                        ->equal( "Expected value 1 not to be > than 0." );

                });

            });

        });

    });

    $this->describe( "with the >= operator", function() {

        $this->it( "the spec passes if the comparison is true", function() {
            
            $this->expect( 1 ) ->to() ->be( ">=" ) ->than( 0 );
            $this->expect( 1 ) ->to() ->be( ">=" ) ->than( 1 );

        });

        $this->it( "the spec fails if the comparison is false", function() {

            $this->expect( function() {

                $this->expect( 1 ) ->to() ->be( ">=" ) ->than( 2 );

            }) ->to() ->raise( \Haijin\Specs\Expectation_Failure_Signal::class, function($e) {

                $this->expect( $e->get_message() ) ->to()
                    ->equal( "Expected value 1 to be >= than 2." );

            });

        });

        $this->describe( "when negating the expectation", function() {

            $this->it( "the spec passes if the comparison is true", function() {
                
                $this->expect( 1 ) ->not() ->to() ->be( ">=" ) ->than( 2 );

            });

            $this->it( "the spec fails if the comparison is false", function() {

                $this->expect( function() {

                    $this->expect( 1 ) ->not() ->to() ->be( ">=" ) ->than( 0 );

                }) ->to() ->raise( \Haijin\Specs\Expectation_Failure_Signal::class, function($e) {

                    $this->expect( $e->get_message() ) ->to()
                        ->equal( "Expected value 1 not to be >= than 0." );

                });

                $this->expect( function() {

                    $this->expect( 1 ) ->not() ->to() ->be( ">=" ) ->than( 1 );

                }) ->to() ->raise( \Haijin\Specs\Expectation_Failure_Signal::class, function($e) {

                    $this->expect( $e->get_message() ) ->to()
                        ->equal( "Expected value 1 not to be >= than 1." );

                });

            });

        });

    });

    $this->describe( "with the < operator", function() {

        $this->it( "the spec passes if the comparison is true", function() {
            
            $this->expect( 1 ) ->to() ->be( "<" ) ->than( 2 );

        });

        $this->it( "the spec fails if the comparison is false", function() {

            $this->expect( function() {

                $this->expect( 1 ) ->to() ->be( "<" ) ->than( 0 );

            }) ->to() ->raise( \Haijin\Specs\Expectation_Failure_Signal::class, function($e) {

                $this->expect( $e->get_message() ) ->to()
                    ->equal( "Expected value 1 to be < than 0." );

            });

        });

        $this->describe( "when negating the expectation", function() {

            $this->it( "the spec passes if the comparison is true", function() {
                
                $this->expect( 1 ) ->not() ->to() ->be( "<" ) ->than( 0 );

            });

            $this->it( "the spec fails if the comparison is false", function() {

                $this->expect( function() {

                    $this->expect( 1 ) ->not() ->to() ->be( "<" ) ->than( 2 );

                }) ->to() ->raise( \Haijin\Specs\Expectation_Failure_Signal::class, function($e) {

                    $this->expect( $e->get_message() ) ->to()
                        ->equal( "Expected value 1 not to be < than 2." );

                });

            });

        });

    });

    $this->describe( "with the <= operator", function() {

        $this->it( "the spec passes if the comparison is true", function() {
            
            $this->expect( 1 ) ->to() ->be( "<=" ) ->than( 2 );
            $this->expect( 1 ) ->to() ->be( "<=" ) ->than( 1 );

        });

        $this->it( "the spec fails if the comparison is false", function() {

            $this->expect( function() {

                $this->expect( 1 ) ->to() ->be( "<=" ) ->than( 0 );

            }) ->to() ->raise( \Haijin\Specs\Expectation_Failure_Signal::class, function($e) {

                $this->expect( $e->get_message() ) ->to()
                    ->equal( "Expected value 1 to be <= than 0." );

            });

        });

        $this->describe( "when negating the expectation", function() {

            $this->it( "the spec passes if the comparison is true", function() {
                
                $this->expect( 1 ) ->not() ->to() ->be( "<=" ) ->than( 0 );

            });

            $this->it( "the spec fails if the comparison is false", function() {

                $this->expect( function() {

                    $this->expect( 1 ) ->not() ->to() ->be( "<=" ) ->than( 2 );

                }) ->to() ->raise( \Haijin\Specs\Expectation_Failure_Signal::class, function($e) {

                    $this->expect( $e->get_message() ) ->to()
                        ->equal( "Expected value 1 not to be <= than 2." );

                });

                $this->expect( function() {

                    $this->expect( 1 ) ->not() ->to() ->be( "<=" ) ->than( 1 );

                }) ->to() ->raise( \Haijin\Specs\Expectation_Failure_Signal::class, function($e) {

                    $this->expect( $e->get_message() ) ->to()
                        ->equal( "Expected value 1 not to be <= than 1." );

                });

            });

        });

    });
});