<?php
/**
 * Database Class.
 *
 * @package wordpress-plugin
 */

namespace WpdbCrud;

/**
 * Database Class.
 */
class Database {
	use Traits, Singleton;

	/**
	 * $table_name
	 *
	 * @var string
	 */

	private $table_name;

	/**
	 * $instance
	 *
	 * @var null
	 */
	private static $instance = null;

	/**
	 * $dbv
	 *
	 * @var string
	 */
	private $dbv = '1.0';

	/**
	 * $mv_admin_url
	 *
	 * @var string
	 */
	/**
	 * $wc_admin_url
	 *
	 * @var string
	 */
	private $wc_admin_url;

	/**
	 * __construct
	 *
	 * @return void
	 */
	public function __construct() {
		global $wpdb;
		$this->table_name = $wpdb->prefix . 'custom_table'; // 'wp_custom_table' is the table name.

		// Initialize the class.
		add_action( 'init', array( $this, 'init' ) );

		$dbv = get_option( 'dbv' );
		if ( $dbv !== $this->dbv ) {
			$this->create_database_tables();
			update_option( 'dbv', $this->dbv );
		}

		$this->wc_admin_url = admin_url( 'admin.php?page=wpdb-crud' );
	}

	/**
	 * Initialize the class.
	 *
	 * @return void
	 */
	public function init() {
		// Add notice if name or email is empty.
		add_action( 'wp', array( $this, 'delete_transient_after_expiry' ) );
		add_action( 'admin_notices', array( $this, 'custom_admin_notice' ) );

		$this->crud_action();
	}


	/**
	 * Delete transient message after expiry.
	 *
	 * @return void
	 */
	public function delete_transient_after_expiry() {
		// Delete the transient message.
		delete_transient( 'crud_message' );
	}

	/**
	 * Insert post.
	 *
	 * @return void
	 */
	public function crud_action() {

		global $wpdb;
		// Return if nonce validation fails.
		if ( ! isset( $_POST['wpdb_crud_nonce'] )
			|| ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['wpdb_crud_nonce'] ) ), 'wpdb_crud_action' )
		) {
			return;
		}

		// Check if 'field's index exists in $_POST before using it.
		$id    = ( isset( $_POST['id'] ) ) ? sanitize_text_field( wp_unslash( $_POST['id'] ) ) : null;
		$name  = ( isset( $_POST['name'] ) ) ? sanitize_text_field( wp_unslash( $_POST['name'] ) ) : null;
		$email = ( isset( $_POST['email'] ) ) ? sanitize_text_field( wp_unslash( $_POST['email'] ) ) : null;

		// Insert post by ID.
		if ( isset( $_POST['submit'] ) && 'Add' === $_POST['submit'] ) {
			$this->wpdb_crud_insert( $name, $email );
		}

		// Update post by ID.
		if ( isset( $_POST['submit'] ) && 'Edit' === $_POST['submit'] ) {
			$this->wpdb_crud_update( $id, $name, $email );
		}

		// Delete post by ID.
		if ( isset( $_POST['submit'] ) && 'Delete' === $_POST['submit'] ) {
			$this->wpdb_crud_delete( $id );
		}
	}

	/**
	 * Insert post.
	 *
	 * @param mixed $name  Post name.
	 * @param mixed $email Post email.
	 *
	 * @return void
	 */
	public function wpdb_crud_insert( $name, $email ) {
		global $wpdb;
		if ( ! empty( $name ) && ! empty( $email ) ) {
			$wpdb->insert( // phpcs:ignore WordPress.DB.DirectDatabaseQuery
				$this->table_name,
				array(
					'name'  => $name,
					'email' => $email,
				)
			);
			// Call functions after each operation.
			set_transient( 'crud_message', array( 'success' => 'Insert operation successful' ), 1 );
		} else {
			// Set transient message after insert.
			set_transient( 'crud_message', array( 'error' => 'Name or Email can\'t be empty.' ), 1 );
			return;
		}

		// Redirect to admin page.
		wp_safe_redirect( $this->wc_admin_url );
	}

	/**
	 * Update post.
	 *
	 * @param mixed $id    Post ID.
	 * @param mixed $name  Post name.
	 * @param mixed $email Post email.
	 *
	 * @return void
	 */
	public function wpdb_crud_update( $id, $name, $email ) {
		global $wpdb;
		if ( ! empty( $name ) && ! empty( $email ) ) {
			$wpdb->update(  // phpcs:ignore WordPress.DB.DirectDatabaseQuery
				$this->table_name,
				array(
					'name'  => $name,
					'email' => $email,
				),
				array( 'id' => $id )
			);

			// Set transient message after update.
			set_transient( 'crud_message', array( 'info' => 'Update operation successful' ), 1 );
		} else {
			// Set transient message after update.
			set_transient( 'crud_message', array( 'error' => 'Name or Email can\'t be empty.' ), 1 );
			return;
		}

		// Redirect to admin page.
		wp_safe_redirect( $this->wc_admin_url );
	}

	/**
	 * Delete post.
	 *
	 * @param mixed $id Post ID.
	 *
	 * @return void
	 */
	public function wpdb_crud_delete( $id ) {
		global $wpdb;
		if ( ! empty( $id ) ) {
			$wpdb->delete( $this->table_name, array( 'id' => $id ) ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery

			// Set transient message after delete.
			set_transient( 'crud_message', array( 'success' => 'Delete operation successful' ), 1 );
		} else {
			return;
		}

		// Redirect to admin page.
		wp_safe_redirect( $this->wc_admin_url );
	}

	/**
	 * Add notice if name or email is empty.
	 *
	 * @return void
	 */
	public function custom_admin_notice() {
		// Check if transient exists.
		$notice = get_transient( 'crud_message' );

		if ( $notice ) {
			// Check if the transient message is an array.
			if ( is_array( $notice ) ) {
				// Print each key-value pair.
				foreach ( $notice as $key => $value ) {
					echo '<div class="notice notice-' . esc_attr( $key ) . ' is-dismissible">';
					echo '<p><strong>' . esc_attr( ucfirst( $key ) ) . ':</strong> ' . esc_html( $value ) . '</p>';
					echo '</div>';
				}
			}
		}
	}


	/**
	 * Get wpdb data count.
	 *
	 * @return int | null
	 */
	public function get_wpdb_data_count() {
		global $wpdb;
		$count = $wpdb->get_var( $wpdb->prepare( 'SELECT COUNT(*) FROM %i', $this->table_name ) ); // phpcs:ignore
		return $count;
	}
	/**
	 * Get wpdb data.
	 *
	 * @return object
	 */
	public function get_wpdb_data() {
		global $wpdb;
		$posts = $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM %i', $this->table_name ) ); // phpcs:ignore
		return $posts;
	}

	/**
	 * Get post by ID.
	 *
	 * @param mixed $id Post ID.
	 *
	 * @return object
	 */
	public function get_post_by_id( $id ) {
		global $wpdb;
		$post = $wpdb->get_row( $wpdb->prepare( 'SELECT * FROM %i WHERE id = %d', $this->table_name, $id ) ); // phpcs:ignore
		return $post;
	}

	/**
	 * Create database tables on install or update db version.
	 *
	 * @return void
	 */
	public function create_database_tables() {
		global $wpdb;
		$charset_collate = $wpdb->get_charset_collate();
		$sql             = "CREATE TABLE $this->table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            name varchar(50) NOT NULL,
            email varchar(50) NOT NULL,
            PRIMARY KEY (id)
        ) $charset_collate;";

		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		dbDelta( $sql );
	}
}

