<?php
/*
 * Wordpress Theme Framework
 *
 * @package   NOT_YET
 * @link      https://github.com/louiswalch/Wordpress-Theme-Framework
 * @author    Louis D Walch <me@louiswalch.com>
 * @copyright 2018 Louis D Walch
 * @license   GPL v2 or later
 *
 * Plugin Name:  Theme Framework
 * Description:  Collection of resources to aid in theme development, clean up the default Frontend output and streamline the Dashboard. 
 * Version:      0.1
 * Plugin URI:   http://www.louis.nyc
 * Author:       Louis D Walch
 * Author URI:   http://www.louis.nyc
 * Requires PHP: 5.3
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 */

namespace HelloFramework;

// Confirm we're inside of Wordpress world.
if (!defined('ABSPATH')) exit;

// Store reference to plugin location.
define('HELLO_DIR', __DIR__);
define('HELLO_FILE', __FILE__);

// Store reference to theme framework customizations
define('FRAMEWORK_DIR', get_template_directory() . '/_framework');

// Confirm we're not running super old PHP.
if (version_compare(PHP_VERSION, '5.3', '<')) {
    add_action( 'admin_notices', function() {
        echo '<div class="error"><p>' . __( 'This Framework requires PHP 5.3 (or higher) to function properly. Please upgrade PHP. The Plugin has been auto-deactivated.', 'hello' ) . '</p></div>';
        if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );
    });
    add_action( 'admin_init', function() {
        deactivate_plugins( plugin_basename( HELLO_FILE ) );
    });
    return;
}

// Framework classes, eventually add autoloading.
require_once HELLO_DIR . '/app/Singleton.php';
require_once HELLO_DIR . '/app/Config.php';
require_once HELLO_DIR . '/app/ConfigInstance.php';
require_once HELLO_DIR . '/app/Framework.php';
require_once HELLO_DIR . '/app/Login.php';
require_once HELLO_DIR . '/app/Dashboard.php';
require_once HELLO_DIR . '/app/DashboardNavigationBar.php';
require_once HELLO_DIR . '/app/Frontend.php';
require_once HELLO_DIR . '/app/FrontendAssets.php';

// Public misc custom output and functionality.
require_once HELLO_DIR . '/public/output.php';
require_once HELLO_DIR . '/public/functionality.php';

// Ok, let's get going!
new Framework;
