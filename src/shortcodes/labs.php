<?php

/**
 * Create the class and return results.
 */
function andyp_labs_stack_callback($atts){


    $stack = new \andyp\labsstack\REST\labs();

    return $stack->out();
}

add_shortcode( 'andyp_labs_stack', 'andyp_labs_stack_callback' );