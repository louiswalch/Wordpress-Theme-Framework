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

        $this->_toggleUsernameRest();

    }


    // ---------------------------------------------------------------------------
    // Restrict password reset to email only:

    private function _toggleUsernameRest() {

        if (HelloFrameworkConfig('login/reset/allow_username')) return

        add_filter('lostpassword_post', function($errors) {
            if (empty($_POST['user_login'])) return $errors;
            $login = trim((string) $_POST['user_login']);
            if (!is_email($login)) {
                $errors->add('invalidcombo', __('Please enter your email address.'));
                return $errors;
            }
            return $errors;
        }, 10, 1);

        add_filter('gettext', function($translated, $text, $domain) {
            if ($text === 'Username or Email Address') {
                return 'Email Address';
            }
            if ($text === 'Please enter your username or email address. You will receive an email message with instructions on how to reset your password.') {
                return 'Please enter your email address. You will receive an email message with instructions on how to reset your password.';
            }
            return $translated;
        }, 10, 3);

    }

}