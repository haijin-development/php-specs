<?php

use Haijin\Specs\Specs_CLI;

$spec->describe( "When running specs with the command line", function() {

    $this->def( "delete_tests_folder", function() {
        if( file_exists( $this->tests_folder ) ) {
            unlink( $this->tests_folder . "specs_boot.php" );
            unlink( $this->tests_folder . "specs/spec_example.php" );
            rmdir( $this->tests_folder . "specs/" );
            rmdir( $this->tests_folder );
        }
    });

    $this->def( "create_tests_folder", function() {
        mkdir( $this->tests_folder );
    });

    $this->let( "tests_folder", function() {
        return __DIR__ . "/../../tmp/";
    });

    $this->let( "cli", function() {
        return new Specs_CLI();
    });

    $this->before_each( function() {
        $this->delete_tests_folder();
        $this->create_tests_folder();
    });

    $this->after_each( function() {
        $this->delete_tests_folder();
    });

    $this->describe( "to initialize the specs", function() {

        $this->let( "boot_file_contents", function() {
            return file_get_contents( __DIR__ . "/../../../init-command-templates/specs_boot.php" );
        });

        $this->let( "spec_example_file_contents", function() {
            return file_get_contents( __DIR__ . "/../../../init-command-templates/spec_example.php" );
        });

        $this->it( "creates the specs files", function() {

            $this->cli->set_tests_path( $this->tests_folder );

            $this->cli->run_specs_init_command();

            $this->expect( __DIR__ . "/../../tmp/specs_boot.php" ) ->to() ->have_file_contents( function($contents) {

                $this->expect( $contents ) ->to() ->equal( $this->boot_file_contents ) ;
            });

            $this->expect( __DIR__ . "/../../tmp/specs/spec_example.php" ) ->to() ->have_file_contents( function($contents) {

                $this->expect( $contents ) ->to() ->equal( $this->spec_example_file_contents ) ;
            });
        });

    });

});