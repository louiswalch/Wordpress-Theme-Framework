<?php 

namespace HelloFramework;

class FrontendAuthentication {

	public function __construct() {

        // Has this been enabled?
        if (!CONFIG('frontend/login_required')) return false;

        // I don't think we need to worry about Cron and the Wordpress CLI. 
        if ((defined('DOING_CRON') && DOING_CRON) || (defined('WP_CLI') && WP_CLI))  return false;

        if (detect_zone('login')) {
            // Add message to the Wordpress login screen.
            $this->addMessage();
        } else {
            // Hook into every time Wordpress is preparing to output a page.
            add_action('template_redirect', [$this, 'validate']);
        }

	}

    public function addMessage() {

        if (!CONFIG('frontend/login_required/message')) return;

        add_filter('login_message', function() {
            // Only show this to people logging into the front end, not dashboard.
            if (isset($_REQUEST['redirect_to']) && (!strpos($_REQUEST['redirect_to'],'wp-admin') !== false)) {
                return '<p class="message framework-login-message">' . CONFIG('frontend/login_required/message') . '</p>';
            }
        });

    }

    public function validate() {

        // If you are already viewing the login page or this page has a post password assigned, we're not worried about you.
        if (detect_environment('login') || !empty($post->post_password)) {
            return $this->_allowed();
        }

        // Some environments can be set up to bypass this requirement all together.
        if (detect_environment('development') && CONFIG('frontend/login_required/ignore_development')) {
            return $this->_allowed();
        }

        if (detect_environment('staging') && CONFIG('frontend/login_required/ignore_staging')) {
            return $this->_allowed();
        }

        // Custom user-supplied logic to determine if the user should be allowed.
        // Returning `true` will grant permission and false will continue with the validation checks.
        if (CONFIG('frontend/login_required/custom')) {
            $callback = CONFIG('frontend/login_required/custom/allow_callback');
            if (is_callable($callback) && ((bool) $callback() === true)) {
                return $this->_allowed();
            }            
        }

        // If the user is logged in, we still need to check against allowed roles.
        // if (is_user_logged_in() && count(CONFIG('frontend/login_required/allow_roles'))) {
        //     $roles  = CONFIG('frontend/login_required/allow_roles');
        //     $user   = wp_get_current_user();
        //     foreach ($roles as $role) {
        //         if (current_user_can($role)) {
        //             return $this->_allowed();
        //         }
        //     }
        // }

        // If no role restriction is in place, all logged in visitors are allowed.
        if (is_user_logged_in()) {
            return $this->_allowed();
        }

        // Alright, that's all we have to check. Guess they need to login.
        return $this->_blocked('Login Required');

    }

    private function _allowed() {

        // All good, nothing to do here.
        return true;

    }

    private function _blocked($reason) {

        // No-Cache headers for browser to avoid any problems.
        nocache_headers();

        // Require login to view the website.
        wp_safe_redirect( wp_login_url( get_permalink() ), 302 );

        exit();
        
    }

}