<?php
/**
 * Singleton Trait.
 *
 * @package wordpress-plugin
 */

namespace WpdbCrud;

trait Singleton {

	/**
	 * Instance of the class.
	 *
	 * @var null
	 */
	private static $instance = null;

	/**
	 * Get the instance of the class.
	 *
	 * @return mixed
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}
}

