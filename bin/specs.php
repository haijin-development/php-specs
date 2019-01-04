<?php

use Haijin\Specs\SpecsRunner;
use Haijin\Specs\ConsoleReportRenderer;

require_once "vendor/autoload.php";


$specs_runner = new SpecsRunner();
$specs_runner->run_on( \getcwd() . "/specs" );

$renderer = new ConsoleReportRenderer();
$renderer->render_report_from( $specs_runner );
