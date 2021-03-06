<?php
declare(strict_types=1);

use Haijin\Specs\Errors\ExpectationFailureSignal;

$spec->describe( "When expecting an array to include a value", function() {

    $this->it( "the spec passes if the array includes the value", function() {
        
        $this->expect( [ "a" => 1, "b" => 2, "c" => 3 ] ) ->to() ->includeValue( 3 );

    });

    $this->it( "the spec fails if the array does not include the value", function() {

        $this->expect( function() {

            $this->expect( [ "a" => 1, "b" => 2, "c" => 3 ] ) ->to() ->includeValue( 4 );

        }) ->to() ->raise( ExpectationFailureSignal::class, function($e) {

            $this->expect( $e->getMessage() ) ->to()
                ->equal( "Expected array to include value 4." );

        });
    });

    $this->describe( "when negating the expectation", function() {

        $this->it( "the spec passes if the array does not include the value", function() {
            
            $this->expect( [ "a" => 1, "b" => 2, "c" => 3 ] ) ->not() ->to() ->includeValue( 4 );

        });

        $this->it( "the spec fails if the array includes the value", function() {

            $this->expect( function() {

            $this->expect( [ "a" => 1, "b" => 2, "c" => 3 ] ) ->not() ->to() ->includeValue( 3 );

            }) ->to() ->raise( ExpectationFailureSignal::class, function($e) {

                $this->expect( $e->getMessage() ) ->to()
                ->equal( "Expected array not to include value 3." );

            });
        });

    });
});