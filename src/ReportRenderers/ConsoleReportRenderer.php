<?php
declare(strict_types=1);

namespace Haijin\Specs\ReportRenderers;

use Haijin\Specs\Evaluator\SpecsStatistics;
use Haijin\Specs\Failures\ExpectationError;
use Haijin\Specs\Failures\ExpectationFailure;
use Haijin\Specs\Failures\InvalidExpectation;
use Haijin\Specs\Runners\SpecsRunner;
use Haijin\Specs\Specs\Spec;
use Questocat\ConsoleColor\ConsoleColor as Output;

/**
 * Renders the report of a specs run to a command line console output.
 *
 * @package Haijin\Specs\ReportRenderers
 */
class ConsoleReportRenderer
{
    /**
     * @var Output The output stream to dump the report contents to.
     */
    protected $output;

    /**
     * ConsoleReportRenderer constructor.
     */
    public function __construct($output = null)
    {
        if ($output === null) {
            $output = new Output();
        }
        $this->output = $output;
    }

    /**
     * Renders a feedback of the status of a run spec to a command line console output.
     * The feedback is a "." for a succeeded spec, an "F" for a failed spec, an "E" for a spec that
     * raised an unexpected error during its execution and a "S" for a skipped spec.
     *
     * @param Spec $spec The run Spec.
     * @param string $specStatus The run Spec status. Can be any of ['passed', 'failed', 'error', 'skipped'].
     */
    public function renderFeedbackOfSpecStatus(Spec $spec, string $specStatus): void
    {
        switch ($specStatus) {
            case 'passed':
                $this->output->green()->render(".", false);
                break;
            case 'failed':
                $this->output->yellow()->render("F", false);
                break;
            case 'error':
                $this->output->red()->render("E", false);
                break;
            case 'skipped':
                $this->output->blue()->render("S", false);
                break;
            default:
                $this->output->render("?", false);
        }
    }

    /**
     * Renders the report of the specs run by the SpecsRunner.
     *
     * @param SpecsRunner $specsRunner The SpecsRunner to report to the command line console.
     */
    public function renderReportFrom(SpecsRunner $specsRunner): void
    {
        $specsStatistics = $specsRunner->getStatistics();

        $this->cr();

        $this->renderReportHeader($specsStatistics);

        $this->renderAllInvalidExpectationsDetails($specsStatistics);

        $this->renderInvalidExpectationsSummary($specsStatistics);
    }

    /**
     * Renders one or more carriage returns to the command line output.
     *
     * @param int $n The number of carriage returns to render.
     */
    protected function cr($n = 1): void
    {
        for ($i = 0; $i < $n; $i++) {
            $this->output->render();
        }
    }

    /**
     * Renders the report header with a summary of the specs run, failed, with errors, skipped and the total of the
     * expectations run during the specs execution.
     *
     * @param SpecsStatistics $specsStatistics The specs statistics collected during the execution of the specs.
     */
    public function renderReportHeader(SpecsStatistics $specsStatistics): void
    {
        $failuresCount = $specsStatistics->invalidExpectationsCount();
        $failedSpecsCount = $specsStatistics->failedSpecsCount();
        $erroredSpecsCount = $specsStatistics->erroredSpecsCount();
        $runSpecsCount = $specsStatistics->runSpecsCount();
        $runExpectationsCount = $specsStatistics->runExpectationsCount();
        $skippedSpecsCount = $specsStatistics->skippedSpecsCount();

        $this->cr();

        $this->output->render("{$failuresCount} failed ", false);

        if ($failuresCount == 1) {
            $this->output->render("expectation.", false);
        } else {
            $this->output->render("expectations.", false);
        }

        $this->cr();

        $this->output->render(
            "Run: {$runSpecsCount}, Skipped: {$skippedSpecsCount}, Errors: {$erroredSpecsCount}, Fails: {$failedSpecsCount}, Expectations: {$runExpectationsCount}.",
            false
        );

        $this->cr();
    }

    /**
     * Renders the details of each invalid expectation found during the specs run.
     * The details include the invalid spec name, the filename of the spec definition, the line number
     * of the failed spec and in the case of unexepected errors the stack trace of the error.
     *
     * @param SpecsStatistics $specsStatistics The specs statistics collected during the execution of the specs.
     */
    public function renderAllInvalidExpectationsDetails(SpecsStatistics $specsStatistics): void
    {
        foreach ($specsStatistics->getInvalidExpectations() as $i => $invalidExpectation) {
            $this->cr();

            $this->renderInvalidExpectationDetails($invalidExpectation, $i);

            $this->cr(2);
        }
    }

    /**
     * Renders the details of the index-th invalid InvalidExpectation.
     *
     * @param InvalidExpectation $invalidExpectation The InvalidExpectation to render.
     * @param int $index The index of the invalid InvalidExpectation.
     */
    public function renderInvalidExpectationDetails(InvalidExpectation $invalidExpectation, $index): void
    {

        if ($invalidExpectation->isFailure()) {
            $this->renderFailedExpectationDetails($invalidExpectation, $index);
        }

        if ($invalidExpectation->isError()) {
            $this->renderErroredExpectationDetails($invalidExpectation, $index);
        }
    }

    /**
     * Renders the details of the index-th ExpectationFailure.
     *
     * @param ExpectationFailure $expectationFailure The ExpectationFailure to render.
     * @param int $index The index of the InvalidExpectation.
     */
    public function renderFailedExpectationDetails(ExpectationFailure $expectationFailure, $index): void
    {
        $this->output->yellowBackground()->render(
            "{$index}) " . $expectationFailure->getDescription()
        );

        $this->cr();

        $this->output->yellow()->render($expectationFailure->getMessage());

        $this->cr();

        $this->output->render("at ", false);
        $this->output->lightBlue()->render($expectationFailure->getSpecFileName(), false);
        $this->output->render(":", false);
        $this->output->lightBlue()->render($expectationFailure->getExpectationLine());
    }

    /**
     * Renders the details of the index-th ExpectationError.
     *
     * @param ExpectationError $expectationError The ExpectationError to render.
     * @param int $index The index of the ExpectationError.
     */
    public function renderErroredExpectationDetails(ExpectationError $expectationError, $index): void
    {
        $this->output->redBackground()->render(
            "{$index}) " . $expectationError->getDescription()
        );

        $this->cr();

        $this->output->red()->render("Exception raised: ", false);
        $this->output->red()->render($expectationError->getMessage());

        $this->cr();

        $this->output->render("at ", false);
        $this->output->lightBlue()->render($expectationError->getSpecFileName(), false);
        $this->output->render(":", false);
        $this->output->lightBlue()->render($expectationError->getSpecLineNumber() - 1);

        $this->cr();
        $this->cr();

        $this->output->render("Stack trace:");

        $this->cr();

        foreach ($expectationError->getStackTrace() as $stackFrame) {

            if (isset($stackFrame["class"])) {
                $this->output->render($stackFrame["class"], false);
                $this->output->render("::", false);

            }

            if (isset($stackFrame["function"])) {
                $this->output->render($stackFrame["function"], false);
            }

            if (isset($stackFrame["file"])) {
                $this->output->lightBlue()->render(" at ", false);

                $this->output->lightBlue()->render($stackFrame["file"], false);
                $this->output->render(":", false);
                $this->output->lightBlue()->render($stackFrame["line"], false);
            }

            $this->cr();
        }
    }

    /**
     * Renders a summary of the invalid specs run.
     * The summary includes the command line to run the failed spec alone.
     *
     * @param SpecsStatistics $specsStatistics The specs statistics collected during the execution of the specs.
     */
    public function renderInvalidExpectationsSummary(SpecsStatistics $specsStatistics): void
    {
        if ($specsStatistics->invalidExpectationsCount() == 0) {
            return;
        }

        $this->output->render("Failed specs summary:");

        foreach ($specsStatistics->getInvalidExpectations() as $i => $invalidExpectation) {

            if (is_a($invalidExpectation, ExpectationFailure::class)) {
                $this->output->yellow()->render("composer specs ", false);
                $this->output->yellow()->render($invalidExpectation->getSpecFileName(), false);
                $this->output->yellow()->render(":", false);
                $this->output->yellow()->render($invalidExpectation->getSpecLineNumber() - 1);
            }

            if (is_a($invalidExpectation, ExpectationError::class)) {
                $this->output->red()->render("composer specs ", false);
                $this->output->red()->render($invalidExpectation->getSpecFileName(), false);
                $this->output->red()->render(":", false);
                $this->output->red()->render($invalidExpectation->getSpecLineNumber() - 1);
            }

        }
    }
}