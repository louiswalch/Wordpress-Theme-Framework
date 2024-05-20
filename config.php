<?php

// ---------------------------------------------------------------------------
// DEFAULT CONFIGURATION
// Any value here can be customized/set within your theme's `_framework/config.php` file.

HelloFrameworkConfig()->set([

    // - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    // Framework Zone/Componants
    // Control what parts of the Framework are active for this site.

    'framework/enable/login'                        => true,
    'framework/enable/email'                        => true,
    'framework/enable/dashboard'                    => true,
    'framework/enable/frontend'                     => true,
    'framework/enable/frontend_assets'              => true,
    // 'framework/enable/frontend_authentication'      => true,


    // - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    // Config Settings

    'config/cache'                                      => false,
    'config/cache/life'                                 => HOUR_IN_SECONDS,
    

    // - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    // Framework Settings

    'framework/framework_dir'                           => '_framework/',
    'framework/includes_dir'                            => '_includes/',
    'framework/modules_dir'                             => '_modules/',
    'framework/assets_dir'                              => 'assets/',


    // - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    // Wordpress Functionality

    'support/author_pages'                              => false,
    'support/categories'                                => true,
    'support/comments'                                  => false,
    'support/customizer'                                => false,
    'support/menus'                                     => true,
    'support/posts'                                     => true,
    'support/tags'                                      => false,
    'support/thumbnails'                                => true,
    'support/widgets'                                   => false,


    // - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    // API Functionality

    'api/users'                                         => false,


    // - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    // Image Settings

    // Enable or disable image functionality.
    'image'                                             => true,

    // Add custom sizes.
    'image/sizes'                                       => ['400', '600', '800', '1200', '1600', '2400'],
    // 'image/sizes'                                    => [ [400, 400, false], [600, 600, false], [800, 800, false], [1200, 1200, false], [1600, 1600, false], [2400, 2400, false] ],

    // JPG quality level for generated images.
    'image/jpg_quality'                                 => 90,

    // Set the default thumbnail dimensions and crop setting.
    'image/thumbnail/w'                                 => 170,
    'image/thumbnail/h'                                 => 140,
    'image/thumbnail/crop'                              => false,

    // Remove specified default Wordpress image sizes.
    'image/remove_default'                              => ['medium', 'medium_large', 'large'],

    // Max width for uploaded images.
    'image/max/w'                                       => 2400,
    

    // - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    // Frontend Settings

    // Add theme CSS and JS files. 
    // Paths relative to `asset/` directory in theme root.
    'frontend/assets/css'                               => [],
    'frontend/assets/css_print'                         => [],
    'frontend/assets/js'                                => [],
    'frontend/assets/js_head'                           => [],
    'frontend/assets/version'                           => false,

    // Remove built-in Wordpress CSS stylesheets
    'frontend/assets/remove_css'                        => ['wp-block-library', 'global-styles', 'classic-theme-styles'],

    // Replace version of jQuery.
    // Path is relative to `asset/` directory in theme root.
    'frontend/assets/js_jquery'                         => '',

    // Add custom query parameters.
    'frontend/query_params'                             => [],

    // Show the Wordpress Admin Bar to logged in users?
    'frontend/admin_bar'                                => false,


    // - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    // Frontend Authentication
    // Require all visitors to login in order to view the site. Authentication is done through standard wp-login.

    // Enable or disable this functionality.
    'frontend/login_required'                           => false,
    // Display message to users on the login screen.
    'frontend/login_required/message'                   => 'Access to this site is currently limited. Please login.',
    // Save yourself a lot of headache and disable for your development environment.
    'frontend/login_required/ignore_development'        => true,
    // You may also want to not require a password on your staging environment.
    'frontend/login_required/ignore_staging'            => false,
    // Which roles are allowed to view the site after logging in? Leave empty for all roles.
    // 'frontend/login_required/allow_roles'               => [],
    // Add in your own logic to determine if this user is allowed to enter without a password.
    // Returning `true` will grant permission, returning 'false' or any other value will be skipped.
    // For examples of using this, check the documentation.
    'frontend/login_required/custom'                    => false,
    'frontend/login_required/custom/allow_callback'     => function(){ return false; },


    // - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    // Frontend Edit Link
    // Display a link to edit current page for logged in users. CSS styling in your theme reccommended.
    'frontend/edit_link'                                => false,
    'frontend/edit_link/text'                           => 'Edit',
    'frontend/edit_link/style'                          => 'position: fixed; top: 0; right: 0;',


    // - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    // Frontend Output Formatting
    
    // WYSIWYG: Filter the output of field to wrap with some HTML/class.
    'frontend/output/wysiwyg/wrap'                      => false,
    'frontend/output/wysiwyg/class'                     => 'wysiwyg',
    'frontend/output/wysiwyg/function'                  => function($content){
        return '<div class="'. HelloFrameworkConfig('frontend/output/wysiwyg/class') .'">' . $content .'</div>';
    },


    // - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    // Image Render Render Library

    // Attribute control for the Image Render library.
    'render/image/default_size'                         => '1600',
    'render/image/default_draggable'                    => false,
    'render/image/default_pinnable'                     => false,
    'render/image/alt_fallback'                         => true,

    // Image caption control for the Image Render library.
    'render/image/default_caption'                      => true,
    'render/image/default_caption_location'             => 'inside', // Two options: inside or outside.
    'render/image/caption_element'                      => 'div',
    'render/image/caption_class'                        => 'caption',
    'render/image/caption_strip_html'                   => true,
    'render/image/caption_placeholder'                  => false,

    // Image description control for the Image Render library.
    'render/image/default_description'                  => false,
    'render/image/default_description_location'         => 'inside', // Two options: inside or outside.
    'render/image/description_element'                  => 'div',
    'render/image/description_class'                    => 'description',

    // Wrapper element control for the Image Render library.
    'render/image/default_wrap'                         => false,
    'render/image/default_wrap_size'                    => '',
    'render/image/default_wrap_autosize'                => false,
    'render/image/default_wrap_class'                   => 'img-wrapper',
    'render/image/default_wrap_fallback'                => true,

    // Wrapper autosize configuration for the Image Render library.
    'render/image/autosize_method'                      => 'padding-bottom',

    // Lazy loading & responsive controls for image output.
    'render/image/default_lazy'                         => true,
    'render/image/default_responsive'                   => true,
    'render/image/lazy_class'                           => 'lazyload lazyload-persist',
    'render/image/lazy_img_srcset'                      => 'data-srcset',
    'render/image/lazy_div_srcset'                      => 'data-bgset',
    'render/image/lazy_sizes'                           => 'data-sizes',


    // - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    // SVG Render Render Library
    'render/svg/svg_inject'                             => false,

    // - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    // Dashboard : Login Settings

    // Add custom CSS to login page.
    // Path relative to `asset/` directory in _framework directory.
    'login/css'                                         => '',

    // - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    // Dashboard : General Settings

    // Add custom CSS to dashboard.
    'dashboard/footer_credit'                           => '♥',

    // Add custom CSS to dashboard.
    // Path relative to `asset/` directory in _framework directory.
    'dashboard/css'                                     => '',

    // Block pages/posts from being deleted. Specify post ID.
    'dashboard/delete_lock'                             => [],

    // Remove some default user roles, we don't need so many.
    'dashboard/remove_roles'                            => [],

    // Display 'Howdy' from the top bar?
    'dashboard/admin_bar/howdy'                         => false,

    // Remove items from the top bar.
    'dashboard/admin_bar/remove'                        => ['wp-logo','archive','updates','new-content'],

    // Relocate items from the sidebar into top bar.
    'dashboard/admin_bar/relocate'                      => ['options-general.php', 'tools.php', 'themes.php', 'plugins.php', 'edit.php?post_type=acf-field-group', 'profile.php', ['wpseo_dashboard', 'dashicons-share']],
    'dashboard/admin_bar/relocate/sub_menu_whitelist'   => ['relevanssi-premium/relevanssi.php', 'redirection.php', 'users-user-role-editor.php'],
    'dashboard/admin_bar/relocate/sub_menu_blacklist'   => ['options-discussion.php', 'site-health.php', 'export-personal-data.php', 'erase-personal-data.php', 'tools.php', 'import.php', 'plugin-editor.php', 'acf-settings-updates', 'wpseo_page_academy', 'wpseo_licenses', 'wpseo_workouts', 'wpseo_redirects', 'wpseo_integrations', 'wpseo_tools', 'wpseo_page_support'],

    // Remove all notices within the back-end
    'dashboard/notices'                                 => true,


    // - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    // Dashboard : Metabox Settings

    // Cleanup/remove metaboxes from the Dashboard view.
    'dashboard/metabox/remove/normal'       => [
        'dashboard_right_now',              // Right Now Widget
        'dashboard_activity',               // Activity Widget
        'dashboard_recent_comments',        // Comments Widget
        'dashboard_incoming_links',         // Incoming Links Widget
        'dashboard_plugins',                // Plugins Widget
        'yoast_db_widget',                  // Yoast's SEO Plugin Widget
        'rg_forms_dashboard',               // Gravity Forms Plugin Widget
        'bbp-dashboard-right-now',          // bbPress Plugin Widget
        'dashboard_site_health',            // Wordpress Site Health
    ],
    'dashboard/metabox/remove/side'         => [
        'dashboard_quick_press',            // Quick Press Widget
        'dashboard_recent_drafts',          // Recent Drafts Widget
        'dashboard_primary',                //
        'dashboard_secondary',              //
    ],

    // Add custom metabox, loading HTML from theme.
    'dashboard/metabox'                     => [], 


    // - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    // Dashboard : Content Editor Settings

    'dashboard/editor/customize'                        => false,
    'dashboard/editor/css'                              => '',
    'dashboard/editor/height'                           => 300,
    'dashboard/editor/resize'                           => true,
    'dashboard/editor/media_buttons'                    => false,
    'dashboard/editor/buttons_1'                        => ['formatselect', 'bold','italic','bullist','numlist','blockquote','alignleft','aligncenter','alignright','link','unlink','pastetext','removeformat'],
    'dashboard/editor/buttons_1_basic'                  => ['bold','italic','bullist','link','unlink'],
    'dashboard/editor/buttons_1_simple'                 => ['bold','italic','link','unlink'],
    'dashboard/editor/buttons_2'                        => [],
    'dashboard/editor/formats'                          => 'Paragraph=p;Heading 1=h1;Heading 2=h2;Heading 3=h3;Heading 4=h4;Heading 5=h5;Heading 6=h6;Preformatted=pre',

    // Buttons 1:
    // 'formatselect','bold','italic','bullist','numlist','blockquote','alignleft','aligncenter','alignright','link','unlink','wp_more','spellchecker','dfw','wp_adv'

    // Buttons 1 Basic:
    // 'bold','italic','underline','blockquote','strikethrough','bullist','numlist','alignleft','aligncenter','alignright','undo','redo','link','fullscreen'

    // Buttons 2:    
    // 'strikethrough','hr','forecolor','pastetext','removeformat','charmap','outdent','indent','undo','redo','wp_hel'


    // - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    // Email Settings

    // Outgoing mail settings.
    'email/name'                                        => get_option('blogname'),
    'email/address'                                     => 'noreply@' . $_SERVER['HTTP_HOST'],
    'email/mime'                                        => 'text/html',

    // Skin outgoing emails by wrapping the message in an HTML header & footer.
    // Values should be defined as paths inside the `_includes` directory.
    'email/skin'                                        => false,
    'email/skin/directory'                              => '_includes/',
    'email/skin/header'                                 => '',
    'email/skin/footer'                                 => '',
    // Force all links (<a href="...">) in the email body to have the following color. Supply hex color value.
    'email/skin/link_color'                             => '', 

    // Send 'Password Change' notifications?
    'email/send/change_password'                        => false,
    // Send 'Email Change' notifications?
    'email/send/change_email'                           => false,
    // Send 'New User' email?
    'email/send/new_user'                               => false,


    // - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    // Environment Detection Settings

    'environment/development'                           => ['.local'],
    'environment/staging'                               => ['staging.'],


    // - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    // Custom Post Types

    'custom_types/autoload'                             => false,
    'custom_types/directory'                            => 'types',


]);
