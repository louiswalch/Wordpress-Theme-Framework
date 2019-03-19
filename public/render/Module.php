<?php

namespace HelloFramework\Render;

use HelloFramework;


/*

PAGE MODULES LOADER

Louis Walch / say@hellolouis.com


-- EXAMPLES --

// Automatically load modules from current page.
MODULES()->auto();

// You can have two sets of modules on one page like this:
MODULES()->auto('content_modules');
MODULES()->auto('sidebar_modules');

// Automatically load modules from a different page, or from the ACF Options.
MODULES()->from(1234)->auto('content_modules');
MODULES()->from('options')->auto('footer_modules');

// When automatically loading all modules, you can specify certain ones to ignore.
MODULES()->auto('content_modules', ['unwanted_module_name', 'ugly_thing']);

// Alter the name of module file to be included: Adding a prefix.
// For a module named 'gallery' in the CMS, this would include 'exhibition_gallery'.
MODULES()->filename('exhibition_')->auto('content_modules');

// You can also use the `filename()` prefix option to map to a subdirectory of _modules.
MODULES()->filename('exhibition/')->auto('content_modules');

// Alter the name of module file to be included: Using `sprintf` command.
// For a module named 'gallery' in the CMS, this would include 'exhibition_gallery_preview'.
MODULES()->filename('exhibition_%s_preview')->auto('content_modules');

// Manually load a specified module while passing in the data to display.
MODULES()->show('text_content', [
    'title' => 'Test Module',
    'text' => 'Testing out manually including a module on this page. Did it work?',
    ]);

*/


// ---------------------------------------------------------------------------
// Modules - Core Class.

class Modules extends Includes {

    protected $_cache_prefix    = 'include_module_';
    protected $_dir             = '_modules/';
    protected $_type            = 'MODULE';

    protected $_filename        = false;

    protected $_from;
    

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
        $modules        = get_field($field_group, $this->_from);
        $count          = count($modules);

        $data_global    = $this->_data;

        if (!$modules || !$count) return false;

        for ($i=0; $i<$count ; $i++) { 

            $first  = ($i == 0);
            $data   = $modules[$i];
            $type   = $data['acf_fc_layout'];

            $data['field_group_name']   = $field_group;
            $data['field_group_index']  = $i;

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
        $modules    = get_field($field_group, $this->_from);
        $count      = count($modules);

        if (!$modules || !$count || !$module_name) return false;

        for ($i=0; $i<$count ; $i++) { 

            $first  = ($i == 0);
            $data   = $modules[$i];
            $type   = $data['acf_fc_layout'];

            $data['field_group_name']   = $field_group;
            $data['field_group_index']  = $i;

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
