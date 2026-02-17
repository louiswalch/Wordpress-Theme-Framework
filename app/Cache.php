<?php 

namespace HelloFramework;

class Cache extends Singleton {

    // These all get assigned within `_reset` beliow:
    protected $_enabled;
    protected $_life;
    protected $_prefix;
    protected $_global;    

    protected $_prefix_sep      = ':';

    // ------------------------------------------------------------

    public function __construct() {

        parent::__construct();
        
        $this->_reset();

    }


    // ------------------------------------------------------------

    public function getArray($name) {
        $data = $this->get($name);
        return json_decode($data, true);        
    }

    public function setArray($name, $data=false) {
        $data = json_encode($data);
        return $this->set($name, $data);
    }


    // ------------------------------------------------------------

    public function load($name, $callback, $type='string') {

        if ($cached = $this->get($name, type:$type)) {
            return $cached;
        }

        $value = $callback();
        $this->set($name, $value, type:$type);

        return $value;

    }


    public function get($name, $type='string') {

        if (!$this->_enabled) return false;

        $name    = $this->_name($name);
        $data   = get_transient($name);

        $this->_reset();

        if (!empty($data)) {
            if ($type == 'array') $data = json_decode($data, true);
            return $data;
        } else {
            return false;
        }

    }

    public function set($name, $data=false, $type='string') {

        if (!$this->_enabled) return false;

        // Make sure we have data to cache.
        if (!$data) return false;

        $name    = $this->_name($name);

        if ($type == 'array') $data = json_encode($data);

        set_transient($name, $data, $this->_life);

        $this->_reset();

        return true;

    }

    public function delete($name) {

        if (!$this->_enabled) return false;

        $name    = $this->_name($name);
        
        return delete_transient($name);

    }

    // ------------------------------------------------------------


    public function global($global=true) {
        if (is_bool($global)) $this->_global = $global;
        return $this;
    }
    public function prefix($value=null, $replace=false) {
        if (!is_null($value)) {
            if ($replace) {
                $this->_prefix = $value;
            } else {
                $this->_prefix .= $value . $this->_prefix_sep;
            }
        }
        return $this;
    }
    public function life($life=null, $global=null) {
        if (!is_null($life)) $this->_life = $life;
        if (!is_null($global) && is_bool($global)) $this->_global = $global;
        return $this;
    }
    public function time($time=null) {
        if (!is_null($time)) $this->_life = $time;
        return $this;
    }

    // ------------------------------------------------------------

    protected function _reset() {

        $this->_enabled = HelloFrameworkConfig('cache');
        $this->_life    = HelloFrameworkConfig('cache/life');
        $this->_prefix  = 'FW' . $this->_prefix_sep;
        $this->_global  = true;

    }

    protected function _name($name) {

        $name    = apply_filters('fragment_cache_prefix', $this->_prefix) . $name;

        if (!$this->_global) {
            $name .= '['.get_queried_object_id().']';
        }

        if (is_user_logged_in()) {
            $name .= '[loggedin]';
        }

        return $name;

    }

}
