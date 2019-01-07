<?php

namespace BeforeAndAfterClosures;

$spec->describe( "When evaluating before and after closures", function() {

    $this->before_all( function() {
        $this->evaluations = [];
        $this->evaluations[] = "before-all-outer";
    });

    $this->before_each( function() {
        $this->evaluations[] = "before-each-outer";
    });

    $this->after_each( function() {
        $this->evaluations[] = "after-each-outer";
    });

    $this->describe( "outer", function() {

        $this->before_all( function() {
            $this->evaluations[] = "before-all-inner";
        });

        $this->after_all( function() {
            $this->evaluations[] = "after-all-inner";
        });

        $this->before_each( function() {
            $this->evaluations[] = "before-each-inner";
        });

        $this->after_each( function() {
            $this->evaluations[] = "after-each-inner";
        });

        $this->it( "evaluates the closures", function() {
            $this->expect( $this->evaluations ) ->to() ->equal([
                "before-all-outer",
                    "before-all-inner",
                        "before-each-outer",
                        "before-each-inner"
            ]);
        });

        $this->it( "evaluates the closures", function() {
            $this->expect( $this->evaluations ) ->to() ->equal([
                "before-all-outer",
                    "before-all-inner",
                        "before-each-outer",
                        "before-each-inner",
                        "after-each-inner",
                        "after-each-outer",
                        "before-each-outer",
                        "before-each-inner"
            ]);
        });

    });

    $this->it( "evaluates the closures", function() {

        $this->expect( $this->evaluations ) ->to() ->equal([
                "before-all-outer",
                    "before-all-inner",
                        "before-each-outer",
                        "before-each-inner",
                        "after-each-inner",
                        "after-each-outer",
                        "before-each-outer",
                        "before-each-inner",
                        "after-each-inner",
                        "after-each-outer",
                    "after-all-inner",
                "before-each-outer"
        ]);

    });

});