<?php

namespace andyp\labsstack;

class initialise {


    public function __construct()
    {

        //  ┌─────────────────────────────────────────────────────────────────────────┐
        //  │                            Add Shortcodes                               │
        //  └─────────────────────────────────────────────────────────────────────────┘
        require __DIR__.'/shortcodes/labs.php';
        require __DIR__.'/shortcodes/labs_rest.php';

    }

}