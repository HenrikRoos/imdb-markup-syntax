<?php

/**
 * Testclass (PHPUnit) test for MovieDatasource class
 * 
 * PHP version 5
 * 
 * @category  Testable
 * @package   Test
 * @author    Henrik Roos <henrik.roos@afternoon.se>
 * @copyright 2013 Henrik Roos
 * @license   https://github.com/HenrikRoos/imdb-markup-syntax/blob/master/imdb-markup-syntax.php GPL2
 * @link      https://github.com/HenrikRoos/imdb-markup-syntax imdb-markup-syntax
 */

namespace IMDbMarkupSyntax;

use IMDbMarkupSyntax\Exceptions\CurlException;
use IMDbMarkupSyntax\Exceptions\ErrorRuntimeException;
use IMDbMarkupSyntax\Exceptions\JsonException;
use PHPUnit_Framework_TestCase;

require_once 'PHPUnit/Autoload.php';
require_once dirname(__FILE__) . '/../class-movie-datasource.php';
require_once dirname(__FILE__) . '/../Exceptions/class-error-runtime-exception.php';
require_once dirname(__FILE__) . '/../Exceptions/class-curl-exception.php';
require_once dirname(__FILE__) . '/../Exceptions/class-json-exception.php';

/**
 * Testclass (PHPUnit) test for MovieDatasource class
 * 
 * @category  Testable
 * @package   Test
 * @author    Henrik Roos <henrik.roos@afternoon.se>
 * @copyright 2013 Henrik Roos
 * @license   https://github.com/HenrikRoos/imdb-markup-syntax/blob/master/imdb-markup-syntax.php GPL2
 * @link      https://github.com/HenrikRoos/imdb-markup-syntax imdb-markup-syntax
 */
class MovieDatasourceTest extends PHPUnit_Framework_TestCase
{

    /** @var array Testdata for movie tconst */
    public $testdata = array(
        "movie" => "tt0137523",
        "tvserie" => "tt0402711",
        "videogame" => "tt1843198",
        "nodata" => "tt0000000",
        "incorrect" => "a b c"
    );

    /**
     * Main use case get a movie data, no error
     * 
     * @covers IMDbMarkupSyntax\MovieDatasource::__construct
     * @covers IMDbMarkupSyntax\MovieDatasource::getData
     * @covers IMDbMarkupSyntax\MovieDatasource::fetchResponse
     * @covers IMDbMarkupSyntax\MovieDatasource::toDataClass
     * 
     * @return void
     */
    public function testMovie()
    {
        $imdb = new MovieDatasource($this->testdata["movie"]);
        $movie = $imdb->getData();
        $this->assertEquals("Fight Club", $movie->title);
    }

    /**
     * Main use case get a tv serie data, no error
     * 
     * @covers IMDbMarkupSyntax\MovieDatasource::__construct
     * @covers IMDbMarkupSyntax\MovieDatasource::getData
     * @covers IMDbMarkupSyntax\MovieDatasource::fetchResponse
     * @covers IMDbMarkupSyntax\MovieDatasource::toDataClass
     * 
     * @return void
     */
    public function testTvSerie()
    {
        $imdb = new MovieDatasource($this->testdata["tvserie"]);
        $movie = $imdb->getData();
        $this->assertEquals("Boston Legal", $movie->title);
    }

    /**
     * Main use case get a video game data, no error
     * 
     * @covers IMDbMarkupSyntax\MovieDatasource::__construct
     * @covers IMDbMarkupSyntax\MovieDatasource::getData
     * @covers IMDbMarkupSyntax\MovieDatasource::fetchResponse
     * @covers IMDbMarkupSyntax\MovieDatasource::toDataClass
     * 
     * @return void
     */
    public function testVideoGame()
    {
        $imdb = new MovieDatasource($this->testdata["videogame"]);
        $movie = $imdb->getData();
        $this->assertEquals("Lego Pirates of the Caribbean: The Video Game",
            $movie->title
        );
    }

    /**
     * Negativ test, No data for this title tconst. HTTP 404
     * 
     * @covers IMDbMarkupSyntax\MovieDatasource::__construct
     * @covers IMDbMarkupSyntax\MovieDatasource::getData
     * @covers IMDbMarkupSyntax\MovieDatasource::fetchResponse
     * @covers IMDbMarkupSyntax\MovieDatasource::toDataClass
     * @covers IMDbMarkupSyntax\Exceptions\ErrorRuntimeException
     * 
     * @return void
     */
    public function testGetMovieNoData()
    {
        try {
            $imdb = new MovieDatasource($this->testdata["nodata"]);
            $imdb->getData();
        } catch (ErrorRuntimeException $exc) {
            $this->assertEquals("No data for this title id.", $exc->getMessage());
            $this->assertEquals(404, $exc->getCode());
            return;
        }
        $this->fail('An expected exception has not been raised.');
    }

    /**
     * Negativ test, incorrect tconst checked in construct
     * 
     * @covers IMDbMarkupSyntax\MovieDatasource::__construct
     * @covers IMDbMarkupSyntax\Exceptions\ErrorRuntimeException
     * 
     * @return void
     */
    public function testIncorrectId()
    {
        try {
            new MovieDatasource($this->testdata["incorrect"]);
        } catch (ErrorRuntimeException $exc) {
            $this->assertEquals("Incorrect tconst: {$this->testdata["incorrect"]}",
                $exc->getMessage()
            );
            return;
        }
        $this->fail('An expected exception has not been raised.');
    }

    /**
     * Negativ test, timeout exception to imdb api. CURLE_OPERATION_TIMEDOUT = 28
     * error code
     * 
     * @covers IMDbMarkupSyntax\MovieDatasource::__construct
     * @covers IMDbMarkupSyntax\MovieDatasource::fetchResponse
     * @covers IMDbMarkupSyntax\Exceptions\CurlException
     * 
     * @return void
     */
    public function testTimeout()
    {
        try {
            $imdb = new MovieDatasource($this->testdata["movie"], 400);
            $imdb->fetchResponse();
        } catch (CurlException $exc) {
            $this->assertEquals(28, $exc->getCode());
            return;
        }
        $this->fail('An expected exception has not been raised.');
    }

    /**
     * Negative test, incorrect request
     * 
     * @covers IMDbMarkupSyntax\MovieDatasource::fetchResponse
     * @covers IMDbMarkupSyntax\MovieDatasource::__construct
     * 
     * @return void
     */
    public function testFetchResponseIncorrectRequest()
    {
        try {
            $imdb = new MovieDatasource($this->testdata["nodata"]);
            $imdb->request = "a b c";
            $imdb->fetchResponse();
        } catch (CurlException $exc) {
            $this->assertEquals("Could not resolve host: a b c; nodename nor"
                . " servname provided, or not known", $exc->getMessage()
            );
            $this->assertEquals(6, $exc->getCode());
            return;
        }
        $this->fail('An expected exception has not been raised.');
    }

    /**
     * Negativ test, incorrect input to curl_init function
     * 
     * @covers IMDbMarkupSyntax\MovieDatasource::fetchResponse
     * @covers IMDbMarkupSyntax\MovieDatasource::__construct
     * @covers IMDbMarkupSyntax\Exceptions\CurlException
     * 
     * @return void
     */
    public function testFetchResponseCurl_initException()
    {
        try {
            $imdb = new MovieDatasource($this->testdata["nodata"]);
            $imdb->request = array();
            $imdb->fetchResponse();
        } catch (CurlException $exc) {
            $this->assertEquals("curl_init() expects parameter 1 to be string, array"
                . " given", $exc->getMessage()
            );
            $this->assertEquals(2, $exc->getCode());
            return;
        }
        $this->fail('An expected exception has not been raised.');
    }

    /**
     * Negativ test, incorrect json syntax
     * 
     * @covers IMDbMarkupSyntax\MovieDatasource::toDataClass
     * @covers IMDbMarkupSyntax\MovieDatasource::__construct
     * @covers IMDbMarkupSyntax\Exceptions\JsonException
     * 
     * @return void
     */
    public function testToDataClassJson_Exception()
    {
        try {
            $imdb = new MovieDatasource($this->testdata["movie"]);
            $imdb->fetchResponse();
            $imdb->response .= "a";
            $imdb->toDataClass();
        } catch (JsonException $exc) {
            $this->assertEquals("JSON_ERROR_SYNTAX", $exc->getMessage());
            $this->assertEquals(JSON_ERROR_SYNTAX, $exc->getCode());
            return;
        }
        $this->fail('An expected exception has not been raised.');
    }

}
