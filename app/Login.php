<?php 

namespace HelloFramework;

class Login  {

	public function __construct() {

        // Add custom CSS to the login page.
        if (CONFIG('dashboard/login/css')) {
            add_action( 'login_enqueue_scripts', function() {
                wp_enqueue_style( 'custom_login_css', asset(CONFIG('dashboard/login/css')), false);
            }, 10 );
        }

        // Misc login stuff.
        add_filter( 'login_headerurl', function() { return home_url(); });
        add_filter( 'login_headertitle', function() { return get_option( 'blogname' ); });

    }

}