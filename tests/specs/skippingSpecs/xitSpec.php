<?php
declare(strict_types=1);

use Haijin\Specs\Runners\SpecsGlobalContextConfiguration;
use Haijin\Specs\Runners\SpecsRunner;

$spec->describe( "When skipping an expression with ->xit()", function() {

    $this->let('specRunner', function () {
        return new SpecsRunner();
    });

    $this->let( "specFile", function() {
        return __DIR__ .
            "/../../specsSamples/specWithSkippedSpecs.php";
    });

    $this->it( "does not evaluate the spec", function(){

        $this->specRunner->runSpecFile($this->specFile);

        $this->expect($this->specRunner->getInvalidExpectations())->to()->equal([]);

    });

});