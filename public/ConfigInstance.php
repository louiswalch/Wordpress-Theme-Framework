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


}
