<?php

namespace HelloFramework\Render;

use HelloFramework;


/*

PAGE MODULES LOADER

Louis Walch / say@hellolouis.com


-- EXAMPLES --

Automatically load modules from current page.
MODULES()->auto();

Automatically load modules from a different page.
MODULES()->from(1234)->auto();

You can have two sets of modules on one page like this:
MODULES()->auto('content_modules');
MODULES()->auto('sidebar_modules');

Manually load a specified module while passing in the data to display.
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

    protected $_from;
    

    // ------------------------------------------------------------
    // Nested Data Helpers
    // We only want to remember the dir, but this will only work for 2 level nesting. Not 3.

    public function newSession() {
        return false;
    }
    protected function _rememberSession() {
        return false;
    }

    protected function _resetSession() {
        return false;
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
            $return .= $this->fetch($type, array_merge($data, $data_global));

        }

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
                $return .= $this->fetch($type, $data);
            }

        }

        echo $return;

    }

    // ------------------------------------------------------------
    // Public setters.
    // Allow for updating of options when generating an image. Meant to be used as a chained method.

    public function from($value=null) {
        if (!is_null($value)) $this->_from = $value;
        return $this;
    }

}
