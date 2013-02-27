<?php

require_once dirname(__FILE__) . '/IMDb-PHP-API/class_IMDb.php';

/**
 * Class for access to IMDb RESTful datasource web api.
 */
class Movie_Datasource extends IMDb {

    /** @var string imdb id for current movie. ex "tt0137523" */
    public $id;

    /** @var string request to web api. */
    public $request;

    /** @var array converted json data from imdb to php types in a array. */
    public $response = array();

    /**
     * Create an instans object for acces to datasource,
     * @param string $id imdb id for current movie ex "tt0137523"
     */
    public function __construct ($id) {
        if (@preg_match("/^tt\d+$/", $id) === FALSE) {
            throw new PCRE_Exception();
        }
        parent::__construct(true, false);
        $this->request = $this->build_url('title/maindetails', $id, 'tconst');
    }

    /**
     * Function for cURL data fetching for current movie.
     * data stores in $this->response
     */
    public function getMovie() {
        $ch = curl_init($this->request);
        if ($ch === FALSE) {
            curl_close($ch);
            throw new Curl_Exception(null, "curl_init return false");
        }
        $options = array(
            CURLOPT_HTTPHEADER => array('Connection: Keep-Alive', 'Content-type: text/plain;charset=UTF-8'),
            CURLOPT_HEADER => false,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_ENCODING => 'deflate',
            CURLOPT_RETURNTRANSFER => true);
        if (!@curl_setopt_array($ch, $options)) {
            curl_close($ch);
            throw new Curl_Exception($ch);
        }
        $json = curl_exec($ch);
        if ($json === FALSE) {
            curl_close($ch);
            throw new Json_Exception();
        }
        curl_close($ch);
        $this->response = json_decode($json);
        if (!isset($this->response)) {
            throw new Json_Exception();
        }
        return $this->response;
    }

}

?>
