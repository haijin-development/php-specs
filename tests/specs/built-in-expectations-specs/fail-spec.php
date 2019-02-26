<?php

$spec->describe( "When failing explicitly a spec", function() {

    $this->it( "the spec fails with the given message", function() {

        $this->expect( function() {

            $this->fail( "Failure text." );

        }) ->to() ->raise( \Haijin\Specs\Expectation_Failure_Signal::class, function($e) {

            $this->expect( $e->get_message() ) ->to()
                ->equal( "Failure text." );

        });
        
    });

});