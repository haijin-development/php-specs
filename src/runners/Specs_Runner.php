<?php

namespace Haijin\Specs;

class Specs_Runner
{
    /// Configuring

    static protected $specs_config;

    static public function configure($configuration_closure)
    {
        self::$specs_config = new Specs_Configuration();
        self::$specs_config->configure( $configuration_closure );

        return self::$specs_config;
    }

    /// Instance methods

    protected $specs_evaluator;

    /// Initializing

    public function __construct()
    {
        $this->specs_evaluator = $this->new_spec_evaluator();
    }

    /// Accessing

    public function get_statistics()
    {
        return $this->specs_evaluator->___get_statistics();
    }

    public function get_invalid_expectations()
    {
        return $this->specs_evaluator->___get_invalid_expectations();
    }

    public function get_last_expectation_status()
    {
        return $this->specs_evaluator->get_last_expectation_status();
    }

    public function on_spec_run_do($closure)
    {
        $this->specs_evaluator->___on_spec_run_do( $closure );
    }

    public function get_specs_config()
    {
        if( self::$specs_config === null ) {

            return new Specs_Configuration();

        }

        return self::$specs_config;
    }

    public function get_initial_context()
    {
        return $this->get_specs_config()->get_specs_context();
    }

    /**
     *  Private - For testing purposes only.
     */
    public function ___get_specs_evaluator()
    {
        return $this->specs_evaluator;
    }

    /// Running

    public function run_on($folder_or_file)
    {
        if( is_file( explode( ":", $folder_or_file )[0] ) ) {
            $parts = explode( ":", $folder_or_file );

            $filename = $parts[ 0 ];
            $line_number = null;
            if( count( $parts ) == 2 ) {
                $line_number = $parts[ 1 ];
            }
            return $this->run_spec_file( $filename, $line_number );
        }

        $spec_files = $this->collect_spec_files_in( $folder_or_file );

        $specs = $this->collect_specs_from_files( $spec_files );

        $this->evaluate_specs( $specs );
    }

    public function run_spec_file($file, $line_number = null)
    {
        $specs = $this->get_spec_from_file( $file );

        if( $line_number !== null )  {
            $specs->restrict_to_line_number( $line_number );
        }

        $this->evaluate_specs( [ $specs ] );
    }


    public function collect_spec_files_in($folder)
    {
        $files = [];

        $folder_contents = array_diff( scandir( $folder ), [ ".", ".." ] );

        foreach( $folder_contents as $each_file) {
            $spec_full_path = $folder . "/" . $each_file;

            if( is_dir( $spec_full_path ) ) {
                $files = array_merge( $files, $this->collect_spec_files_in( $spec_full_path ) );
            } else {
                $files[] = $spec_full_path;
            }
        }

        return $files;
    }

    public function collect_specs_from_files($spec_files)
    {
        $specs = [];

        foreach( $spec_files as $file) {
            $specs[] = $this->get_spec_from_file( $file );
        }

        return $specs;
    }

    public function get_spec_from_file($spec_file)
    {
        $spec_descripion = new Spec_Description( "", "", $this->get_initial_context() );

        $spec_descripion->define_in_file( $spec_file );

        return $spec_descripion;
    }

    public function evaluate_specs($specs_collection)
    {
        $this->specs_evaluator->___reset();

        $this->specs_evaluator->___configure( $this->get_specs_config() );

        $this->specs_evaluator->___run_all( $specs_collection );
    }

    /// Creating instances

    public function new_spec_evaluator()
    {
        return new Spec_Evaluator();
    }
}