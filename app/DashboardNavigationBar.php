<?php 

namespace HelloFramework;

class DashboardNavigationBar extends Singleton {

    public $_action_priority   = 9999;
    public $_menu_priority     = 10;

    public $_relocate_slugs    = array();
    public $_menus             = array();


    // ------------------------------------------------------------
    // Remove desingnated items from the Top Navigation Bar.

    public function remove($slugs=array()) {

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

    public function relocate($slugs=array()) {

        // Not told to move anything.
        if (!$slugs || !count($slugs)) return false;

        // Store the slugs to use later.
        $this->_relocate_slugs = $slugs;

        // Find which sidebar menus we are looking for, store it, then remove them.
        add_action('admin_menu', array($this, 'loadMainMenus'), $this->_action_priority);

        // Take stored menus and add them to the top bar, then add any of their sub-pages.
        add_action('admin_bar_menu', array($this, 'addTopMenus'), $this->_action_priority);

        // Add CSS for better text colors and position the icon correctly. 
        add_action('admin_head', array($this, 'addCSS'), $this->_action_priority);

    }

    public function loadMainMenus() {

        global $menu; 
        global $menu_top;

        foreach ($menu as $key => $value) {
            if (in_array($value[2], $this->_relocate_slugs)) {
                // Store this top level menu to be added later.
                $this->_menus[$value[2]] = $value;
                // Remove it from the sidebar.
                unset($key);
                remove_menu_page($value[2]);
            }

        }

    }

    public function addTopMenus() {

        // Add spacer before the new items.
        // $this->_addTopSpacer();

        foreach ($this->_relocate_slugs as $slug) {
            if (array_key_exists($slug, $this->_menus)) {
                $this->_addTopMenu($this->_menus[$slug]);
            }
        }

    }

    private function _addTopMenu($item) {

        global $wp_admin_bar;
        global $submenu;

        // Make sure we have submenus for this, there won't be if the user is not allowed to use this.
        if (!array_key_exists($item[2], $submenu)) return false;

        $title  = $item[0];
        $slug   = $item[2];
        $id     = 'top-' . $item[5];
        $icon   = $item[6];

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

    private function _addTopSpacer() {

        global $wp_admin_bar;

        $wp_admin_bar->add_menu(array(
            'id'    => 'top-spacer', 
            'title' => '&nbsp;&nbsp;', 
            'href'  => '',
        ));

    }

    public function addCSS() {
        echo '<style>
            #wpadminbar>#wp-toolbar .custom_top_menu .ab-item SPAN.ab-icon:before, #wpadminbar>#wp-toolbar .custom_top_menu .ab-item SPAN.ab-label  { color: rgba(255,255,255,0.5) !important; }
            #wpadminbar>#wp-toolbar .custom_top_menu:hover .ab-item SPAN.ab-icon:before, #wpadminbar>#wp-toolbar .custom_top_menu:hover .ab-item SPAN.ab-label  { color: #fff !important; }
            #wpadminbar .custom_top_menu .ab-sub-wrapper ul li a:hover { color: #00a0d2 !important;}
            #wpadminbar .custom_top_menu .ab-icon:before { top: 2px;}
        </style>';
    }


}