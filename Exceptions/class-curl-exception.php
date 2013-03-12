<?php

/**
 * Class for exception handler for PCRE functions
 */

namespace IMDb_Markup_Syntax\Exceptions;

use Exception;

/**
 * Exception class for curl exceptions
 * @author Henrik Roos <henrik@afternoon.se>
 * @package Exception
 */
class Curl_Exception extends Exception
{

    /**
     * Create an instnas of exception class and grep the last error from curl class.
     * @param resource $resource resource from curl_init
     * @param string $message extra messages
     * @param int $code if $resource is null set custom errro code
     * @param Exception $previous
     */
    public function __construct($resource = null, $message = "", $code = 0, Exception $previous = null)
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
