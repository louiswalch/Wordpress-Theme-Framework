<?php

namespace HelloFramework\Render;

use HelloFramework;

/*

WORDPRESS INCLUDES
Louis Walch / say@hellolouis.com

For documentation and examples of how to use this, visit:
https://github.com/louiswalch/Wordpress-Theme-Framework/blob/master/docs/libraries.mdble.
$include_contents = INCLUDES()->fetch('header');

*/

class Includes extends HelloFramework\Singleton {

    protected $_remember        = array('_cache_this', '_cache_life', '_cache_global', '_cache_prefix', '_cache_key', '_data', '_key', '_from');
    protected $_memory          = array();

    protected $_cache_on        = true;
    protected $_cache_this      = false;
    protected $_cache_life      = DAY_IN_SECONDS;
    protected $_cache_global    = false;
    protected $_cache_prefix    = 'includes/';
    protected $_cache_key       = false;
    protected $_data            = array();
    protected $_debug           = false;
    protected $_dir_config_key  = 'framework/includes_dir';
    protected $_dir             = false;
    protected $_key             = false;
    protected $_type            = 'INCLUDE';
    protected $_from            = false;


    // ------------------------------------------------------------

    public function __construct() {

        parent::__construct();

        // Load custom settings from the framework config file.
        $this->_dir         = CONFIG($this->_dir_config_key);

        // Default settings based on current environment.
        $this->_cache_on    = !detect_environment('development');
        $this->_debug       = !detect_environment('production');

        // Store default values to be used for each new instance.
        foreach($this->_remember as $key) {
            $this->_default[$key] = $this->{$key};
        }

    }

    // ------------------------------------------------------------
    // Public getter.

    public function getData($key=null, $default = false, $group = null) {

        $value  = $default;

        if ($group) {
            if (array_key_exists($group, $this->_data) && array_key_exists($key, $this->_data[$group])) {
                $value = $this->_data[$group][$key];
            }
        } else if (array_key_exists($key, $this->_data)) {
            $value =  $this->_data[$key];
        }
        
        return $value;
        
    }


    // ------------------------------------------------------------
    // Cache Helpers

    protected function _cacheName() {

        $key    = apply_filters('fragment_cache_prefix', $this->_cache_prefix) . $this->_cache_key;

        if (!$this->_cache_global) {
            $key .= '_page-'.get_the_ID();
        }

        if (is_user_logged_in()) {
            $key .= '_loggedin';
        }

        return $key;

    }

    protected function _cacheFetch() {

        // Mke sure caching is for the entire site as well as this request.
        if (!$this->_cache_on || !$this->_cache_this) return false;

        $name    = $this->_cacheName();
        $data   = get_transient($name);

        if (!empty($data)) {
            return $data;
        } else {
            return false;
        }

    }

    protected function _cacheStore($data) {

        // Mke sure caching is for the entire site as well as this request.
        if (!$this->_cache_on || !$this->_cache_this) return false;

        // Make sure we have data to cache.
        if (!strlen($data)) return false;

        $name = $this->_cacheName();

        set_transient($name, $data, $this->_cache_life);

    }


    // ------------------------------------------------------------
    // Public setters.
    // Allow for updating of options when generating an image. Meant to be used as a chained method.

    public function cache($global=false, $set=true, $key=null) {
        if (is_bool($global)) $this->_cache_global = $global;
        if ($global == 'global') $this->_cache_global = true;
        if (!is_null($set)) $this->_cache_this = $set;
        if (!is_null($key)) $this->_cache_key = $key;
        return $this;
    }
    public function dir($dir=null) {
        if (!is_null($dir)) $this->_dir = $dir;
        return $this;
    }
    public function life($life=null) {
        if (!is_null($life)) $this->_cache_life = $life;
        return $this;
    }
    public function data($one=null, $two=null) {
        if (is_string($one) && !is_null($two)) {
            $this->_data[$one] = $two;
        } else if (is_array($one)) {
            $this->_data = $one;
        }
        return $this;
    }
    public function debug($incoming=null) {
        if (!is_null($incoming)) $this->_debug = $incoming;
        return $this;
    }
    public function key($incoming=false) {
        if ($incoming) {
            $this->_key         = preg_replace(['/[^a-z0-9\.\/_-]/', '/[_]+/'], ['', '_'], strtolower($incoming));
            if (!$this->_cache_key) $this->_cache_key   = $this->_key;
        }
        return $this;
    }


    // ------------------------------------------------------------
    // Load Helpers

    protected function _getIncludeContents() {

        global $post;

        $path   = $this->_getIncludePath();
        $output = '';

        if ($path && file_exists($path)) {

            ob_start();

            // Set local variables with the names being passed in from the data array.
            if (count($this->_data)) {
                extract($this->_data); 
                $data = $this->_data;
            } else {
                $data = array();
            }

            include($path);
            $output = ob_get_contents();
            ob_end_clean();

        } else {

            if (WP_DEBUG) {
                $output = '<section style="padding:10px; border: 1px solid #ff00ff; color: #ff00ff;">Include not found ('  .$this->_key.  '/ '.$path. ' )</section>';
            } else {
                $output = '<!--  ERROR: Include not found ('  .$this->_key. ') -->';
            }

        }

        return $output;

    }

    protected function  _getIncludeFile() {

        return $this->_key;

    }

    protected function _getIncludePath() {

        $file_path = $this->_getIncludeFile();

        // Make sure that the file name ends with .php
        if (!strpos('.php', $file_path)) $file_path .= '.php';

        // Prepend name of the directory we store all our includes in.
        $server_path = get_template_directory() . '/' . $this->_dir;

        // Confirm the directory exists, we don't care so much about the file.
        if (!file_exists($server_path)) {
            exit( 'We apologise. The webite is currently expereincing some problems. Try again later, if the problem persists please contact a system administrator. [Reference: Framework Include Path]' );
        }
        
        return $server_path . $file_path;

    }

    protected function _debug($key, $source) {        
        if ($this->_debug) {
            if ($this->_cache_on && $this->_cache_this) {
                return ('<!-- '.$this->_type.' '.$this->_key.' | Cache: '. $this->_boolDebug($this->_cache_on) .'/'. $this->_boolDebug($this->_cache_this) .' / '.$this->_cache_key.' | Source:'.$source.' -->'); 
            } else {
                return ('<!-- '.$this->_type.' '.$this->_key.' -->');
            }                
        }
    }

    protected function _boolDebug($var) {
        return (($var) ? 'Yes' : 'No');
    }


    // ------------------------------------------------------------
    // Nested Data Helpers
    // This is required so nested includes work and pass the correct information.

    public function newSession() {

        $this->_rememberSession();

        return $this;

    }

    protected function _rememberSession() {

        $_previous  = array();

        // Store previous variables, this allows for nested includes.
        foreach($this->_remember as $key) {
            $_previous[$key] = $this->{$key};
        }

        // Add to the memory bank, technically could have multiple previous includes.
        $this->_memory[]  = $_previous;         

    }

    protected function _resetSession() {

        $_session = array_pop($this->_memory);

        if ($_session && count($_session)) {
            foreach ($_session as $key => $value) {
                $this->{$key} = $value;
            }                
        }

    }


    // ------------------------------------------------------------

    protected function _fetch($key, $data=false) {

        $this->key($key);
        $this->data($data);

        // First let's see if we have something already cached.
        $source = 'Cache';
        $output = $this->_cacheFetch();

        // If that doesn't work, load it from the template file.
        if (!$output) {

            $output = $this->_getIncludeContents();
            $source = 'Live';

            $this->_cacheStore($output);

        }

        // Store the cache output before we reset everything.
        $return = $this->_debug($key, $source) . chr(10) . $output;

        return $return;

    }


    // ------------------------------------------------------------
    // PUBLIC
    // Load and output the contents of a snippet, either live or from cache.

    public function fetch($key, $data=false) {

        // Get data to return.
        $return = $this->_fetch($key, $data);

        // Reset settings for the next request.
        $this->_resetSession();

        return $return;

    }

    public function show($key, $data=false) {

        echo $this->fetch($key, $data);

    }

}