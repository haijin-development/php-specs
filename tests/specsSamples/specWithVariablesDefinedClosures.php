<?php
declare(strict_types=1);

$spec->describe( "Outer", function() {

    $this->beforeAll( function() {
        $this->beforeAllOuter = true;
    });

    $this->afterAll( function() {
        $this->afterAllOuter = true;
    });

    $this->beforeEach( function() {
        $this->beforeEachOuter = true;
    });

    $this->afterEach( function() {
        $this->afterEchOuter = true;
    });

    $this->describe( "inner", function() {

        $this->beforeAll( function() {
            $this->beforeAllInner = true;
        });

        $this->afterAll( function() {
            $this->afterAllInner = true;
        });

        $this->beforeEach( function() {
            $this->beforeEachInner = true;
        });

        $this->afterEach( function() {
            $this->afterEachInner = true;
        });

        $this->it( "has inner and outer variable definitions", function() {

            $this->specOnly = true;

            $this->expect( $this->beforeAll )                  ->to() ->be() ->true();
            $this->expect( $this->beforeAllOuter )            ->to() ->be() ->true();
            $this->expect( $this->beforeAllInner )            ->to() ->be() ->true();
            $this->expect( $this->beforeEach )                 ->to() ->be() ->true();
            $this->expect( $this->beforeEachOuter )           ->to() ->be() ->true();
            $this->expect( $this->beforeEachInner )           ->to() ->be() ->true();

            $this->expect( $this->specOnly )                   ->to() ->be() ->true();

            $this->expect( isset( $this->afterAll ) )          ->to() ->be() ->false();
            $this->expect( isset( $this->afterAllOuter ) )    ->to() ->be() ->false();
            $this->expect( isset( $this->afterAllInner ) )    ->to() ->be() ->false();
            $this->expect( isset( $this->afterEach ) )         ->to() ->be() ->false();
            $this->expect( isset( $this->afterEachOuter ) )   ->to() ->be() ->false();
            $this->expect( isset( $this->afterEachInner ) )   ->to() ->be() ->false();

        });

        $this->it( "has inner and outer variable definitions", function() {

            $this->expect( $this->beforeAll )                  ->to() ->be() ->true();
            $this->expect( $this->beforeAllOuter )            ->to() ->be() ->true();
            $this->expect( $this->beforeAllInner )            ->to() ->be() ->true();
            $this->expect( $this->beforeEach )                 ->to() ->be() ->true();
            $this->expect( $this->beforeEachOuter )           ->to() ->be() ->true();
            $this->expect( $this->beforeEachInner )           ->to() ->be() ->true();

            $this->expect( isset( $this->specOnly ) )          ->to() ->be() ->false();

            $this->expect( isset( $this->afterAll ) )          ->to() ->be() ->false();
            $this->expect( isset( $this->afterAllOuter ) )    ->to() ->be() ->false();
            $this->expect( isset( $this->afterAllInner ) )    ->to() ->be() ->false();
            $this->expect( isset( $this->afterEach ) )         ->to() ->be() ->false();
            $this->expect( isset( $this->afterEachOuter ) )   ->to() ->be() ->false();
            $this->expect( isset( $this->afterEachInner ) )   ->to() ->be() ->false();
        });

    });

    $this->it( "has the outer variable definitions", function() {

        $this->expect( $this->beforeAll )                  ->to() ->be() ->true();
        $this->expect( $this->beforeAllOuter )            ->to() ->be() ->true();
        $this->expect( isset( $this->beforeAllInner ) )   ->to() ->be() ->false();
        $this->expect( $this->beforeEach )                 ->to() ->be() ->true();
        $this->expect( $this->beforeEachOuter )           ->to() ->be() ->true();
        $this->expect( isset( $this->beforeEachInner ) )  ->to() ->be() ->false();

        $this->expect( isset( $this->specOnly ) )          ->to() ->be() ->false();

        $this->expect( isset( $this->afterAll ) )          ->to() ->be() ->false();
        $this->expect( isset( $this->afterAllOuter ) )    ->to() ->be() ->false();
        $this->expect( isset( $this->afterAllInner ) )    ->to() ->be() ->false();
        $this->expect( isset( $this->afterEach ) )         ->to() ->be() ->false();
        $this->expect( isset( $this->afterEachOuter ) )   ->to() ->be() ->false();
        $this->expect( isset( $this->afterEachInner ) )   ->to() ->be() ->false();

    });

});
