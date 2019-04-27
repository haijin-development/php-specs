<?php
declare(strict_types=1);

use Haijin\Specs\Errors\ExpectationFailureSignal;

$spec->describe( 'When failing explicitly a spec', function() {

    $this->it( 'the spec fails with the given message', function() {

        $this->expect( function() {

            $this->fail( 'Failure text.' );

        }) ->to() ->raise( ExpectationFailureSignal::class, function($e) {

            $this->expect( $e->getMessage() ) ->to()
                ->equal( 'Failure text.' );

            $this->expect( $e->getDescription() ) ->to()
                ->equal( 'When failing explicitly a spec the spec fails with the given message' );

        });
        
    });

});