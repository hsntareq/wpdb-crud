<?php
$field_id    = $post->id;
$field_name  = $post->name;
$field_email = $post->email;
$button_text = ( isset( $_GET['action'] ) && 'edit' === $_GET['action'] ) ? 'Edit' : 'Add';
?>
<form method="post">
	<input type="hidden" name="id" value="<?php echo esc_attr( $field_id ); ?>">
	<table class="form-table wp-list-table widefat striped" style="width:inherit">
		<tr>
			<th><label for="name">Name</label></th>
			<td><input type="text" placeholder="Type name" name="name" id="name"
					value="<?php echo esc_attr( $field_name ); ?>"></td>
		</tr>
		<tr>
			<th><label for="email">Email</label></th>
			<td><input type="email" name="email" placeholder="Type email" id="email"
					value="<?php echo esc_attr( $field_email ); ?>"></td>
		</tr>
	</table>
	<p>
		<button name="submit" type="submit" class="button button-primary"><?php echo esc_attr( $button_text ) ?></button>
		<a href="<?php echo esc_url( admin_url( 'admin.php?page=wpdb-crud' ) ); ?>"
			class="button button-secondary">Cancel</a>
	</p>
</form>
