<?php

use Haijin\Specs\Errors\ExpectationDefinitionError;

$spec->describe('When using invalid expectations', function() {

    $this->describe('if the expectation is not defined', function(){

        $this->it( 'raises an ExpectationDefinitionError', function() {

            $this->expect( function() {

                $this->expect( 123 ) ->to() ->undefinedExpectation();

            }) ->to() ->raise( ExpectationDefinitionError::class, function($e) {

                $this->expect( $e->getMessage() ) ->to()
                    ->equal( "The expectation '->undefinedExpectation(...)' is not defined." );

            });

        });
    });
});