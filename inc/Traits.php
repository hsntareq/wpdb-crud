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

		// echo esc_attr( $file );

		if ( file_exists( $file ) ) {
			foreach ( $args as $key => $value ) {
				${$key} = $value;
			}

			include_once $file;
		}
	}


	/**
	 * The instance of this class.
	 *
	 * @return Enqueue
	 */
	public static function get_instance(): Enqueue {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance ?? new self();
	}
}
