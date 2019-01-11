<?php

use Haijin\Specs\Specs_Runner;

$spec->describe( "When defining a expression with let", function() {

    $this->let( "n", function() {
        return 3 + 4;
    });

    $this->let( "accumulator", function() {
        return Accumulator::inc();
    });

    $this->it( "evaluates the expression when its referenced", function(){

        $this->expect( $this->n ) ->to() ->equal( 7 );

    });

    $this->it( "lazily evaluates the expression only once, the first time its referenced", function(){

        $this->accumulator;
        $this->accumulator;
        $this->accumulator;

        $this->expect( $this->accumulator ) ->to() ->equal( 1 );

    });

    $this->it( "raises an error if the named expression is not defined", function(){

        $this->expect( function() {

            $this->undefined_expression;

        }) ->to() ->raise( Haijin\Specs\Undefined_Named_Expression_Error::class, function($e) {

            $this->expect( $e->getMessage() ) ->to()
                ->equal( "Undefined expression named 'undefined_expression'." );

        });

    });

    $this->describe( "in the parent spec", function() {

        $this->it( "the child spec inherits the expression", function() {

            $this->expect( $this->n ) ->to() ->equal( 7 );

        });

        $this->describe( "and overrides it in the child spec", function() {

            $this->let( "n", function() {
                return 1 + 2;
            });

            $this->it( "overrides the parent named expression", function() {

                $this->expect( $this->n ) ->to() ->equal( 3 );

            });

        });

        $this->it( "and a child overrides the named expression, the parent spec preserves the expression", function() {

            $this->expect( $this->n ) ->to() ->equal( 7 );

        });

    });


    $this->describe( "that references another named expression", function() {

        $this->let( "m", function() {
            return $this->n + 1;
        });

        $this->it( "lazily resolves the reference", function() {

            $this->expect( $this->m ) ->to() ->equal( 8 );

        });

    });

    $this->describe( "in the Specs_Runner config", function() {

        $this->before_all( function() {
            Specs_Runner::configure( function($specs) {

                $this->let( "n", function() {
                    return 3 + 4;
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
                "/../../specs-samples/spec-with-global-let-reference.php";
        });

        $this->it( "evaluates the expression when its referenced", function(){

            $this->spec_runner->run_spec_file( $this->spec_file );

            $this->expect( $this->spec_runner->get_invalid_expectations() ) ->to() ->equal( [] );

        });

    });

});

class Accumulator
{
    static public $accumulator = 0;

    static public function inc()
    {
        self::$accumulator += 1;

        return self::$accumulator;
    }
}