<?php

namespace Haijin\Specs;

class SpecsRunner
{
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
        $spec = new Spec();

        $spec->define_in_file( $spec_file );
    }
}