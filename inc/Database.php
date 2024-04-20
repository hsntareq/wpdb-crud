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
		$this->crud_action();
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
		} else {
			// Add notice if ID is empty.
			add_action( 'admin_notices', array( $this, 'empty_name_email_notice' ) );
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
		} else {
			// Add notice if name or email is empty.
			add_action( 'admin_notices', array( $this, 'empty_name_email_notice' ) );
			return;
		}

		// Redirect to admin page.
		wp_safe_redirect( $this->wc_admin_url );
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
		} else {
			// Set transient message.
			set_transient( 'wpdb_crud_message', 'Name or Email cannot be empty.', 5 );
			// Add notice if name or email is empty.
			add_action( 'admin_notices', array( $this, 'empty_name_email_notice' ) );
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
	public function empty_name_email_notice() {
		?>
		<div class="notice notice-error is-dismissible">
			<p><?php esc_html_e( 'Name or Email cannot be empty.', 'wpdb-crud' ); ?></p>
		</div>
		<?php
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

