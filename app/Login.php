<?php 

namespace HelloFramework;

class Login  {

	public function __construct() {
        
        // Has this been enabled?
        if (!HelloFrameworkConfig('framework/enable/login')) return false;

        // Add custom CSS to the login page.
        if (HelloFrameworkConfig('dashboard/login/css')) {
            add_action( 'login_enqueue_scripts', function() {
                wp_enqueue_style( 'custom_login_css', framework_internal_asset(HelloFrameworkConfig('dashboard/login/css')), false);
            }, 99 );
        }

        // Add ability to force login from visitors to view the website.
        new FrontendAuthentication();

        // Add custom HTML to the footer of login page.
        if (HelloFrameworkConfig('dashboard/footer_credit')) {
            add_action('login_footer', function() {
                echo HelloFrameworkConfig('dashboard/footer_credit');
            });
        }

        // Misc login stuff.
        add_filter( 'login_headerurl', function() { return home_url(); });

    }

}