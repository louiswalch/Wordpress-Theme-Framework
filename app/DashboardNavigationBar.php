<?php 

namespace HelloFramework;

class DashboardNavigationBar extends Singleton {

    public $_action_priority        = 9999;
    public $_menu_priority          = 10;

    public $_relocate               = array();

    private $_submenu_whitelist     = array();
    private $_submenu_blacklist     = array();

    private $_icon_replace          = array(
        'wpseo_dashboard'      => 'dashicons-rest-api',
        );

    // ------------------------------------------------------------
    // Remove desingnated items from the Top Navigation Bar.

    public function removeTopMenus($slugs=array()) {

        // Not told to remove anything.
        if (!$slugs || !count($slugs)) return false;

        add_action('wp_before_admin_bar_render', function() {
            global $wp_admin_bar;
            foreach (HelloFrameworkConfig('dashboard/admin_bar/remove') as $slug) {
                $wp_admin_bar->remove_menu($slug);
            }        
        }, 999);

    }


    // ------------------------------------------------------------
    // Move designited items from the Sidebar to Top Navigation Bar.

    public function relocateSideMenus($relocations = array()) {

        // Not told to move anything.
        if (!$relocations || !count($relocations)) return false;

        // Top menu subpage, special url handling.
        $this->_submenu_whitelist = HelloFrameworkConfig('dashboard/admin_bar/relocate/sub_menu_whitelist');
        $this->_submenu_blacklist = HelloFrameworkConfig('dashboard/admin_bar/relocate/sub_menu_blacklist');

        // Prepare a keyed version of the menu relations.
        foreach ($relocations as $item) {
            $key                    = (is_array($item)) ? $item[0] : $item;
            $this->_relocate[$key]  = ((is_array($item)) ? $item[1] : []);
        } 

        // Process sidebar menu, removing those specified and store their configuration.
        add_action('admin_menu', array($this, 'processMainMenus'), $this->_action_priority);

        // Loop over the config items, remove them from the sidebar menu and add them up top.
        add_action('admin_bar_menu', array($this, 'addTopMenus'), $this->_action_priority);

        // Add CSS for better text colors and position the icon correctly. 
        add_action('admin_head', array($this, 'addCSS'), $this->_action_priority);

    }

    public function processMainMenus() {

        global $menu, $submenu; 

        foreach ($menu as $key => $value) {

            $slug = $value[2];

            if (!array_key_exists($slug, $this->_relocate)) continue;

            // Check for custom icon being set from the theme config file. 
            if (is_string($this->_relocate[$slug])) {
                $value[6] = $this->_relocate[$slug];
            }

            // Add all menu values to be used in later action to add.
            $this->_relocate[$slug] = $value;

            // Remove item from side menu.
            unset($menu[$key]);
            remove_menu_page($slug);

        }

    }

    public function addTopMenus() {

        // Loop over all the items we need to move and relocate them.
        foreach ($this->_relocate as $item) {
            $this->_addTopMenu($item);
        }

    }

    private function _addTopMenu($item) {

        global $wp_admin_bar;
        global $submenu;

        if (!is_array($item) || !count($item)) return;

        $title  = $item[0];
        $slug   = $item[2];
        $id     = 'top-' . $item[5];
        $icon   = (array_key_exists($slug, $this->_icon_replace)) ? $this->_icon_replace[$slug] : $item[6];

        // Make sure we have submenus for this, there won't be if the user is not allowed to use this.
        if (!array_key_exists($slug, $submenu)) return false;

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
            $this->_addTopSubMenus($sub, $key, $id, $slug);
        }

    }

    private function _addTopSubMenus($item, $key, $parent_id, $parent_slug) {

        global $wp_admin_bar;

        $title          = $item[0];
        $id             = $parent_id.'-'.$key;
        $path           = $item[2];

        // Remove items from submenu
        if (in_array($path, $this->_submenu_blacklist)) return;

        // Path correction
        if (!strpos($parent_slug, '.php')) $parent_slug = 'admin.php';
        if (in_array($path, $this->_submenu_whitelist) || !strpos($path, '.php')) $path = $parent_slug.'?page='.$path;

        $href           = admin_url($path);

        $wp_admin_bar->add_menu(array('parent' => $parent_id, 'title' => $title, 'id' => $id, 'href' => $href ));

    }


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