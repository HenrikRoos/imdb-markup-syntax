<?php
/**
 * Exception class for WP_Error class. **Must have WordPress 2.1+**
 *
 * PHP version 5
 *
 * @category  Runnable
 * @package   Exception
 * @author    Henrik Roos <henrik.roos@afternoon.se>
 * @copyright 2014 Henrik Roos
 * @license   http://opensource.org/licenses/gpl-3.0.html GPL-3.0
 * @link      https://github.com/HenrikRoos/imdb-markup-syntax imdb-markup-syntax
 */

/**
 * Exception class for WP_Error class. **Must have WordPress 2.1+**
 *
 * @category  Runnable
 * @package   Exception
 * @author    Henrik Roos <henrik.roos@afternoon.se>
 * @copyright 2014 Henrik Roos
 * @license   http://opensource.org/licenses/gpl-3.0.html GPL-3.0
 * @link      https://github.com/HenrikRoos/imdb-markup-syntax imdb-markup-syntax
 */
class WP_Exception extends Exception {

	/**
	 * Create intans object and wrap the WP_Error class
	 *
	 * @param WP_Error $wp_error An WordPress Error class
	 * @param Exception $previous The previous exception used for the exception
	 *                            chaining
	 *
	 * @since WordPress 2.1
	 */
	public function __construct( WP_Error $wp_error, Exception $previous = null ) {
		$message = $wp_error->get_error_message();
		parent::__construct( $message, null, $previous );
	}

}
