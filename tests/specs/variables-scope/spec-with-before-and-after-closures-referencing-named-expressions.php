<?php

$spec->describe( "A spec with before and after closures referencing methods and named expressions", function() {

    $this->def( "sum", function($n, $m) {
        return $n + $m;
    });

    $this->let( "value", function() {
        return 7;
    });

    $this->before_all( function() {

        $this->expect( $this->sum( 3, $this->value ) ) ->to() ->equal( 10 );

    });

    $this->after_all( function() {

        $this->expect( $this->sum( 3, $this->value ) ) ->to() ->equal( 10 );

    });

    $this->before_each( function() {

        $this->expect( $this->sum( 3, $this->value ) ) ->to() ->equal( 10 );

    });

    $this->after_each( function() {

        $this->expect( $this->sum( 3, $this->value ) ) ->to() ->equal( 10 );

    });

    $this->it( "resolves the references", function() {

        $this->expect( $this->sum( 3, $this->value ) ) ->to() ->equal( 10 );

    });
});
