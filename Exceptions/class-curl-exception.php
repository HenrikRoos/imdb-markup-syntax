<?php

/**
 * Exception class for curl exceptions
 */
class Curl_Exception extends Exception {

    /**
     * Create an instnas of exception class and grep the last error from curl class.
     * @param resource $ch resource from curl_init
     * @param string $message extra messages
     * @param int $code if $ch is null set custom errro code
     */
    public function __construct($ch = null, $message = "", $code = 0, Exception $previous = null) {
        if (isset($ch) && curl_errno($ch) > 0) {
            $code = curl_errno($ch);
            $message .= curl_error($ch);
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
