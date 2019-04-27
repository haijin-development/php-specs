<?php
declare(strict_types=1);

$spec->describe( "A single spec", function() {

    $this->it( "fails", function() {

        $this->expect( 1 ) ->to() ->equal( 2 );

    });

});