<?php
declare(strict_types=1);

use Haijin\Specs\Errors\ExpectationFailureSignal;

$spec->describe( "When expecting an array to include a key", function() {

    $this->let( "array", function () {
        return [ "a" => 1, "b" => 2, "c" => 3 ];
    });

    $this->it( "the spec passes if the array includes the key", function() {
        
        $this->expect( $this->array ) ->to() ->includeKey( "c" );

    });

    $this->it( "the spec fails if the array does not include the key", function() {

        $this->expect( function() {

            $this->expect( $this->array ) ->to() ->includeKey( "d" );

        }) ->to() ->raise( ExpectationFailureSignal::class, function($e) {

            $this->expect( $e->getMessage() ) ->to()
                ->equal( "Expected array to include key \"d\"." );

        });
    });

    $this->describe( "when negating the expectation", function() {

        $this->it( "the spec passes if the array does not include the key", function() {
            
            $this->expect( $this->array ) ->not() ->to() ->includeKey( "d" );

        });

        $this->it( "the spec fails if the array includes the key", function() {

            $this->expect( function() {

                $this->expect( $this->array ) ->not() ->to() ->includeKey( "c" );

            }) ->to() ->raise( ExpectationFailureSignal::class, function($e) {

                $this->expect( $e->getMessage() ) ->to()
                ->equal( "Expected array not to include key \"c\"." );

            });
        });

    });

    $this->describe( "with a value closure", function() {

        $this->it( "the spec passes and the value closure if the array includes the key", function() {

            $this->valueClosureWasCalled = false;

            $this->expect( $this->array ) ->to() ->includeKey( "c", function($value) {
                $this->valueClosureWasCalled = true;

                $this->expect( $value ) ->to() ->equal( 3 );
            });

            $this->expect( $this->valueClosureWasCalled ) ->to() ->be() ->true();
        });

        $this->it( "the spec fails if the array does not include the key, and the value closure is not evaluated", function() {

            $this->expect( function() {

                $this->expect( $this->array ) ->to() ->includeKey( "d", function($value) {
                    throw new RuntimeException( "This closure should not be evaluated" );
                });

            }) ->to() ->raise( ExpectationFailureSignal::class, function($e) {

                $this->expect( $e->getMessage() ) ->to()
                    ->equal( "Expected array to include key \"d\"." );

            });

        });

    });
});