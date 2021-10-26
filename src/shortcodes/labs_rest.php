<?php

/**
 * Create the class and return results.
 */
function andyp_labs_rest_callback($atts, $content = null){

    $labs = new \andyp\labsstack\REST\labs_rest();

    if (isset($atts['count'])){
        $labs->set_count($atts['count']);
    }
    if (isset($atts['type'])){
        $labs->set_posttype($atts['type']);
    }
    if (isset($atts['cat'])){
        $labs->set_category($atts['cat']);
    }
    if (isset($atts['classes'])){
        $labs->set_classes($atts['classes']);
    }
    if (isset($atts['order'])){
        $labs->set_order($atts['order']);
    }
    if (isset($content)){
        $labs->set_content($content);
    }

    $labs->run();

    return $labs->out();
}

add_shortcode( 'andyp_labs_rest', 'andyp_labs_rest_callback' );