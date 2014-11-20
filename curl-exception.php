<?php
/**
 * Exception class for curl exceptions
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
 * Exception class for curl exceptions
 *
 * @category  Runnable
 * @package   Exception
 * @author    Henrik Roos <henrik.roos@afternoon.se>
 * @copyright 2014 Henrik Roos
 * @license   http://opensource.org/licenses/gpl-3.0.html GPL-3.0
 * @link      https://github.com/HenrikRoos/imdb-markup-syntax imdb-markup-syntax
 */
class Curl_Exception extends Exception {

	/**
	 * Create an instnas of exception class and grep the last error from curl class.
	 *
	 * @param resource $resource Resource from curl_init
	 * @param string $message Extra messages
	 * @param int $code If $resource is null set custom errro code
	 * @param Exception $previous The previous exception used for the exception
	 *                            chaining
	 */
	public function __construct(
		$resource = null,
		$message = '',
		$code = 0,
		Exception $previous = null
	) {
		if ( isset( $resource ) && curl_errno( $resource ) > 0 ) {
			$code = curl_errno( $resource );
			$message .= curl_error( $resource );
		} else {
			$error_get_last = error_get_last();
			if ( isset( $error_get_last ) ) {
				$code    = $error_get_last['type'];
				$message = $error_get_last['message'];
			}
		}
		$version = curl_version();
		$message .= ' curl_version: ' . $version['version'];
		parent::__construct( $message, $code, $previous );
	}

}
