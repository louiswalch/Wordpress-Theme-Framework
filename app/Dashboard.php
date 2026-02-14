<?php 

namespace HelloFramework;

class Dashboard  {

	public function __construct() {
        
        // Has this been enabled?
        if (!HelloFrameworkConfig('framework/enable/dashboard')) return false;

        // Render helper classes, must be called from global instance functions:
        require_once HELLO_DIR . '/public/render/DataStore.php';
        require_once HELLO_DIR . '/public/render/DataStoreInstance.php';

        // Remove desingnated items from the Top Navigation Bar.
        if (HelloFrameworkConfig('dashboard/admin_bar/remove')) {
            DashboardNavigationBar::get_instance()->removeTopMenus(HelloFrameworkConfig('dashboard/admin_bar/remove'));
        }

        // Move designited items from the Sidebar to Top Navigation Bar.
        if (HelloFrameworkConfig('dashboard/admin_bar/relocate')) {
            DashboardNavigationBar::get_instance()->relocateSideMenus(HelloFrameworkConfig('dashboard/admin_bar/relocate'));
        }

        // Custom Admin Dashboard CSS
        if (HelloFrameworkConfig('dashboard/css')) {
            add_action('admin_enqueue_scripts', function() {
                wp_enqueue_style('custom-admin-css', framework_internal_asset(HelloFrameworkConfig('dashboard/css')));
            }, 99);
        }

        // Remove 'Howdy' from the Admin Top Bar.
        // add_filter('admin_bar_menu', function($wp_admin_bar) {
        //     $my_account = $wp_admin_bar->get_node('my-account');
        //     if ($my_account) {
        //         $new_title = str_replace('Howdy,', '', $my_account->title);
        //         $wp_admin_bar->add_node([
        //             'id'    => 'my-account',
        //             'title' => $new_title,
        //         ]);
        //     }
        // }, 25);

        $this->_toggleUserRoles();

        $this->_restrictDelete();

        $this->_toggleEmoji();

        $this->_toggleCommentsAdmin();

        $this->_toggleTags();

        $this->_toggleCustomizer();

        $this->_togglePosts();

        $this->_toggleNotices();

        $this->_toggleMetaboxes();
 
        $this->_addMetaboxes();

        $this->_addFooterCredit();

        $this->_cleanUserEdit();

        $this->_setContentEditor();

        // Remove Gutenberg panel on Dashboard.
        remove_action( 'try_gutenberg_panel', 'wp_try_gutenberg_panel' );
        add_filter('use_block_editor_for_post_type', '__return_false');


    }


    // ------------------------------------------------------------
    // Block certain pages from being deleted

    private function _restrictDelete(){

        if (!HelloFrameworkConfig('dashboard/delete_lock')) return;

        add_action('wp_trash_post', [$this, 'restrictDeleteCallback'], 10, 1);
        add_action('before_delete_post', [$this, 'restrictDeleteCallback'], 10, 1);

    }

    public function restrictDeleteCallback($post_id){

        $restricted = HelloFrameworkConfig('dashboard/delete_lock');
        if (in_array($post_id, $restricted)) {
            exit("You are not authorized to delete this page/item.");
        }

    }


    // ------------------------------------------------------------
    // Remove WP Emoji

    private function _toggleEmoji() {
        remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
        remove_action( 'admin_print_styles', 'print_emoji_styles' );        
    }


    // ------------------------------------------------------------
    // Remove Comment functionality.

    private function _toggleCommentsAdmin() {

        if (HelloFrameworkConfig('support/comments')) return false;

        add_action('admin_init', function () {
 
            // Remove comments metabox from dashboard
            remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal');

        });

        // Remove comments page in menu
        add_action('admin_menu', function () {
            remove_menu_page('edit-comments.php');
            remove_submenu_page('options-general.php', 'options-discussion.php');
        });

        // Remove comments links from admin bar
        add_action('init', function () {
            if (is_admin_bar_showing()) {
                remove_action('admin_bar_menu', 'wp_admin_bar_comments_menu', 60);
            }
        });

        // Remove comments icon from admin bar
        add_action('wp_before_admin_bar_render', function() {
            global $wp_admin_bar;
            $wp_admin_bar->remove_menu('comments');
        });

        /*

        // Remove comment column from WP-Admin list pages.    
        add_filter('manage_pages_columns', function($defaults) {
            unset($defaults['comments']);
            return $defaults;
        });
        add_filter('manage_posts_columns', function($defaults) {
            unset($defaults['comments']);
            return $defaults;
        });

        // Remove metabox from edit post page.
        add_action( 'admin_init', function() {
            remove_meta_box( 'commentsdiv', 'post', 'normal' );
            remove_meta_box( 'commentsdiv', 'page', 'normal' );
            remove_meta_box( 'commentstatusdiv', 'post', 'normal' );
            remove_meta_box( 'commentstatusdiv', 'page', 'normal' );
            remove_meta_box( 'trackbacksdiv', 'post', 'normal' );
            remove_meta_box( 'trackbacksdiv', 'page', 'normal' );
        });

        */

    }


    // ------------------------------------------------------------
    // Turn off the 'tags' functionality from Admin & Remove tags column from WP-Admin list pages.

    private function _toggleTags() {

        if (HelloFrameworkConfig('support/tags')) return;

        add_action('admin_menu', function() {
            remove_submenu_page('edit.php', 'edit-tags.php?taxonomy=post_tag');
            remove_meta_box( 'tagsdiv-post_tag','post','normal' ); 
        });
        add_filter('manage_pages_columns', function($defaults) {
            unset($defaults['tags']);
            return $defaults;
        });
        add_filter('manage_posts_columns', function($defaults) {
            unset($defaults['tags']);
            return $defaults;
        });

    }


    // ------------------------------------------------------------
    // Remove the "Customize" page.
    // https://github.com/parallelus/customizer-remove-all-parts/blob/master/wp-crap.php

    private function _toggleCustomizer() {

        if (HelloFrameworkConfig('support/customizer')) return;

        add_action( 'init', function() {

            add_filter( 'map_meta_cap', function($caps = array(), $cap = '', $user_id = 0, $args = array()) {
                if ($cap == 'customize') {
                    return array('nope');
                }
                return $caps;      
            }, 10, 4 );

        }, 10 );

        add_action( 'admin_init', function() {

            remove_action( 'plugins_loaded', '_wp_customize_include', 10);
            remove_action( 'admin_enqueue_scripts', '_wp_customize_loader_settings', 11);

            add_action( 'load-customize.php', function() {
                wp_die('The Customizer is currently disabled.');
            });

        }, 10 );

    }


    // ------------------------------------------------------------
    // Remove unneeded user roles.
    // TODO: This really only needs to happen once, like on plugin activation.

    private function _toggleUserRoles() {

        if (!HelloFrameworkConfig('dashboard/remove_roles')) return;

        foreach (HelloFrameworkConfig('dashboard/remove_roles') as $role) {
            if (get_role($role)) remove_role($role);
        }    

    }


    // ------------------------------------------------------------
    // Disable posts.

    private function _togglePosts(){

        if (HelloFrameworkConfig('support/posts')) return;

        add_action('admin_menu', function() {
            remove_menu_page('edit.php');
        });

    }


    // ------------------------------------------------------------
    // Remove all notices within the back-end:

    private function _toggleNotices() {

        if (HelloFrameworkConfig('dashboard/notices') === true) return;

        add_action('admin_head', function() {
            remove_all_actions('admin_notices');
        }, 1);

    }    


    // ------------------------------------------------------------
    // Remove uneeded metaboxes from the dashboard. 

    private function _toggleMetaboxes() {

        if (!HelloFrameworkConfig('dashboard/metabox/remove/normal') && !HelloFrameworkConfig('dashboard/metabox/remove/side')) return;

        add_action( 'wp_dashboard_setup', function() {

            global $wp_meta_boxes;

            foreach (HelloFrameworkConfig('dashboard/metabox/remove/normal') as $box) {
                unset($wp_meta_boxes['dashboard']['normal']['core'][$box]);
            }
            foreach (HelloFrameworkConfig('dashboard/metabox/remove/side') as $box) {
                unset($wp_meta_boxes['dashboard']['side']['core'][$box]);
            }

        });

        // Hide "Drag boxes here" on dashboard. 
        add_action('admin_footer', function() {
            echo '<style type="text/css">#dashboard-widgets .meta-box-sortables.ui-sortable.empty-container { display: none !important; }</style>';
        });


    }


    // ------------------------------------------------------------
    // Add Custom Metaboxes

    private function _addMetaboxes() {

        if (!HelloFrameworkConfig('dashboard/metabox')) return;

        add_action('wp_dashboard_setup', function() {

            global $wp_meta_boxes;

            foreach (HelloFrameworkConfig('dashboard/metabox') as $key => $box) {

                $id         = 'custom_' . $key;
                $name       = $box[0];
                $path       = FRAMEWORK_ROOT .'/metabox/' . $box[1];
                $context    = (!empty($box[2])) ? $box[2] : 'normal';
                $priority   = (!empty($box[3])) ? $box[3] : 'default';

                if (!file_exists($path))  continue;

                add_meta_box($id, $name, [$this, 'addMetaboxesCallback'], 'dashboard', $context, $priority, ['path'=>$path]);

            }

        });

    }

    public function addMetaboxesCallback($object, $args) {
        if (empty($args['args']['path'])) return;
        echo file_get_contents($args['args']['path']);
    }


    // ------------------------------------------------------------
    // Add custom footer credit.

    private function _addFooterCredit() {

        if (!HelloFrameworkConfig('dashboard/footer_credit')) return;

        add_action( 'admin_init', function() {
            remove_filter('admin_footer_text', 'fau_swap_footer_admin');
            add_filter( 'admin_footer_text', function() {
                return HelloFrameworkConfig('dashboard/footer_credit');
            }, 11 );
        });

    }


    // ------------------------------------------------------------
    // Clean up the 'User Edit' screen in Dashboard.
    
    public function _cleanUserEdit() {

        global $pagenow;

        // Remove Color Scheme.
        remove_action( 'admin_color_scheme_picker', 'admin_color_scheme_picker' );

        if (($pagenow == 'user-edit.php' || $pagenow == 'profile.php')) {

            // Remove unnesscary fields.
            add_action('admin_head', function($output) {
                ob_start([$this, 'cleanUserEditCallback']);
            });
            add_action('admin_footer', function() {
                ob_end_flush();
            });

        }

    }

    private function cleanUserEditCallback($output) {

        // Remove the "Personal Options" title
        $output = preg_replace( '#<h2>Personal Options</h2>.+?/table>#s', '', $output, 1 );
        // // Remove the "Name" title
        // $output = preg_replace('#<h2>' . __("Name") . '</h2>#s', '', $output, 1); 
        // // Remove the "Contact Info" title
        // $output = preg_replace('#<h2>' . __("Contact Info") . '</h2>#s', '', $output, 1); 
        // // Remove the "Visual Editor" field
        // $output = preg_replace('#<tr class="user-rich-editing-wrap(.*?)</tr>#s', '', $output, 1);
        // // Remove the "Keyboard Shortcuts"
        // $output = preg_replace('#<tr class="user-comment-shortcuts-wrap(.*?)</tr>#s', '', $output, 1); 
        // // Remove the "Website" field
        // $output = preg_replace('#<tr class="user-url-wrap(.*?)</tr>#s', '', $output, 1);

        // Remove the "About Yourself" title
        $output = preg_replace( '#<h2>About Yourself</h2>.+?/table>#s', '', $output, 1 );
        // Remove the "About the users" title
        $output = preg_replace( '#<h2>About the user</h2>.+?/table>#s', '', $output, 1 );
        // Remove the "Profile Picture" field
        $output = preg_replace('#<tr class="user-profile-picture(.*?)</tr>#s', '', $output, 1);
        // Remove the "Biographical Info" field
        $output = preg_replace('#<tr class="user-description-wrap(.*?)</tr>#s', '', $output, 1);

        return $output;

    }


    // ------------------------------------------------------------
    // Content Editor Settings

    private function _setContentEditor() {

        if (!HelloFrameworkConfig('dashboard/editor/customize')) return;


        if (HelloFrameworkConfig('dashboard/editor/css')) {
            add_action( 'admin_init', function() {
                add_editor_style(framework_internal_asset(HelloFrameworkConfig('dashboard/editor/css')));
            });
        }


        // Basic Content Editor settings.
        add_filter('tiny_mce_before_init', function($settings) {
            $settings['wp_autoresize_on']        = HelloFrameworkConfig('dashboard/editor/resize');
            $settings['resize']                  = HelloFrameworkConfig('dashboard/editor/resize');
            $settings['statusbar']               = true;
            return $settings; 
        }, 10);


        // Forcefully hide all the 'Add Media' buttons for Content Editor.
        if (!HelloFrameworkConfig('dashboard/editor/media_buttons')) {
            add_action('admin_head', function(){
                remove_action( 'media_buttons', 'media_buttons' );
            });
        }
        

        // Cut down the first row of editor icons to just what we need.
        add_filter('mce_buttons', function($buttons, $editor_id) {
            return HelloFrameworkConfig('dashboard/editor/buttons_1') ?: [];
        }, 10, 2 );


        // Also cut down the second row of editor icons to just what we need.
        add_filter('mce_buttons_2', function($buttons, $editor_id) {
            return HelloFrameworkConfig('dashboard/editor/buttons_2') ?: [];
        }, 10, 2 );


        // Change the options available in format dropdown.
        add_filter( 'tiny_mce_before_init', function($init) {  
            $editor_formats = HelloFrameworkConfig('dashboard/editor/formats');
            if (is_array($editor_formats)) {
                // https://wordpress.stackexchange.com/a/128950/8642
                // Make sure you are using the 'styleselect' button rather then 'formatselect'.
                $init['style_formats'] = json_encode(HelloFrameworkConfig('dashboard/editor/formats'));  
                unset($init['preview_styles']);
            } else {
                $init['block_formats'] = $editor_formats;
            }
            return $init;  
        });   


        // Allow control over the "Basic" set of buttons added by ACF, and add a third "Very Simple" set.
        add_filter('acf/fields/wysiwyg/toolbars', function($toolbars) {
            if (HelloFrameworkConfig('dashboard/editor/buttons_1_basic')) {
                if (isset($toolbars['Basic'])) {
                    $toolbars['Basic'][1] = HelloFrameworkConfig('dashboard/editor/buttons_1_basic');
                }
            }
            if (HelloFrameworkConfig('dashboard/editor/buttons_1_simple')) {
                $toolbars['Very Simple'] = array();
                $toolbars['Very Simple'][1] = HelloFrameworkConfig('dashboard/editor/buttons_1_simple');
            }
            return $toolbars;
        });
    

        // Removing the Media Button causes Content Editor to appear too close to the permalink.
        if (!HelloFrameworkConfig('dashboard/editor/media_buttons')) {
            add_action('admin_head', function() {
                echo '<style>#postdivrich { padding-top: 25px; padding-bottom: 20px; }; </style>';
            });            
        }

        
        // Allow custom height to be set on WYSIWYG field
        // https://gist.github.com/stianandreassen/6dc87c88c43b2bc43d0ea1a94bd5cd1e
        add_action('acf/render_field_settings/type=wysiwyg', function($field){
            acf_render_field_setting($field, [
                'label'         => __('Height', 'acf'),
                'instructions'  => __('', 'acf'),
                'name'          => 'height',
                'type'          => 'number',
                'min'           => 25,
                'max'           => 600,
                'default_value' => HelloFrameworkConfig('dashboard/editor/height'),
                'prepend'       => 'px',
            ]);
        }, 10, 1);
        add_action('acf/render_field/type=wysiwyg', function ($field) {
            if (isset($field['height'])) {
                $height = $field['height'];
                $field_class = '.acf-'.str_replace('_', '-', $field['key']); ?>
                <style type="text/css">
                <?php echo $field_class; ?> iframe {
                    min-height: <?php echo $height; ?>px;
                }
                </style>
                <script type="text/javascript">
                jQuery(window).on('load', function() {
                    jQuery('<?php echo $field_class; ?>').each(function() {
                        setTimeout(() => {
                            jQuery('#'+jQuery(this).find('iframe').attr('id')).height(<?php echo $height; ?>);
                        });
                    });
                });
                </script>
                <?php
            }
        }, 10, 1);


    }


}