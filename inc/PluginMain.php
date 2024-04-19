<?php
/**
 * Plugin File: WPDB CRUD
 * Description: This plugin will show related random posts under each post.
 *
 * @package wp-plugin
 * @since 1.0
 */

namespace WpdbCrud;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * PluginMain Class
 */
final class PluginMain {

	/**
	 * Plugin version
	 *
	 * @var string
	 */
	const PLUGIN_VERSION = '1.0';

	/**
	 * Instance of the PluginMain class
	 *
	 * @var PluginMain|null
	 */
	private static $instance = null;

	/**
	 * Class constructor (private to enforce singleton pattern).
	 *
	 * @return void
	 */
	private function __construct() {
		// All the initialization tasks.
		$this->register_hooks();
	}

	/**
	 * Get the singleton instance of PluginMain.
	 *
	 * @return PluginMain
	 */
	public static function get_instance(): PluginMain {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Register hooks and do other setup tasks.
	 */
	private function register_hooks() {

		register_activation_hook( WC_PLUGIN_FILE, array( $this, 'activate' ) );
		register_deactivation_hook( WC_PLUGIN_FILE, array( $this, 'deactivate' ) );

		add_action( 'plugins_loaded', array( $this, 'init_plugin' ) );
	}

	/**
	 * Initialize classes to the plugin.
	 * This method will run after the plugins_loaded action has been fired.
	 * This is a good place to include files and instantiate classes.
	 * This method is called by the register_hooks method.
	 *
	 * @return void
	 */
	public function init_plugin() {
		// Defining plugin constants.
		$this->define_constants();

		Database::get_instance(); // Instentiate Database class.
		Enqueue::get_instance(); // Instentiate Asset Enqueue class.
	}

	/**
	 * Function to define all constants.
	 */
	private function define_constants() {

		// This WC_PLUGIN_VERSION constant is defined 'PLUGIN_VERSION' property of the PluginMain class.
		if ( ! defined( 'WC_PLUGIN_VERSION' ) ) {
			define( 'WC_PLUGIN_VERSION', self::PLUGIN_VERSION );
		}

		// It is defined as the plugin directory path without the trailing slash.
		if ( ! defined( 'WC_PLUGIN_PATH' ) ) {
			define( 'WC_PLUGIN_PATH', untrailingslashit( plugin_dir_path( WC_PLUGIN_FILE ) ) );
		}

		// WC_PLUGIN_URL is defined as the URL for the plugin directory.
		if ( ! defined( 'WC_PLUGIN_URL' ) ) {
			define( 'WC_PLUGIN_URL', untrailingslashit( plugin_dir_url( WC_PLUGIN_FILE ) ) );
		}

		// WC_PLUGIN_ASSETS is the URL for the assets directory of the Learn Plugin.
		if ( ! defined( 'WC_PLUGIN_ASSETS' ) ) {
			define( 'WC_PLUGIN_ASSETS', WC_PLUGIN_URL . '/assets' );
		}
	}

	/**
	 * Run code when the plugin is activated
	 */
	public function activate() {

		Database::get_instance()->create_database_tables();

		$installed = get_option( 'wpdb_crud__installed' );

		if ( ! $installed ) {
			update_option( 'wpdb_crud__installed', time() );
		}

		update_option( 'wpdb_crud__version', self::PLUGIN_VERSION );
	}
	/**
	 * Run code when the plugin is activated
	 */
	public function deactivate() {

		Database::get_instance()->deactivate();

		delete_option( 'wpdb_crud__installed' );
		delete_option( 'wpdb_crud__version' );
	}
}
