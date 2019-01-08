<?php

$spec->describe( "When expecting a folder to have contents", function() {

    $this->let( "folder_path", function() {
        return __DIR__ . "/../../specs-samples";
    });

    $this->it( "the spec passes if the folder has the contents", function() {
        
        $this->expect( $this->folder_path ) ->to() ->have_folder_contents( function($files, $base_path) {

            $this->expect( $base_path ) ->to() ->match( "/^.+\/specs-samples$/" );

            $this->expect( $files ) ->to() ->include_all([
                ".",
                "..",
                "file-sample.txt",
                "single-spec-failure.php",
                "spec-with-one-failure-one-error-and-two-success.php"
            ]);

        });

    });

    $this->it( "the spec fails if the folder does not exist", function() {

        $this->expect( function() {

            $this->expect( "missing_folder/" ) ->to() ->have_folder_contents( function($file, $base_path) {

                $this->expect( $base_path ) ->to() ->match( "/^.+\/specs-samples$/" );

                $this->expect( $files ) ->to() ->include_all([
                    ".",
                    "..",
                    "file-sample.txt",
                    "single-spec-failure.php",
                    "spec-with-one-failure-one-error-and-two-success.php"
                ]);

            });

        }) ->to() ->raise( \Haijin\Specs\Expectation_Failure_Signal::class, function($e) {

            $this->expect( $e->get_message() ) ->to()
                ->equal( "Expected the folder \"missing_folder/\" to have contents, but is does not exist." );

        });
        
    });

});