<?php
declare(strict_types=1);

use Haijin\Specs\Errors\ExpectationFailureSignal;

$spec->describe( "When expecting a file to have contents", function() {

    $this->let( "filePath", function() {
        return __DIR__ . "/../../specsSamples/fileSample.txt";
    });

    $this->it( "the spec passes if the file has the contents", function() {
        
        $this->expect( $this->filePath ) ->to() ->haveFileContents( function($contents) {
            $this->expect( $contents ) ->to() ->equal( "Sample file contents." );
        });

    });

    $this->it( "the spec fails if the file does not exist", function() {

        $this->expect( function() {

            $this->expect( "missingFile.txt" ) ->to() ->haveFileContents( function($contents) {
                $this->expect( $contents ) ->to() ->equal( "Sample file contents." );
            });

        }) ->to() ->raise( ExpectationFailureSignal::class, function($e) {

            $this->expect( $e->getMessage() ) ->to()
                ->equal( "Expected the file \"missingFile.txt\" to have contents, but is does not exist." );

        });
        
    });

});