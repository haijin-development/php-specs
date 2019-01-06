<?php

use Haijin\Specs\SpecsRunner;

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

        }) ->to() ->raise( Haijin\Specs\UndefinedNamedExpressionError::class, function($e) {

            $this->expect( $e->getMessage() ) ->to()
                ->equal( "Undefined expression named 'undefined_expression'." );

        });

    });

    $this->describe( "in the container description", function() {

        $this->it( "inherits the expression from its container", function() {

            $this->expect( $this->n ) ->to() ->equal( 7 );

        });

        $this->describe( "and overrides it", function() {

            $this->let( "n", function() {
                return 1 + 2;
            });

            $this->it( "overrides the container expression", function() {

                $this->expect( $this->n ) ->to() ->equal( 3 );

            });

        });

        $this->it( "and overrides the expressions in a child description, it preserves the overriden expression", function() {

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