<?php

namespace IMDb_Markup_Syntax\Exceptions;

use Exception;

/**
 * Exception handler for PCRE functions
 * @package imdb-markup-syntax-exception
 * @author Henrik Roos <henrik at afternoon.se>
 */
class PCRE_Exception extends Exception {

    /**
     * Mapping error code into error name
     * @var array of pcre_errors
     */
    public $pcre_errors = array(
        0 => "PREG_NO_ERROR",
        1 => "PREG_INTERNAL_ERROR",
        2 => "PREG_BACKTRACK_LIMIT_ERROR",
        3 => "PREG_RECURSION_LIMIT_ERROR",
        4 => "PREG_BAD_UTF8_ERROR",
        5 => "PREG_BAD_UTF8_OFFSET_ERROR");

    /**
     * Create object and grep last preg error code
     * @param string $message extra message
     * @param int $code if no preg_last_error or error_get_last use this code
     * @param Exception $previous
     */
    public function __construct($message = "", $code = 0, Exception $previous = null) {
        $preg_last_error = preg_last_error();
        $error_get_last = error_get_last();
        if (!empty($preg_last_error)) {
            $code = $preg_last_error;
            $message .= $this->pcre_errors[$code];
        } else if (!empty($error_get_last)) {
            $code = $error_get_last["type"];
            $message .= $error_get_last["message"];
        }
        parent::__construct($message, $code, $previous);
    }

}

?>
