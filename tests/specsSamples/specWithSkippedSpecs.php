<?php
declare(strict_types=1);

$spec->describe( "Skip", function() {

    $this->xit( "a spec", function() {

        $this->expect( 1 ) ->to() ->equal( 2 );

    });

    $this->xdescribe( "a description", function() {

        $this->it( "with a spec", function() {

            $this->expect( 1 ) ->to() ->equal( 2 );

        });

        $this->it( "with another spec", function() {

            $this->expect( 1 ) ->to() ->equal( 2 );

        });

    });
});