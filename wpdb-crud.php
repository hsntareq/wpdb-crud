<?php
/**
 * Plugin Name: WPDB CRUD
 * Plugin URI: https://example.com/custom-database-plugin
 * Description: A plugin to demonstrate WordPress database operations using OOP approach.
 * Version: 1.0
 * Author: Your Name
 * Author URI: https://example.com
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

// Debug pring function.
if ( ! function_exists( 'pr' ) ) {
	function pr( $data ) {
		echo '<pre>';
		print_r( $data );
		echo '</pre>';
	}
}

// Debug dump function.
if ( ! function_exists( 'vd' ) ) {
	function vd( $data ) {
		echo '<pre>';
		print_r( $data );
		echo '</pre>';
	}
}
