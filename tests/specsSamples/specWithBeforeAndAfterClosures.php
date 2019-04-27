<?php
declare(strict_types=1);

$spec->describe( "Outer", function() {

    $this->beforeAll( function() {
        $this->evaluations[] = "before-all-outer";
    });

    $this->afterAll( function() {
        $this->evaluations[] = "after-all-outer";
    });

    $this->beforeEach( function() {
        $this->evaluations[] = "before-each-outer";
    });

    $this->afterEach( function() {
        $this->evaluations[] = "after-each-outer";
    });

    $this->describe( "inner", function() {

        $this->beforeAll( function() {
            $this->evaluations[] = "before-all-inner";
        });

        $this->afterAll( function() {
            $this->evaluations[] = "after-all-inner";
        });

        $this->beforeEach( function() {
            $this->evaluations[] = "before-each-inner";
        });

        $this->afterEach( function() {
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
