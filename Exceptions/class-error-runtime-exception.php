<?php
/**
 * Create intans object for imdb api error.
 */
class Error_Runtime_Exception extends Exception {
    /**
     * Create intans object for imdb api repsonse error.
     * @param stdClass $response from imdb api respons as json convert
     * @param string $message extra message
     * @param long $code error code ex 404 for not fond
     * @param Exception $previous
     */
    public function __construct($response, $message = "", $code = 0, Exception $previous = null) {
        if (isset($response->error) && isset($response->error->message) && isset($response->error->status)) {
            $message .= $response->error->message;
            $code = $response->error->status;
        }
        parent::__construct($message, $code, $previous);
    }
}
?>
