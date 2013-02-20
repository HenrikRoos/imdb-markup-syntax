<?php

/**
 * Exception handler for PCRE functions
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
     * @param type $message
     * @param type $code
     * @param Exception $previous
     */
    public function __construct($message = "", $code = 0, Exception $previous = null) {
        $last_error = error_get_last();
        if (empty($last_error)) {
            $code = preg_last_error();
            $message = $this->pcre_errors[$code];
        } else {
            $code = $last_error["type"];
            $message = $last_error["message"];
        }
        parent::__construct($message, $code, $previous);
    }

}

?>
