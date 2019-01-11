<?php

use Haijin\Specs\Specs_Runner;

$spec->describe( "When defining a method with def", function() {

    $this->def( "sum", function($n, $m) {
        return $n + $m;
    });


    $this->it( "evaluates the method when its called", function(){

        $this->expect( $this->sum( 3, 4 ) ) ->to() ->equal( 7 );

    });

    $this->describe( "When defining a method in an inner scope", function() {

        $this->def( "inc", function($n) {
            return $n + 1;
        });

        $this->it( "evaluates the method", function(){

            $this->expect( $this->inc( 3 ) ) ->to() ->equal( 4 );

        });

    });

    $this->it( "raises an error with a method defined in an inner scope called from an outer scope", function(){

        $this->expect( function() {

            $this->expect( $this->inc( 3 ) ) ->to() ->equal( 4 );

        }) ->to() ->raise( \Haijin\Specs\Undefined_Method_Error::class, function($error) {

            $this->expect( $error->getMessage() ) ->to()
                ->equal( "Undefined method named 'inc'." );

        });

    });

    $this->describe( "that calls another defined method", function() {

        $this->def( "sum_and_inc", function($n, $m) {
            return $this->sum( $n, $m ) + 3;
        });

        $this->it( "evaluates the method", function(){

            $this->expect( $this->sum_and_inc( 3, 4 ) ) ->to() ->equal( 10 );

        });

    });

    $this->describe( "in the Specs_Runner config", function() {

        $this->before_all( function() {
            Specs_Runner::configure( function($specs) {

                $this->def( "sum", function($n, $m) {
                    return $n + $m;
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
                "/../../specs-samples/spec-with-global-method-reference.php";
        });

        $this->it( "evaluates the expression when its referenced", function(){

            $this->spec_runner->run_spec_file( $this->spec_file );

            $this->expect( $this->spec_runner->get_invalid_expectations() ) ->to() ->equal( [] );

        });

    });

});