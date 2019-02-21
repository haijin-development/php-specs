<?php

namespace Haijin\Specs;

class Spec_Description extends Spec_Base
{
    protected $spec_closures;
    protected $nested_specs;

    public function __construct($description, $nested_description, $context)
    {
        parent::__construct( $description, $nested_description, $context );

        $this->spec_closures = new Spec_Closures();
        $this->nested_specs = [];
    }

    /// Accessing

    public function get_nested_specs()
    {
        return $this->nested_specs;
    }

    public function get_before_all_closure()
    {
        return $this->spec_closures->before_all_closure;
    }

    public function get_after_all_closure()
    {
        return $this->spec_closures->after_all_closure;
    }

    public function get_before_each_closure()
    {
        return $this->spec_closures->before_each_closure;
    }

    public function get_after_each_closure()
    {
        return $this->spec_closures->after_each_closure;
    }

    public function restrict_to_line_number($line_number)
    {
        foreach( $this->nested_specs as $nested_spec) {

            if( $nested_spec->is_in_line_number( $line_number ) ) {

                $this->nested_specs = [ $nested_spec ];

                $nested_spec->restrict_to_line_number( $line_number );

                return;

            }

        }
    }

    public function is_in_line_number($line_number)
    {
        foreach( $this->nested_specs as $nested_spec) {

            if( $nested_spec->is_in_line_number( $line_number ) ) {
                return true;
            }
        }

        return false;
    }

    /// DSL

    public function before_all($closure)
    {
        $this->spec_closures->before_all_closure = $closure;
    }

    public function after_all($closure)
    {
        $this->spec_closures->after_all_closure = $closure;
    }

    public function before_each($closure)
    {
        $this->spec_closures->before_each_closure = $closure;
    }

    public function after_each($closure)
    {
        $this->spec_closures->after_each_closure = $closure;
    }

    public function describe($description_text, $closure)
    {
        $nested_spec_description = new self(
            $description_text,
            $this->get_full_description(),
            $this->context
        );

        $this->add_nested_spec( $nested_spec_description, false, $closure );
    }

    public function xdescribe($description_text, $closure)
    {
        $nested_spec_description = new self(
            $description_text,
            $this->get_full_description(),
            $this->context
        );

        $this->add_nested_spec( $nested_spec_description, true, $closure );
    }

    public function let($expression_name, $closure)
    {
        $this->context->at_named_expression_put( $expression_name, $closure );
    }

    public function def($method_name, $closure)
    {
        $this->context->at_method_put( $method_name, $closure );
    }

    public function it($description_text, $closure)
    {
        $nested_spec = new Spec(
            $description_text,
            $this->get_full_description(),
            $this->context,
            $closure
        );

        $nested_spec->set_file_name( \debug_backtrace( 0, 1 )[ 0 ][ "file" ] );
        $nested_spec->set_line_number( \debug_backtrace( 0, 1 )[ 0 ][ "line" ] );

        $this->add_nested_spec( $nested_spec, false, null );
    }

    public function xit($description_text, $closure)
    {
        $nested_spec = new Spec(
            $description_text,
            $this->get_full_description(),
            $this->context,
            $closure
        );

        $this->add_nested_spec( $nested_spec, true, null );
    }

    protected function add_nested_spec($nested_spec, $skipping, $closure)
    {
        $nested_spec->be_skipping( $skipping );

        $this->nested_specs[] = $nested_spec;

        if( $closure !== null ) {
            $nested_spec->eval( $closure );
        }
    }

    /// Double dispatch evaluations

    public function evaluate_with($spec_evaluator)
    {
        $spec_evaluator->___evaluate_spec_description( $this );
    }
}