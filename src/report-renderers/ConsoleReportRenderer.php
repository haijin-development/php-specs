<?php

namespace Haijin\Specs;

use Questocat\ConsoleColor\ConsoleColor as Output;

class ConsoleReportRenderer
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
        $failures_count = $this->invalid_expectations_count();

        $this->output->render( "{$failures_count} failed ", false );

        if( $failures_count == 1 ) {
            $this->output->render( "expectation.", false );
        } else {
            $this->output->render( "expectations.", false );
        }

        $this->cr();
    }

    public function render_invalid_expectation_details($invalid_expectation, $index)
    {
        $this->output->redBackground()->render(
            "{$index}) " . $invalid_expectation->get_description()
        );

        $this->cr();

        $this->output->blueBackground()->render( $invalid_expectation->get_message() );

        $this->cr();

        $this->output->lightBlueBackground()->render( $invalid_expectation->get_file_name(), false );
        $this->output->render( ":", false );
        $this->output->lightBlueBackground()->render( $invalid_expectation->get_line() );
    }

    public function invalid_expectations()
    {
        return $this->specs_statistics->get_invalid_expectations();
    }

    public function invalid_expectations_count()
    {
        return count( $this->invalid_expectations() );
    }

    protected function cr($n = 1)
    {
        for( $i = 0; $i < $n; $i++ ) { 
            $this->output->render();
        }
    }
}