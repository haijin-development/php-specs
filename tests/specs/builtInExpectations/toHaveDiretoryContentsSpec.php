<?php
declare(strict_types=1);

use Haijin\Specs\Errors\ExpectationFailureSignal;

$spec->describe( "When expecting a directory to have contents", function() {

    $this->let( "dirPath", function() {
        return __DIR__ . "/../../specsSamples";
    });

    $this->it( "the spec passes if the directory has the contents", function() {
        
        $this->expect( $this->dirPath ) ->to() ->haveDirectoryContents( function($files, $basePath) {

            $this->expect( $basePath ) ->to() ->match( "|^.+\/specsSamples$|" );

            $this->expect( $files ) ->to() ->includeAll([
                ".",
                "..",
                "fileSample.txt",
                "singleSpecFailure.php",
                "specWithOneFailureOneErrorAndTwoSuccessOneSkip.php"
            ]);

        });

    });

    $this->it( "the spec fails if the directory does not exist", function() {

        $this->expect( function() {

            $this->expect( "missingDir/" ) ->to() ->haveDirectoryContents( function($files, $basePath) {

                $this->expect( $basePath ) ->to() ->match( "|^.+\/specsSamples$|" );

            });

        }) ->to() ->raise( ExpectationFailureSignal::class, function($e) {

            $this->expect( $e->getMessage() ) ->to()
                ->equal( "Expected the directory \"missingDir/\" to have contents, but is does not exist." );

        });
        
    });

    $this->it( "the spec fails if the directory is a file", function() {

        $this->expect( function() {

            $this->expect( $this->dirPath . '/fileSample.txt' ) ->to() ->haveDirectoryContents( function($files, $basePath) {

                $this->expect( $basePath ) ->to() ->match( "|^.+\/specsSamples$|" );

            });

        }) ->to() ->raise( ExpectationFailureSignal::class, function($e) {

            $this->expect( $e->getMessage() ) ->to() ->match(
                "|Expected \".+fileSample.txt\" to be a directory, but it is a file.|"
            );

        });

    });

});