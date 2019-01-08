<?php

namespace Haijin\Specs;

class Specs_Runner
{
    /// Configuring

    static protected $configuration_closure;

    static public function configure($configuration_closure)
    {
        self::$configuration_closure = $configuration_closure;
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

    /// Running

    public function run_on($folder)
    {
        $spec_files = $this->collect_spec_files_in( $folder );

        $specs = $this->collect_specs_from_files( $spec_files );

        $this->evaluate_specs( $specs );
    }

    public function run_spec_file($file)
    {
        $specs = $this->get_spec_from_file( $file );

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
        $spec_descripion = new Spec_Description( "", "", null );
        $spec_descripion->define_in_file( $spec_file );

        return $spec_descripion;
    }

    public function evaluate_specs($specs_collection)
    {
        $this->specs_evaluator->___reset();

        if( self::$configuration_closure !== null ) {
            $this->specs_evaluator->___configure( self::$configuration_closure );
        }

        $this->specs_evaluator->___run_all( $specs_collection );
    }

    /// Creating instances

    public function new_spec_evaluator()
    {
        return new Spec_Evaluator();
    }
}