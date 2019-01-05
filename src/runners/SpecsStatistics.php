<?php

namespace Haijin\Specs;

class SpecsStatistics
{
    protected $specs_count;
    protected $expectations_count;
    protected $invalid_expectations;

    /// Initializing

    public function __construct()
    {
        $this->specs_count = 0;
        $this->expectations_count = 0;
        $this->invalid_expectations = [];
    }

    /// Accessing

    public function get_specs_count()
    {
        return $this->specs_count;
    }

    public function inc_specs_count()
    {
        $this->specs_count += 1;
    }

    public function get_expectations_count()
    {
        return $this->expectations_count;
    }

    public function inc_expectations_count()
    {
        $this->expectations_count += 1;
    }

    public function get_invalid_expectations()
    {
        return $this->invalid_expectations;
    }

    public function add_invalid_expectation($invalid_expectation)
    {
        $this->invalid_expectations[] = $invalid_expectation;
    }
}