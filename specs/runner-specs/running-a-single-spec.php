<?php

use Haijin\Specs\SpecsRunner;

$spec->describe( "When running a single spec from a file", function() {

    $this->describe( "each failed expectations", function(){

        $this->it( "has the failure message", function() {

            $spec_file = __DIR__ . "/../../specs-samples/single-spec-failure.php";

            $spec_runner = new SpecsRunner();

            $spec_runner->run_spec_file( $spec_file );

            $failed_expectations = $spec_runner->get_invalid_expectations();

            $this->expect( $failed_expectations[0]->get_message() )
                ->to() ->equal("Expected value to equal 2, got 1.");

        });

        $this->it( "has the nested description", function() {

            $spec_file = __DIR__ . "/../../specs-samples/single-spec-failure.php";

            $spec_runner = new SpecsRunner();

            $spec_runner->run_spec_file( $spec_file );

            $failed_expectations = $spec_runner->get_invalid_expectations();

            $this->expect( $failed_expectations[0]->get_description() )
                ->to() ->equal( "A single spec fails" );

        });

        $this->it( "has the file name", function() {

            $spec_file = __DIR__ . "/../../specs-samples/single-spec-failure.php";

            $spec_runner = new SpecsRunner();

            $spec_runner->run_spec_file( $spec_file );

            $failed_expectations = $spec_runner->get_invalid_expectations();

            $this->expect( $failed_expectations[0]->get_file_name() )
                ->to() ->end_with( "specs-samples/single-spec-failure.php" );

        });

        $this->it( "has the number of line in the file", function() {

            $spec_file = __DIR__ . "/../../specs-samples/single-spec-failure.php";

            $spec_runner = new SpecsRunner();

            $spec_runner->run_spec_file( $spec_file );

            $failed_expectations = $spec_runner->get_invalid_expectations();

            $this->expect( $failed_expectations[0]->get_line() ) ->to() ->equal( 7 );

        });

    });

});