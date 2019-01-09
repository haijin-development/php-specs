<?php

use Haijin\Specs\Specs_Runner;

$spec->describe( "When running specs from a file", function() {

    $this->let( "spec_runner", function() {
        return new Specs_Runner();
    });

    $this->describe( "each failed expectation", function(){

        $this->let( "spec_file", function() {
            return __DIR__ . "/../../specs-samples/single-spec-failure.php";
        });

        $this->it( "has the failure message", function() {

            $this->spec_runner->run_spec_file( $this->spec_file );

            $failed_expectations = $this->spec_runner->get_invalid_expectations();

            $this->expect( $failed_expectations[0]->get_message() )
                ->to() ->equal( "Expected value to equal 2, got 1." );

        });

        $this->it( "has the nested description", function() {

            $this->spec_runner->run_spec_file( $this->spec_file );

            $failed_expectations = $this->spec_runner->get_invalid_expectations();

            $this->expect( $failed_expectations[0]->get_description() )
                ->to() ->equal( "A single spec fails" );

        });

        $this->it( "has the file name", function() {

            $this->spec_runner->run_spec_file( $this->spec_file );

            $failed_expectations = $this->spec_runner->get_invalid_expectations();

            $this->expect( $failed_expectations[0]->get_file_name() )
                ->to() ->end_with( "specs-samples/single-spec-failure.php" );

        });

        $this->it( "has the number of line in the file", function() {

            $this->spec_runner->run_spec_file( $this->spec_file );

            $failed_expectations = $this->spec_runner->get_invalid_expectations();

            $this->expect( $failed_expectations[0]->get_line() ) ->to() ->equal( 7 );

        });

    });

    $this->describe( "the expectation runner statistics", function(){

        $this->let( "spec_file", function() {
            return __DIR__ .
                "/../../specs-samples/spec-with-one-failure-one-error-and-two-success.php";
        });

        $this->it( "has the failures count", function() {

            $this->spec_runner->run_spec_file( $this->spec_file );

            $count = $this->spec_runner->get_statistics()->failed_specs_count();

            $this->expect( $count ) ->to() ->equal( 1 );
        });

        $this->it( "has the errors count", function() {

            $this->spec_runner->run_spec_file( $this->spec_file );

            $count = $this->spec_runner->get_statistics()->errored_specs_count();

            $this->expect( $count ) ->to() ->equal( 1 );
        });

        $this->it( "has the run specs count", function() {

            $this->spec_runner->run_spec_file( $this->spec_file );

            $count = $this->spec_runner->get_statistics()->run_specs_count();

            $this->expect( $count ) ->to() ->equal( 4 );
        });

        $this->it( "has the run expectations count", function() {

            $this->spec_runner->run_spec_file( $this->spec_file );

            $count = $this->spec_runner->get_statistics()->run_expectations_count();

            $this->expect( $count ) ->to() ->equal( 3 );
        });
    });

    $this->describe( "with a on_spec_run_do closure defined", function() {

        $this->let( "spec_file", function() {
            return __DIR__ .
                "/../../specs-samples/spec-with-one-failure-one-error-and-two-success.php";
        });

        $this->it( "evaluates the on_spec_run_do after each spec run", function() {

            $this->specs_evaluations = 0;

            $context = $this;

            $this->spec_runner->on_spec_run_do( function($spec, $status) use($context) {

                $context->specs_evaluations += 1;

            });

            $this->spec_runner->run_spec_file( $this->spec_file );

            $this->expect( $this->specs_evaluations ) ->to() ->equal( 4 );

        });

    });

    $this->describe( "with global before_all_ after_all, before_each and after_each closures", function() {

        $this->let( "spec_file", function() {
            return __DIR__ .
                "/../../specs-samples/spec-with-one-failure-one-error-and-two-success.php";
        });

        $this->before_all( function() {
            Specs_Runner::configure( function($specs) {

                $specs->before_all( function() {
                    $this->evaluations = [];
                    $this->evaluations[] = "before-all";
                });

                $specs->after_all( function() {
                    $this->evaluations[] = "after-all";
                });

                $specs->before_each( function() {
                    $this->evaluations[] = "before-each";
                });

                $specs->after_each( function() {
                    $this->evaluations[] = "after-each";
                });

            });

        });

        $this->after_all( function() {
            Specs_Runner::configure( function($specs) {
            });
        });

        $this->it( "evaluates the closures on every spec", function() {

            $this->spec_runner->run_spec_file( $this->spec_file );

            $this->expect( $this->spec_runner->___get_specs_evaluator()->evaluations ) ->to()
                ->equal([
                    "before-all",
                    "before-each",
                    "after-each",
                    "before-each",
                    "after-each",
                    "before-each",
                    "after-each",
                    "before-each",
                    "after-each",
                    "after-all"
                ]);
        });

    });
});