<?php
declare(strict_types=1);

$spec->describe( "A spec with a reference to a global named expression", function() {

    $this->let( "m", function() {
        return $this->n + 3;
    });

    $this->it( "evaluates the named expression", function() {

        $this->expect( $this->n ) ->to() ->equal( 7 );

    });

    $this->it( "evaluates the named expression from another local named expression", function() {

        $this->expect( $this->m ) ->to() ->equal( 10 );

    });

});