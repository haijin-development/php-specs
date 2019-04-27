<?php
declare(strict_types=1);

use Haijin\Specs\Tools\AttributeReader;

$spec->describe('An AttributeReader', function () {

    $this->describe('when reading values from arrays', function () {

        $this->it('reads a value', function (){

            $array = [ 'a', 'b', 'c'];

            $attributeReader = new AttributeReader($array, 0);

            $this->expect($attributeReader->read()) ->to() ->equal('a');

        });

        $this->it('answers whether it has an attribute defined or not', function (){

            $array = [ 'a', 'b', 'c'];

            $attributeReader = new AttributeReader($array, 0);

            $this->expect($attributeReader->hasAttributeDefined()) ->to() ->be() ->true();

            $attributeReader = new AttributeReader($array, 3);

            $this->expect($attributeReader->hasAttributeDefined()) ->to() ->be() ->false();

        });

        $this->it('raises an error if the attribute is not defined', function (){

            $this->expect( function () {

                $array = [ 'a', 'b', 'c'];

                $attributeReader = new AttributeReader($array, 3);

                $attributeReader->read();

            }) ->to() ->raise(
                RuntimeException::class,
                function ($error) {
                    $this->expect($error->getMessage()) ->to() ->equal("Missing attribute 3.");
                }
            );

        });

    });

    $this->describe('when reading values from objects', function () {

        $this->it('reads a value', function (){

            $object = (object)['a' => 1, 'b' => 2, 'c' => 3];

            $attributeReader = new AttributeReader($object, 'a');

            $this->expect($attributeReader->read()) ->to() ->equal(1);

        });

        $this->it('answers whether it has an attribute defined or not', function (){

            $object = (object)['a' => 1, 'b' => 2, 'c' => 3];

            $attributeReader = new AttributeReader($object, 'a');

            $this->expect($attributeReader->hasAttributeDefined()) ->to() ->be() ->true();

            $attributeReader = new AttributeReader($object, 'd');

            $this->expect($attributeReader->hasAttributeDefined()) ->to() ->be() ->false();

        });

        $this->it('raises an error if the attribute is not defined', function (){

            $this->expect( function () {

                $object = (object)['a' => 1, 'b' => 2, 'c' => 3];

                $attributeReader = new AttributeReader($object, 'd');

                $attributeReader->read();

            }) ->to() ->raise(
                RuntimeException::class,
                function ($error) {
                    $this->expect($error->getMessage()) ->to() ->equal("Missing attribute d.");
                }
            );

        });

    });

    $this->describe('when reading values from a scalar value', function () {

        $this->it('answers whether it has an attribute defined or not', function (){

            $attributeReader = new AttributeReader(1, 'a');

            $this->expect($attributeReader->hasAttributeDefined()) ->to() ->be() ->false();
        });

        $this->it('raises an error when reading the attribute', function (){

            $this->expect( function () {

                $attributeReader = new AttributeReader(1, 'a');

                $attributeReader->read();

            }) ->to() ->raise(
                RuntimeException::class,
                function ($error) {
                    $this->expect($error->getMessage()) ->to() ->equal("Missing attribute a.");
                }
            );

        });

    });

    $this->describe('when reading values with an unknown format', function () {

        $this->it('raises an error when reading the attribute', function (){

            $this->expect( function () {

                $object = (object)['a' => 1, 'b' => 2, 'c' => 3];

                $attributeReader = new AttributeReader($object, '[a]');

                $attributeReader->read();

            }) ->to() ->raise(
                RuntimeException::class,
                function ($error) {
                    $this->expect($error->getMessage()) ->to() ->equal("Missing attribute [a].");
                }
            );

        });

    });

});