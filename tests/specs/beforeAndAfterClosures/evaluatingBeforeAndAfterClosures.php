<?php
declare(strict_types=1);

use Haijin\Specs\Runners\SpecsGlobalContextConfiguration;
use Haijin\Specs\Runners\SpecsRunner;

$spec->describe('When evaluating before and after closures', function () {

    $this->let('initialSpecsContextDefinitions', function () {
        return SpecsGlobalContextConfiguration::configure(function ($specs) {

            $specs->beforeAll(function () {
                $this->evaluations = [];
                $this->evaluations[] = 'before-all';
            });

            $specs->afterAll(function () {
                $this->evaluations[] = 'after-all';
            });

            $specs->beforeEach(function () {
                $this->evaluations[] = 'before-each';
            });

            $specs->afterEach(function () {
                $this->evaluations[] = 'after-each';
            });

        });
    });

    $this->let('specRunner', function () {
        return new SpecsRunner();
    });

    $this->let('specFile', function () {
        return __DIR__ .
            '/../../specsSamples/specWithBeforeAndAfterClosures.php';
    });

    $this->it('evaluates the onSpecRunDo after each spec run', function () {

        $this->specRunner->runSpecFile($this->specFile, null, $this->initialSpecsContextDefinitions);

        $this->expect($this->specRunner->getInvalidExpectations())->to()->equal([]);

        $evaluations = $this->specRunner->___getSpecsEvaluator()->evaluations;

        $this->expect($evaluations)->to()->equal([
            'before-all',
            'before-all-outer',
            'before-all-inner',
            'before-each',
            'before-each-outer',
            'before-each-inner',
            'after-each-inner',
            'after-each-outer',
            'after-each',
            'before-each',
            'before-each-outer',
            'before-each-inner',
            'after-each-inner',
            'after-each-outer',
            'after-each',
            'after-all-inner',
            'before-each',
            'before-each-outer',
            'after-each-outer',
            'after-each',
            'after-all-outer',
            'after-all'
        ]);

    });

});