<?php

namespace {

    // ---------------------------------------------------------------------------
    // Allow theme to access the Data Cache class.

    function DATACACHE() {

        $instance = HelloFramework\Render\DataCache::get_instance();
        return $instance;

    }

}