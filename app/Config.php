<?php 

namespace HelloFramework;

class Config extends Singleton {

    private $_data           = [];

    private $_config_file   = '/config';

    // ------------------------------------------------------------

    public function __construct() {

        parent::__construct();

        // Load framework default configuration values.
        $this->_load(HELLO_DIR . $this->_config_file);

        // Load theme-specific configuration overrides.
        $this->_load(FRAMEWORK_DIR . $this->_config_file);

        // Load environment-specific configuration overrides if they exist.
        $this->_load(FRAMEWORK_DIR . $this->_config_file .'_'. detect_environment());

    }

    // ------------------------------------------------------------

    private function _load($path) {

        if (file_exists($path.'.php')) include $path.'.php';

    }

    public function set($key = null, $value = null) {

        if (is_null($key)) return false;
        // if (!array_key_exists($key, $this->_data)) return null;
        
        if (is_array($key)) {
            foreach ($key as $sub_key => $sub_value) {
                $this->set($sub_key, $sub_value);
            }
            return true;
        }

        // If already set, make sure the data type if the same. Reduces chance for error.
        // if (array_key_exists($key, $this->_data) && (gettype($this->_data[$key]) != gettype($value))) {
        //     // trigger_error('Type mis-match for configuration "'.$key.'"', E_USER_NOTICE);
        //     return false;
        // }
        
        // Update the value.
        $this->_data[$key] = $value;

        return true;

    }

    public function get($key) {

        if (!array_key_exists($key, $this->_data)) return null;

        $value = $this->_data[$key];

        // Better validation for empty strings.
        if (is_string($value) && empty($value)) {
            return false;
        }
        // Better validation for empty arrays.
        if (is_array($value) && !count($value)) {
            return false;
        }

        return $value;

    }

    public function dump() {

        return $this->_data; 

    }

}