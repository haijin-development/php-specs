<?php
declare(strict_types=1);

use Haijin\Specs\ValueExpectations\ValueExpectationsLibrary;
use Haijin\Specs\Errors\ExpectationDefinitionError;

$spec->describe( "When defining custom expectations", function() {

    $this->beforeAll(function (){
        $this->defineCustomExpectations();
    });

    $this->it( "evaluates the custom expectation", function(){

        $this->expect( 1 ) ->withCustom(3) ->to() ->custom( 1 );
        $this->expect( 1 ) ->withCustom(3) ->not() ->to() ->custom( 2 );

    });

    $this->it( "raises an error if the definition is missing the assertWith definition", function(){

        $this->expect( function (){
            $this->expect( 1 ) ->to() ->customWithMissingClosures( 1 );
        }) ->to() ->raise(
            ExpectationDefinitionError::class,
            function($error) {
                $this->expect($error->getMessage()) ->to() ->equal(
                    "Expectation definition 'customWithMissingClosures' is missing the 'assertWith()' closure."
                );
            }
        );

    });

    $this->it( "raises an error if the definition is missing the negateWith definition", function(){

        $this->expect( function (){
            $this->expect( 1 ) ->not() ->to() ->customWithMissingClosures( 1 );
        }) ->to() ->raise(
            ExpectationDefinitionError::class,
            function($error) {
                $this->expect($error->getMessage()) ->to() ->equal(
                    "Expectation definition 'customWithMissingClosures' is missing the 'negateWith()' closure."
                );
            }
        );

    });


    $this->def( 'defineCustomExpectations', function () {

        ValueExpectationsLibrary::defineParticle('withCustom', function ($value) {

            $this->storeParamAt('customParticleParam', $value);

        });

        ValueExpectationsLibrary::defineExpectation('custom', function () {

            $this->before(function ($expectedValue) {
                $this->expectedValue = $expectedValue;
            });

            $this->assertWith(function ($expectedValue) {

                if ($this->getStoredParamAt('customParticleParam') !== 3) {
                    $this->raiseFailure(
                        "The param 'customParticleParam' was not stored in the particle evaluation."
                    );
                }

                if ($expectedValue !== $this->expectedValue) {
                    $this->raiseFailure(
                        "The before block was not evaluated."
                    );
                }

                if ($this->getActualValue() !== $this->expectedValue) {
                    $this->raiseFailure(
                        "The expectation failed."
                    );
                }

            });

            $this->negateWith(function ($expectedValue) {

                if ($expectedValue !== $this->expectedValue) {
                    $this->raiseFailure(
                        "The before block was not evaluated."
                    );
                }

                if ($this->getActualValue() === $this->expectedValue) {
                    $this->raiseFailure(
                        "The expectation failed."
                    );
                }
            });

            $this->after(function ($expectedValue) {
                $this->expectedValue = null;
            });

        });

        ValueExpectationsLibrary::defineExpectation('customWithMissingClosures', function () {

            $this->before(function ($expectedValue) {
                $this->expectedValue = $expectedValue;
            });

            $this->after(function ($expectedValue) {
                $this->expectedValue = null;
            });

        });

    });

});