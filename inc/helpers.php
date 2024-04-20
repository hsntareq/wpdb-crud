<?php
/**
 * Functions.
 *
 * @package wordpress-plugin
 */

// Debug pring function.
if ( ! function_exists( 'pr' ) ) {
	/**
	 * Print debug data.
	 *
	 * @param mixed $data Data to print.
	 *
	 * @return void
	 */
	function pr( $data ) {
		echo '<pre>';
		print_r( $data ); // phpcs:ignore
		echo '</pre>';
	}
}

// Debug dump function.
if ( ! function_exists( 'vd' ) ) {
	/**
	 * Dump debug data.
	 *
	 * @param mixed $data Data to dump.
	 *
	 * @return void
	 */
	function vd( $data ) {
		echo '<pre>';
		var_dump( $data ); // phpcs:ignore
		echo '</pre>';
	}
}

