<?php

$spec->describe( "Outer", function() {

    $this->before_all( function() {
        $this->before_all_outer = true;
    });

    $this->after_all( function() {
        $this->after_all_outer = true;
    });

    $this->before_each( function() {
        $this->before_each_outer = true;
    });

    $this->after_each( function() {
        $this->after_ech_outer = true;
    });

    $this->describe( "inner", function() {

        $this->before_all( function() {
            $this->before_all_inner = true;
        });

        $this->after_all( function() {
            $this->after_all_inner = true;
        });

        $this->before_each( function() {
            $this->before_each_inner = true;
        });

        $this->after_each( function() {
            $this->after_each_inner = true;
        });

        $this->it( "has inner and outer variable definitions", function() {

            $this->spec_only = true;

            $this->expect( $this->before_all )                  ->to() ->be() ->true();
            $this->expect( $this->before_all_outer )            ->to() ->be() ->true();
            $this->expect( $this->before_all_inner )            ->to() ->be() ->true();
            $this->expect( $this->before_each )                 ->to() ->be() ->true();
            $this->expect( $this->before_each_outer )           ->to() ->be() ->true();
            $this->expect( $this->before_each_inner )           ->to() ->be() ->true();

            $this->expect( $this->spec_only )                   ->to() ->be() ->true();

            $this->expect( isset( $this->after_all ) )          ->to() ->be() ->false();
            $this->expect( isset( $this->after_all_outer ) )    ->to() ->be() ->false();
            $this->expect( isset( $this->after_all_inner ) )    ->to() ->be() ->false();
            $this->expect( isset( $this->after_each ) )         ->to() ->be() ->false();
            $this->expect( isset( $this->after_each_outer ) )   ->to() ->be() ->false();
            $this->expect( isset( $this->after_each_inner ) )   ->to() ->be() ->false();

        });

        $this->it( "has inner and outer variable definitions", function() {

            $this->expect( $this->before_all )                  ->to() ->be() ->true();
            $this->expect( $this->before_all_outer )            ->to() ->be() ->true();
            $this->expect( $this->before_all_inner )            ->to() ->be() ->true();
            $this->expect( $this->before_each )                 ->to() ->be() ->true();
            $this->expect( $this->before_each_outer )           ->to() ->be() ->true();
            $this->expect( $this->before_each_inner )           ->to() ->be() ->true();

            $this->expect( isset( $this->spec_only ) )          ->to() ->be() ->false();

            $this->expect( isset( $this->after_all ) )          ->to() ->be() ->false();
            $this->expect( isset( $this->after_all_outer ) )    ->to() ->be() ->false();
            $this->expect( isset( $this->after_all_inner ) )    ->to() ->be() ->false();
            $this->expect( isset( $this->after_each ) )         ->to() ->be() ->false();
            $this->expect( isset( $this->after_each_outer ) )   ->to() ->be() ->false();
            $this->expect( isset( $this->after_each_inner ) )   ->to() ->be() ->false();
        });

    });

    $this->it( "has the outer variable definitions", function() {

        $this->expect( $this->before_all )                  ->to() ->be() ->true();
        $this->expect( $this->before_all_outer )            ->to() ->be() ->true();
        $this->expect( isset( $this->before_all_inner ) )   ->to() ->be() ->false();
        $this->expect( $this->before_each )                 ->to() ->be() ->true();
        $this->expect( $this->before_each_outer )           ->to() ->be() ->true();
        $this->expect( isset( $this->before_each_inner ) )  ->to() ->be() ->false();

        $this->expect( isset( $this->spec_only ) )          ->to() ->be() ->false();

        $this->expect( isset( $this->after_all ) )          ->to() ->be() ->false();
        $this->expect( isset( $this->after_all_outer ) )    ->to() ->be() ->false();
        $this->expect( isset( $this->after_all_inner ) )    ->to() ->be() ->false();
        $this->expect( isset( $this->after_each ) )         ->to() ->be() ->false();
        $this->expect( isset( $this->after_each_outer ) )   ->to() ->be() ->false();
        $this->expect( isset( $this->after_each_inner ) )   ->to() ->be() ->false();

    });

});
