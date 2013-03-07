<?php

namespace IMDb_Markup_Syntax\Exceptions;

use Exception;

/**
 * Exception class for json exceptions
 * @author Henrik Roos <henrik at afternoon.se>
 */
class Json_Exception extends Exception {

    /** @var array json error codes */
    public $json_errors = array(
        0 => "JSON_ERROR_NONE",
        1 => "JSON_ERROR_DEPTH",
        2 => "JSON_ERROR_STATE_MISMATCH",
        3 => "JSON_ERROR_CTRL_CHAR",
        4 => "JSON_ERROR_SYNTAX",
        5 => "JSON_ERROR_UTF8"
    );

    /**
     * Create object and grep the last error from json
     * @param string $message extra message
     * @param int $code if json_last_error = 0 use this code
     * @param Exception $previous
     */
    public function __construct($message = "", $code = 0, Exception $previous = null) {
        if (json_last_error() > 0) {
            $code = json_last_error();
            $message .= $this->json_errors[json_last_error()];
        }
        parent::__construct($message, $code, $previous);
    }

}

?>
