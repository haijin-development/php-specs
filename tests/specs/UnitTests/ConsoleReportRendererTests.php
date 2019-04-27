<?php
declare(strict_types=1);

use Haijin\Specs\Runners\SpecsRunner;
use Haijin\Specs\Specs\Spec;
use Haijin\Specs\Mocks\Dummy;
use Haijin\Specs\ReportRenderers\ConsoleReportRenderer;

$spec->describe('A ConsoleReportRendererTests', function () {

    $this->let('output', function () {
        $this->text = '';

        $dummy = new Dummy();
        return $dummy
            ->on('green', function () use($dummy) {
                return $dummy;
            })
            ->on('yellow', function () use($dummy) {
                return $dummy;
            })
            ->on('red', function () use($dummy) {
                return $dummy;
            })
            ->on('blue', function () use($dummy) {
                return $dummy;
            })
            ->on('lightBlue', function () use($dummy) {
                return $dummy;
            })
            ->on('yellowBackground', function () use($dummy) {
                return $dummy;
            })
            ->on('redBackground', function () use($dummy) {
                return $dummy;
            })
            ->on('render', function ($string, $cr=true) use($dummy) {
                $this->text .= $string;
                if($cr!==false) {
                    $this->text .= "\n";
                }
            });
    });

    $this->let('spec', function (){
        return new Spec('', function (){}, null, null);
    });

    $this->let( "specRunner", function() {
        return new SpecsRunner();
    });

    $this->let('renderer', function (){
        return new ConsoleReportRenderer($this->output);
    });

    $this->describe('when rendering the feedback of a spec evaluation', function () {

        $this->it('renders a passed spec', function (){

            $this->renderer->renderFeedbackOfSpecStatus($this->spec, 'passed');

            $this->expect($this->text) ->to() ->equal('.');
        });

        $this->it('renders a failed spec', function (){

            $this->renderer->renderFeedbackOfSpecStatus($this->spec, 'failed');

            $this->expect($this->text) ->to() ->equal('F');
        });

        $this->it('renders an error spec', function (){

            $this->renderer->renderFeedbackOfSpecStatus($this->spec, 'error');

            $this->expect($this->text) ->to() ->equal('E');
        });

        $this->it('renders a skipped spec', function (){

            $this->renderer->renderFeedbackOfSpecStatus($this->spec, 'skipped');

            $this->expect($this->text) ->to() ->equal('S');
        });

        $this->it('renders an unknown spec', function (){

            $this->renderer->renderFeedbackOfSpecStatus($this->spec, '-');

            $this->expect($this->text) ->to() ->equal('?');
        });

    });

    $this->describe('when rendering the report', function () {

        $this->let( "onePassingSpecFile", function() {
            return __DIR__ . "/../../specsSamples/specWithNoInvalidSpecs.php";
        });

        $this->let( "oneFailingSpecFile", function() {
            return __DIR__ . "/../../specsSamples/specWithOneInvalidSpec.php";
        });

        $this->let( "severalFailingSpecFile", function() {
            return __DIR__ . "/../../specsSamples/specWithOneFailureOneErrorAndTwoSuccessOneSkip.php";
        });

        $this->it('renders the report', function (){

            $this->specRunner->runSpecFile( $this->onePassingSpecFile );

            $this->renderer->renderReportFrom($this->specRunner);

            $expectedText = '|^' .
                '(.*)' .
                '0 failed expectations[.](.*)' .
                'Run: 1, Skipped: 0, Errors: 0, Fails: 0, Expectations: 1[.](.*)' .
                '$|ms';

            $this->expect($this->text) ->to() ->match($expectedText);

        });

        $this->it('renders the report', function (){

            $this->specRunner->runSpecFile( $this->oneFailingSpecFile );

            $this->renderer->renderReportFrom($this->specRunner);

            $expectedText = '|^' .
                '(.*)' .
                '1 failed expectation[.](.*)' .
                'Run: 1, Skipped: 0, Errors: 0, Fails: 1, Expectations: 1[.](.*)' .
                '0\) A single spec fails(.*)' .
                'Expected value to equal 2, got 1[.](.*)' .
                'at (.*)specWithOneInvalidSpec[.]php[:]8(.*)' .
                'Failed specs summary[:](.*)' .
                'composer specs (.*)specWithOneInvalidSpec.php[:]9(.*)' .
                '$|ms';

            $this->expect($this->text) ->to() ->match($expectedText);

        });

        $this->it('renders the report', function (){

            $this->specRunner->runSpecFile( $this->severalFailingSpecFile );

            $this->renderer->renderReportFrom($this->specRunner);

            $expectedText = '|^' .
                '(.*)' .
                '2 failed expectations[.](.*)' .
                'Run: 4, Skipped: 1, Errors: 1, Fails: 1, Expectations: 3[.](.*)' .
                '0\) A single spec fails(.*)' .
                'Expected value to equal 2, got 1[.](.*)' .
                'at (.*)specWithOneFailureOneErrorAndTwoSuccessOneSkip[.]php[:]14(.*)' .
                '1\) A single spec throws an Exception(.*)' .
                'Exception raised: Intended exception raised.(.*)' .
                'at (.*)specWithOneFailureOneErrorAndTwoSuccessOneSkip[.]php[:]33(.*)' .
                'Stack trace:(.*)' .
                'Failed specs summary[:](.*)' .
                'composer specs (.*)specWithOneFailureOneErrorAndTwoSuccessOneSkip.php[:]15(.*)' .
                'composer specs (.*)specWithOneFailureOneErrorAndTwoSuccessOneSkip.php[:]33(.*)' .
                '$|ms';

            $this->expect($this->text) ->to() ->match($expectedText);

        });

    });

});