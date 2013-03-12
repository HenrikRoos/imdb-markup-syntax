<?php

/**
 * Exception class for curl exceptions
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

namespace IMDb_Markup_Syntax\Exceptions;

use Exception;

/**
 * Exception class for curl exceptions
 * 
 * @category  Runnable
 * @package   Exception
 * @author    Henrik Roos <henrik.roos@afternoon.se>
 * @copyright 2013 Henrik Roos
 * @license   https://github.com/HenrikRoos/imdb-markup-syntax/blob/master/imdb-markup-syntax.php GPL2
 * @link      https://github.com/HenrikRoos/imdb-markup-syntax imdb-markup-syntax
 */
class Curl_Exception extends Exception
{

    /**
     * Create an instnas of exception class and grep the last error from curl class.
     * 
     * @param resource  $resource Resource from curl_init
     * @param string    $message  Extra messages
     * @param int       $code     If $resource is null set custom errro code
     * @param Exception $previous The previous exception
     */
    public function __construct($resource = null, $message = "", $code = 0,
            Exception $previous = null)
    {
        if (isset($resource) && curl_errno($resource) > 0) {
            $code = curl_errno($resource);
            $message .= curl_error($resource);
        } else {
            $error_get_last = error_get_last();
            if (isset($error_get_last)) {
                $code = $error_get_last["type"];
                $message = $error_get_last["message"];
            }
        }
        parent::__construct($message, $code, $previous);
    }

}

?>
