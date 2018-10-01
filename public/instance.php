<?php

namespace {

    // ---------------------------------------------------------------------------
    // Allow theme to access to Configuration class. 

    function CONFIG($key = null) {

        $instance = HelloFramework\Config::get_instance();

        if (!is_null($key)) {
            // Shorthand to get specific value.
            return $instance->get($key);
        } else {
            return $instance;
        }

    }

    // ---------------------------------------------------------------------------
    // Allow theme to access the Image render class.

    function IMAGE() {

        $instance = HelloFramework\Render\Image::get_instance();
        return $instance;

    }    

    // ---------------------------------------------------------------------------

    function SVG() {

        $instance = HelloFramework\Render\SVG::get_instance();
        return $instance;

    }


    // ---------------------------------------------------------------------------

    function INCLUDES() {

        $instance = HelloFramework\Render\Includes::get_instance();
        return $instance->newSession();

    }
    
    // Public helper to return data by key. Meant to be used within an include file.
    // Will return 'false' if the key is not found.
    function get_include_var($key, $group = null) {
    
        $instance = HelloFramework\Render\Includes::get_instance();
        return $instance->getData($key, $group);
    
    }




}
