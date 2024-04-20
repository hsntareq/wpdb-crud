<?php
/**
 * Admin Page.
 *
 * @package wordpress-plugin
 */

namespace WpdbCrud;

/**
 * Admin Page.
 */
class Admin_Page {
	use Traits, Singleton;

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
		add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
	}

	/**
	 * Add admin menu.
	 *
	 * @return void
	 */
	public function add_admin_menu() {
		add_menu_page( 'WPDB CRUD', 'WPDB CRUD', 'manage_options', 'wpdb-crud', array( $this, 'admin_page' ), 'dashicons-database' );
	}

	/**
	 * Admin page.
	 *
	 * @return void
	 */
	public function admin_page() {
		global $wpdb;
		$total_rows = Database::get_instance()->get_wpdb_data_count();
		$results    = Database::get_instance()->get_wpdb_data();

		// On edit url load data to form.
		if ( isset( $_GET['action'] ) && 'edit' === $_GET['action'] ) { // phpcs:ignore
			if ( isset( $_GET['id'] ) ) { // phpcs:ignore
				$id   = sanitize_text_field( wp_unslash( $_GET['id'] ) ); // phpcs:ignore
				$post = Database::get_instance()->get_post_by_id( $id );
				self::render( 'layout', compact( 'results', 'total_rows', 'post' ) );
			} else {
				return;
			}
		} else {
			self::render( 'layout', compact( 'results', 'total_rows' ) );
		}
	}
}

