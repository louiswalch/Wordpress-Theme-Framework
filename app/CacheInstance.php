<?php

namespace {

    // ---------------------------------------------------------------------------
    // This library is only avilable to classes within the framework, not exposed to the theme.
    
    function HelloFrameworkCache($key = null) {

        $instance = HelloFramework\Cache::get_instance();

        if (!is_null($key)) {
            // Shorthand to get specific value.
            return $instance->get($key);
        } else {
            return $instance;
        }

    }

    // Legacy support, but so it doesn't collide with Sage.

    if (!function_exists('CACHE')) {
        function CACHE($key = null) {
            return HelloFrameworkCache($key);
        }
    }

}