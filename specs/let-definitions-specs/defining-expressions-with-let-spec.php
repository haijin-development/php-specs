<?php

use Haijin\Specs\SpecsRunner;

$spec->describe( "When defining expressions with let", function() {

    $this->let( "n", function() {
        return 3 + 4;
    });

    $this->let( "accumulator", function() {
        return Accumulator::inc();
    });

    $this->it( "evaluates the expression when its referenced", function(){

        $this->expect( $this->n ) ->to() ->equal( 7 );

    });

    $this->it( "lazily evaluates the expression only the first time its referenced", function(){

        $this->accumulator;
        $this->accumulator;

        $this->expect( $this->n ) ->to() ->equal( 1 );

    });
});

class Accumulator
{
    static public $accumulator = 0;
}