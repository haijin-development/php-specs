<?php
declare(strict_types=1);

use Haijin\Specs\Errors\ExpectationFailureSignal;

$spec->describe( "When expecting a directory to exist", function() {

    $this->let( "dirPath", function() {
        return __DIR__ . "/../../specsSamples/";
    });

    $this->it( "the spec passes if the directory exists", function() {
        
        $this->expect( $this->dirPath ) ->to() ->be() ->aDirectory();

    });

    $this->it( "the spec fails if the directory does not exist", function() {

        $this->expect( function() {

            $this->expect( "missingDirectory/" ) ->to() ->be() ->aDirectory();

        }) ->to() ->raise( ExpectationFailureSignal::class, function($e) {

            $this->expect( $e->getMessage() ) ->to()
                ->equal( "Expected the directory \"missingDirectory/\" to exist." );

        });
        
    });

    $this->describe( "when negating the expectation", function() {

        $this->it( "the spec passes if the directory does not exist", function() {
            
            $this->expect( "missingDirectory/" ) ->not() ->to() ->be() ->aDirectory();

        });

        $this->it( "the spec fails if the directory exists", function() {

            $this->expect( function() {

                $this->expect( $this->dirPath ) ->not() ->to() ->be() ->aDirectory();

            }) ->to() ->raise( ExpectationFailureSignal::class, function($e) {

                $this->expect( $e->getMessage() ) ->to()
                    ->match( "|Expected the directory \"(.+)\" not to exist.|", function($matches) {
                        $this->expect( $matches[ 1 ] ) ->to() ->endWith( "/specsSamples/" );
                    });

            });
            
        });

    });
});