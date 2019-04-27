<?php
declare(strict_types=1);

use Haijin\Specs\Errors\ExpectationFailureSignal;

$spec->describe( "When expecting a file path to exist", function() {

    $this->let( "filePath", function() {
        return __DIR__ . "/../../specsSamples/fileSample.txt";
    });

    $this->it( "the spec passes if the file exists", function() {
        
        $this->expect( $this->filePath ) ->to() ->be() ->aFile();

    });

    $this->it( "the spec fails if the file does not exist", function() {

        $this->expect( function() {

            $this->expect( "missingFile.txt" ) ->to() ->be() ->aFile();

        }) ->to() ->raise( ExpectationFailureSignal::class, function($e) {

            $this->expect( $e->getMessage() ) ->to()
                ->equal( "Expected the file \"missingFile.txt\" to exist." );

        });
        
    });

    $this->describe( "when negating the expectation", function() {

        $this->it( "the spec passes if the file does not exist", function() {
            
            $this->expect( "missingFile.txt" ) ->not() ->to() ->be() ->aFile();

        });

        $this->it( "the spec fails if the file exists", function() {

            $this->expect( function() {

                $this->expect( $this->filePath ) ->not() ->to() ->be() ->aFile();

            }) ->to() ->raise( ExpectationFailureSignal::class, function($e) {

                $this->expect( $e->getMessage() ) ->to()
                    ->match( "|Expected the file \"(.+)\" not to exist.|", function($matches) {
                        $this->expect( $matches[ 1 ] ) ->to() ->endWith( "specsSamples/fileSample.txt" );
                    });

            });
            
        });

    });
});