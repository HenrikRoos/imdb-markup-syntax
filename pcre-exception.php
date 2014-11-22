<?php
/**
 * Exception class for PCRE exceptions
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
 * Exception class for PCRE exceptions
 *
 * @category  Runnable
 * @package   Exception
 * @author    Henrik Roos <henrik.roos@afternoon.se>
 * @copyright 2014 Henrik Roos
 * @license   http://opensource.org/licenses/gpl-3.0.html GPL-3.0
 * @link      https://github.com/HenrikRoos/imdb-markup-syntax imdb-markup-syntax
 */
class PCRE_Exception extends Exception {

	/** @var array Mapping error code into error name */
	public $pcre_errors = array(
		0 => 'PREG_NO_ERROR',
		1 => 'PREG_INTERNAL_ERROR',
		2 => 'PREG_BACKTRACK_LIMIT_ERROR',
		3 => 'PREG_RECURSION_LIMIT_ERROR',
		4 => 'PREG_BAD_UTF8_ERROR',
		5 => 'PREG_BAD_UTF8_OFFSET_ERROR',
	);

	/**
	 * Create object and grep last preg error code
	 *
	 * @param string $message Extra message
	 * @param int $code If no preg_last_error or error_get_last use this
	 *                            code
	 * @param Exception $previous The previous exception used for the exception
	 *                            chaining
	 */
	public function __construct( $message = '', $code = 0, Exception $previous = null ) {
		$preg_last_error = preg_last_error();
		$error_get_last  = error_get_last();
		if ( ! empty( $preg_last_error ) ) {
			$code = $preg_last_error;
			$message .= $this->pcre_errors[ $code ];
		} else if ( ! empty( $error_get_last ) ) {
			$code = $error_get_last['type'];
			$message .= $error_get_last['message'];
		}
		parent::__construct( $message, $code, $previous );
	}

}
