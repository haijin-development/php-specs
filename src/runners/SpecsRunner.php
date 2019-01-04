<?php

namespace Haijin\Specs;

class SpecsRunner
{
    protected $invalid_expectations;

    /// Initializing

    public function __construct()
    {
        $this->invalid_expectations = [];
    }

    /// Accessing

    public function get_invalid_expectations()
    {
        return $this->invalid_expectations;
    }

    /// Asking

    public function has_invalid_expectations()
    {
        return ! empty( $this->invalid_expectations );
    }

    /// Running

    public function run_on($folder)
    {
        $spec_files = $this->collect_spec_files_in( $folder );

        $this->run_spec_files( $spec_files );
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

    public function run_spec_files($spec_files)
    {
        foreach( $spec_files as $each_spec_file ) {
            $this->run_spec_file( $each_spec_file );
        }
    }

    public function run_spec_file($spec_file)
    {
        try {

            $spec = new Spec();
            $spec->define_in_file( $spec_file );

        } catch( ExpectationFailureSignal $signal ) {

            $this->invalid_expectations[] = new ExpectationFailure(
                $signal->get_description(),
                $signal->get_message(),
                $signal->get_trace()
            );

        }
    }
}