<?php

use Haijin\Specs\Specs_Runner;

$spec->describe( "When defining variables in before and after closures", function() {

    $this->before_all( function() {
        Specs_Runner::configure( function($specs) {

            $specs->before_all( function() {
                $this->before_all = true;
            });

            $specs->after_all( function() {
                $this->after_all = true;
            });

            $specs->before_each( function() {
                $this->before_each = true;
            });

            $specs->after_each( function() {
                $this->after_each = true;
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
            "/../../specs-samples/spec-with-variables-defined-closures.php";
    });

    $this->it( "the variables are defined during the scope of the expression in which they were defined", function() {

        $this->spec_runner->run_spec_file( $this->spec_file );

        $this->expect( $this->spec_runner->get_invalid_expectations() ) ->to() ->equal( [] );

    });

});