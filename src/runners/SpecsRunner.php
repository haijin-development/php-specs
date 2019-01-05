<?php

namespace Haijin\Specs;

class SpecsRunner
{
    protected $statistics;

    /// Initializing

    public function __construct()
    {
        $this->statistics = new SpecsStatistics();
    }

    /// Accessing

    public function get_statistics()
    {
        return $this->statistics;
    }

    public function get_invalid_expectations()
    {
        return $this->statistics->get_invalid_expectations();
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
        $spec_descripion = new SpecDescription( "", "" );
        $spec_descripion->define_in_file( $spec_file );

        return $spec_descripion;
    }

    public function evaluate_specs($specs_collection)
    {
        foreach( $specs_collection as $spec ) {
            $this->evaluate_spec( $spec );
        }
    }

    public function evaluate_spec($spec)
    {
        $spec->evaluate( $this->statistics );
    }
}