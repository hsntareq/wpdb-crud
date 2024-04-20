<?php
/**
 * Plugin Name: WPDB CRUD
 * Plugin URI: https://example.com/custom-database-plugin
 * Description: A plugin to demonstrate WordPress database operations using OOP approach.
 * Version: 1.0.0
 * Author: Your Name
 * Author URI: https://example.com
 * License: GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: wpdb-crud
 * Domain Path: /languages
 * Requires at least: 5.2
 * Requires PHP: 7.0
 *
 * @package wordpress-plugin
 * @since 1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! defined( 'WC_PLUGIN_FILE' ) ) {
	define( 'WC_PLUGIN_FILE', __FILE__ );
}

if ( file_exists( __DIR__ . '/vendor/autoload.php' ) ) {
	require_once __DIR__ . '/vendor/autoload.php';
}

WpdbCrud\PluginMain::get_instance();
