<?php

namespace andyp\isometricrest;

class initialise {


    public function __construct()
    {

        //  ┌─────────────────────────────────────────────────────────────────────────┐
        //  │                            Add Shortcodes                               │
        //  └─────────────────────────────────────────────────────────────────────────┘
        require __DIR__.'/shortcodes/isometric_rest.php';

    }

}