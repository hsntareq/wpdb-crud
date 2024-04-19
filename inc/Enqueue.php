<?php
/**
 * Enqueue Class
 *
 * @package wp-plugin
 * @since 1.0
 */

namespace WpdbCrud;

/**
 * Enqueue Class.
 */
class Enqueue {
	/**
	 * $instance
	 *
	 * @var null
	 */
	private static $instance = null;
	/**
	 * __construct
	 *
	 * @return void
	 */
	public function __construct() {
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin' ) );
	}

	/**
	 * The instance of this class.
	 *
	 * @return Enqueue
	 */
	public static function get_instance(): Enqueue {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance ?? new self();
	}

	/**
	 * Enqueue style and script for the plugin.
	 *
	 * @return void
	 */
	public function enqueue() {
		wp_enqueue_style( 'pvc-front-style', WC_PLUGIN_URL . '/assets/css/style.css', array(), WC_PLUGIN_VERSION, 'all' );
		wp_enqueue_script( 'pvc-front-script', WC_PLUGIN_URL . '/assets/js/main.js', array(), WC_PLUGIN_VERSION, true );
	}

	/**
	 * Enqueue the shortcode style if shortcode is called in the post or page.
	 *
	 * @return void
	 */
	public function enqueue_admin() {
		global $pagenow;
		// print_r($pagenow);die;

		// Check if script is used in the post or page.
		if ( 'admin.php' !== $pagenow && 'toplevel_page_wpdb-crud' !== $pagenow ) {
			return;
		}

		wp_enqueue_style( 'pvc-admin-style', WC_PLUGIN_URL . '/assets/css/admin.css', array(), WC_PLUGIN_VERSION, 'all' );

		// wp_enqueue_script( 'pvc-admin-script', WC_PLUGIN_URL . '/assets/js/main-admin.js', array(), WC_PLUGIN_VERSION, true );
	}
}
