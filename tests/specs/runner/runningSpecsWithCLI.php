<?php
declare(strict_types=1);

use Haijin\Specs\Runners\SpecsCLI;

$spec->describe('When running specs with the command line', function() {

    $this->let('specsSubFolder', function() {
        return 'specs';
    });

    $this->let( 'cli', function() {
        return new SpecsCLI($this->testsFolder, $this->specsSubFolder);
    });

    $this->describe('to show the command line help', function () {

        $this->let( 'testsFolder', function() {
            return __DIR__ . '/../../sampleTestsFolder/';
        });

        $this->it('shows the help', function () {
            ob_start();

            $this->cli->runHelpCommand();

            $helpText = ob_get_clean();

            $this->expect($helpText) ->to() ->equal(
                "" .
                "Usage:" . "\n" .
                "\n" .
                "  php ./vendor/bin/specs help|--help|-h" . "\n" .
                "    Show this help message." . "\n" .
                "\n" .
                "  php ./vendor/bin/specs init" . "\n" .
                "    Creates the initial directories setup for the specs if it does not exists." . "\n" .
                "\n" .
                "  php ./vendor/bin/specs [directory|filename[:line]]" . "\n" .
                "    If no filename or directory is given runs all the specs files located in the directory tests/specs/." . "\n" .
                "    Example:" . "\n" .
                "      php ./vendor/bin/specs" . "\n" .
                "\n" .
                "    If a directory is given runs all the specs located in that directory." . "\n" .
                "    Example:" . "\n" .
                "      php ./vendor/bin/specs tests/specs/beforeAndAfterClosures" . "\n" .
                "\n" .
                "    If a filename is given runs all the specs located in that filename." . "\n" .
                "    Example:" . "\n" .
                "      php ./vendor/bin/specs tests/specs/beforeAndAfterClosures/evaluatingBeforeAndAfterClosures.php" . "\n" .
                "\n" .
                "    If a filename:line is given runs the single spec located at the given line number of that filename." . "\n" .
                "    Example:" . "\n" .
                "      php ./vendor/bin/specs tests/specs/beforeAndAfterClosures/evaluatingBeforeAndAfterClosures.php:48" . "\n" .
                ""
            );
        });
    });

    $this->describe('to initialize the specs', function() {

        $this->beforeEach( function() {
            $this->deleteTestsFolder();
        });

        $this->afterEach( function() {
            $this->deleteTestsFolder();
        });

        $this->def( 'deleteTestsFolder', function() {
            if( file_exists( $this->testsFolder ) ) {
                unlink( $this->testsFolder . 'specsBoot.php' );
                unlink( $this->testsFolder . 'specs/specExample.php' );
                rmdir( $this->testsFolder . 'specs/' );
                rmdir( $this->testsFolder );
            }
        });

        $this->def('createTestsFolder', function() {
            mkdir( $this->testsFolder );
        });

        $this->let('testsFolder', function() {
            return __DIR__ . '/../../tmp/';
        });

        $this->let('bootFileContents', function() {
            return file_get_contents(__DIR__ . '/../../../initCommandTemplates/specsBoot.php');
        });

        $this->let('specExampleFileContents', function() {
            return file_get_contents(__DIR__ . '/../../../initCommandTemplates/specExample.php');
        });

        $this->it('creates the specs files', function() {

            ob_start();

            $this->cli->runSpecsInitCommand();

            $output = ob_get_clean();

            $this->expect( $this->testsFolder . 'specsBoot.php' ) ->to() ->haveFileContents( function($contents) {

                $this->expect( $contents ) ->to() ->equal( $this->bootFileContents ) ;

            });

            $this->expect( $this->testsFolder . 'specs/specExample.php' ) ->to() ->haveFileContents( function($contents) {

                $this->expect( $contents ) ->to() ->equal( $this->specExampleFileContents ) ;

            });

            $this->expect($output ) ->to()
                ->match("|^Created 'tests/specs/' folder in (.+)../../tmp/specs/[.]\nCreated 'specsBoot.php' in (.+)../../tmp/specsBoot.php[.]\n$|");
        });

        $this->it('does not fail if the specs files exist already', function() {

            ob_start();

            $this->cli->runSpecsInitCommand();

            ob_clean();

            $this->cli->runSpecsInitCommand();

            $output = ob_get_clean();

            $this->expect( $this->testsFolder . 'specsBoot.php' ) ->to() ->haveFileContents( function($contents) {

                $this->expect( $contents ) ->to() ->equal( $this->bootFileContents ) ;

            });

            $this->expect( $this->testsFolder . 'specs/specExample.php' ) ->to() ->haveFileContents( function($contents) {

                $this->expect( $contents ) ->to() ->equal( $this->specExampleFileContents ) ;

            });

            $this->expect($output ) ->to()
                ->match("|^'tests/specs/' folder not created because it already exists in (.+)../../tmp/specs/[.]\n'specsBoot.php' not created because it already exists in (.+)../../tmp/specsBoot.php[.]\n$|");

        });

    });

    $this->describe('to run the command line help', function () {

        $this->let( 'testsFolder', function() {
            return __DIR__ . '/../../sampleTestsFolder/';
        });

        $this->it('runs the specs', function () {
            $this->cli->runSpecsCommand($this->testsFolder . '/specs');
        });
    });

});