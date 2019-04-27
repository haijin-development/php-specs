<?php
declare(strict_types=1);

$spec->describe( "A spec with before and after closures referencing methods and named expressions", function() {

    $this->def( "sum", function($n, $m) {
        return $n + $m;
    });

    $this->let( "value", function() {
        return 7;
    });

    $this->beforeAll( function() {

        $this->expect( $this->sum( 3, $this->value ) ) ->to() ->equal( 10 );

    });

    $this->afterAll( function() {

        $this->expect( $this->sum( 3, $this->value ) ) ->to() ->equal( 10 );

    });

    $this->beforeEach( function() {

        $this->expect( $this->sum( 3, $this->value ) ) ->to() ->equal( 10 );

    });

    $this->afterEach( function() {

        $this->expect( $this->sum( 3, $this->value ) ) ->to() ->equal( 10 );

    });

    $this->it( "resolves the references", function() {

        $this->expect( $this->sum( 3, $this->value ) ) ->to() ->equal( 10 );

    });
});
