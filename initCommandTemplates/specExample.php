<?php
declare(strict_types=1);

/*
 * An spec example template.
 */

$spec->describe( 'When some condition ... ', function() {

    $this->let( 'value', function() {
        return 1;
    });

    $this->it( ' returns a value ... ', function() {

        $this->expect( $this->value ) ->to() ->equal( 1 );

    });

});