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

    // Keep track of the last image we rendered.
    private $_last_id;


    // All of these get reset after each request because they can be set for each embed,
    private $_defaults  = [];
    private $_debug     = false;
    private $_attr;
    private $_classes;
    private $_draggable;
    private $_pinnable;
    private $_showcaption;
    private $_showdescription;
    private $_lazy;
    private $_responsive;
    private $_size;
    private $_wrap;
    private $_wrap_class;
    private $_wrap_size;
    private $_wrap_autosize;
    private $_wrap_fallback;
    private $_caption_location;
    private $_caption_element;
    private $_caption_placeholder;
    private $_caption_class;
    private $_caption_strip_html;
    private $_description_location;
    private $_description_element;
    private $_description_class;


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

            $this->_defaults['_debug']                  = false;
            $this->_defaults['_attr']                   = array();
            $this->_defaults['_classes']                = array();
            $this->_defaults['_draggable']              = HelloFrameworkConfig('render/image/default_draggable');
            $this->_defaults['_pinnable']               = HelloFrameworkConfig('render/image/default_pinnable');
            $this->_defaults['_size']                   = HelloFrameworkConfig('render/image/default_size');

            $this->_defaults['_lazy']                   = HelloFrameworkConfig('render/image/default_lazy');
            $this->_defaults['_responsive']             = HelloFrameworkConfig('render/image/default_responsive');
            
            $this->_defaults['_showcaption']            = HelloFrameworkConfig('render/image/default_caption');
            $this->_defaults['_caption_location']       = HelloFrameworkConfig('render/image/default_caption_location');
            $this->_defaults['_caption_element']        = HelloFrameworkConfig('render/image/caption_element');
            $this->_defaults['_caption_placeholder']    = HelloFrameworkConfig('render/image/caption_placeholder');
            $this->_defaults['_caption_class']          = HelloFrameworkConfig('render/image/caption_class');
            $this->_defaults['_caption_strip_html']     = HelloFrameworkConfig('render/image/caption_strip_html');

            $this->_defaults['_showdescription']        = HelloFrameworkConfig('render/image/default_description');
            $this->_defaults['_description_location']   = HelloFrameworkConfig('render/image/default_description_location');
            $this->_defaults['_description_element']    = HelloFrameworkConfig('render/image/description_element');
            $this->_defaults['_description_class']      = HelloFrameworkConfig('render/image/description_class');

            $this->_defaults['_wrap']                   = HelloFrameworkConfig('render/image/default_wrap');
            $this->_defaults['_wrap_size']              = HelloFrameworkConfig('render/image/default_wrap_size');
            $this->_defaults['_wrap_autosize']          = HelloFrameworkConfig('render/image/default_wrap_autosize');
            $this->_defaults['_wrap_class']             = HelloFrameworkConfig('render/image/default_wrap_class');
            $this->_defaults['_wrap_fallback']          = HelloFrameworkConfig('render/image/default_wrap_fallback');

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

        if (strlen($alt)) {
            $alt = strip_tags($alt);
            if (HelloFrameworkConfig('render/image/alt_limit') && is_numeric(HelloFrameworkConfig('render/image/alt_limit'))) {
                return character_limiter($alt, HelloFrameworkConfig('render/image/alt_limit'), '...');
            }
            return $alt;
        } else if (HelloFrameworkConfig('render/image/alt_fallback')) {
            if (($title = get_the_title($image_id))) {
                return $title;
            }
            return str_replace(['-', '_', '.jpg', '.jpeg', '.png'], ' ', basename(get_attached_file($image_id)));
        }

        return '';

    }

    private function _getImageData($image, $mode='img') {

        $attributes                     = array();

        $image_id                       = $this->_getImageId($image);

        $image_dims                     = wp_get_attachment_image_src( $this->_getImageId($image), 'full');

        $image_mime                     = get_post_mime_type($image_id);
        if (!$image_mime) return false;

        $image_caption                  = ($this->_showcaption) ? $this->_getImageCaption($image_id) : '';
        $image_description              = ($this->_showdescription) ? $this->_getImageDescription($image_id) : '';

        $image_src                      = ($this->_lazy && ($image_mime != 'image/gif')) ? $this->_alphadata : wp_get_attachment_image_url($image_id, $this->_size);
        $image_srcset                   = ($image_mime == 'image/gif') ? wp_get_attachment_image_url($image_id, $this->_size) : wp_get_attachment_image_srcset($image_id, $this->_size);

        // ---

        $attribute_src                  = ($this->_lazy && $mode == 'div') ? false : 'src';
        $attribute_srcset               = ($this->_lazy) ? HelloFrameworkConfig('render/image/lazy_'. $mode .'_srcset') : 'srcset';
        $attribute_sizes                = ($this->_lazy) ? HelloFrameworkConfig('render/image/lazy_sizes') : 'sizes';

        if ($attribute_src)                             $attributes[$attribute_src]     = $image_src;
        if ($this->_responsive && $attribute_srcset)    $attributes[$attribute_srcset]  = $image_srcset;
        if ($this->_responsive && $attribute_sizes)     $attributes[$attribute_sizes]   = 'auto';

        // ---

        if ($this->_lazy) {
            $this->classes(HelloFrameworkConfig('render/image/lazy_class'));
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

        $attributes['class']            = implode(' ', $this->_classes); 

        $attributes['meta']             = array(
            'id'                        => $image_id,
            'width'                     => $image_dims[1],
            'height'                    => $image_dims[2],
            'caption'                   => $image_caption,
            'description'               => $image_description,
            );

        if ($this->_debug) pr($attributes, 'attributes');

        return $attributes;

    }


    // ------------------------------------------------------------
    // Build HTML attributes from an array.
    // https://stackoverflow.com/a/34063755/107763       

    private function _generateAttributes($array=[]) {
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
                $wrapper_attributes['style'] = $this->_generateWrapAutosizeDimensions($image_data['meta']);
            }
              
            // Wrap the image in our wrapper element.
            $image_embed = '<div ' . $this->_generateAttributes($wrapper_attributes) . '>' . $image_embed;

            // If the description is set to appear right after the image, add it to the HTML before we wrap it.
            $image_embed .= $this->_generateDescriptionElement($image_data, 'inside');

            // If the caption is set to appear right after the image, add it to the HTML before we wrap it.
            $image_embed .= $this->_generateCaptionElement($image_data, 'inside');

            // Close the wrapper element.
            $image_embed .= '</div>';

            // If the caption is set to appear after the wrapper, now we add it.
            $image_embed .= $this->_generateCaptionElement($image_data, 'outside');

            // If the description is set to appear after the wrapper, now we add it.
            $image_embed .= $this->_generateDescriptionElement($image_data, 'outside');

        } else {

            // If we aren't wrapping the image, still need to add the description.
            $image_embed .= $this->_generateDescriptionElement($image_data, '*');
            // And caption.
            $image_embed .= $this->_generateCaptionElement($image_data, '*');


        }

        return $image_embed;

    }

    private function _generateWrapAutosizeDimensions($image_meta) {

        if (empty($image_meta['width']) || empty($image_meta['height'])) return '';

        $w      = $image_meta['width'];
        $h      = $image_meta['height'];        

        if (HelloFrameworkConfig('render/image/autosize_method') == 'aspect-ratio') {
            return 'aspect-ratio:' . $w .'/' .$h. ';';
        } else {
            return 'padding-bottom:' . ($h/$w*100) . '%;';
        }

    }

    public function get_autosize($image=false) {

        $image_id   = $this->_getImageId($image) ?: $this->_last_id;
        $image_dims = wp_get_attachment_image_src( $image_id, 'full');

        return $this->_generateWrapAutosizeDimensions([
            'width'     => $image_dims[1],
            'height'    => $image_dims[2],
        ]);

    }

    public function get_dimensions($image=false) {

        $image_id   = $this->_getImageId($image) ?: $this->_last_id;
        $image_dims = wp_get_attachment_image_src( $image_id, 'full');

        return [
            'width'     => $image_dims[1],
            'height'    => $image_dims[2],
        ];

    }



    // ------------------------------------------------------------
    // Description output helper

    private function _generateDescriptionElement($image_data, $location = null) {

        if (empty($image_data['meta']['description']) || !$this->_showdescription) return '';

        if ($location != '*' && $this->_description_location != $location) return '';

        return $this->_formatDescriptionOutput($image_data['meta']['description']);

    }

    private function _formatDescriptionOutput($content) {
        return '<'. $this->_description_element .' class="'. $this->_description_class .'"><div>' . $content . '</div></'. $this->_description_element .'>';
    }

    private function _getImageDescription($image_id) {
        return get_post_field('post_content', $image_id);
    }

    public function get_description($image=false, $format = false) {

        $image_id   = $this->_getImageId($image) ?: $this->_last_id;
        $caption    = $this->_getImageDescription($image_id);
    
        if ($format) {
            return $this->_formatDescriptionOutput($caption);
        } else {
            return $caption;
        }

    }

    // ------------------------------------------------------------
    // Caption output helper

    private function _generateCaptionElement($image_data, $location = null) {

        if (!$this->_showcaption) return '';

        if ($location != '*' && $this->_caption_location != $location) return '';

        if (!$this->_caption_placeholder && empty($image_data['meta']['caption'])) return '';

        return $this->_formatCaptionOutput($image_data['meta']['caption']);

    }

    private function _formatCaptionOutput($caption) {
        if (empty($caption)) $caption = '&nbsp;';
        return '<'. $this->_caption_element .' class="'. $this->_caption_class .'">' . $caption . '</'. $this->_caption_element .'>';
    }

    private function _getImageCaption($image_id) {
        if ($this->_caption_strip_html) {
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
        $attr       = wp_get_attachment_image_src($image_id, 'full');

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
        if (strpos($this->_wrap_size, 'autosize') !== false) {
            // TEMP!!!
            $this->autosize(true);
        }
        return $this;

    }
    public function fallback($incoming=false) {
        if (isset($incoming) && is_bool($incoming)) $this->_wrap_fallback = $incoming;
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
    public function description($one=null) {
        if (is_string($one)) {
            $this->_showdescription         = true;
            $this->_description_location    = $one;
        } else if (is_bool($one)) {
            $this->_showdescription         = $one;
        }
        return $this;
    }    
    public function classes($incoming=false) {
        if ($incoming && strlen($incoming)) $this->_classes[] = $incoming;
        return $this;
    }
    public function lazy($incoming=false){
        if (isset($incoming)) $this->_lazy = $incoming;
        return $this;            
    }
    public function responsive($incoming=false){
        if (isset($incoming)) $this->_responsive = $incoming;
        return $this;            
    }
    public function size($incoming=false) {
        if ($incoming) {
            $this->_size = $incoming;
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
    public function debug($incoming=false){
        if (isset($incoming)) $this->_debug = $incoming;
        return $this;            
    }

    // ------------------------------------------------------------
    // When no image is specified do fallback behavior

    private function _generateFallback() {

        $return     = false;

        if ($this->_wrap_fallback) {
            $fallback   = '<div class="'. $this->_wrap_class .' fallback '. $this->_wrap_size .'"></div>';
            $return     = str_replace('autosize', '', $fallback);
        }

        $this->_reset();

        return $return;

    }    

    // ------------------------------------------------------------
    // Image Generator: Return a DIV element with this image as it's data-src for lazy loading. 

    public function div($image=false, $size=false, $inside='') {

        $this->size($size);

        $data           = $this->_getImageData($image, 'div');

        if (!$data) return $this->_generateFallback();

        if (!$this->_responsive) {
            $data['style'] = 'background-image: url('.$data['src'].')';     
            unset($data['src']);
        }

        $attributes     = $this->_generateAttributes($data);
        $output         = $this->_generateWrap('<div '.$attributes.'>'. $inside .'</div>', $image, $data);

        // Reset all the request settings.
        $this->_reset();

        return $output;
    }


    // ------------------------------------------------------------
    // Image Generator: Return a IMG element with this image. 

    public function img($image=false, $size=false) {

        if (!$image) return $this->_generateFallback();

        $this->size($size);

        $data                   = $this->_getImageData($image, 'img');

        if (!$data) return $this->_generateFallback();

        if (!$this->_pinnable)      $data['data-pin-nopin'] = 'true';
        if (!$this->_draggable)     $data['draggable']      = 'false';

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

        if (!$image) return $this->_generateFallback();

        $this->size($size);

        $image_id       = $this->_getImageId($image);

        $image_src      = wp_get_attachment_image_url($image_id, $this->_size);

        // Reset all the request settings.
        $this->_reset();

        return $image_src;
    }


}
