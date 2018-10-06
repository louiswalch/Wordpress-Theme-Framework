<?php

namespace {

    // ---------------------------------------------------------------------------
    // Modules - Public instance & helper function.

    function MODULES() {

        $instance = HelloFramework\Render\Modules::get_instance();
        return $instance;

    }

    // ---------------------------------------------------------------------------
    // Modules - Public function to return data by key. Meant to be used within an include file.

    function get_module_var($key, $group = null) {

        return MODULES()->getData($key, $group);
    
    }


}
