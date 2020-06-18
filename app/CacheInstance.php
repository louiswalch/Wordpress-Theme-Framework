<?php

namespace {

    // ---------------------------------------------------------------------------
    // This library is only avilable to classes within the framework, not exposed to the theme.
        
    function CACHE($key = null) {

        $instance = HelloFramework\Cache::get_instance();

        if (!is_null($key)) {
            // Shorthand to get specific value.
            return $instance->get($key);
        } else {
            return $instance;
        }

    }

}