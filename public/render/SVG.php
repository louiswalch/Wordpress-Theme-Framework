<?php

namespace HelloFramework\Render;

use HelloFramework;

/*

SVG LOADER 

Louis Walch / say@hellolouis.com


-- EXAMPLES --

// Display an in-line SVG (stored in assets/img/arrow.svg):
echo SVG()->show('arrow');

// Display an SVG (stored in assets/img/arrow.svg) as <img> with attributes:
echo SVG()->img('test', array('class'=>'testing', 'alt'=>'Name of the image'))


*/


class SVG extends HelloFramework\Singleton {

    protected $_dir    = '/img/';

    // ------------------------------------------------------------
    // Build HTML attributes from an array.
    // https://stackoverflow.com/a/34063755/107763       

    private function _getAttributes($array=array()) {     

        return implode(' ', array_map(
            function ($k, $v) { return $k .'="'. htmlspecialchars($v) .'"'; },
            array_keys($array), $array));
        
    }

    // ------------------------------------------------------------
    // Utility Methods

    private function _buildServerPath($key) {

        $key = '/assets' . $this->_dir . $key;

        if (!strpos('.svg', $key)) {
            $key .= '.svg';
        }

        return get_stylesheet_directory() . $key;

    }

    private function _buildWebPath($key) {

        $key = $this->_dir . $key;

        if (!strpos('.svg', $key)) {
            $key .= '.svg';
        }

        return asset($key, false);

    }



    // ------------------------------------------------------------
    // Display this SVG as an IMG element.

    function img($key, $attributes=array()) {

        $path       = $this->_buildWebPath($key);
        $attributes = $this->_getAttributes($attributes);

        return '<img src="'. $path .'" '. $attributes . '/>';

    }

    // ------------------------------------------------------------
    // Display this SVG as an DIV background image.

    function div($key, $attributes=array()) {

        $path       = $this->_buildWebPath($key);
        $attributes = $this->_getAttributes($attributes);

        return '<div style="background-image:url('. $path .')" '. $attributes . '></div>';

    }


    // ------------------------------------------------------------
    // Load and output the contents of an SVG.

    function show($key) {

        $path       = $this->_buildServerPath($key);

        // Make sure the file exists before 
        if (file_exists($path)) {

            return file_get_contents($path);

        } else {

            return '<!--  ERROR: SVG not found ('  .$key.  ') -->';

        }
        
    }


}
