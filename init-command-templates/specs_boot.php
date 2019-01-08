<?php

Spec_Evaluator::configure( function($specs) {

    $specs->before_all( function() {
        print "Before all specs";
    });

    $specs->after_all( function() {
        print "After all specs";
    });

    $specs->before_each( function() {
        print "Before each spec";
    });

    $specs->after_each( function() {
        print "After each spec";
    });
});