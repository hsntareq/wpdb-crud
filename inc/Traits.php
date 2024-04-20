<?php
/**
 * Template Class.
 *
 * @package wordpress-plugin
 */

namespace WpdbCrud;

/**
 * Template Class.
 *
 * @param mixed $view_path View path.
 */
trait Traits {

	/**
	 * Path to the views directory.
	 *
	 * @var string
	 */
	private static $view_path = WC_PLUGIN_PATH . '/views/';

	/**
	 * Render the template.
	 *
	 * @param string $file File name.
	 * @param array  $args Arguments.
	 * @return void
	 */
	public function render( $file, $args = array() ) {

		$file = self::$view_path . $file . '.php';

		if ( file_exists( $file ) ) {
			foreach ( $args as $key => $value ) {
				${$key} = $value;
			}

			include_once $file;
		}
	}
}
