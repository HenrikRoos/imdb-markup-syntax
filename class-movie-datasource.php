<?php

/**
 * Class for access to IMDb RESTful datasource web api
 * 
 * PHP version 5
 * 
 * @category  Runnable
 * @package   Core
 * @author    Henrik Roos <henrik.roos@afternoon.se>
 * @copyright 2013 Henrik Roos
 * @license   https://github.com/HenrikRoos/imdb-markup-syntax/blob/master/imdb-markup-syntax.php GPL2
 * @link      https://github.com/HenrikRoos/imdb-markup-syntax imdb-markup-syntax
 */

namespace IMDbMarkupSyntax;

use IMDb;
use IMDbMarkupSyntax\Exceptions\CurlException;
use IMDbMarkupSyntax\Exceptions\ErrorRuntimeException;
use IMDbMarkupSyntax\Exceptions\JsonException;
use stdClass;

require_once dirname(__FILE__) . '/IMDb-PHP-API/class_IMDb.php';
require_once dirname(__FILE__) . '/Exceptions/class-error-runtime-exception.php';
require_once dirname(__FILE__) . '/Exceptions/class-curl-exception.php';
require_once dirname(__FILE__) . '/Exceptions/class-json-exception.php';

/**
 * Class for access to IMDb RESTful datasource web api
 * 
 * @category  Runnable
 * @package   Core
 * @author    Henrik Roos <henrik.roos@afternoon.se>
 * @copyright 2013 Henrik Roos
 * @license   https://github.com/HenrikRoos/imdb-markup-syntax/blob/master/imdb-markup-syntax.php GPL2
 * @link      https://github.com/HenrikRoos/imdb-markup-syntax imdb-markup-syntax
 */
class MovieDatasource extends IMDb
{

    /** @var string imdb tconst for current movie. <i>e.g. tt0137523</i> */
    public $tconst;

    /** @var string request to web api. */
    public $request;

    /** @var string raw data respomse from web api */
    public $response;

    /**
     * @var int The maximum number of milliseconds to allow cURL functions to
     * execute. If libcurl is built to use the standard system name resolver, that
     * portion of the connect will still use full-second resolution for timeouts with
     * a minimum timeout allowed of one second.
     */
    public $timeout;

    /**
     * Create an instans object for acces to datasource
     * 
     * @param string $tconst  Imdb tconst for current movie <i>e.g. tt0137523</i>
     * @param int    $timeout The maximum number of milliseconds to allow execute to
     * imdb.
     * 
     * @throws ErrorRuntimeException if incorrect tconst.
     */
    public function __construct($tconst, $timeout = 0)
    {
        if (@preg_match("/^tt\d+$/", $tconst) == 0) {
            throw new ErrorRuntimeException(null, "Incorrect tconst: {$tconst}");
        }
        parent::__construct(true, false);
        $this->request = $this->build_url('title/maindetails', $tconst, 'tconst');
        $this->timeout = $timeout;
    }

    /**
     * Fetch and convert data from IMDb from current tconst. Data stores in
     * $this->response
     * 
     * @return stdClass movie data
     * 
     * @throws CurlException          On error in web api request
     * @throws JsonException          If error in decode
     * @throws ErrorRuntimeException If response has error in result ex no data for
     * this tconst.
     */
    public function getData()
    {
        $this->fetchResponse();
        return $this->toDataClass();
    }

    /**
     * Convert raw json data from web api to movie stdClass
     * 
     * @return stdClass movie data
     * 
     * @throws JsonException          If error in decode
     * @throws ErrorRuntimeException If response has error in result ex no data for
     * this tconst.
     */
    public function toDataClass()
    {
        $obj = json_decode($this->response);
        if (!isset($obj)) {
            throw new JsonException();
        }
        if (isset($obj->error)) {
            throw new ErrorRuntimeException($obj);
        }
        return $obj->data;
    }

    /**
     * Function for cURL data fetching for current movie. Data stores in
     * $this->response
     * 
     * @return void
     * 
     * @throws CurlException On error in web api request
     */
    public function fetchResponse()
    {
        $resource = @curl_init($this->request);
        if (!isset($resource) || $resource === false) {
            throw new CurlException(null, "curl_init return false or null");
        }
        $options = array(
            CURLOPT_HTTPHEADER => array('Connection: Keep-Alive',
                'Content-type: text/plain;charset=UTF-8'),
            CURLOPT_HEADER => false,
            CURLOPT_TIMEOUT_MS => $this->timeout,
            CURLOPT_ENCODING => 'deflate',
            CURLOPT_RETURNTRANSFER => true);
        curl_setopt_array($resource, $options);
        $response = curl_exec($resource);
        if ($response === false) {
            throw new CurlException($resource);
        }
        curl_close($resource);
        $this->response = $response;
    }

}

?>
