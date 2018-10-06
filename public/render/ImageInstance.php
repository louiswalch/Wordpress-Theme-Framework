<?php

namespace {

    // ---------------------------------------------------------------------------
    // Allow theme to access the Image render class.

    function IMAGE() {

        $instance = HelloFramework\Render\ImageRender::get_instance();
        return $instance;

    }    

}