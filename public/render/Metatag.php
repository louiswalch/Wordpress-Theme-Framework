<?php

/*

META TAGS 

Louis Walch / say@hellolouis.com

For documentation and examples of how to use this, visit:
https://github.com/louiswalch/Wordpress-Theme-Framework/blob/master/docs/libraries.md

*/

    class SocialMetaTags {

        // Allow for a single instance of this class across all scopes.
        private static $instance;

        private $_init          = false;

        private $_cache         = [];
        private $_image_size    = 400;
        private $_delimiter     = ' : ';
        private $_field_id      = false;

        private $_type          = false;
        private $_taxonomy      = false;
        private $_term          = false;

        public $is_woocommerce  = false;
        public $is_product      = false;


        // ------------------------------------------------------------

        public function __construct() {

            self::$instance =& $this;

        }

        public function init() {

            if ($this->_init) return true;

            wp_reset_postdata();

            $this->_type            = get_post_type();
            $this->_term            = (is_tax()) ? get_queried_object() : false;
            $this->_taxonomy        = ($this->_term) ? $this->_term->taxonomy : false;

            $this->_field_id        = ($this->_term) ? 'term_'.$this->_term->term_id : get_the_ID();

            $this->is_woocommerce   = (function_exists('is_woocommerce') && is_woocommerce());
            $this->is_product       = ($this->is_woocommerce && is_product());

            // pr($this->_type, '_type');
            // pr($this->_taxonomy, '_taxonomy');
            // pr($this->_term, '_term');

            $this->_init            = true;

        }


        // ------------------------------------------------------------
        // This will return the class singlton, makes it easier for this to be a global class.

        public static function &get_instance() {
            return self::$instance;
        }

        // ------------------------------------------------------------
        // Fetch/complile field value. Result is cached so multiple calls for the same field isn't an issue.

        public function __get($name) {

            // Make sure we have initialized the class. Can't do it on the constructor.
            if (!$this->_init) $this->init();

            // First look for existing value.
            if (array_key_exists($name, $this->_cache)) {
                return $this->_cache[$name];
            }

            // Make sure this is a valid field.
            if (!method_exists($this, '_get' . ucfirst($name) )) return null;

            // Fetch the field value and cache the result.
            $this->_cache[$name] = $this->_sanitize( $this->{ '_get' . ucfirst($name) }() );
       
            return $this->_cache[$name];

        }


        // ------------------------------------------------------------

        protected function _sanitize($string) {

            $string = strip_tags($string);
            $string = str_replace('"', '', $string);
            $string = preg_replace( "/\r|\n/", "", $string);

            return $string;

        }


        // ------------------------------------------------------------

        protected function _getSite() {
            return get_bloginfo('name');
        }

        protected function _getUrl() {
            return get_the_permalink();
        }

        protected function _getType() {

            if ($this->_type && ($this->_type == 'product')) {
                return 'product';
            } else {
                return 'website';
            }

        }

        protected function _getTitle() {

            // Does this page have a override value set in custom field?
            if (get_field('share_title', $this->_field_id)) return get_field('share_title', $this->_field_id);

            // Treat categories/taxonomies special and show full hirarchy.
            if ($this->_taxonomy) {

                if (is_taxonomy_hierarchical($this->_taxonomy)) { 

                    $parts      = array();
                    $ancestors  = array_reverse(get_ancestors($this->_term->term_id, $this->_taxonomy));

                    foreach ($ancestors as $ancestor_id) {
                        $parts[] = get_term( $ancestor_id, $this->_taxonomy)->name;
                    }

                    $parts[]    = $this->_term->name;

                    return implode($this->_delimiter, $parts);

                } else {

                    return $term->name;

                }

            }

            // Default value is wordpress title.
            return get_the_title();

        }

        protected function _getDescription() {

            // Does this page have a override value set in custom field?
            if (get_field('share_description', $this->_field_id)) return get_field('share_description', $this->_field_id); 

            // Term Description.
            if ($this->_term) return $this->_term->description;

            // Page Content as Excerpt.
            if (get_the_content()) return strip_tags(get_the_excerpt());

            return '';

        }

        protected function _getImage() {

            // Does this page have a override value set in custom field?
            if (get_field('share_image', $this->_field_id)) return IMAGE()->src(get_field('share_image', $this->_field_id), $this->_image_size);

            if (!$this->_taxonomy && has_post_thumbnail() && ($src = get_the_post_thumbnail_url(get_the_ID(), $this->_image_size))) return $src;

            if (get_field('thumbnail', $this->_field_id)) return IMAGE()->src(get_field('thumbnail', $this->_field_id), $this->_image_size);
         
            // Site-wide fallback image.
            if (get_field('default_share_image', 'options')) return IMAGE()->src(get_field('default_share_image', 'options'), $this->_image_size);

            // Woocommerce fallback image.
            if ($this->is_woocommerce) return wc_placeholder_img_src();

            return false;

        }

        protected function _getPrice() {

            $product = wc_get_product( get_the_ID() );
            
            return ($product) ? number_format($product->get_price(), 2, ".", "" ) : '';

        }

        protected function _getAvailability() {

            $product = wc_get_product( get_the_ID() );

            return ($product->is_in_stock()) ? 'instock' : 'pending';
            
        }


    }

    $SocialMetaTags = new SocialMetaTags();

    function METATAGS() {

        $instance = &SocialMetaTags::get_instance();
        return $instance;

    }