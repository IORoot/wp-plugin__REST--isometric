<?php

/**
 * Create the class and return results.
 */
function andyp_rest_isometric_callback($atts){


    $stack = new \andyp\labsstack\REST\labs_stack();

    return $stack->out();
}

add_shortcode( 'andyp_rest_isometric', 'andyp_rest_isometric_callback' );