<?php

namespace HelloFramework\Render;

use HelloFramework;

/*

SVG LOADER 
Louis Walch / say@hellolouis.com

For documentation and examples of how to use this, visit:
https://github.com/louiswalch/Wordpress-Theme-Framework/blob/master/docs/libraries.md

*/


class SVG extends HelloFramework\Singleton {

    protected $_dir    = 'img/';

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

        $key = '/assets/' . $this->_dir . $key;

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


        if ( CONFIG( 'render/svg/svg_inject' ) ){
            return $this->img( $key, [ 'onload' => 'SVGInject(this)', 'alt' => 'Image replaced with SVGInject' ] );
        }

        $path       = $this->_buildServerPath($key);

        // Make sure the file exists before 
        if (file_exists($path)) {

            // Get SVG file contents.
            $result = file_get_contents($path);

            // Strip out <?xml tag.
            $result = preg_replace('/\<\?xml.+\?\>/', '', $result, 1);

            return $result;

        } else {

            return '<!--  ERROR: SVG not found ('  .$key.  ') -->';

        }
        
    }


}