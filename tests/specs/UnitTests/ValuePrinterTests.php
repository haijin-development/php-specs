<?php
declare(strict_types=1);

$spec->describe('A ValuePrinter', function () {

    $this->describe('when displaying values', function () {

        $this->it('displays a null value', function (){

            $this->expect(\Haijin\Specs\Tools\ValuePrinter::printStringOf(null)) ->to() ->equal('null');

        });

        $this->it('displays a string value', function (){

            $this->expect(\Haijin\Specs\Tools\ValuePrinter::printStringOf('a')) ->to() ->equal('"a"');

        });

        $this->it('displays a true value', function (){

            $this->expect(\Haijin\Specs\Tools\ValuePrinter::printStringOf(true)) ->to() ->equal('true');

        });

        $this->it('displays a false value', function (){

            $this->expect(\Haijin\Specs\Tools\ValuePrinter::printStringOf(false)) ->to() ->equal('false');

        });

        $this->it('displays an integer value', function (){

            $this->expect(\Haijin\Specs\Tools\ValuePrinter::printStringOf(123)) ->to() ->equal('123');

        });

        $this->it('displays a double value', function (){

            $this->expect(\Haijin\Specs\Tools\ValuePrinter::printStringOf(123.01)) ->to() ->equal('123.01');

        });

    });

    $this->describe('when displaying arrays', function () {

        $this->it('displays an empty array', function (){

            $this->expect(\Haijin\Specs\Tools\ValuePrinter::printStringOf([])) ->to() ->equal('[]');

        });

        $this->it('displays an array of values', function (){

            $this->expect(\Haijin\Specs\Tools\ValuePrinter::printStringOf([1, 'a', null])) ->to()
                ->equal('[0 => 1, 1 => "a", 2 => null]');

        });

        $this->it('displays a nested array', function (){

            $this->expect(\Haijin\Specs\Tools\ValuePrinter::printStringOf([[1], ['a']])) ->to()
                ->equal('[0 => [0 => 1], 1 => [0 => "a"]]');

        });

    });

    $this->describe('when displaying objects', function () {

        $this->it('displays an object name', function (){

            $this->expect(\Haijin\Specs\Tools\ValuePrinter::printStringOf(new stdclass())) ->to()
                ->equal('a stdClass');

        });

        $this->it('displays an object name begining with a vowel', function (){

            $this->expect(\Haijin\Specs\Tools\ValuePrinter::printStringOf(new AClass())) ->to()
                ->equal('an AClass');

        });

    });

});

class AClass
{
}