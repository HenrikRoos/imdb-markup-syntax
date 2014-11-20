<?php
/**
 * Exception class for runtime exceptions
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
 * Exception class for runtime exceptions
 *
 * @category  Runnable
 * @package   Exception
 * @author    Henrik Roos <henrik.roos@afternoon.se>
 * @copyright 2014 Henrik Roos
 * @license   http://opensource.org/licenses/gpl-3.0.html GPL-3.0
 * @link      https://github.com/HenrikRoos/imdb-markup-syntax imdb-markup-syntax
 */
class Runtime_Exception extends Exception {

	/**
	 * Create intans object for imdb api repsonse error.
	 *
	 * @param string $message Extra message
	 * @param int $code Error code ex 404 for not fond
	 * @param stdClass $response From imdb api respons as json convert
	 * @param Exception $previous The previous exception used for the exception
	 *                            chaining
	 */
	public function __construct(
		$message = '',
		$code = 0,
		stdClass $response = null,
		Exception $previous = null
	) {
		if ( isset( $response->error ) && isset( $response->error->message ) && isset( $response->error->status )
		) {
			$message .= $response->error->message;
			$code = $response->error->status;
		}
		parent::__construct( $message, $code, $previous );
	}

}
