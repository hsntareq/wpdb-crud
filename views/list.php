<div style="display:flex; align-items:center;gap:30px">
	<h2>WPDB CRUD</h2> <a class="button button-small button-secondary"
		href="<?php echo esc_url( admin_url( 'admin.php?page=wpdb-crud&action=add' ) ); ?>">Add New</a>
</div>
<p>This is a custom admin page for WPDB CRUD plugin.</p>

<table class="wp-list-table widefat striped">
	<thead>
		<tr>
			<th>ID</th>
			<th>Name</th>
			<th>Email</th>
			<th>Action</th>
		</tr>
	</thead>
	<tbody>
		<?php
		foreach ( $results as $row ) {
			$edit_url = admin_url( 'admin.php?page=wpdb-crud&action=edit&id=' . $row->id );
			echo '<tr>';
			echo '<td>' . esc_html( $row->id ) . '</td>';
			echo '<td>' . esc_html( $row->name ) . '</td>';
			echo '<td>' . esc_html( $row->email ) . '</td>';
			echo '<td width="100"><a href="' . esc_url( $edit_url ) . '" class="button button-small button-primary">Edit</a>
			<button class="button button-small button-danger">Delete</button></td>';
			echo '</tr>';
		}
		?>
	</tbody>
</table>
<p>Total rows: <?php echo esc_html( $total_rows ); ?> </p>
<style>
	.button-danger {
		border-color: #dc3232 !important;
		border-color: #dc3232 !important;
		background-color: #dc3232 !important;
		color: #fff !important;
	}
</style>
