<?php

use Haijin\Specs\Specs_Runner;

$spec->describe( "When evaluating before and after closures", function() {

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

    $this->let( "spec_runner", function() {
        return new Specs_Runner();
    });

    $this->let( "spec_file", function() {
        return __DIR__ .
            "/../../specs-samples/spec-with-before-and-after-closures.php";
    });

    $this->it( "evaluates the on_spec_run_do after each spec run", function() {

        $this->spec_runner->run_spec_file( $this->spec_file );

        $this->expect( $this->spec_runner->get_invalid_expectations() ) ->to() ->equal( [] );

        $evaluations = $this->spec_runner->___get_specs_evaluator()->evaluations;

        $this->expect( $evaluations ) ->to() ->equal([
            "before-all",
                "before-all-outer",
                    "before-all-inner",
                        "before-each",
                        "before-each-outer",
                        "before-each-inner",
                        "after-each-inner",
                        "after-each-outer",
                        "after-each",
                        "before-each",
                        "before-each-outer",
                        "before-each-inner",
                        "after-each-inner",
                        "after-each-outer",
                        "after-each",
                    "after-all-inner",
                    "before-each",
                    "before-each-outer",
                    "after-each-outer",
                    "after-each",
                "after-all-outer",
            "after-all"
        ]);

    });

});