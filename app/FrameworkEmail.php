<?php 

namespace HelloFramework;

class FrameworkEmail {

	public function __construct() {

        // Has this been enabled?
        if (!HelloFrameworkConfig('framework/enable/email')) return false;

        // General settings for all outgoing emails.
        add_filter('wp_mail_content_type', function() {
            return HelloFrameworkConfig('email/mime');
        });
        add_filter('wp_mail_from', function($original) {
            return (HelloFrameworkConfig('email/address')) ? HelloFrameworkConfig('email/address') : $original;
        });
        add_filter('wp_mail_from_name', function($original) {
            return HelloFrameworkConfig('email/name');
        });

        // Disable 'Notice of Password Change' email.
        if (!HelloFrameworkConfig('email/send/change_password')) {
            add_filter('send_password_change_email', '__return_false' );
        }
        // Disable 'Notice of Email Change' email.
        if (!HelloFrameworkConfig('email/send/change_email')) {
            add_filter('send_email_change_email', '__return_false' );
        }
        // Disable 'New User' notification sent to Admin.
        if (!HelloFrameworkConfig('email/send/new_user')) {    
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
            return $this->formatEmail($email);
        }, 1, 1000);

    }


    public function formatEmail($email) {

        // Remove site name from subject line of all outgoing emails.
        // https://wordpress.stackexchange.com/a/116287/8642        
        $blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);
        $email['subject'] = str_replace("[".$blogname."] - ", "", $email['subject']);    
        $email['subject'] = str_replace("[".$blogname."]", "", $email['subject']);

        // Wrap all outgoing email from Wordpress.
        if (HelloFrameworkConfig('email/skin')) {

            $header_output      = '';
            $header_file        = get_template_directory() . '/' . HelloFrameworkConfig('email/skin/directory') . HelloFrameworkConfig('email/skin/header') .'.php';
            $footer_output      = '';
            $footer_file        = get_template_directory() . '/' . HelloFrameworkConfig('email/skin/directory') . HelloFrameworkConfig('email/skin/footer') .'.php';

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
            if (($link_color = HelloFrameworkConfig('email/skin/link_color'))) {

                // No style: Adds color
                // Yes style: 2 attributes
                $email['message'] = preg_replace('@<a(.*)>@U', '<a$1 style="color: '.$link_color.';">', $email['message']);

            }

            $email['message'] = $header_output  . $email['message'] . $footer_output;


        }

        return $email;
 
    }


}