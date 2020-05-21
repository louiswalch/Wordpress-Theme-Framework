<?php 

namespace HelloFramework;

class DashboardNavigationBar extends Singleton {

    public $_action_priority   = 9999;
    public $_menu_priority     = 10;

    public $_config_relocate   = array();
    public $_wordpress_menu    = array();


    // ------------------------------------------------------------
    // Remove desingnated items from the Top Navigation Bar.

    public function removeTopMenus($slugs=array()) {

        // Not told to remove anything.
        if (!$slugs || !count($slugs)) return false;

        add_action('wp_before_admin_bar_render', function() {
            global $wp_admin_bar;
            foreach (CONFIG('dashboard/admin_bar/remove') as $slug) {
                $wp_admin_bar->remove_menu($slug);
            }        
        }, 999);

    }


    // ------------------------------------------------------------
    // Move designited items from the Sidebar to Top Navigation Bar.

    public function relocateSideMenus($slugs=array()) {

        // Not told to move anything.
        if (!$slugs || !count($slugs)) return false;

        // Store the slugs to use later.
        $this->_config_relocate = $slugs;

        // Fetch and store a reference to the current Wordpress menu for later reference.
        add_action('admin_menu', array($this, 'preloadMainMenus'), $this->_action_priority);

        // Loop over the config items, remove them from the sidebar menu and add them up top.
        add_action('admin_bar_menu', array($this, 'addTopMenus'), $this->_action_priority);

        // Add CSS for better text colors and position the icon correctly. 
        add_action('admin_head', array($this, 'addCSS'), $this->_action_priority);

    }

    public function preloadMainMenus() {

        global $menu; 

        foreach ($menu as $key => $value) {
            $this->_wordpress_menu[$value[2]] = array_merge($value, ['key'=>$key]);
        }

    }

    public function addTopMenus() {

        global $menu;

        // Loop over all the items we need to move and relocate them.
        foreach ($this->_config_relocate as $item) {
            $this->_addTopMenu($item);
        }

    }

    private function _addTopMenu($item) {

        global $wp_admin_bar;
        global $submenu;

        $menu_slug   = (is_array($item)) ? $item[0] : $item;
        $menu_icon   = (is_array($item) && count($item) > 1) ? $item[1] : false;

        // Make sure we have submenus for this, there won't be if the user is not allowed to use this.
        if (!array_key_exists($menu_slug, $submenu)) return false;

        $menu   = $this->_wordpress_menu[$menu_slug];

        $title  = $menu[0];
        $slug   = $menu[2];
        $id     = 'top-' . $menu[5];
        $icon   = $menu_icon ? $menu_icon : $menu[6];

        // Sometimes the names end with a number, like Plugins. 
        $title  = preg_replace('/[0-9]+/', '', $title);

        $wp_admin_bar->add_menu(array(
            'id'        => $id, 
            'title'     => '<span class="ab-icon dashicons '.$icon.'"></span><span class="ab-label">'.$title.'</span>', 
            'href'      => admin_url($slug),
            'meta'      => array(
                'class' => 'custom_top_menu',
            ),
        ), $this->_menu_priority);


        foreach ($submenu[$slug] as $key => $sub) {
            $this->_addTopSubMenus($sub, $key, $id);
        }

        $this->_removeMenuMenu($slug, $menu['key']);

    }

    private function _addTopSubMenus($item, $key, $parent) {

        global $wp_admin_bar;

        $title  = $item[0];
        $id     = $parent.'-'.$key;
        $href   = (strpos($item[2], '.php') && ($item[2] != 'redirection.php')) ? $item[2] : ('admin.php?page='.$item[2]);
        //$href   = 'admin.php?page='.$item[2];
        $href   = admin_url($href);

        $wp_admin_bar->add_menu(array('parent' => $parent, 'title' => $title, 'id' => $id, 'href' => $href ));

    }

    private function _removeMenuMenu($slug, $key) {

        global $menu;

        if (in_array($slug, $this->_wordpress_menu)) {
            // Remove it from the sidebar.
            unset($menu[$key]);
            remove_menu_page($slug);
        }

    }

    // private function _addTopSpacer() {
    //     global $wp_admin_bar;
    //     $wp_admin_bar->add_menu(array(
    //         'id'    => 'top-spacer', 
    //         'title' => '&nbsp;&nbsp;', 
    //         'href'  => '',
    //     ));
    // }

    public function addCSS() {
        echo '<style>
            #wpadminbar>#wp-toolbar .custom_top_menu .ab-item SPAN.ab-icon:before, #wpadminbar>#wp-toolbar .custom_top_menu .ab-item SPAN.ab-label  { color: rgba(255,255,255,0.5) !important; }
            #wpadminbar>#wp-toolbar .custom_top_menu:hover .ab-item SPAN.ab-icon:before, #wpadminbar>#wp-toolbar .custom_top_menu:hover .ab-item SPAN.ab-label  { color: #fff !important; }
            #wpadminbar .custom_top_menu .ab-sub-wrapper ul li a:hover { color: #00a0d2 !important;}
            #wpadminbar .custom_top_menu .ab-icon:before { top: 2px;}
            #wpadminbar .custom_top_menu:hover { opacity: 1; }
            #wpadminbar .custom_top_menu:hover .ab-sub-wrapper { display: block; }
        </style>';
    }


}