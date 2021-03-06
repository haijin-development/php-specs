<?php
declare(strict_types=1);

use Haijin\Specs\Errors\ExpectationFailureSignal;

$spec->describe( "When expecting an array to include a collection of values", function() {

    $this->it( "the spec passes if the array includes all the values", function() {
        
        $this->expect( [ 1, 2, 3 ] ) ->to() ->includeAll( [] );
        $this->expect( [ 1, 2, 3 ] ) ->to() ->includeAll( [ 1 ] );
        $this->expect( [ 1, 2, 3 ] ) ->to() ->includeAll( [ 1, 2, 3 ] );

    });

    $this->it( "the spec fails if the array does not include all the values", function() {

        $this->expect( function() {

        $this->expect( [ 1, 2, 3 ] ) ->to() ->includeAll( [ 1, 2, 0 ] );

        }) ->to() ->raise( ExpectationFailureSignal::class, function($e) {

            $this->expect( $e->getMessage() ) ->to()
                ->equal( "Expected array to include all the expected values." );

        });
    });

    $this->describe( "when negating the expectation", function() {

        $this->it( "the spec passes if the array does not include all the values", function() {
            
        $this->expect( [ 1, 2, 3 ] ) ->not() ->to() ->includeAll( [ 1, 2, 0 ] );

        });

        $this->it( "the spec fails if the array includes all the values", function() {

            $this->expect( function() {

            $this->expect( [ 1, 2, 3 ] ) ->not() ->to() ->includeAll( [] );

            }) ->to() ->raise( ExpectationFailureSignal::class, function($e) {

                $this->expect( $e->getMessage() ) ->to()
                    ->equal( "Expected array not to include all the expected values." );

            });
        });

        $this->it( "the spec fails if the array includes all the values", function() {

            $this->expect( function() {

            $this->expect( [ 1, 2, 3 ] ) ->not() ->to() ->includeAll( [ 1 ] );

            }) ->to() ->raise( ExpectationFailureSignal::class, function($e) {

                $this->expect( $e->getMessage() ) ->to()
                    ->equal( "Expected array not to include all the expected values." );

            });

        });

        $this->it( "the spec fails if the array includes all the values", function() {

            $this->expect( function() {

            $this->expect( [ 1, 2, 3 ] ) ->not() ->to() ->includeAll( [ 1, 2, 3 ] );

            }) ->to() ->raise( ExpectationFailureSignal::class, function($e) {

                $this->expect( $e->getMessage() ) ->to()
                    ->equal( "Expected array not to include all the expected values." );

            });

        });

    });

});