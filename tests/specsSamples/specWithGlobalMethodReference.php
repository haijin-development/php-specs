<?php
declare(strict_types=1);

$spec->describe( "A spec with a reference to a global method", function() {

    $this->it( "evaluates the method", function() {

        $this->expect( $this->sum( 3, 4 ) ) ->to() ->equal( 7 );

    });

});