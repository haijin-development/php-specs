<?php
declare(strict_types=1);

$spec->describe( "A custom method defined with def()", function() {

    $this->def('sum', function ($n, $m) {
        return $n + $m;
    });

    $this->it( "evaluates in a spec", function() {

        $this->expect( $this->sum(3, 4) ) ->to() ->equal( 7 );

    });
});