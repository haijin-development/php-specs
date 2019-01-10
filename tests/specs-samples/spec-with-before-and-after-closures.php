<?php

$spec->describe( "Outer", function() {

    $this->before_all( function() {
        $this->evaluations[] = "before-all-outer";
    });

    $this->after_all( function() {
        $this->evaluations[] = "after-all-outer";
    });

    $this->before_each( function() {
        $this->evaluations[] = "before-each-outer";
    });

    $this->after_each( function() {
        $this->evaluations[] = "after-each-outer";
    });

    $this->describe( "inner", function() {

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
                "before-all",
                    "before-all-outer",
                        "before-all-inner",
                            "before-each",
                            "before-each-outer",
                            "before-each-inner"
            ]);
        });

        $this->it( "evaluates the closures", function() {
            $this->expect( $this->evaluations ) ->to() ->equal([
                "before-all",
                    "before-all-outer",
                        "before-all-inner",
                            "before-each",
                            "before-each-outer",
                            "before-each-inner",
                            "after-each-inner",
                            "after-each-outer",
                            "after-each",
                            "before-each",
                            "before-each-outer",
                            "before-each-inner"
            ]);
        });

    });

    $this->it( "evaluates the closures", function() {

        $this->expect( $this->evaluations ) ->to() ->equal([
            "before-all",
                "before-all-outer",
                    "before-all-inner",
                        "before-each",
                        "before-each-outer",
                        "before-each-inner",
                        "after-each-inner",
                        "after-each-outer",
                        "after-each",
                        "before-each",
                        "before-each-outer",
                        "before-each-inner",
                        "after-each-inner",
                        "after-each-outer",
                        "after-each",
                    "after-all-inner",
                    "before-each",
                    "before-each-outer"
        ]);

    });

});
