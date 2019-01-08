<?php

$spec->describe( "A single spec", function() {

    $this->it( "fails", function() {

        $this->expect( 1 ) ->to() ->equal( 2 );

    });

    $this->it( "passes", function() {

        $this->expect( 1 ) ->to() ->equal( 1 );

    });

    $this->it( "also passes", function() {

        $this->expect( 1 ) ->to() ->equal( 1 );

    });

    $this->it( "throws an Exception", function() {

        throw new Exception( "Intended exception raised." );

    });
});