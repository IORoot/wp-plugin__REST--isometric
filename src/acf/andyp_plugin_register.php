<?php

add_action( 'plugins_loaded', function() {
    do_action('register_andyp_plugin', [
        'title'     => 'Labs - Menu Stack Image',
        'icon'      => 'image-multiple',
        'color'     => '#A5D6A7',
        'path'      => __FILE__,
    ]);
} );