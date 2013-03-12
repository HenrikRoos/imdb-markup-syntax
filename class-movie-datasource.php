<?php

namespace IMDb_Markup_Syntax;

use IMDb;
use IMDb_Markup_Syntax\Exceptions\Curl_Exception;
use IMDb_Markup_Syntax\Exceptions\Error_Runtime_Exception;
use IMDb_Markup_Syntax\Exceptions\Json_Exception;
use stdClass;

require_once dirname(__FILE__) . '/IMDb-PHP-API/class_IMDb.php';
require_once dirname(__FILE__) . '/Exceptions/class-error-runtime-exception.php';
require_once dirname(__FILE__) . '/Exceptions/class-curl-exception.php';
require_once dirname(__FILE__) . '/Exceptions/class-json-exception.php';

/**
 * Class for access to IMDb RESTful datasource web api.
 * @author Henrik Roos <henrik@afternoon.se>
 * @package Core
 */
class Movie_Datasource extends IMDb
{

    /** @var string imdb tconst for current movie. <i>e.g. tt0137523</i> */
    public $tconst;

    /** @var string request to web api. */
    public $request;

    /** @var string raw data respomse from web api */
    public $response;

    /**
     * @var int The maximum number of milliseconds to allow cURL functions to execute. If libcurl is built
     * to use the standard system name resolver, that portion of the connect will still use full-second
     * resolution for timeouts with a minimum timeout allowed of one second.
     */
    public $timeout;

    /**
     * Create an instans object for acces to datasource,
     * @param string $tconst imdb tconst for current movie <i>e.g. tt0137523</i>
     * @param int $timeout The maximum number of milliseconds to allow execute to imdb.
     * @throws Error_Runtime_Exception if incorrect tconst.
     */
    public function __construct($tconst, $timeout = 0)
    {
        if (@preg_match("/^tt\d+$/", $tconst) == 0) {
            throw new Error_Runtime_Exception(null, "Incorrect tconst: {$tconst}");
        }
        parent::__construct(true, false);
        $this->request = $this->build_url('title/maindetails', $tconst, 'tconst');
        $this->timeout = $timeout;
    }

    /**
     * Fetch and convert data from IMDb from current tconst. Data stores in $this->response
     * @return stdClass movie data
     * @throws Curl_Exception on error in web api request
     * @throws Json_Exception if error in decode
     * @throws Error_Runtime_Exception if response has error in result ex no data for this tconst.
     */
    public function getData()
    {
        $this->fetchResponse();
        return $this->toDataClass();
    }

    /**
     * Convert raw json data from web api to movie stdClass.
     * @return stdClass movie data
     * @throws Json_Exception if error in decode
     * @throws Error_Runtime_Exception if response has error in result ex no data for this tconst.
     */
    public function toDataClass()
    {
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
     * Function for cURL data fetching for current movie. Data stores in $this->response
     * @throws Curl_Exception on error in web api request
     */
    public function fetchResponse()
    {
        $resource = @curl_init($this->request);
        if (!isset($resource) || $resource === FALSE) {
            throw new Curl_Exception(null, "curl_init return false or null");
        }
        $options = array(
            CURLOPT_HTTPHEADER => array('Connection: Keep-Alive', 'Content-type: text/plain;charset=UTF-8'),
            CURLOPT_HEADER => false,
            CURLOPT_TIMEOUT_MS => $this->timeout,
            CURLOPT_ENCODING => 'deflate',
            CURLOPT_RETURNTRANSFER => true);
        curl_setopt_array($resource, $options);
        $response = curl_exec($resource);
        if ($response === FALSE) {
            throw new Curl_Exception($resource);
        }
        curl_close($resource);
        $this->response = $response;
    }

}

?>
