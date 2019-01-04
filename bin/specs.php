<?php

use Haijin\Specs\SpecsRunner;

require_once "vendor/autoload.php";


$specs_runner = new SpecsRunner();

$specs_runner->run_on( \getcwd() . "/specs" );

if( $specs_runner->has_invalid_expectations() ) {
    $failures_count = count( $specs_runner->get_invalid_expectations() );

    print( "\n" );
    print( "{$failures_count} failed expectations." );
    print( "\n" );

    foreach( $specs_runner->get_invalid_expectations() as $invalid_expectation ) {
        print( "\n" );
        print( "Failed: " . $invalid_expectation->get_description() );
        print( "\n" );
        print( $invalid_expectation->get_message() );
        print( "\n" );
        print( $invalid_expectation->get_file_and_line() );
        print( "\n" );
        print( "\n" );
    }
}