<?php
declare(strict_types=1);

use Haijin\Specs\Errors\ExpectationFailureSignal;
use Haijin\Specs\ValueExpectations\ValueExpectationsLibrary;

$spec->describe( "When evaluating a ->during(\$closure) particle", function() {

    $this->it( "the spec fails if the file does not exist", function() {

        $this->expect( 1 ) ->during( function() { return 2; }) ->to() ->doNothing();

    });
});

ValueExpectationsLibrary::defineExpectation('doNothing', function () {

    $this->assertWith(function () {

        $closureValue = $this->getStoredParamAt('duringClosure')();

        if ($closureValue !== 2) {
            $this->raiseFailure(
                "The ->during() particle was not evaluated correctly."
            );
        }

    });
});
