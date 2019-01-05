<?php

namespace Haijin\Specs;

class SpecsRunner
{
    protected $specs_evaluator;

    /// Initializing

    public function __construct()
    {
        $this->specs_evaluator = $this->new_spec_evaluator();
    }

    /// Accessing

    public function get_statistics()
    {
        return $this->specs_evaluator->get_statistics();
    }

    public function get_invalid_expectations()
    {
        return $this->specs_evaluator->get_invalid_expectations();
    }

    public function get_last_expectation_status()
    {
        return $this->specs_evaluator->get_last_expectation_status();
    }

    public function after_each_spec_do($closure)
    {
        $this->specs_evaluator->after_each_spec_do( $closure );
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
        $spec_descripion = new SpecDescription( "", "", null );
        $spec_descripion->define_in_file( $spec_file );

        return $spec_descripion;
    }

    public function evaluate_specs($specs_collection)
    {
        $this->specs_evaluator->reset();

        foreach( $specs_collection as $spec ) {
            $this->evaluate_spec( $spec );
        }
    }

    public function evaluate_spec($spec)
    {
        $this->specs_evaluator->evaluate( $spec );
    }

    /// Creating instances

    public function new_spec_evaluator()
    {
        return new SpecEvaluator();
    }
}