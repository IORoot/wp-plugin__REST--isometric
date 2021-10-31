<?php

/**
 * Create the class and return results.
 */
function andyp_isometric_rest_callback($atts, $content = null){

    $iso = new \andyp\isometricrest\REST\isometric_rest();

    if (isset($atts['count'])){
        $iso->set_count($atts['count']);
    }
    if (isset($atts['type'])){
        $iso->set_posttype($atts['type']);
    }
    if (isset($atts['category'])){
        $iso->set_category($atts['category']);
    }    
    if (isset($atts['cat_id'])){
        $iso->set_cat_id($atts['cat_id']);
    }
    if (isset($atts['classes'])){
        $iso->set_classes($atts['classes']);
    }
    if (isset($atts['order'])){
        $iso->set_order($atts['order']);
    }    
    if (isset($atts['endpoint'])){
        $iso->set_endpoint($atts['endpoint']);
    }
    if (isset($content)){
        $iso->set_content($content);
    }

    $iso->run();

    return $iso->out();
}

add_shortcode( 'andyp_isometric_rest', 'andyp_isometric_rest_callback' );