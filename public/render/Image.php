<?php

namespace HelloFramework\Render;

use HelloFramework;

/*

IMAGE LOADER 
Louis Walch / say@hellolouis.com

For documentation and examples of how to use this, visit:
https://github.com/louiswalch/Wordpress-Theme-Framework/blob/master/docs/libraries.md

*/

class ImageRender extends HelloFramework\Singleton {

    private $_alphadata = 'data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==';

    // Keep track of the last image we rendered.
    private $_last_id;

    // All of these get reset after each request because they can be set for each emebed,
    private $_alpha;
    private $_attr;
    private $_classes;
    private $_pinnable;
    private $_showcaption;
    private $_low;
    private $_low_size;
    private $_size;
    private $_srcset;
    private $_wrap;
    private $_wrap_class;
    private $_wrap_size;
    private $_wrap_autosize;
    private $_caption_location;
    private $_caption_element;
    private $_caption_class;


    // ------------------------------------------------------------

    public function __construct() {

        parent::__construct();

        // Reset all the request settings.
        $this->_reset();

    }

    // ------------------------------------------------------------
    // Default options, these will get reset to original values once a request has completed.
    private function _reset() {

        $this->_alpha               = false;
        $this->_attr                = array();
        $this->_classes             = array();
        $this->_draggable           = CONFIG('render/image/default_draggable');
        $this->_pinnable            = CONFIG('render/image/default_pinnable');
        $this->_low                 = false;
        $this->_low_size            = '400';
        $this->_size                = CONFIG('render/image/default_size');
        $this->_srcset              = true;

        $this->_showcaption         = CONFIG('render/image/default_caption');
        $this->_caption_location    = CONFIG('render/image/default_caption_location');
        $this->_caption_element     = CONFIG('render/image/caption_element');
        $this->_caption_class       = CONFIG('render/image/caption_class');

        $this->_wrap                = CONFIG('render/image/default_wrap');
        $this->_wrap_size           = CONFIG('render/image/default_wrap_size');
        $this->_wrap_autosize       = CONFIG('render/image/default_wrap_autosize');
        $this->_wrap_class          = CONFIG('render/image/default_wrap_class');

    }


    // ------------------------------------------------------------
    // Image getters.

    private function _getImageId($image) {

        $image_id = false;

        // Sometimes an object is passed in.
        if (is_object($image) && property_exists($image, 'ID')) {
            $image_id = $image->ID;
        }

        // Sometimes an array is passed in.
        if (is_array($image) && array_key_exists('ID', $image)) {
            $image_id = $image['ID'];
        }

        // Now make sure we have an ID. Should this error?
        if (is_numeric($image)) {
            $image_id = $image;
        }

        if ($image_id) {
    
            // Keep reference to the last image we rendered. Could be useful.
            $this->_last_id = $image_id;
            return $image_id;
    
        } else {
    
            return false;

        }

    }

    private function _getImageAlt($image_id) {

        // // Sometimes an object is passed in.
        // if (is_object($image) && property_exists($image, 'alt')) {
        //     return $image->alt;
        // }

        // // Sometimes an array is passed in.
        // if (is_array($image) && array_key_exists('alt', $image)) {
        //     return $image['alt'];
        // }

        // pr($image, '_getImageAlt');

        $alt = get_post_meta($image_id, '_wp_attachment_image_alt', true);

        if (strlen($alt)) {
            return character_limiter($alt, 95, '...');
        }

        if (CONFIG('render/image/alt_fallback')) {
            return get_the_title($image_id);
        }

        return '';

    }

    private function _getImageData($image, $strip_tags=true) {

        $image_id       = $this->_getImageId($image);

        $image_caption  = $this->_getImageCaption($image_id);

        $image_alt      = $this->_getImageAlt($image_id);

        $image_src      = wp_get_attachment_image_url($image_id, $this->_size);
        $image_srclow   = $this->_low ? wp_get_attachment_image_url($image_id, $this->_low_size) : '';

        $image_srcset   = $this->_srcset ? wp_get_attachment_image_srcset( $image_id, $this->_size ) : '';
        $image_sizes    = $this->_srcset ? CONFIG('render/image/srcset_sizes') : '';

        $image_align    =  (class_exists('acf')) ? get_field('crop_alignment', $image_id) : '';

        $attributes     = array(
            'alt'       => $strip_tags ? strip_tags($image_alt) : $image_alt,
            'caption'   => $strip_tags ? strip_tags($image_caption) : $image_caption,
            //'data-caption' => $strip_tags ? strip_tags($image_caption) : $image_caption,
            'class'     => $image_align .' '. implode(' ', $this->_classes),
            'src'       => $image_src,
            'src_low'   => $image_srclow,
            'srcset'    => $image_srcset,
            'sizes'     => $image_sizes
            );

        if (CONFIG('render/image/lazysizes')) {
            $attributes['src']           = $this->_alphadata;
            $attributes['srcset']        = $this->_alphadata;
            $attributes['data-src']      = $image_src;
            $attributes['data-srcset']   = $image_srcset;
            $attributes['data-sizes']    = 'auto';
            unset($attributes['sizes']);
        }
        
        return $attributes;

    }

    private function _getImageAutosizePaddingRatio($image) {

        if (!$this->_wrap_autosize) return '';

        $props  = wp_get_attachment_image_src( $this->_getImageId($image), 'full' );
        $h      = $props[2];
        $w      = $props[1];
        
        return 'padding-bottom:' . ($h/$w*100) . '%;';

    }

    private function _getOrSize($image) {

        $props  = wp_get_attachment_image_src( $this->_getImageId($image), 'full' );
        $h      = $props[2];
        $w      = $props[1];

        return  [$w,$h];

    }

    // ------------------------------------------------------------
    // Generate Pinterest image. Used when drawing as a background image.

    private function _getPinterestForDiv($image=false, $alt='') {    

        // if ($this->_pinnable) {

        //     $image_id       = $this->_getImageId($image);
        //     $image_src      = wp_get_attachment_image_url($image_id, 1200);

        //     if ($image_src) {
        //         return '<img class="pinterest" src="'. $image_src .'" alt="'. $alt .'" style="display: none;" />';                    
        //     }

        // }

        return '';
        
    }


    // ------------------------------------------------------------
    // Build HTML attributes from an array.
    // https://stackoverflow.com/a/34063755/107763       

    private function _getAttributes($array=array(),$forImg=true) {

        // caption is not a valid HTML5 Attribute for img/div (WC3 Standards)
        unset( $array[ 'caption' ] ); 
        if ( !$forImg ){
             // sizes is not a valid HTML5 Attribute for DIV (WC3 Standards)
            unset( $array[ 'sizes' ] );
        }

        $array = array_merge( $array, $this->_attr );

        return implode(' ', array_map(
            function ($k, $v) { return $k .'="'.  htmlspecialchars($v) .'"'; },
            array_keys($array), $array));
        
    }


    // ------------------------------------------------------------
    // Sometimes we wrap the output in another div.

    private function _getWrap($image_embed, $image, $image_data) {

        if ($this->_wrap) {

            $wrapper_attrubutes = [
                'class' => $this->_wrap_class .' '. $this->_wrap_size,
                'style' => $this->_getImageAutosizePaddingRatio($image)
            ];

            $sizes      = $this->_getOrSize($image);

            // If the caption is set to appear right after the image, add it to the HTML before we wrap it.
            $image_embed .= $this->_getCaptionElement($image_data, 'after-image');

            // Wrap the image in our wrapper element.
            $image_embed = '<div ' . $this->_getAttributes($wrapper_attrubutes) . ' data-width="' . $sizes[0] . '" data-height="' . $sizes[1] . '" >' . $image_embed .'</div>';

            // If the caption is set to appear after the wrappe, now we add it.
            $image_embed .= $this->_getCaptionElement($image_data, 'last');

        } else {

            // If we aren't wrapping the image, still need to add the caption,
            $image_embed .= $this->_getCaptionElement($image_data, '*');

        }

        return $image_embed;

    }


    // ------------------------------------------------------------
    // Caption output helper

    private function _getCaptionElement($image_data, $location = null) {

        if (empty($image_data['caption']) || !$this->_showcaption) return '';

        if ($location == '*' || $this->_caption_location != $location) return '';

        return $this->_formatCaption($image_data['caption']);

    }

    private function _formatCaption($caption) {
        return '<'. $this->_caption_element .' class="'. $this->_caption_class .'">' . $caption . '</'. $this->_caption_element .'>';
    }

    private function _getImageCaption($image_id, $format = true) {
        return wp_get_attachment_caption($image_id);
    }

    public function get_caption($image=false, $format = false) {

        $image_id   = $this->_getImageId($image) ?: $this->_last_id;
        $caption    = $this->_getImageCaption($image_id);
    
        if ($format) {
            return $this->_formatCaption($caption);
        } else {
            return $caption;
        }

    }

    // ------------------------------------------------------------
    // Allow for updating of options when generating an image. Meant to be used as a chained method.

    public function low($incoming=true) {
        if (isset($incoming)) $this->_low = $incoming;
        return $this;
    }
    public function srcset($incoming=true) {
        if (isset($incoming)) $this->_srcset = $incoming;
        return $this;
    }
    public function wrap($one=false) {
        // - Passing in a boolean will allow you to enable/disable the wrap feature.
        // - Passing in a string will treat that value as a class name denoting size and add it to the wrap element.
        // - There is a special cause where passing in 'autosize' will enable the autosize calculation.
        if (is_string($one)) {
            $this->_wrap = true;
            $this->_wrap_size = $one;
        } else if (is_bool($one)) {
            $this->_wrap = $one;
        }
        if (in_array($this->_wrap_size, ['autosize', 'auto'])) {
            $this->_wrap_autosize = true;
        }
        return $this;

    }
    public function autosize($incoming=null) {
        if (!is_null($incoming)) $this->_wrap_autosize = $incoming;
        return $this;
    }
    public function caption($one=null) {
        if (is_string($one)) {
            $this->_showcaption         = true;
            $this->_caption_location    = $one;
        } else if (is_bool($one)) {
            $this->_showcaption         = $one;
        }
        return $this;
    }
    public function classes($incoming=false) {
        if ($incoming){
            if ( strrpos($incoming, 'lazyload') !== false ){
                $incoming .= ' lazyload-persist';
            }
        }
        if ($incoming) $this->_classes[] = $incoming;
        return $this;
    }
    public function lazy(){
        $this->_alpha               = true;
        $this->_classes[]           = 'lazyload lazyload-persist';
        $this->_attr['data-sizes']  = 'auto';
        return $this;            
    }
    public function size($incoming=false) {
        if ($incoming) {
            $this->_size = $incoming;
            // CONFIG()->set('image/srcset_max', (int) $incoming);
        }
        return $this;
    }
    public function alpha($incoming=false) {
        if (isset($incoming)) $this->_alpha = $incoming;
        return $this;
    }
    public function attributes($incoming=false) {
        return $this->attr($incoming);
    }
    public function attr($one=false, $two=null) {
        if (is_string($one) && !is_null($two)) {
            $this->_attr[$one] = $two;
        } else if (is_array($one)) {
            $this->_attr = $one;
        }
        return $this;
    }
    public function pinnable($incoming=false) {
        if (isset($incoming) && is_bool($incoming)) $this->_pinnable = $incoming;
        return $this;
    }


    // ------------------------------------------------------------
    // Image Generator: Return a DIV element with this image as it's data-src for lazy loading. 

    public function div($image=false, $size=false) {

        $this->size($size);

        $data           = $this->_getImageData($image);

        $pinterest      = $this->_getPinterestForDiv($image, $data['caption']);

        if (CONFIG('render/image/lazysizes')) {
            $data[CONFIG('render/image/div_src')]       = $data['data-src'];
            $data[CONFIG('render/image/div_srcset')]    = $data['data-srcset'];
        } else {
            $data[CONFIG('render/image/div_src')]       = $data['src'];
            $data[CONFIG('render/image/div_srcset')]    = $data['srcset'];
        }

        if ($this->_low) {
            $data['style'] = 'background-image: url(' . $data['src_low'] . ');';
        }

        unset($data['alt']);
        // unset($data['caption']);
        unset($data['src_low']);
        unset($data['src']);
        unset($data['srcset']);

        $attributes     = $this->_getAttributes($data, false);
        $output         = $this->_getWrap('<div '.$attributes.'>'. $pinterest .'</div>', $image, $data);

        // Reset all the request settings.
        $this->_reset();

        return $output;
    }


    // ------------------------------------------------------------
    // Image Generator: Return a IMG element with this image. 

    public function img($image=false, $size=false) {

        $this->size($size);

        $data                   = $this->_getImageData($image);

        if ($this->_alpha) {
            $data['data-src']   = $data['src'];
            $data['src']        = $this->_alphadata;
        }

        if (!$this->_pinnable) {
            $data['data-pin-nopin'] = 'true';
        }

        if (!$this->_draggable) {
            $data['draggable'] = 'false';
        }

        if ($this->_low) {
            $data['src'] = $data['src_low'];
        }

        unset($data['src_low']);

        $attributes             = $this->_getAttributes($data);
        $output                 = '<img '. $attributes .' />';

        $output                 = $this->_getWrap($output, $image, $data);

        // Reset all the request settings.
        $this->_reset();

        return $output;

    }

    // ------------------------------------------------------------
    // Image Generator: Just the image source.

    public function src($image=false, $size=false) {

        if (!$image) return false;

        $this->size($size);

        $image_id       = $this->_getImageId($image);

        $image_src      = wp_get_attachment_image_url($image_id, $this->_size);

        // Reset all the request settings.
        $this->_reset();

        return $image_src;
    }


}
