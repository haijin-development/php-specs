<?php

$spec->describe( "When expecting a file to have contents", function() {

    $this->let( "file_path", function() {
        return __DIR__ . "/../../specs-samples/file-sample.txt";
    });

    $this->it( "the spec passes if the file has the contents", function() {
        
        $this->expect( $this->file_path ) ->to() ->have_file_contents( function($contents) {
            $this->expect( $contents ) ->to() ->equal( "Sample file contents." );
        });

    });

    $this->it( "the spec fails if the file does not exist", function() {

        $this->expect( function() {

            $this->expect( "missing_file.txt" ) ->to() ->have_file_contents( function($contents) {
                $this->expect( $contents ) ->to() ->equal( "Sample file contents." );
            });

        }) ->to() ->raise( \Haijin\Specs\Expectation_Failure_Signal::class, function($e) {

            $this->expect( $e->get_message() ) ->to()
                ->equal( "Expected the file \"missing_file.txt\" to have contents, but is does not exist." );

        });
        
    });

});