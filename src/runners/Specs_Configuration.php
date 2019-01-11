<?php

namespace Haijin\Specs;

class Specs_Configuration
{
    protected $spec_closures;
    protected $spec_context;

    /// Initializing

    public function __construct()
    {
        $this->___spec_closures = new Spec_Closures();
        $this->spec_context = new Spec_Context();
    }

    /// Configuring

    public function configure($configuration_closure)
    {
        $configuration_closure->call( $this, $this );
    }

    /// Accessing

    public function get_before_all_closure()
    {
        return $this->___spec_closures->before_all_closure;
    }

    public function get_after_all_closure()
    {
        return $this->___spec_closures->after_all_closure;
    }

    public function get_before_each_closure()
    {
        return $this->___spec_closures->before_each_closure;
    }

    public function get_after_each_closure()
    {
        return $this->___spec_closures->after_each_closure;
    }

    public function get_specs_context()
    {
        return $this->spec_context;
    }

    /// DSL

    public function before_all($closure)
    {
        $this->___spec_closures->before_all_closure = $closure;
    }

    public function after_all($closure)
    {
        $this->___spec_closures->after_all_closure = $closure;
    }

    public function before_each($closure)
    {
        $this->___spec_closures->before_each_closure = $closure;
    }

    public function after_each($closure)
    {
        $this->___spec_closures->after_each_closure = $closure;
    }

    public function let($expression_name, $closure)
    {
        $this->spec_context->at_named_expression_put( $expression_name, $closure );
    }

    public function def($method_name, $closure)
    {
        $this->spec_context->at_method_put( $method_name, $closure );
    }
}