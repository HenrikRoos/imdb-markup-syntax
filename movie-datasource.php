<?php
/**
 * Class for access to IMDb RESTful datasource web api
 *
 * PHP version 5
 *
 * @category  Runnable
 * @package   Core
 * @author    Henrik Roos <henrik.roos@afternoon.se>
 * @copyright 2014 Henrik Roos
 * @license   http://opensource.org/licenses/gpl-3.0.html GPL-3.0
 * @link      https://github.com/HenrikRoos/imdb-markup-syntax imdb-markup-syntax
 */

require_once 'runtime-exception.php';
require_once 'curl-exception.php';
require_once 'json-exception.php';

/**
 * Class for access to IMDb RESTful datasource web api
 *
 * @category  Runnable
 * @package   Core
 * @author    Henrik Roos <henrik.roos@afternoon.se>
 * @copyright 2014 Henrik Roos
 * @license   http://opensource.org/licenses/gpl-3.0.html GPL-3.0
 * @link      https://github.com/HenrikRoos/imdb-markup-syntax imdb-markup-syntax
 */
class Movie_Datasource {

	/** @var string IMDb API URL */
	public $baseurl = 'http://app.imdb.com/';
	/** @var array Parameter to the request */
	public $params = array();
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
	 * @param string $tconst Imdb tconst for current movie <i>e.g. tt0137523</i>
	 * @param string $locale Localization for data
	 * @param int $timeout The maximum number of milliseconds to allow execute to
	 *                        imdb.
	 *
	 * @throws Runtime_Exception if incorrect tconst.
	 */
	public function __construct( $tconst = null, $locale = '', $timeout = 0 ) {
		if ( ! is_null( $tconst ) && preg_match( '/^tt\d+$/', $tconst ) == 0 ) {
			throw new Runtime_Exception(
				sprintf( __( 'Incorrect tconst %s', 'imdb-markup-syntax' ), $tconst )
			);
		}
		$this->tconst  = $tconst;
		$this->timeout = $timeout;
		$this->locale  = $locale;
		$this->set_request( $tconst, $locale );
	}

	/**
	 * Build and set request
	 *
	 * @param string $query That are you locking for? <i>e.g. movie id: tt0137523 or
	 *                       persion id</i>
	 * @param string $locale Localization for data
	 * @param string $key That kind of data are $query? Defualt <i>tconst</i>
	 * @param string $method Type of method to API defualt is find title by id.
	 * Alternative find after persion id: <i>name/maindetails</i> or just simple
	 * <i>find</i> RFC 4646 language
	 *
	 * @throws Runtime_Exception
	 * @return void
	 */
	public function set_request(
		$query,
		$locale = '',
		$key = 'tconst',
		$method = 'title/maindetails'
	) {
		if ( empty( $query ) ) {
			throw new Runtime_Exception( __( 'Empty query', 'imdb-markup-syntax' ) );
		}
		if ( ! empty( $locale ) ) {
			$this->params['locale'] = $locale;
		}
		$this->params[ $key ] = urlencode( $query );

		$this->request
			= $this->baseurl . $method . '?' . http_build_query( $this->params );
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
	public function get_data() {
		$this->fetch_response();

		return $this->to_data_class();
	}

	/**
	 * Function for cURL data fetching for current movie. Data stores in
	 * $this->response
	 *
	 * @return void
	 *
	 * @throws Curl_Exception On error in web api request
	 */
	public function fetch_response() {
		$resource = @curl_init( $this->request );
		if ( ! isset( $resource ) || $resource === false ) {
			throw new Curl_Exception(
				null, __( 'curl_init return false or null', 'imdb-markup-syntax' )
			);
		}
		curl_setopt( $resource, CURLOPT_RETURNTRANSFER, true );
		if ( $this->timeout > 0 ) {
			curl_setopt( $resource, CURLOPT_TIMEOUT_MS, $this->timeout );
		}
		$response = @curl_exec( $resource );
		if ( $response === false ) {
			throw new Curl_Exception( $resource );
		}
		curl_close( $resource );
		$this->response = $response;
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
	public function to_data_class() {
		$obj = json_decode( $this->response );
		if ( json_last_error() !== JSON_ERROR_NONE || ! is_object( $obj ) ) {
			throw new Json_Exception();
		}
		if ( isset( $obj->error ) || ! isset( $obj->data ) ) {
			throw new Runtime_Exception( null, null, $obj );
		}

		return $obj->data;
	}

}
