<?php
/**
 * List view.
 *
 * @package wordpress-plugin
 */

?>
<h1 class="wp-heading-inline"><?php esc_attr_e( 'WPDB CRUD', 'wpdb-crud' ); ?></h1>
<a class="page-title-action "
	href="<?php echo esc_url( admin_url( 'admin.php?page=wpdb-crud&action=add' ) ); ?>"><?php esc_attr_e( 'Add new data', 'wpdb-crud' ); ?></a>
<p><?php esc_attr_e( 'This is a list of data from database for WPDB CRUD plugin.', 'wpdb-crud' ); ?></p>

<table class="wp-list-table widefat striped">
	<thead>
		<tr>
			<th><?php esc_html_e( 'ID', 'wpdb-crud' ); ?></th>
			<th><?php esc_html_e( 'Name', 'wpdb-crud' ); ?></th>
			<th><?php esc_html_e( 'Email', 'wpdb-crud' ); ?></th>
			<th><?php esc_html_e( 'Action', 'wpdb-crud' ); ?></th>
		</tr>
	</thead>
	<tbody>
		<?php
		if ( count( $results ) > 0 ) {
			foreach ( $results as $row ) {
				$edit_url = admin_url( 'admin.php?page=wpdb-crud&action=edit&id=' . $row->id );
				echo '<tr>';
				echo '<td>' . esc_html( $row->id ) . '</td>';
				echo '<td>' . esc_html( $row->name ) . '</td>';
				echo '<td>' . esc_html( $row->email ) . '</td>';
				echo '<td width="150">';
				echo '<a href="' . esc_url( $edit_url ) . '" class="button button-small button-primary">Edit</a>
				<form method="post" style="display:inline-block;">
					<input type="hidden" name="id" value="' . esc_attr( $row->id ) . '">
					<input type="hidden" name="wpdb_crud_nonce" value="' . esc_attr( wp_create_nonce( 'wpdb_crud_action' ) ) . '">
					<button name="submit" onclick="return confirm(\'Are you sure you want to delete?\')" value="Delete" type="submit" class="button button-small button-danger">Delete</button>
				</form>';
				echo '</td>';
				echo '</tr>';
			}
		} else {
			echo '<tr><td colspan="4">No records found.</td></tr>';
		}
		?>
	</tbody>
</table>
<p>Total rows: <?php echo esc_html( $total_rows ); ?> </p>
