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
        $this->setEmailParameters();

        // Toggle core Wordpress functionality.
        $this->toggleSupports();

        // Assign custom image sizes and remove unneeded ones.
        $this->setImageParameters();

        // Should comments be disabled?
        $this->setCommentStatus();
         
        // Should custom post types be auto-loaded from the theme?
        $this->autoLoadCustomTypes();

        // Location specific customizations.
        if ($this->zone == 'frontend')  new Frontend;
        if ($this->zone == 'admin')     new Dashboard;
        if ($this->zone == 'login')     new Login;

	}

    // ------------------------------------------------------------
    // General settings for all outgoing emails.

    protected function setEmailParameters() {

        // General settings for all outgoing emails.
        add_filter('wp_mail_content_type', function() {
            return CONFIG('email/mime');
        });
        add_filter('wp_mail_from', function( $original ) {
            return CONFIG('email/address');
        });
        add_filter('wp_mail_from_name', function( $original ) {
            return CONFIG('email/name');
        });

        // Disable 'Notice of Password Change' email.
        if (!CONFIG('email/change_password')) {
            add_filter('send_email_change_email', '__return_false' );
        }

        // Disable 'New User' notification sent to Admin.
        if (!CONFIG('email/new_user')) {    
            add_filter('wp_new_user_notification_email_admin', '__return_false');
        }

        // Clean up password reset email, there are some weird brackets in there that mess up when HTML.
        add_filter('retrieve_password_message', function( $message ) {
            $message = str_replace('<','',$message);
            $message = str_replace('>','',$message);
            return $message;
        }, 10, 1 );

        // Clean up new user email, there are some weird brackets in there that mess up when HTML.
        add_filter('wp_new_user_notification_email', function( $email, $user, $blogname ) {

            $email['message'] = str_replace('<', '', $email['message']);
            $email['message'] = str_replace('>', '', $email['message']);

            $email['message'] = str_replace(wp_login_url().'?', '__XXX__', $email['message']);
            $email['message'] = str_replace(wp_login_url(), '', $email['message']);
            $email['message'] = str_replace('__XXX__', wp_login_url().'?', $email['message']);

            return $email;

        }, 10, 3 );

        // Skin outgoing email.
        add_filter('wp_mail', function($email) {

            // Remove site name from subject line of all outgoing emails.
            // https://wordpress.stackexchange.com/a/116287/8642        
            $blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);
            $email['subject'] = str_replace("[".$blogname."] - ", "", $email['subject']);    
            $email['subject'] = str_replace("[".$blogname."]", "", $email['subject']);

            // Wrap all outgoing email from Wordpress.
            if (CONFIG('email/skin')) {

                $header_output      = '';
                $header_file        = get_template_directory() . '/_includes/' . CONFIG('email/skin/header') .'.php';
                $footer_output      = '';
                $footer_file        = get_template_directory() . '/_includes/' . CONFIG('email/skin/footer') .'.php';

                if (file_exists($header_file)) {

                    ob_start();
                    include($header_file);
                    $header_output = ob_get_contents();
                    ob_end_clean();

                }

                if (file_exists($footer_file)) {

                    ob_start();
                    include($footer_file);
                    $footer_output = ob_get_contents();
                    ob_end_clean();

                }

                // Force all links (<a href="...">) in the email body to have the following color.
                if (($link_color = CONFIG('email/skin/link_color'))) {

                    $email['message'] = preg_replace('@style="(.*)"@U', '', $email['message']);
                    $email['message'] = preg_replace('@<a(.*)>@U', '<a$1 style="color: '.$link_color.';">', $email['message']);

                }

                $email['message'] = $header_output  . $email['message'] . $footer_output;

            }

            return $email;

        }, 1000);


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
            return CONFIG('render/image/srcset_max');
        }, 10 , 2 );

    }

    // ------------------------------------------------------------

    protected function setCommentStatus() {

        if (CONFIG('dashboard/comments')) return;

        // Remove comments support.
        add_action('init', function() {
            remove_post_type_support('post', 'comments');
            remove_post_type_support('page', 'comments');
        }, 100);

    }


    // ------------------------------------------------------------

    protected function autoLoadCustomTypes() {

        if (!CONFIG('custom_types/autoload')) return;

        require_all_files(FRAMEWORK_DIR . '/' . CONFIG('custom_types/directory').'/');

    }   

    protected function toggleSupports() {

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



    // ------------------------------------------------------------
    // Build HTML attributes from an array.
    // https://stackoverflow.com/a/34063755/107763       

    // public function getAttributes($array=array()) {     

    //     $array = array_merge($array, $this->_attr);

    //     return implode(' ', array_map(
    //         function ($k, $v) { return $k .'="'. htmlspecialchars($v) .'"'; },
    //         array_keys($array), $array));
        
    // }


}