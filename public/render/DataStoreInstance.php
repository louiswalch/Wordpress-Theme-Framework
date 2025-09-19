<?php

namespace {

    // ---------------------------------------------------------------------------
    // Allow theme to access the Data Store class.

    function DATASTORE() {

        $instance = HelloFramework\Render\DataStore::get_instance();
        return $instance;

    }

}