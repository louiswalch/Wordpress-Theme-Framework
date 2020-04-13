<?php

namespace HelloFramework\Render;

use HelloFramework;

/*

PAGE MODULES LOADER
Louis Walch / say@hellolouis.com

For documentation and examples of how to use this, visit:
https://github.com/louiswalch/Wordpress-Theme-Framework/blob/master/docs/libraries.md

*/


// ---------------------------------------------------------------------------
// Modules - Core Class.

class Modules extends Includes {

    protected $_cache_prefix    = 'include_module_';
    protected $_dir_config_key  = 'framework/modules_dir';
    // protected $_dir             = '_modules/';
    protected $_type            = 'MODULE';

    protected $_filename        = false;

    protected $_from            = false; 


    // ------------------------------------------------------------
    // Nested Data Helpers
    // Little different then how includes work so let's hardcode the memory for now.

    public function newSession() {
        return $this;
    }
    protected function _rememberSession() {
        return false;
    }
    protected function _resetSession() {
        $this->_dir         = '_modules/';
        $this->_filename    = false;
        $this->_from        = false;
        $this->_data        = [];
        return false;
    }


    // ------------------------------------------------------------

    protected function  _getIncludeFile() {

        $value = parent::_getIncludeFile();

        if ($this->_filename) {

            if (strpos($this->_filename , '%s')) {
                return sprintf($this->_filename, $value);
            } else {
                return $this->_filename . $value;
            }

        } else {

            return $value;

        } 

    }


    // ------------------------------------------------------------
    // Load Helpers

    public function auto($field_group = 'modules', $skip = array()) {
        
        $return         = '';
 
        $modules        = gettype( $field_group ) === 'string' ? get_field($field_group, $this->_from) : $field_group;
        $count          = is_array($modules) ? count($modules) : 0;
        $data_global    = $this->_data;

        if (!$modules || !$count) return false;

        for ($i=0; $i<$count ; $i++) { 

            $first  = ($i == 0);
            $last   = ($i == ($count - 1));
            $data   = $modules[$i];
            $type   = $data['acf_fc_layout'];

            $data['_name']   = $field_group;
            $data['_index']  = $i;
            $data['_first']  = $first;
            $data['_last']   = $last;

            // Sometimes we want to skip modules and load them manually in a different location.
            if (in_array($type, $skip)) continue;

            // $return .= '<a id="'.$field_group.'_'.$i.'" module="'.$type.'"></a>';
            $return .= $this->_fetch($type, array_merge($data, $data_global));

        }

        $this->_resetSession();

        echo $return;

    }


    // Manually load just one module from a group into a location on current page.
    public function manual($field_group = 'modules', $module_name = false) {
        
        $return     = '';
        $modules    = gettype( $field_group ) === 'string' ? get_field($field_group, $this->_from) : $field_group;
        $count      = count($modules);

        if (!$modules || !$count || !$module_name) return false;

        for ($i=0; $i<$count ; $i++) { 

            $first  = ($i == 0);
            $last   = ($i == ($count - 1));
            $data   = $modules[$i];
            $type   = $data['acf_fc_layout'];

            $data['_name']   = $field_group;
            $data['_index']  = $i;
            $data['_first']  = $first;
            $data['_last']   = $last;

            if ($type == $module_name) {
                // $return .= '<a id="'.$field_group.'_'.$i.'" module="'.$type.'"></a>';
                $return .= $this->_fetch($type, $data);
            }

        }

        $this->_resetSession();

        echo $return;

    }


    // ------------------------------------------------------------
    // Public setters.
    // Allow for updating of options when generating an image. Meant to be used as a chained method.

    public function from($value=null) {
        if (!is_null($value)) $this->_from = $value;
        return $this;
    }

    public function filename($value=null) {
        if (!is_null($value)) $this->_filename = $value;
        return $this;        
    }


}
