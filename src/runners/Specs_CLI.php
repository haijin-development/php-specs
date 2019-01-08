<?php

namespace Haijin\Specs;

use Haijin\Specs\Specs_Runner;
use Haijin\Specs\Console_Report_Renderer;

class Specs_CLI
{
    protected $argv;
    protected $specs_runner;
    protected $reporter;

    protected $tests_folder;
    protected $specs_folder;

    /// Initializing

    public function __construct()
    {
        $this->argv = null;
        $this->renderer = null;
        $this->specs_runner = null;

        $this->tests_folder = $this->default_tests_folder();
        $this->specs_folder = $this->default_specs_folder();
    }

    /// Config

    public function default_tests_folder()
    {
        return \getcwd() . "/tests/";
    }

    public function default_specs_folder()
    {
        return $this->tests_folder . "specs/";
    }

    public function specs_boot_file()
    {
        return $this->tests_folder . "specs_boot.php";
    }

    /// Asking

    public function is_init_command()
    {
        return isset( $this->argv[ 1 ] ) && $this->argv[ 1 ] == "init";
    }

    /// Command line interface

    public function evaluate($argv)
    {
        $this->argv = $argv;

        if( $this->is_init_command() ) {

            $this->run_specs_init_command();

        } else {

            $this->run_specs();

        }

        exit( 0 );
    }

    /// Running init command

    public function run_specs_init_command()
    {
        if( ! $this->exists_specs_folder() ) {

            $this->create_specs_folder();

            echo "Created 'tests/specs/' folder in {$this->specs_folder}.\n";

        } else {

            echo "'tests/specs/' folder not created because it already exists in {$this->specs_folder}.\n";

        }

        if( ! $this->exists_specs_boot_file() ) {

            $this->create_specs_boot_file();

            echo "Created 'specs_boot.php' in '{$this->specs_boot_file()}'.\n";

        } else {

            echo "'specs_boot.php' not created because it already exists in '{$this->specs_boot_file()}'.\n";

        }
    }

    public function exists_specs_boot_file()
    {
        return file_exists( $this->specs_boot_file() );
    }

    public function create_specs_boot_file()
    {
        copy(
            __DIR__ . "/../../init-command-templates/specs_boot.php",
            $this->specs_boot_file()
        );
    }

    public function exists_specs_folder()
    {
        return file_exists( $this->specs_folder );
    }

    public function create_specs_folder()
    {
        if( ! file_exists( $this->tests_folder ) ) {
            mkdir( $this->tests_folder );
        }

        if( ! file_exists( $this->specs_folder ) ) {
            mkdir( $this->specs_folder );
        }


        if( ! file_exists( $this->specs_folder . "spec_example.php" ) ) {
            copy(
                __DIR__ . "/../../init-command-templates/spec_example.php",
                $this->specs_folder . "spec_example.php"
            );
        }
    }

    /// Running specs command

    public function run_specs()
    {
        $this->initialize_runner();

        $this->load_specs_boot_file();

        $this->specs_runner->run_on( $this->specs_folder );

        $this->renderer->render_report_from( $this->specs_runner );

        if( $this->specs_runner->get_statistics()->invalid_expectations_count() != 0 ) {
            exit( 1 );
        }
    }

    public function initialize_runner()
    {
        $this->renderer = new Console_Report_Renderer();
        $this->specs_runner = new Specs_Runner();

        $renderer = $this->renderer;

        $this->specs_runner->on_spec_run_do( function($spec, $status) use($renderer) {

            $renderer->render_feedback_of_spec_status( $spec, $status );

        });
    }

    public function load_specs_boot_file()
    {
        if( $this->exists_specs_boot_file() ) {
            require_once( $this->specs_boot_file() );
        }
    }
}