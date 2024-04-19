<div class="wrap wpdb-crud-wrap">
	<?php if ( 'edit' === $_GET['action'] ) : ?>
		<h2>Edit User</h2>
		<?php self::render( 'form', compact( 'post' ) ); ?>
	<?php elseif ( 'add' === $_GET['action'] ) : ?>
		<h2>Add User</h2>
		<?php self::render( 'form', compact( 'results', 'total_rows' ) ); ?>
	<?php else : ?>
		<?php self::render( 'list', compact( 'results', 'total_rows' ) ); ?>
	<?php endif; ?>

</div>
