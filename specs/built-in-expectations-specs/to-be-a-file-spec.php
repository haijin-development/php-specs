<?php

$spec->describe( "When expecting a file path to exist", function() {

    $this->let( "file_path", function() {
        return __DIR__ . "/../../specs-samples/file-sample.txt";
    });

    $this->it( "the spec passes if the file exists", function() {
        
        $this->expect( $this->file_path ) ->to() ->be() ->a_file();

    });

    $this->it( "the spec fails if the file does not exist", function() {

        $this->expect( function() {

            $this->expect( "missing_file.txt" ) ->to() ->be() ->a_file();

        }) ->to() ->raise( \Haijin\Specs\ExpectationFailureSignal::class, function($e) {

            $this->expect( $e->get_message() ) ->to()
                ->equal( "Expected the file \"missing_file.txt\" to exist." );

        });
        
    });

    $this->describe( "when negating the expectation", function() {

        $this->it( "the spec passes if the file does not exist", function() {
            
            $this->expect( "missing_file.txt" ) ->not() ->to() ->be() ->a_file();

        });

        $this->it( "the spec fails if the file exists", function() {

            $this->expect( function() {

                $this->expect( $this->file_path ) ->not() ->to() ->be() ->a_file();

            }) ->to() ->raise( \Haijin\Specs\ExpectationFailureSignal::class, function($e) {

                $this->expect( $e->get_message() ) ->to()
                    ->match( "/Expected the file \"(.+)\" not to exist./", function($matches) {
                        $this->expect( $matches[ 1 ] ) ->to() ->end_with( "specs-samples/file-sample.txt" );
                    });

            });
            
        });

    });
});