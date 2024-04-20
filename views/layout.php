<?php
/**
 * WPDB CRUD layout.
 *
 * @package wordpress-plugin
 */

?>
<div class="wrap wpdb-crud-wrap">
	<?php if ( isset( $_GET['action'] ) && 'edit' === $_GET['action'] ) : // phpcs:ignore ?>
		<h1 class="wp-heading-inline"><?php esc_attr_e( 'Edit User', 'wpdb-crud' ); ?></h1>
		<?php self::render( 'form', compact( 'post' ) ); ?>
	<?php elseif ( isset( $_GET['action'] ) && 'add' === $_GET['action'] ) : // phpcs:ignore ?>
		<h1 class="wp-heading-inline"><?php esc_attr_e( 'Add User', 'wpdb-crud' ); ?></h1>
		<?php self::render( 'form', compact( 'results', 'total_rows' ) ); ?>
	<?php else : ?>
		<?php self::render( 'list', compact( 'results', 'total_rows' ) ); ?>
	<?php endif; ?>
</div>
