<?php 

namespace HelloFramework;

class Config extends Singleton {

    private $_data          = [];
    private $_config_file   = '/config';

    private $_cache_key     = 'Framework:Config';

    // private $_migrations    = [
    //     'dashboard/login/css'       =>  'login/css',
    // ];


    // ------------------------------------------------------------

    public function __construct() {

        parent::__construct();

        // First check if we have a cached version of the config.
        if (HelloFrameworkConfig('config/cache') && ($cached = CACHE()->getArray($this->_cache_key))) {
            $this->_data = $cached;
            return true;      
        }
    
        // Load framework default configuration values.
        $this->_load(HELLO_DIR . $this->_config_file);

        // Load theme-specific configuration overrides.
        $this->_load(FRAMEWORK_ROOT . $this->_config_file);

        // Load environment-specific configuration overrides if they exist.
        $this->_load(FRAMEWORK_ROOT . $this->_config_file .'_'. detect_environment());

        // Migrate any legacy configuration keys, this allows older themes to still work if the framework is upgraded.
        // $this->migrate($this->_migrations);

        // Cache the config to speed up subsequent requests.
        if (HelloFrameworkConfig('config/cache')) {
            CACHE()->life(HelloFrameworkConfig('config/cache/life'))->setArray($this->_cache_key, $this->_data);
        }

    }

    // ------------------------------------------------------------
    // If configuration keys are ever changed, this can be used to allow old integrations to work after upgrading.

    // public function migrate($param_1 = false, $param_2 = false, $delete = true) {

    //     if (is_string($param_1) && is_string($param_2)) {
    //         $this->_migrate($param_1, $param_2, $delete);
    //     } else {
    //         foreach ($param_1 as $old => $new) {
    //             $this->_migrate($old, $new, $delete);
    //         }
    //     }

    // }

    // private function _migrate($old, $new, $delete) {

    //     // Confirm if the legacy key is in the config.
    //     if (!array_key_exists($old, $this->_data)) return false;

    //     // Update current config key with value from legacy key.
    //     $this->_data[$new] = $this->_data[$old];

    //     // Should the legacy key be removed from config data?
    //     if ($delete) unset($this->_data[$old]);

    // } 


    // ------------------------------------------------------------

    private function _load($path) {

        if (file_exists($path.'.php')) { 
            include $path.'.php';
        }

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
        //    // trigger_error('Type mis-match for configuration "'.$key.'"', E_USER_NOTICE);
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
