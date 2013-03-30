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

namespace IMDb_Markup_Syntax;

use IMDb_Markup_Syntax\Exceptions\Curl_Exception;
use IMDb_Markup_Syntax\Exceptions\Json_Exception;
use IMDb_Markup_Syntax\Exceptions\Runtime_Exception;
use stdClass;

require_once dirname(__FILE__) . '/Exceptions/Runtime_Exception.php';
require_once dirname(__FILE__) . '/Exceptions/Curl_Exception.php';
require_once dirname(__FILE__) . '/Exceptions/Json_Exception.php';

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
class Movie_Datasource
{

    /** @var string IMDb API URL */
    public $baseurl = "https://app.imdb.com/";

    /** @var array Parameter to the request */
    public $params = array(
        "api" => "v1",
        "appid" => "iphone1_1",
        "apiPolicy" => "app1_1",
        "apiKey" => "2wex6aeu6a8q9e49k7sfvufd6rhh0n"
    );

    /** @var string imdb tconst for current movie. <i>e.g. tt0137523</i> */
    public $tconst;

    /** @var string Localization for data, defualt <i>en_US</i> standard RFC 4646 */
    public $locale;

    /** @var string Request to web api. */
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
     * @param string $locale  Localization for data, defualt <i>en_US</i> standard
     * RFC 4646 language
     * @param int    $timeout The maximum number of milliseconds to allow execute to
     * imdb.
     * 
     * @throws Runtime_Exception if incorrect tconst.
     */
    public function __construct($tconst = null, $locale = "en_US", $timeout = 0)
    {
        if (!is_null($tconst) && @preg_match("/^tt\d+$/", $tconst) == 0) {
            throw new Runtime_Exception(null, "Incorrect tconst: {$tconst}");
        }
        $this->tconst = $tconst;
        $this->timeout = $timeout;
        $this->locale = $locale;
        $this->setRequest($tconst, $locale);
    }

    /**
     * Build and set request 
     * 
     * @param string $query  That are you locking for? <i>e.g. movie id: tt0137523 or
     * persion id</i>
     * @param string $locale Localization for data, defualt <i>en_US</i> standard
     * @param string $key    That kind of data are $query? Defualt <i>tconst</i>
     * @param string $method Type of method to API defualt is find title by id.
     * Alternative find after persion id: <i>name/maindetails</i> or just simple
     * <i>find</i>
     * RFC 4646 language
     * 
     * @return void
     */
    public function setRequest($query, $locale = "en_US", $key = "tconst",
        $method = "title/maindetails"
    )
    {
        if (empty($query)) {
            throw new Runtime_Exception(null, "No query");
        }
        $this->params["locale"] = $locale;
        $this->params[$key] = urlencode($query);
        $this->params["timestamp"] = $_SERVER["REQUEST_TIME"];

        // Generate a signature
        $sig = hash_hmac("sha1",
            $this->baseurl . $method . "?" . http_build_query($this->params),
            $this->params["apiKey"]
        );
        $this->params["sig"] = "app1-" . $sig;

        $this->request
            = $this->baseurl . $method . "?" . http_build_query($this->params);
    }

    /**
     * Fetch and convert data from IMDb from current tconst. Data stores in
     * $this->response
     * 
     * @return stdClass movie data
     * 
     * @throws Curl_Exception    On error in web api request
     * @throws Json_Exception    If error in decode
     * @throws Runtime_Exception If response has error in result ex no data for
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
     * @throws Json_Exception    If error in decode
     * @throws Runtime_Exception If response has error in result ex no data for
     * this tconst.
     */
    public function toDataClass()
    {
        $obj = json_decode($this->response);
        if (!isset($obj)) {
            throw new Json_Exception();
        }
        if (isset($obj->error)) {
            throw new Runtime_Exception($obj);
        }
        return $obj->data;
    }

    /**
     * Function for cURL data fetching for current movie. Data stores in
     * $this->response
     * 
     * @return void
     * 
     * @throws Curl_Exception On error in web api request
     */
    public function fetchResponse()
    {
        $resource = @curl_init($this->request);
        if (!isset($resource) || $resource === false) {
            throw new Curl_Exception(null, "curl_init return false or null");
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
            throw new Curl_Exception($resource);
        }
        curl_close($resource);
        $this->response = $response;
    }

}

?>
