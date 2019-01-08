<?php

namespace Haijin\Specs;

use Haijin\Specs\Specs_Runner;
use Haijin\Specs\Console_Report_Renderer;

class Specs_CLI
{
    protected $specs_runner;
    protected $reporter;

    /// Initializing

    public function __construct()
    {
        $this->renderer = null;
        $this->specs_runner = null;

        $this->specs_folder = $this->default_specs_folder();
    }

    /// Config

    public function default_specs_folder()
    {
        return \getcwd() . "/tests/specs";
    }

    /// Running specs

    public function initialize_runner()
    {
        $this->renderer = new Console_Report_Renderer();
        $this->specs_runner = new Specs_Runner();

        $renderer = $this->renderer;

        $this->specs_runner->on_spec_run_do( function($spec, $status) use($renderer) {

            $renderer->render_feedback_of_spec_status( $spec, $status );

        });
    }

    public function run_specs()
    {
        $this->initialize_runner();

        $this->specs_runner->run_on( $this->specs_folder );

        $this->renderer->render_report_from( $this->specs_runner );

        if( $this->specs_runner->get_statistics()->invalid_expectations_count() == 0 ) {
            exit( 0 );
        }

        exit( 1 );
    }
}