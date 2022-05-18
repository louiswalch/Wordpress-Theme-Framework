<?php

namespace {

    // ---------------------------------------------------------------------------
    // Allow theme to access to Configuration class. 

    function HelloFrameworkConfig($key = null) {

        $instance = HelloFramework\Config::get_instance();

        if (!is_null($key)) {
            // Shorthand to get specific value.
            return $instance->get($key);
        } else {
            return $instance;
        }

    }

    // Legacy support, but so it doesn't collide with Sage.

    if (!function_exists('CONFIG')) {
        function CONFIG($key = null) {
            return HelloFrameworkConfig($key);
        }
    }


}
