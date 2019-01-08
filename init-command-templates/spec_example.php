<?php

$spec->describe( "When ... ", function() {

    $this->let( "value", function() {
        return 1;
    });

    $this->it( " ... ", function() {

        $this->expect( $this->value ) ->to() ->equal( 1 );

    });

});