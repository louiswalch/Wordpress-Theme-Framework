<?php

// ---------------------------------------------------------------------------
// DEFAULT CONFIGURATION
// Any value here can be customized/set within your theme's `_framework/config.php` file.

CONFIG()->set([


    // - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    // Image Settings

    // Should thumbnail support be enabled for this theme?
    'image/enabled'                     => true,

    // Add custom sizes.
    'image/sizes'                       => ['400', '600', '800', '1200', '1600', '2400'],
    // 'image/sizes'                    => [ [400, 400, false], [600, 600, false], [800, 800, false], [1200, 1200, false], [1600, 1600, false], [2400, 2400, false], [400, 400, false], [400, 400, false] ],

    // JPG quality level for generated images.
    'image/jpg_quality'                 => 90,

    // Set the default thumbnail dimensions and crop setting.
    'image/thumbnail/w'                 => 170,
    'image/thumbnail/h'                 => 140,
    'image/thumbnail/crop'              => true,

    // Remove specified default Wordpress image sizes.
    'image/remove_default'              => ['medium', 'medium_large', 'large'],

    // Max width for responsive srcset images.
    'image/srcset_max'                  => 2400,


    // - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    // Frontend Settings

    // Add theme CSS and JS files. 
    'frontend/assets/version'           => false,
    'frontend/assets/css'               => [],
    'frontend/assets/css_print'         => [],
    'frontend/assets/js'                => [],

    // Add custom query parameters.
    'frontend/query_params'             => [],

    // Show the Wordpress Admin Bar to logged in users?
    'frontend/hide_admin_bar'           => true,


    // - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    // Dashboard : Login Settings

    'dashboard/login/css'               => '',

    // - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    // Dashboard : General Settings

    // Add custom CSS to dashboard.
    'dashboard/footer_credit'           => '♥',

    // Add custom CSS to dashboard.
    'dashboard/css'                     => '',

    // Disable some Wordpress functionality.
    'dashboard/tags'                    => false,
    'dashboard/menus'                   => false,
    'dashboard/widgets'                 => false,
    'dashboard/customizer'              => false,

    // Block pages/posts from being deleted.
    'dashboard/delete_lock'             => [],

    // Block pages/posts from being deleted.
    'dashboard/remove_roles'            => ['subscriber', 'contributor'],

    // Display 'Howdy' from the top bar?
    'dashboard/bar/howdy'               => false,

    // Remove items from the top bar.
    'dashboard/bar/remove'              => [],

    // Relocate items from the sidebar into top bar.
    'dashboard/bar/relocate'            => [],

    // - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    // Dashboard : Metabox Settings

    // Cleanup/remove metaboxes from the Dashboard view.
    'dashboard/metabox/remove/normal'   => [
        'dashboard_right_now',          // Right Now Widget
        'dashboard_activity',           // Activity Widget
        'dashboard_recent_comments',    // Comments Widget
        'dashboard_incoming_links',     // Incoming Links Widget
        'dashboard_plugins',            // Plugins Widget
        'yoast_db_widget',              // Yoast's SEO Plugin Widget
        'rg_forms_dashboard',           // Gravity Forms Plugin Widget
        'bbp-dashboard-right-now',      // bbPress Plugin Widget
    ],
    'dashboard/metabox/remove/side'     => [
        'dashboard_quick_press',        // Quick Press Widget
        'dashboard_recent_drafts',      // Recent Drafts Widget
        'dashboard_primary',            //
        'dashboard_secondary',          //
    ],

    // Add custom metabox, loading HTML from theme.
    'dashboard/metabox'                 => [], 


    // - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    // Dashboard : Content Editor Settings

    'dashboard/editor/customize'        => false,
    'dashboard/editor/css'              => '',
    'dashboard/editor/height'           => 200,
    'dashboard/editor/resize'           => true,
    'dashboard/editor/media_buttons'    => false,
    'dashboard/editor/buttons_1'        => [],
    'dashboard/editor/buttons_2'        => [],
    'dashboard/editor/formats'          => '',

    // Buttons 1:
    // 'formatselect','bold','italic','bullist','numlist','blockquote','alignleft','aligncenter','alignright','link','unlink','wp_more','spellchecker','dfw','wp_adv'

    // Buttons 2:    
    // 'strikethrough','hr','forecolor','pastetext','removeformat','charmap','outdent','indent','undo','redo','wp_hel'


    // - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    // Email Settings

    // Outgoing mail settings.
    'email/name'                        => get_option('blogname'),
    'email/address'                     => 'noreply@' . $_SERVER['HTTP_HOST'],
    'email/mime'                        => 'text/html',

    // Send 'New User' notifications?
    'email/change_password'             => false,

    // Send 'Notice of Password Change' email?
    'email/new_user'                    => false,


    // - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    // Misc Settings

    'comments'                          => false,
    'posts'                             => true,



]);
