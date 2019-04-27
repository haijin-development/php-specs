<?php
declare(strict_types=1);

use \Haijin\Specs\Mocks\Dummy;

$spec->describe( "When defining methods on a Dummy object", function() {

    $this->it( "evaluates the method with no parameters", function(){

        $dummy = (new Dummy())->on( 'method1', function (){
            return 123;
        });

        $this->expect($dummy->method1()) ->to() ->equal(123);
    });

    $this->it( "evaluates the method with parameters", function(){

        $dummy = (new Dummy())->on( 'method2', function ($n, $m){
            return $n + $m;
        });

        $this->expect($dummy->method2(3, 4)) ->to() ->equal(7);
    });

    $this->it( "receives the evaluations counter as the last parameter", function(){

        $dummy = (new Dummy())->on( 'method2', function ($n, $m, $evaluationsCounter){
            return $evaluationsCounter;
        });

        $this->expect($dummy->method2(3, 4)) ->to() ->equal(1);
        $this->expect($dummy->method2(3, 4)) ->to() ->equal(2);
        $this->expect($dummy->method2(3, 4)) ->to() ->equal(3);
    });

    $this->it( "raises an error if the method was not defined", function(){

        $this->expect(function () {
            $dummy = new Dummy();
            $dummy->method1();
        }) ->to() ->raise(
            RuntimeException::class,
            function($error) {
                $this->expect($error->getMessage()) ->to()
                    ->equal("The method 'method1' was not defined in dummyObject.");
            }
        );
    });

    $this->it( "raises an error if the method was not defined and the dummy object has a name", function(){

        $this->expect(function () {
            $dummy = new Dummy('object');
            $dummy->method1();
        }) ->to() ->raise(
            RuntimeException::class,
            function($error) {
                $this->expect($error->getMessage()) ->to()
                    ->equal("The method 'method1' was not defined in object.");
            }
        );
    });

});