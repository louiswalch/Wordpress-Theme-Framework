<?php 

namespace HelloFramework;

class FrameworkEmail {

	public function __construct() {

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
            return $this->formatEmail($email);
        }, 1000);

    }


    public function formatEmail($email) {

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
 
    }


}