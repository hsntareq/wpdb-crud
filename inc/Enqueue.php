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
	use Singleton;

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
	 * Enqueue the shortcode style if shortcode is called in the post or page.
	 *
	 * @return void
	 */
	public function enqueue_admin() {
		global $pagenow;

		// Check if script is used in the post or page.
		if ( 'admin.php' !== $pagenow && 'toplevel_page_wpdb-crud' !== $pagenow ) {
			return;
		}

		wp_enqueue_style( 'wc-admin-style', WC_PLUGIN_URL . '/assets/css/admin.css', array(), WC_PLUGIN_VERSION, 'all' );
		wp_enqueue_script( 'wc-admin-script', WC_PLUGIN_URL . '/assets/js/admin.js', array(), WC_PLUGIN_VERSION, true );
	}
}
