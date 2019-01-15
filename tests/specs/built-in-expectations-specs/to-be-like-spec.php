<?php

namespace To_Be_Like_Spec;

$spec->describe( "When expecting an object to be like", function() {


    $this->describe( "an indexed array", function() {

        $this->let( "array", function() {

            return [ 1, [ 2 ], 3, [ 4 ] ];

        });

        $this->it( "the spec passes if the object is like the expected array", function() {

            $this->expect( $this->array ) ->to() ->be() ->like([ 1, [ 2 ], 3 ]);

        });

        $this->it( "the spec fails if the object is not like the expected array", function() {

            $this->expect( function() {

                $this->expect( $this->array ) ->to() ->be() ->like([ 2, [ 2 ], 3 ]);

            }) ->to() ->raise( \Haijin\Specs\Expectation_Failure_Signal::class, function($e) {

                $this->expect( $e->get_message() ) ->to() ->equal( "At 0 expected 2, got 1." );

            });

        });

        $this->it( "the spec fails if the object is not like the expected array", function() {

            $this->expect( function() {

                $this->expect( $this->array ) ->to() ->be() ->like([ 1, [ 2 ], 3, [ 4, 5 ] ]);

            }) ->to() ->raise( \Haijin\Specs\Expectation_Failure_Signal::class, function($e) {

                $this->expect( $e->get_message() ) ->to()
                    ->equal( "The object was expected to have an attribute defined at \"3.1\"." );

            });

        });

    });

    $this->describe( "an associative array", function() {

        $this->let( "array", function() {

            return [ "a" => 1, "b" => [ "b1" => 2 ], "c" => 3, "d" => [ 4 ] ];

        });

        $this->it( "the spec passes if the object is like the expected array", function() {

            $this->expect( $this->array ) ->to() ->be() ->like([
                "a" => 1, "b" => [ "b1" => 2 ], "c" => 3, "d" => [ 4 ] ]
            );

        });

        $this->it( "the spec fails if the object is not like the expected array", function() {

            $this->expect( function() {

                $this->expect( $this->array ) ->to() ->be() ->like([
                    "a" => 2, "b" => [ "b1" => 2 ], "c" => 3
                ]);

            }) ->to() ->raise( \Haijin\Specs\Expectation_Failure_Signal::class, function($e) {

                $this->expect( $e->get_message() ) ->to()
                    ->equal( "At \"a\" expected 2, got 1." );

            });

        });

        $this->it( "the spec fails if the object is not like the expected array", function() {

            $this->expect( function() {

                $this->expect( $this->array ) ->to() ->be() ->like([
                    "a" => 1, "b" => [ "b1" => 2 ], "c" => 3, "d" => [ 4, "e" => 5 ]
                ]);

            }) ->to() ->raise( \Haijin\Specs\Expectation_Failure_Signal::class, function($e) {

                $this->expect( $e->get_message() ) ->to()
                    ->equal( "The object was expected to have an attribute defined at \"d.e\"." );

            });

        });

    });

    $this->describe( "an object", function() {

        $this->let( "object", function() {

            $object_2 = new SampleClass();
            $object_2->p = 1;
            $object_2->q = 2;
            $object_2->r = 3;

            $object = new SampleClass();

            $object->p = 1;
            $object->q = $object_2;
            $object->r = 3;

            return $object;

        });

        $this->it( "the spec passes if the object is like the expected object", function() {

            $this->expect( $this->object ) ->to() ->be() ->like([
                "p" => 1, "q" => [ "p" => 1, "q" => 2, "r" => 3 ] ]
            );

            $this->expect( $this->object ) ->to() ->be() ->like([
                "get_p()" => 1, "q" => [ "get_p()" => 1, "q" => 2, "r" => 3 ] ]
            );

        });

        $this->it( "the spec fails if the object is not like the expected object", function() {

            $this->expect( function() {

                $this->expect( $this->object ) ->to() ->be() ->like([
                    "p" => 1, "q" => [ "p" => 3, "q" => 2, "r" => 3 ]
                ]);

            }) ->to() ->raise( \Haijin\Specs\Expectation_Failure_Signal::class, function($e) {

                $this->expect( $e->get_message() ) ->to()
                    ->equal( "At \"q.p\" expected 3, got 1." );

            });

        });

        $this->it( "the spec fails if the object is not like the expected array", function() {

            $this->expect( function() {

                $this->expect( $this->object ) ->to() ->be() ->like([
                    "p" => 1, "q" => [ "p" => 1, "q" => 2, "r" => 3, "s" => 4 ]
                ]);

            }) ->to() ->raise( \Haijin\Specs\Expectation_Failure_Signal::class, function($e) {

                $this->expect( $e->get_message() ) ->to()
                    ->equal( "The object was expected to have an attribute defined at \"q.s\"." );

            });

        });

        $this->it( "the spec fails if the object is not like the expected array", function() {

            $this->expect( function() {

                $this->expect( $this->object ) ->to() ->be() ->like([
                    "p" => 1, "q" => [ "p" => 1, "q" => 2, "get_r()" => 3 ]
                ]);

            }) ->to() ->raise( \Haijin\Specs\Expectation_Failure_Signal::class, function($e) {

                $this->expect( $e->get_message() ) ->to()
                    ->equal( "The object was expected to have an attribute defined at \"q.get_r()\"." );

            });

        });

    });

    $this->describe( "a scalar", function() {

        $this->let( "string", function() {

            return "123";

        });

        $this->it( "the spec passes if the object is like the expected scalar", function() {

            $this->expect( $this->string ) ->to() ->be() ->like( "123" );

        });

        $this->it( "the spec fails if the object is not like the expected string", function() {

            $this->expect( function() {

                $this->expect( $this->string ) ->to() ->be() ->like( "1234" );

            }) ->to() ->raise( \Haijin\Specs\Expectation_Failure_Signal::class, function($e) {

                $this->expect( $e->get_message() ) ->to()
                    ->equal( "At \"\" expected \"1234\", got \"123\"." );

            });

        });

    });

    $this->describe( "another custom expectation", function() {

        $this->let( "array", function() {

            return [ 1, [ 2 ], 3 ];

        });

        $this->it( "the spec passes if the object passes the custom expectation", function() {

            $this->expect( $this->array ) ->to() ->be() ->like([
                function($value) { $this->expect( $value ) ->to() ->equal( 1 ); },
                [
                    function($value) { $this->expect( $value ) ->to() ->equal( 2 ); }
                ],
                function($value) { $this->expect( $value ) ->to() ->equal( 3 ); }
            ]);

        });

        $this->it( "the spec fails if the object does not pass the custom expectation", function() {

            $this->expect( function() {

                $this->expect( $this->array ) ->to() ->be() ->like([
                    function($value) { $this->expect( $value ) ->to() ->equal( 1 ); },
                    [
                        function($value) { $this->expect( $value ) ->to() ->equal( 3 ); }
                    ],
                    function($value) { $this->expect( $value ) ->to() ->equal( 3 ); }
                ]);

            }) ->to() ->raise( \Haijin\Specs\Expectation_Failure_Signal::class, function($e) {

                $this->expect( $e->get_message() ) ->to()
                    ->equal( "Expected value to equal 3, got 2." );

            });

        });

    });

});

class SampleClass
{
    public $p;
    public $q;
    public $r;

    public function get_p()
    {
        return $this->p;
    }
}