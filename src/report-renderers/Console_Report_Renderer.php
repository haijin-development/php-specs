<?php

namespace Haijin\Specs;

use Questocat\ConsoleColor\ConsoleColor as Output;

class Console_Report_Renderer
{
    protected $output;
    protected $specs_statistics;

    public function __construct()
    {
        $this->output = new Output();
        $this->specs_statistics = null;
    }

    public function render_feedback_of_spec_status($spec, $spec_status)
    {
        switch( $spec_status ) {
            case 'passed':
                $this->output->green()->render( ".", false );
                break;
            case 'failed':
                $this->output->yellow()->render( "F", false );
                break;
            case 'error':
                $this->output->red()->render( "E", false );
                break;
            default:
                $this->output->render( ".", false );
        }
    }

    public function render_report_from($specs_runner)
    {
        $this->specs_statistics = $specs_runner->get_statistics();

        $this->cr();

        $this->render_report_header();

        foreach( $this->specs_statistics->get_invalid_expectations() as $i => $invalid_expectation ) {
            $this->cr();

            $this->render_invalid_expectation_details( $invalid_expectation, $i );

            $this->cr(2);
        }
    }

    public function render_report_header()
    {
        $failures_count = $this->specs_statistics->invalid_expectations_count();
        $failed_specs_count = $this->specs_statistics->failed_specs_count();
        $errored_specs_count = $this->specs_statistics->errored_specs_count();
        $run_specs_count = $this->specs_statistics->run_specs_count();
        $run_expectations_count = $this->specs_statistics->run_specs_count();

        $this->cr();

        $this->output->render( "{$failures_count} failed ", false );

        if( $failures_count == 1 ) {
            $this->output->render( "expectation.", false );
        } else {
            $this->output->render( "expectations.", false );
        }

        $this->cr();

        $this->output->render( "Run: {$run_specs_count}, Errors: {$errored_specs_count}, Fails: {$failed_specs_count}, Expectations: {$run_expectations_count}.", false );

        $this->cr();
    }

    public function render_invalid_expectation_details($invalid_expectation, $index)
    {

        if( is_a( $invalid_expectation, Expectation_Failure::class ) ) {
            $this->render_failed_expectation_details( $invalid_expectation, $index );
        }

        if( is_a( $invalid_expectation, Expectation_Error::class ) ) {
            $this->render_errored_expectation_details( $invalid_expectation, $index );
        }
    }

    public function render_failed_expectation_details($expectation_failure, $index)
    {
        $this->output->yellowBackground()->render(
            "{$index}) " . $expectation_failure->get_description()
        );

        $this->cr();

        $this->output->yellow()->render( $expectation_failure->get_message() );

        $this->cr();

        $this->output->render( "at ", false );
        $this->output->lightBlue()->render( $expectation_failure->get_file_name(), false );
        $this->output->render( ":", false );
        $this->output->lightBlue()->render( $expectation_failure->get_line() );
    }

    public function render_errored_expectation_details($expectation_error, $index)
    {
        $this->output->redBackground()->render(
            "{$index}) " . $expectation_error->get_description()
        );

        $this->cr();

        $this->output->red()->render( "Exception raised: ", false );
        $this->output->red()->render( $expectation_error->get_message(), false );

        $this->cr();
        $this->cr();

        $this->output->render( "Stack trace:" );

        $this->cr();

        foreach( $expectation_error->get_stack_trace() as $stack_frame ) {

            $this->output->render( $stack_frame[ "class" ] , false );
            $this->output->render( "::", false );
            $this->output->render( $stack_frame[ "function" ] , false );

            if( array_key_exists( "file", $stack_frame ) ) {
                $this->output->lightBlue()->render( " at ", false );

                $this->output->lightBlue()->render( $stack_frame[ "file" ], false );
                $this->output->render( ":" , false );
                $this->output->lightBlue()->render( $stack_frame[ "line" ] , false );
            }

            $this->cr();
         }
    }

    public function invalid_expectations()
    {
        return $this->specs_statistics->get_invalid_expectations();
    }

    protected function cr($n = 1)
    {
        for( $i = 0; $i < $n; $i++ ) { 
            $this->output->render();
        }
    }
}