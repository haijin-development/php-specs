<?php

use Haijin\Specs\SpecsRunner;

require_once "vendor/autoload.php";


$specs_runner = new SpecsRunner();

$specs_runner->run_on( \getcwd() . "/specs" );