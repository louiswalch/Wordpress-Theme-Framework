<?php

namespace {

    // ---------------------------------------------------------------------------

    function INCLUDES() {

        $instance = HelloFramework\Render\Includes::get_instance();
        return $instance->newSession();

    }
    
    // ---------------------------------------------------------------------------
    // Public helper to return data by key. Meant to be used within an include file.
    // Will return 'false' if the key is not found.

    function get_include_var($key, $default = false, $group = null) {
    
        $instance = HelloFramework\Render\Includes::get_instance();
        return $instance->getData($key, $default, $group);
    
    }

}
