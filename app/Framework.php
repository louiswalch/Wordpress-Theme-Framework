<?php 

namespace HelloFramework;

class Framework {

    protected $environment;
    protected $zone;

    protected $config;

	public function __construct() {

        // Load configuration. This will automatically load default, theme, and environment values.
        $this->config           = new Config();

        // Determine what part of Wordpress user is in and what server this is.
        $this->zone             = detect_zone();
        $this->environment      = detect_environment();

        // Assign outgoing email settings.
        new FrameworkEmail;

        // Toggle core Wordpress functionality.
        $this->toggleSupports();

        // Assign custom image sizes and remove unneeded ones.
        $this->setImageParameters();
         
        // Should custom post types be auto-loaded from the theme?
        $this->autoLoadCustomTypes();

        // Location specific customizations.
        if ($this->zone == 'frontend')  new Frontend;
        if ($this->zone == 'admin')     new Dashboard;
        if ($this->zone == 'login')     new Login;

	}


    // ------------------------------------------------------------

    protected function setImageParameters() {

        // Assign JPG quality for generated thumbnails/images.
        add_filter( 'jpeg_quality', function($current) {
            return CONFIG('image/jpg_quality');
        }, 10, 2);

        // Define custom thumbnail size.
        set_post_thumbnail_size(CONFIG('image/thumbnail/w'), CONFIG('image/thumbnail/h'), CONFIG('image/thumbnail/crop'));

        // Add custom image sizes that work better with 'srcset'.
        foreach (CONFIG('image/sizes') as $size) {
            $complex    = (is_array($size));
            $width      = ($complex && !empty($size[0])) ? $size[0] : $size;
            $height     = ($complex && !empty($size[1])) ? $size[1] : $size;
            $crop       = ($complex && !empty($size[2])) ? $size[1] : false;
            $name       = ($complex) ? ($width . 'x' . $height) : $width;
            add_image_size($name, $width, $height, $crop); 
        }

        // Remove the built-in Wordpress image sizes.
        foreach (CONFIG('image/remove_default') as $size) {
            update_option($size . '_size_h', 0 );
            update_option($size . '_size_w', 0 );
        }
        add_filter('intermediate_image_sizes_advanced', function($sizes) {
            foreach (CONFIG('image/remove_default') as $size) {
                unset($sizes[$size]);
            }
            return $sizes;
        });
    
        // Increase the Wordpress Max 'srcset' Size 
        // http://aaronrutley.com/responsive-images-in-wordpress-with-acf/
        add_filter( 'max_srcset_image_width', function() {
            return CONFIG('image/max/w');
        }, 10 , 2 );

    }


    // ------------------------------------------------------------

    protected function autoLoadCustomTypes() {

        if (!CONFIG('custom_types/autoload')) return;

        require_all_files(FRAMEWORK_ROOT . '/' . CONFIG('custom_types/directory').'/');

    }   

    protected function toggleSupports() {

        // Remove comments support.
        if (!CONFIG('support/comments')) {
            remove_post_type_support('post', 'comments');
            remove_post_type_support('page', 'comments');
        }

        // Author pages are usually not needed. Redirect away from author permalinks.
        if (!CONFIG('support/author_pages')) {
            add_action('template_redirect', function() {
                global $wp_query;
                if (is_author()) {
                    wp_redirect(get_option('home'), 301); 
                    exit; 
                }
            });
        }

        if (CONFIG('support/thumbnails')) {
            add_theme_support('post-thumbnails');
        }

        if (CONFIG('support/menus')) {
            add_theme_support('menus');
        }

        if (CONFIG('support/widgets')) {
            add_theme_support('widgets');
        }

        if (!CONFIG('support/categories')) {

            // https://joshuadnelson.com/code/remove-default-wordpress-taxonomies/
            add_action( 'init', function(){
                global $wp_taxonomies;
                $taxonomies = array('category', 'post_tag');
                foreach( $taxonomies as $taxonomy ) {
                    if (taxonomy_exists($taxonomy)) unset($wp_taxonomies[$taxonomy]);
                }
            });

        }

    }

}