<?php

require_once dirname(__FILE__) . '/IMDb-PHP-API/class_IMDb.php';
require_once dirname(__FILE__) . '/Exceptions/class-error-runtime-exception.php';
require_once dirname(__FILE__) . '/Exceptions/class-curl-exception.php';
require_once dirname(__FILE__) . '/Exceptions/class-json-exception.php';

/**
 * Class for access to IMDb RESTful datasource web api.
 */
class Movie_Datasource extends IMDb {

    /** @var string imdb id for current movie. ex "tt0137523" */
    public $id;

    /** @var string request to web api. */
    public $request;

    /** @var string raw data respomse from web api */
    public $response;

    /**
     * The maximum number of milliseconds to allow cURL functions to execute. If libcurl is built
     * to use the standard system name resolver, that portion of the connect will still use full-second
     * resolution for timeouts with a minimum timeout allowed of one second.
     * @var int
     */
    public $timeout;

    /**
     * Create an instans object for acces to datasource,
     * @param string $id imdb id for current movie ex "tt0137523"
     * @param int $timeout The maximum number of milliseconds to allow execute to imdb.
     * @throws Error_Runtime_Exception if incorrect id.
     */
    public function __construct($id, $timeout = 0) {
        if (@preg_match("/^tt\d+$/", $id) == 0) {
            throw new Error_Runtime_Exception(null, "Incorrect id: {$id}");
        }
        parent::__construct(true, false);
        $this->request = $this->build_url('title/maindetails', $id, 'tconst');
        $this->timeout = $timeout;
    }

    /**
     * Fetch and convert data from IMDb from current movie id.
     * data stores in $this->response
     * @return array movie data
     * @throws Curl_Exception on error in web api request
     * @throws Json_Exception if error in decode
     * @throws Error_Runtime_Exception if response has error in result ex no data for this id.
     */
    public function getMovie() {
        $this->fetchResponse();
        return $this->toDataArray();
    }

    /**
     * Convert raw json data from web api to movie array.
     * @return array movie data
     * @throws Json_Exception if error in decode
     * @throws Error_Runtime_Exception if response has error in result ex no data for this id.
     */
    public function toDataArray() {
        $obj = json_decode($this->response);
        if (!isset($obj)) {
            throw new Json_Exception();
        }
        if (isset($obj->error)) {
            throw new Error_Runtime_Exception($obj);
        }
        return $obj->data;
    }

    /**
     * Function for cURL data fetching for current movie.
     * data stores in $this->response
     * @throws Curl_Exception on error in web api request
     */
    public function fetchResponse() {
        $ch = @curl_init($this->request);
        if (!isset($ch) || $ch === FALSE) {
            throw new Curl_Exception(null, "curl_init return false or null");
        }
        $options = array(
            CURLOPT_HTTPHEADER => array('Connection: Keep-Alive', 'Content-type: text/plain;charset=UTF-8'),
            CURLOPT_HEADER => false,
            CURLOPT_TIMEOUT_MS => $this->timeout,
            CURLOPT_ENCODING => 'deflate',
            CURLOPT_RETURNTRANSFER => true);
        curl_setopt_array($ch, $options);
        $response = curl_exec($ch);
        if ($response === FALSE) {
            throw new Curl_Exception($ch);
        }
        curl_close($ch);
        $this->response = $response;
    }

}

?>
