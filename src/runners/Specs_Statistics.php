<?php

namespace Haijin\Specs;

class Specs_Statistics
{
    protected $run_specs_count;
    protected $run_expectations_count;
    protected $invalid_expectations;

    /// Initializing

    public function __construct()
    {
        $this->run_specs_count = 0;
        $this->run_expectations_count = 0;
        $this->invalid_expectations = [];
    }

    /// Accessing

    public function inc_run_specs_count()
    {
        $this->run_specs_count += 1;
    }

    public function inc_expectations_count()
    {
        $this->run_expectations_count += 1;
    }

    public function get_invalid_expectations()
    {
        return $this->invalid_expectations;
    }

    public function add_invalid_expectation($invalid_expectation)
    {
        $this->invalid_expectations[] = $invalid_expectation;
    }

    /// Querying

    public function invalid_expectations_count()
    {
        return count( $this->invalid_expectations );
    }

    public function failed_specs_count()
    {
        $count = 0;

        foreach( $this->invalid_expectations as $invalid_spec ) {
            if( is_a( $invalid_spec, Expectation_Failure::class ) ) {
                $count += 1;
            }
        }

        return $count;
    }

    public function errored_specs_count()
    {
        $count = 0;

        foreach( $this->invalid_expectations as $invalid_spec ) {
            if( is_a( $invalid_spec, Expectation_Error::class ) ) {
                $count += 1;
            }
        }

        return $count;
    }

    public function run_specs_count()
    {
        return $this->run_specs_count;
    }

    public function run_expectations_count()
    {
        return $this->run_expectations_count;
    }
}