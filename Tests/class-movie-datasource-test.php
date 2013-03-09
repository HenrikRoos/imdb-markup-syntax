<?php

namespace IMDb_Markup_Syntax;

use IMDb_Markup_Syntax\Exceptions\Curl_Exception;
use IMDb_Markup_Syntax\Exceptions\Error_Runtime_Exception;
use IMDb_Markup_Syntax\Exceptions\Json_Exception;
use PHPUnit_Framework_TestCase;

require_once 'PHPUnit/Autoload.php';
require_once dirname(__FILE__) . '/../class-movie-datasource.php';
require_once dirname(__FILE__) . '/../Exceptions/class-error-runtime-exception.php';
require_once dirname(__FILE__) . '/../Exceptions/class-curl-exception.php';
require_once dirname(__FILE__) . '/../Exceptions/class-json-exception.php';

/**
 * Testclass (PHPUnit) test for Movie_Datasource class
 * @author Henrik Roos <henrik at afternoon.se>
 * @package Test
 */
class Movie_DatasourceTest extends PHPUnit_Framework_TestCase {

    /** @var array testdata for movie id */
    public $testdata = array(
        "movie" => "tt0137523",
        "tvserie" => "tt0402711",
        "videogame" => "tt1843198",
        "nodata" => "tt0000000",
        "incorrect" => "a b c"
    );

    /**
     * Main use case get a movie data, no error
     * @covers IMDb_Markup_Syntax\Movie_Datasource::__construct
     * @covers IMDb_Markup_Syntax\Movie_Datasource::getData
     * @covers IMDb_Markup_Syntax\Movie_Datasource::fetchResponse
     * @covers IMDb_Markup_Syntax\Movie_Datasource::toDataClass
     */
    public function testMovie() {
        $imdb = new Movie_Datasource($this->testdata["movie"]);
        $movie = $imdb->getData();
        $this->assertEquals("Fight Club", $movie->title);
    }

    /**
     * Main use case get a tv serie data, no error
     * @covers IMDb_Markup_Syntax\Movie_Datasource::__construct
     * @covers IMDb_Markup_Syntax\Movie_Datasource::getData
     * @covers IMDb_Markup_Syntax\Movie_Datasource::fetchResponse
     * @covers IMDb_Markup_Syntax\Movie_Datasource::toDataClass
     */
    public function testTvSerie() {
        $imdb = new Movie_Datasource($this->testdata["tvserie"]);
        $movie = $imdb->getData();
        $this->assertEquals("Boston Legal", $movie->title);
    }

    /**
     * Main use case get a video game data, no error
     * @covers IMDb_Markup_Syntax\Movie_Datasource::__construct
     * @covers IMDb_Markup_Syntax\Movie_Datasource::getData
     * @covers IMDb_Markup_Syntax\Movie_Datasource::fetchResponse
     * @covers IMDb_Markup_Syntax\Movie_Datasource::toDataClass
     */
    public function testVideoGame() {
        $imdb = new Movie_Datasource($this->testdata["videogame"]);
        $movie = $imdb->getData();
        $this->assertEquals("Lego Pirates of the Caribbean: The Video Game", $movie->title);
    }

    /**
     * Negativ test, No data for this title id. HTTP 404
     * @covers IMDb_Markup_Syntax\Movie_Datasource::__construct
     * @covers IMDb_Markup_Syntax\Movie_Datasource::getData
     * @covers IMDb_Markup_Syntax\Movie_Datasource::fetchResponse
     * @covers IMDb_Markup_Syntax\Movie_Datasource::toDataClass
     * @covers IMDb_Markup_Syntax\Exceptions\Error_Runtime_Exception
     */
    public function testGetMovieNoData() {
        try {
            $imdb = new Movie_Datasource($this->testdata["nodata"]);
            $imdb->getData();
        } catch (Error_Runtime_Exception $exc) {
            $this->assertEquals("No data for this title id.", $exc->getMessage());
            $this->assertEquals(404, $exc->getCode());
            return;
        }
        $this->fail('An expected exception has not been raised.');
    }

    /**
     * Negativ test, incorrect id checked in construct
     * @covers IMDb_Markup_Syntax\Movie_Datasource::__construct
     * @covers IMDb_Markup_Syntax\Exceptions\Error_Runtime_Exception
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
     * @covers IMDb_Markup_Syntax\Movie_Datasource::__construct
     * @covers IMDb_Markup_Syntax\Movie_Datasource::fetchResponse
     * @covers IMDb_Markup_Syntax\Exceptions\Curl_Exception
     */
    public function testTimeout() {
        try {
            $imdb = new Movie_Datasource($this->testdata["movie"], 400);
            $imdb->fetchResponse();
        } catch (Curl_Exception $exc) {
            $this->assertEquals(28, $exc->getCode());
            return;
        }
        $this->fail('An expected exception has not been raised.');
    }

    /**
     * Negative test, incorrect request.
     * @covers IMDb_Markup_Syntax\Movie_Datasource::fetchResponse
     * @covers IMDb_Markup_Syntax\Movie_Datasource::__construct
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
     * @covers IMDb_Markup_Syntax\Movie_Datasource::fetchResponse
     * @covers IMDb_Markup_Syntax\Movie_Datasource::__construct
     * @covers IMDb_Markup_Syntax\Exceptions\Curl_Exception
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
     * @covers IMDb_Markup_Syntax\Movie_Datasource::toDataClass
     * @covers IMDb_Markup_Syntax\Movie_Datasource::__construct
     * @covers IMDb_Markup_Syntax\Exceptions\Json_Exception
     */
    public function testToDataClassJson_Exception() {
        try {
            $imdb = new Movie_Datasource($this->testdata["movie"]);
            $imdb->fetchResponse();
            $imdb->response .= "a";
            $imdb->toDataClass();
        } catch (Json_Exception $exc) {
            $this->assertEquals("JSON_ERROR_SYNTAX", $exc->getMessage());
            $this->assertEquals(JSON_ERROR_SYNTAX, $exc->getCode());
            return;
        }
        $this->fail('An expected exception has not been raised.');
    }

}
