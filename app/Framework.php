<?php 

namespace HelloFramework;

class Framework {

    protected $environment;
    protected $area;

    protected $config;

	public function __construct() {

        // Determine what part of Wordpress user is in and what server this is.
        $this->area             = detect_area();
        $this->environment      = detect_environment();

        // Load configuration. This will automatically load default, theme, and environment values.
        $this->config           = new Config($this->environment);

        // Assign outgoing email settings.
        $this->setEmailParameters();

        // Assign custom image sizes and remove unneeded ones.
        $this->setImageParameters();

        // Should comments be disabled?
        $this->setCommentStatus();
         
        // Location specific customizations.
        if ($this->area == 'frontend')  new Frontend;
        if ($this->area == 'admin')     new Dashboard;
        if ($this->area == 'login')     new Login;

	}

    // ------------------------------------------------------------
    // General settings for all outgoing emails.

    protected function setEmailParameters() {

        // General settings for all outgoing emails.
        add_filter('wp_mail_content_type', function() {
            return CONFIG('email/mime');
        }, 11);
        add_filter('wp_mail_from', function( $original ) {
            return CONFIG('email/address');
        });
        add_filter('wp_mail_from_name', function( $original ) {
            return CONFIG('email/name');
        });

        // Remove site name from subject line of all outgoing emails.
        // https://wordpress.stackexchange.com/a/116287/8642        
        add_filter('wp_mail', function($email) {
            $blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);
            $email['subject'] = str_replace("[".$blogname."] - ", "", $email['subject']);    
            $email['subject'] = str_replace("[".$blogname."]", "", $email['subject']);
            return $email;
        });

        // Disable 'Notice of Password Change' email.
        if (!CONFIG('email/change_password')) {
            add_filter('send_email_change_email', '__return_false' );
        }

        // Disable 'New User' notification sent to Admin.
        if (!CONFIG('email/new_user')) {    
            add_filter('wp_new_user_notification_email_admin', '__return_false');
        }

    }


    // ------------------------------------------------------------

    protected function setImageParameters() {

        // If thumbnail support is not enabled, no need to do any of this.
        if (!CONFIG('image/enabled')) return false;

        // Enable thumbnails for this theme.
        add_theme_support('post-thumbnails');

        // Assign JPG quality for generated thumbnails/images.
        add_filter( 'jpeg_quality', function($current) {
            return CONFIG('image/jpg_quality');
        }, 10, 2);

        // Define custom thumbnail size.
        update_option('thumbnail_size_w', CONFIG('image/thumbnail/w'));
        update_option('thumbnail_size_h', CONFIG('image/thumbnail/h'));
        update_option('thumbnail_crop', CONFIG('image/thumbnail/crop'));

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
            return CONFIG('image/srcset_max');
        }, 10 , 2 );

    }

    // ------------------------------------------------------------

    protected function setCommentStatus() {

        if (CONFIG('comments')) return;

        // Remove comments support.
        add_action('init', function() {
            remove_post_type_support('post', 'comments');
            remove_post_type_support('page', 'comments');
        }, 100);

    }

}