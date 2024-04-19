<?php
/**
 * Database Class.
 */

namespace WpdbCrud;

class Database {
	use Traits;
	private $table_name;
	private $dbv = '1.3';
	function __construct() {
		error_log( 'Database class loaded' );
		global $wpdb;
		$this->table_name = $wpdb->prefix . 'custom_table'; // wp_custom_table
		add_action( 'init', array( $this, 'init' ) );
		// register_activation_hook( __FILE__, [ $this, 'create_database_tables' ] );
		// register_deactivation_hook( __FILE__, [ $this, 'deactivate' ] );

		// create an admin page
		add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );

		$dbv = get_option( 'dbv' );
		if ( $dbv != $this->dbv ) {
			$this->create_database_tables();
			update_option( 'dbv', $this->dbv );
		}
		// $this->create_database_tables();
	}

	/*
	 * Singleton instance
	 */
	public static function get_instance() {
		static $instance = null;
		if ( null === $instance ) {
			$instance = new self();
		}
		return $instance;
	}

	function init() {
		// handle form submission
		if ( isset( $_POST['submit'] ) ) {
			global $wpdb;
			$name  = sanitize_text_field( $_POST['name'] );
			$email = sanitize_text_field( $_POST['email'] );

			$wpdb->insert(
				$this->table_name,
				array(
					'name'  => $name,
					'email' => $email,
				)
			);

			// redirect to the list page
			wp_redirect( admin_url( 'admin.php?page=wpdb-crud' ) );
		}

	}

	// Get post by ID.
	function get_post_by_id( $id ) {
		global $wpdb;
		$post = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $this->table_name WHERE id = %d", $id ) );
		return $post;
	}

	function add_admin_menu() {
		add_menu_page( 'WPDB CRUD', 'WPDB CRUD', 'manage_options', 'wpdb-crud', array( $this, 'admin_page' ), 'dashicons-database' );
	}

	function admin_page() {
		global $wpdb;
		$total_rows = $wpdb->get_var( $wpdb->prepare( 'SELECT COUNT(*) FROM %i', $this->table_name ) );
		$results    = $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM %i', $this->table_name ) );


		// On edit url load data to form.
		if ( isset( $_GET['action'] ) && $_GET['action'] == 'edit' ) {
			$id   = $_GET['id'];
			$post = $this->get_post_by_id( $id );
		}

		self::render( 'layout', compact( 'results', 'total_rows', 'post' ) );

	}

	function deactivate() {
		global $wpdb;
		// $wpdb->query("DROP TABLE IF EXISTS $this->table_name");
	}

	function create_database_tables() {
		global $wpdb;
		$charset_collate = $wpdb->get_charset_collate();
		$sql             = "CREATE TABLE $this->table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            name varchar(50) NOT NULL,
            email varchar(50) NOT NULL,
            -- phone varchar(15) NOT NULL,
            -- gender varchar(10) NOT NULL,
            PRIMARY KEY (id)
        ) $charset_collate;";

		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		dbDelta( $sql );

		// drop phone column
		// $wpdb->query("ALTER TABLE $this->table_name DROP COLUMN phone");

		// insert some dummy data
		// $wpdb->insert($this->table_name, ['name' => 'John Doe', 'email' => 'john@doe.com']);
		// $wpdb->insert($this->table_name, ['name' => 'Jane Doe', 'email' => 'jane@doe.com']);
		// $wpdb->insert($this->table_name, ['name' => 'Jimmy Doe', 'email' => 'jimmy@doe.com']);

		// update 4th record
		// $wpdb->update($this->table_name, ['email' => 'wedevs@academy.local'], ['id' => 4]);

		// delete
		// $wpdb->delete($this->table_name, ['id' => 5]);

		// prepared insert
		// $wpdb->query($wpdb->prepare(
		// "INSERT INTO $this->table_name (name, email) VALUES (%s, %s)",
		// 'ABCD', 'abcd@abcd.com'));

		// $name = "XYZ";
		// $email = "xyz@xyz.com";
		// $wpdb->insert(
		// $this->table_name,
		// array(
		// 'name' => $wpdb->prepare('%s', $name),
		// 'email' => $wpdb->prepare('%s', $email)
		// )
		// );

		// $wpdb->query('START TRANSACTION');
		// $wpdb->query('COMMIT');
		// $wpdb->query('ROLLBACK');
	}
}

