<?php
/**
 * Exception class for json exceptions
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
 * Exception class for json exceptions
 *
 * @category  Runnable
 * @package   Exception
 * @author    Henrik Roos <henrik.roos@afternoon.se>
 * @copyright 2014 Henrik Roos
 * @license   http://opensource.org/licenses/gpl-3.0.html GPL-3.0
 * @link      https://github.com/HenrikRoos/imdb-markup-syntax imdb-markup-syntax
 */
class Json_Exception extends Exception {

	/** @var array json error codes */
	public $json_errors = array(
		0 => 'JSON_ERROR_NONE',
		1 => 'JSON_ERROR_DEPTH',
		2 => 'JSON_ERROR_STATE_MISMATCH',
		3 => 'JSON_ERROR_CTRL_CHAR',
		4 => 'JSON_ERROR_SYNTAX',
		5 => 'JSON_ERROR_UTF8',
	);

	/**
	 * Create object and grep the last error from json
	 *
	 * @param string $message Extra message
	 * @param int $code If json_last_error = 0 use this code
	 * @param Exception $previous The previous exception used for the exception
	 *                            chaining
	 */
	public function __construct( $message = '', $code = 0, Exception $previous = null ) {
		if ( json_last_error() > 0 ) {
			$code = json_last_error();
			$message .= $this->json_errors[ json_last_error() ];
		}
		parent::__construct( $message, $code, $previous );
	}

}
