<?php

use Haijin\Specs\SpecsRunner;
use Haijin\Specs\ConsoleReportRenderer;

require_once "vendor/autoload.php";


$renderer = new ConsoleReportRenderer();

$specs_runner = new SpecsRunner();

$specs_runner->after_each_spec_do( function($spec, $status) use($renderer) {
    $renderer->render_feedback_of_spec_status( $spec, $status );
});



$specs_runner->run_on( \getcwd() . "/specs" );

$renderer->render_report_from( $specs_runner );
