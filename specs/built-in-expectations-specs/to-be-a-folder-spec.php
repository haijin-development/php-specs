<?php

$spec->describe( "When expecting a folder to exist", function() {

    $this->let( "folder_path", function() {
        return __DIR__ . "/../../specs-samples/";
    });

    $this->it( "the spec passes if the folder exists", function() {
        
        $this->expect( $this->folder_path ) ->to() ->be() ->a_folder();

    });

    $this->it( "the spec fails if the folder does not exist", function() {

        $this->expect( function() {

            $this->expect( "missing_folder/" ) ->to() ->be() ->a_folder();

        }) ->to() ->raise( \Haijin\Specs\ExpectationFailureSignal::class, function($e) {

            $this->expect( $e->get_message() ) ->to()
                ->equal( "Expected the folder \"missing_folder/\" to exist." );

        });
        
    });

    $this->describe( "when negating the expectation", function() {

        $this->it( "the spec passes if the folder does not exist", function() {
            
            $this->expect( "missing_folder/" ) ->not() ->to() ->be() ->a_folder();

        });

        $this->it( "the spec fails if the file exists", function() {

            $this->expect( function() {

                $this->expect( $this->folder_path ) ->not() ->to() ->be() ->a_folder();

            }) ->to() ->raise( \Haijin\Specs\ExpectationFailureSignal::class, function($e) {

                $this->expect( $e->get_message() ) ->to()
                    ->match( "/Expected the folder \"(.+)\" not to exist./", function($matches) {
                        $this->expect( $matches[ 1 ] ) ->to() ->end_with( "/specs-samples/" );
                    });

            });
            
        });

    });
});