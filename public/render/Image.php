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

    private $_alphadata             = 'data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==';
    private $_strip_caption_tags    = true;

    // Keep track of the last image we rendered.
    private $_last_id;


    // All of these get reset after each request because they can be set for each embed,
    private $_defaults = [];
    private $_attr;
    private $_classes;
    private $_pinnable;
    private $_showcaption;
    private $_lazy;
    // private $_low;
    // private $_low_size;
    private $_size;
    // private $_srcset;
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
        $this->_reset(true);

    }

    // ------------------------------------------------------------
    // Default options, these will get reset to original values once a request has completed.

    private function _reset($first = false) {

        if ($first) {

            $this->_defaults['_attr']                = array();
            $this->_defaults['_classes']             = array();
            $this->_defaults['_draggable']           = CONFIG('render/image/default_draggable');
            $this->_defaults['_pinnable']            = CONFIG('render/image/default_pinnable');
            // $this->_defaults['_low']                 = false;
            // $this->_defaults['_low_size']            = '400';
            $this->_defaults['_size']                = CONFIG('render/image/default_size');
            // $this->_defaults['_srcset']              = true;

            $this->_defaults['_lazy']                = CONFIG('render/image/default_lazy');
            
            $this->_defaults['_showcaption']         = CONFIG('render/image/default_caption');
            $this->_defaults['_caption_location']    = CONFIG('render/image/default_caption_location');
            $this->_defaults['_caption_element']     = CONFIG('render/image/caption_element');
            $this->_defaults['_caption_class']       = CONFIG('render/image/caption_class');

            $this->_defaults['_wrap']                = CONFIG('render/image/default_wrap');
            $this->_defaults['_wrap_size']           = CONFIG('render/image/default_wrap_size');
            $this->_defaults['_wrap_autosize']       = CONFIG('render/image/default_wrap_autosize');
            $this->_defaults['_wrap_class']          = CONFIG('render/image/default_wrap_class');

        }

        foreach ($this->_defaults as $key => $value) {
            $this->$key = $value;
        }

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

        if ($this->_strip_caption_tags) {
            $alt = strip_tags($alt);
        }

        if (strlen($alt)) {
            return character_limiter($alt, 95, '...');
        }

        if (CONFIG('render/image/alt_fallback')) {
            return get_the_title($image_id);
        }

        return '';

    }

    private function _getImageData($image, $mode='img') {

        $attributes                     = array();

        $image_id                       = $this->_getImageId($image);
        $image_dims                     = wp_get_attachment_image_src( $this->_getImageId($image), 'full');

        $image_caption                  = $this->_getImageCaption($image_id);

        $image_src                      = ($this->_lazy) ? $this->_alphadata : wp_get_attachment_image_url($image_id, $this->_size);
        $image_srcset                   = wp_get_attachment_image_srcset($image_id, $this->_size);

        // ---

        $attribute_src                  = ($this->_lazy && $mode == 'div') ? false : 'src';
        $attribute_srcset               = ($this->_lazy) ? CONFIG('render/image/lazy_'. $mode .'_srcset') : 'srcset';
        $attribute_sizes                = ($this->_lazy) ? CONFIG('render/image/lazy_sizes') : 'sizes';

        if ($attribute_src)             $attributes[$attribute_src]     = $image_src;
        if ($attribute_srcset)          $attributes[$attribute_srcset]  = $image_srcset;
        if ($attribute_sizes)           $attributes[$attribute_sizes]   = 'auto';

        // ---

        if ($this->_lazy) {
            $this->classes(CONFIG('render/image/lazy_class'));
        }
        if (class_exists('acf')) {
            $this->classes(get_field('crop_alignment', $image_id));
        }

        // ---

        if ($mode == 'img') {
            $image_alt                 = $this->_getImageAlt($image_id);
            $attributes['alt']         = $image_alt; 
        }

        // ---

        // $image_srclow   = $this->_low ? wp_get_attachment_image_url($image_id, $this->_low_size) : '';
        // $attributes['src_low']      = $image_srclow;

        $attributes['class']            = implode(' ', $this->_classes); 

        $attributes['meta']             = array(
            'id'                        => $image_id,
            'width'                     => $image_dims[1],
            'height'                    => $image_dims[2],
            'caption'                   => $image_caption,
            );

        return $attributes;

    }


    // ------------------------------------------------------------
    // Build HTML attributes from an array.
    // https://stackoverflow.com/a/34063755/107763       

    private function _generateAttributes($array=array()) {
        $array      = array_merge( $array, $this->_attr );
        return format_attributes($array, ['meta']);
    }


    // ------------------------------------------------------------
    // Sometimes we wrap the output in another div.

    private function _generateWrap($image_embed, $image, $image_data) {

        if ($this->_wrap) {

            $wrapper_attributes = [
                'class'         => $this->_wrap_class .' '. $this->_wrap_size,
                'data-width'    => $image_data['meta']['width'],
                'data-height'   => $image_data['meta']['height'],
            ];

            // 
            if ($this->_wrap_autosize) {
                $wrapper_attributes['style'] = $this->_generateWrapAutosizeDimensions($image_data);
            }
              
            // Wrap the image in our wrapper element.
            $image_embed = '<div ' . $this->_generateAttributes($wrapper_attributes) . '>' . $image_embed;

            // If the caption is set to appear right after the image, add it to the HTML before we wrap it.
            $image_embed .= $this->_generateCaptionElement($image_data, 'inside');

            // Close the wrapper element.
            $image_embed .= '</div>';

            // If the caption is set to appear after the wrapper, now we add it.
            $image_embed .= $this->_generateCaptionElement($image_data, 'outside');

        } else {

            // If we aren't wrapping the image, still need to add the caption,
            $image_embed .= $this->_generateCaptionElement($image_data, '*');

        }

        return $image_embed;

    }

    private function _generateWrapAutosizeDimensions($image_data) {

        $w      = $image_data['meta']['width'];
        $h      = $image_data['meta']['height'];        
        return 'padding-bottom:' . ($h/$w*100) . '%;';

    }


    // ------------------------------------------------------------
    // Caption output helper

    private function _generateCaptionElement($image_data, $location = null) {

        if (empty($image_data['meta']['caption']) || !$this->_showcaption) return '';

        if ($location == '*' || $this->_caption_location != $location) return '';

        return $this->_formatCaptionOutput($image_data['meta']['caption']);

    }

    private function _formatCaptionOutput($caption) {
        return '<'. $this->_caption_element .' class="'. $this->_caption_class .'">' . $caption . '</'. $this->_caption_element .'>';
    }

    private function _getImageCaption($image_id, $format = true) {
        if (!$this->_showcaption) return '';
        if ($this->_strip_caption_tags) {
            return strip_tags(wp_get_attachment_caption($image_id));
        } else {
            return wp_get_attachment_caption($image_id);
        }
    }

    public function get_caption($image=false, $format = false) {

        $image_id   = $this->_getImageId($image) ?: $this->_last_id;
        $caption    = $this->_getImageCaption($image_id);
    
        if ($format) {
            return $this->_formatCaptionOutput($caption);
        } else {
            return $caption;
        }

    }

    // ------------------------------------------------------------

    public function get_orientation($image=false) {

        $image_id   = $this->_getImageId($image) ?: $this->_last_id;
        $attr       = wp_get_attachment_image_src($image_id);

        $width      = $attr[1];
        $height     = $attr[2];

        if ($width == $height) {
            return 'square';
        } else if ($height > $width) {
            return 'vertical';
        } else {
            return 'horizontal';
        }

    }


    // ------------------------------------------------------------
    // Allow for updating of options when generating an image. Meant to be used as a chained method.

    // public function low($incoming=true) {
    //     if (isset($incoming)) $this->_low = $incoming;
    //     return $this;
    // }
    // public function srcset($incoming=true) {
    //     if (isset($incoming)) $this->_srcset = $incoming;
    //     return $this;
    // }
    public function wrap($one=true) {
        // - Passing in a boolean will allow you to enable/disable the wrap feature.
        // - Passing in a string will treat that value as a class name denoting size and add it to the wrap element.
        // - There is a special cause where passing in 'autosize' will enable the autosize calculation.
        if (is_string($one)) {
            $this->_wrap        = true;
            $this->_wrap_size   = $one;
        } else if (is_bool($one)) {
            $this->_wrap        = $one;
        }
        if (strpos($this->_wrap_size, 'autosize')) {
            // TEMP!!!
            $this->autosize(true);
        }
        return $this;

    }
    public function autosize($incoming=null) {
        if ($incoming === true) {
            $this->_wrap_autosize       = $incoming;
            $this->_wrap                = true;
            if (!strpos($this->_wrap_size, 'autosize')) {
                $this->_wrap_size       .= ' autosize';
            }
        } else if (!is_null($incoming)) {
            $this->_wrap_autosize       = $incoming;
        }
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
        // if ($incoming){
        //     if ( strrpos($incoming, 'lazyload') !== false ){
        //         $incoming .= ' lazyload-persist';
        //     }
        // }
        if ($incoming && strlen($incoming)) $this->_classes[] = $incoming;
        return $this;
    }
    public function lazy($incoming=false){
        if (isset($incoming)) $this->_lazy = $incoming;
        // $this->_classes[]           = 'lazyload lazyload-persist';
        // $this->_attr['data-sizes']  = 'auto';
        return $this;            
    }
    public function size($incoming=false) {
        if ($incoming) {
            $this->_size = $incoming;
            // CONFIG()->set('image/max/w', (int) $incoming);
        }
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

    public function div($image=false, $size=false, $inside='') {

        $this->size($size);

        $data           = $this->_getImageData($image, 'div');

        // if (CONFIG('render/image/lazysizes')) {
        //     $data[CONFIG('render/image/div_src')]       = $data['data-src'];
        //     $data[CONFIG('render/image/div_srcset')]    = $data['data-srcset'];
        // } else {
        //     $data[CONFIG('render/image/div_src')]       = $data['src'];
        //     $data[CONFIG('render/image/div_srcset')]    = $data['srcset'];
        // }

        // if ($this->_low) {
        //     $data['style'] = 'background-image: url(' . $data['src_low'] . ');';
        // }

        // if (isset($data['alt'])) unset($data['alt']);


        // unset($data['alt']);
        // // unset($data['caption']);
        // // unset($data['src_low']);
        // unset($data['src']);
        // unset($data['srcset']);

        $attributes     = $this->_generateAttributes($data);
        $output         = $this->_generateWrap('<div '.$attributes.'>'. $inside .'</div>', $image, $data);

        // Reset all the request settings.
        $this->_reset();

        return $output;
    }


    // ------------------------------------------------------------
    // Image Generator: Return a IMG element with this image. 

    public function img($image=false, $size=false) {

        $this->size($size);

        $data                   = $this->_getImageData($image, 'img');

        if (!$this->_pinnable)      $data['data-pin-nopin'] = 'true';
        if (!$this->_draggable)     $data['draggable']      = 'false';

        // if ($this->_low)        $data['src'] = $data['src_low'];

        // unset($data['src_low']);
        // if (isset($data['sizes']))  unset($data['sizes']);

        $attributes             = $this->_generateAttributes($data);
        $output                 = '<img '. $attributes .' />';

        $output                 = $this->_generateWrap($output, $image, $data);

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
