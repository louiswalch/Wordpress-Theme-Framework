<?php 

namespace HelloFramework;

class Cache extends Singleton {

    protected $_enabled         = true; // Master control over all caching. Only use for development & testing.

    protected $_life;
    protected $_prefix;
    protected $_prefix_sep      = ':';
    protected $_global;

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

    public function get($name) {

        if (!$this->_enabled) return false;

        $name    = $this->_name($name);
        $data   = get_transient($name);

        $this->_reset();

        if (!empty($data)) {
            return $data;
        } else {
            return false;
        }

    }

    public function set($name, $data=false) {

        if (!$this->_enabled) return false;

        // Make sure we have data to cache.
        if (!$data) return false;

        $name    = $this->_name($name);

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

    // ------------------------------------------------------------

    protected function _reset() {

        $this->_life    = DAY_IN_SECONDS;
        $this->_prefix  = 'Framework' . $this->_prefix_sep;
        $this->_global  = true;

    }

    protected function _name($name) {

        $name    = apply_filters('fragment_cache_prefix', $this->_prefix) . $name;

        if (!$this->_global) {
            $name .= '['.get_the_ID().']';
        }

        if (\is_user_logged_in()) {
            $name .= '[loggedin]';
        }

        return $name;

    }

}
