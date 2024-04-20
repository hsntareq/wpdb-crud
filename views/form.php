<?php
/**
 * Form view.
 *
 * @package wordpress-plugin
 */

$field_id    = $post->id ?? '';
$field_name  = $post->name ?? '';
$field_email = $post->email ?? '';
$button_text = ( isset( $_GET['action'] ) && 'edit' === $_GET['action'] ) ? 'Edit' : 'Add'; // phpcs:ignore

?>
<form method="post">
	<input type="hidden" name="id" value="<?php echo esc_attr( $field_id ); ?>">
	<!-- nonce action field -->
	<?php wp_nonce_field( 'wpdb_crud_action', 'wpdb_crud_nonce' ); ?>
	<table class="form-table wp-list-table widefat striped" style="width:inherit">
		<tr>
			<th><label for="name"><?php esc_attr_e( 'Name', 'wpdb-crud' ); ?></label></th>
			<td><input type="text" placeholder="Type name" name="name" id="name"
					value="<?php echo esc_attr( $field_name ); ?>"></td>
		</tr>
		<tr>
			<th><label for="email"><?php esc_attr_e( 'Email', 'wpdb-crud' ); ?></label></th>
			<td><input type="email" name="email" placeholder="Type email" id="email"
					value="<?php echo esc_attr( $field_email ); ?>"></td>
		</tr>
	</table>
	<p>
		<button name="submit" type="submit" value="<?php echo esc_attr( $button_text ); ?>"
			class="button button-primary"><?php echo esc_attr( $button_text ); ?></button>
		<a href="<?php echo esc_url( admin_url( 'admin.php?page=wpdb-crud' ) ); ?>"
			class="button button-secondary"><?php esc_attr_e( 'Cancel', 'wpdb-crud' ); ?></a>
	</p>
</form>
