<?php

require_once 'PHPUnit/Autoload.php';
require_once dirname(__FILE__) . '/../class-movie-datasource.php';
require_once dirname(__FILE__) . '/../Exceptions/class-error-runtime-exception.php';
require_once dirname(__FILE__) . '/../Exceptions/class-curl-exception.php';
require_once dirname(__FILE__) . '/../Exceptions/class-json-exception.php';

/**
 * Testclass (PHPUnit) test for Movie_Datasource class
 */
class Movie_DatasourceTest extends PHPUnit_Framework_TestCase {

    /** @var array testdata for movie id */
    public $testdata = array(
        "positive" => "tt0137523",
        "nodata" => "tt0000000",
        "incorrect" => "a b c"
    );

    /**
     * Negativ test, incorrect id checked in construct
     * @covers Movie_Datasource::__construct
     * @covers Error_Runtime_Exception
     */
    public function testIncorrectId() {
        try {
            new Movie_Datasource($this->testdata["incorrect"]);
        } catch (Error_Runtime_Exception $exc) {
            $this->assertEquals("Incorrect id: {$this->testdata["incorrect"]}", $exc->getMessage());
            return;
        }
        $this->fail('An expected exception has not been raised.');
    }

    /**
     * Negativ test, timeout exception to imdb api. CURLE_OPERATION_TIMEDOUT = 28 error code.
     * @covers Movie_Datasource::__construct
     * @covers Movie_Datasource::fetchResponse
     * @covers Curl_Exception
     */
    public function testTimeout() {
        try {
            $imdb = new Movie_Datasource($this->testdata["positive"], 400);
            $imdb->fetchResponse();
        } catch (Curl_Exception $exc) {
            $this->assertEquals(28, $exc->getCode());
            return;
        }
        $this->fail('An expected exception has not been raised.');
    }

    /**
     * Main use case get a movie data no error
     * @covers Movie_Datasource::__construct
     * @covers Movie_Datasource::getMovie
     * @covers Movie_Datasource::fetchResponse
     * @covers Movie_Datasource::toDataArray
     */
    public function testGetMoviePositive() {
        $imdb = new Movie_Datasource($this->testdata["positive"]);
        $movie = $imdb->getMovie();
        $this->assertEquals("Fight Club", $movie->title);
    }

    /**
     * Negativ test, No data for this title id. HTTP 404
     * @covers Movie_Datasource::__construct
     * @covers Movie_Datasource::getMovie
     * @covers Movie_Datasource::fetchResponse
     * @covers Movie_Datasource::toDataArray
     * @covers Error_Runtime_Exception
     */
    public function testGetMovieNoData() {
        try {
            $imdb = new Movie_Datasource($this->testdata["nodata"]);
            $imdb->getMovie();
        } catch (Error_Runtime_Exception $exc) {
            $this->assertEquals("No data for this title id.", $exc->getMessage());
            $this->assertEquals(404, $exc->getCode());
            return;
        }
        $this->fail('An expected exception has not been raised.');
    }

    /**
     * Negative test, incorrect request.
     * @covers Movie_Datasource::fetchResponse
     * @covers Movie_Datasource::__construct
     */
    public function testFetchResponseIncorrectRequest() {
        try {
            $imdb = new Movie_Datasource($this->testdata["nodata"]);
            $imdb->request = "a b c";
            $imdb->fetchResponse();
        } catch (Curl_Exception $exc) {
            $this->assertEquals("Could not resolve host: a b c; nodename nor servname provided, or not known", $exc->getMessage());
            $this->assertEquals(6, $exc->getCode());
            return;
        }
        $this->fail('An expected exception has not been raised.');
    }

    /**
     * Negativ test, incorrect input to curl_init function
     * @covers Movie_Datasource::fetchResponse
     * @covers Movie_Datasource::__construct
     * @covers Curl_Exception
     */
    public function testFetchResponseCurl_initException() {
        try {
            $imdb = new Movie_Datasource($this->testdata["nodata"]);
            $imdb->request = array();
            $imdb->fetchResponse();
        } catch (Curl_Exception $exc) {
            $this->assertEquals("curl_init() expects parameter 1 to be string, array given", $exc->getMessage());
            $this->assertEquals(2, $exc->getCode());
            return;
        }
        $this->fail('An expected exception has not been raised.');
    }

    /**
     * Negativ test, incorrect json syntax
     * @covers Movie_Datasource::toDataArray
     * @covers Movie_Datasource::__construct
     * @covers Json_Exception
     */
    public function testToDataArrayJson_Exception() {
        try {
            $imdb = new Movie_Datasource($this->testdata["positive"]);
            $imdb->fetchResponse();
            $imdb->response .= "a";
            $imdb->toDataArray();
        } catch (Json_Exception $exc) {
            $this->assertEquals("JSON_ERROR_SYNTAX", $exc->getMessage());
            $this->assertEquals(JSON_ERROR_SYNTAX, $exc->getCode());
            return;
        }
        $this->fail('An expected exception has not been raised.');
    }

}
