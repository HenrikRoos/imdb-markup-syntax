<?php

/**
 * Exception class for runtime exceptions
 * 
 * PHP version 5
 * 
 * @category  Runnable
 * @package   Exception
 * @author    Henrik Roos <henrik.roos@afternoon.se>
 * @copyright 2013 Henrik Roos
 * @license   https://github.com/HenrikRoos/imdb-markup-syntax/blob/master/imdb-markup-syntax.php GPL2
 * @link      https://github.com/HenrikRoos/imdb-markup-syntax imdb-markup-syntax
 */

namespace IMDbMarkupSyntax\Exceptions;

use Exception;
use stdClass;

/**
 * Exception class for runtime exceptions
 * 
 * @category  Runnable
 * @package   Exception
 * @author    Henrik Roos <henrik.roos@afternoon.se>
 * @copyright 2013 Henrik Roos
 * @license   https://github.com/HenrikRoos/imdb-markup-syntax/blob/master/imdb-markup-syntax.php GPL2
 * @link      https://github.com/HenrikRoos/imdb-markup-syntax imdb-markup-syntax
 */
class ErrorRuntimeException extends Exception
{

    /**
     * Create intans object for imdb api repsonse error.
     * 
     * @param stdClass  $response From imdb api respons as json convert
     * @param string    $message  Extra message
     * @param int       $code     Error code ex 404 for not fond
     * @param Exception $previous The previous exception used for the exception
     * chaining
     */
    public function __construct($response, $message = "", $code = 0,
        Exception $previous = null
    ) {
        if (isset($response->error) && isset($response->error->message)
            && isset($response->error->status)
        ) {
            $message .= $response->error->message;
            $code = $response->error->status;
        }
        parent::__construct($message, $code, $previous);
    }

}

?>
