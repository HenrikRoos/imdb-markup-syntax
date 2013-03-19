<?php

/**
 * Sub testclass to Movie_DatasourceTest for method fetchResponse in Movie_Datasource
 * class
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

namespace IMDb_Markup_Syntax\Movie_DatasourceTest;

use IMDb_Markup_Syntax\Movie_Datasource;
use PHPUnit_Framework_TestCase;

require_once dirname(__FILE__) . '/../../Movie_Datasource.php';
require_once 'PHPUnit/Autoload.php';

/**
 * Sub testclass to Movie_DatasourceTest for method fetchResponse in Movie_Datasource
 * class
 * 
 * @category  Testable
 * @package   Test
 * @author    Henrik Roos <henrik.roos@afternoon.se>
 * @copyright 2013 Henrik Roos
 * @license   https://github.com/HenrikRoos/imdb-markup-syntax/blob/master/imdb-markup-syntax.php GPL2
 * @link      https://github.com/HenrikRoos/imdb-markup-syntax imdb-markup-syntax
 */
class Fetch_ResponseTest extends PHPUnit_Framework_TestCase
{

    /**
     * Negativ test, incorrect input to curl_init function
     * 
     * @expectedException        IMDb_Markup_Syntax\Exceptions\Curl_Exception
     * @expectedExceptionMessage curl_init() expects parameter 1 to be string
     * @expectedExceptionCode    2
     * 
     * @covers IMDb_Markup_Syntax\Movie_Datasource::__construct
     * @covers IMDb_Markup_Syntax\Movie_Datasource::fetchResponse
     * @covers IMDb_Markup_Syntax\Exceptions\Curl_Exception
     * 
     * @return void
     */
    public function testCurlInitException()
    {
        //Given
        $imdb = new Movie_Datasource($GLOBALS["movieDatasourceData"]["nodata"]);
        $imdb->request = array();

        //When
        $imdb->fetchResponse();
    }

    /**
     * Negative test, incorrect request
     * 
     * @expectedException        IMDb_Markup_Syntax\Exceptions\Curl_Exception
     * @expectedExceptionMessage Could not resolve host: a b c; nodename nor servname
     * @expectedExceptionCode    6
     * 
     * @covers IMDb_Markup_Syntax\Movie_Datasource::__construct
     * @covers IMDb_Markup_Syntax\Movie_Datasource::fetchResponse
     * @covers IMDb_Markup_Syntax\Exceptions\Curl_Exception
     * 
     * @return void
     */
    public function testIncorrectRequest()
    {
        //Given
        $imdb = new Movie_Datasource($GLOBALS["movieDatasourceData"]["nodata"]);
        $imdb->request = "a b c";

        //When
        $imdb->fetchResponse();
    }

    /**
     * Negativ test, timeout exception to imdb api. CURLE_OPERATION_TIMEDOUT = 28
     * error code
     * 
     * @expectedException     IMDb_Markup_Syntax\Exceptions\Curl_Exception
     * @expectedExceptionCode 28
     * 
     * @covers IMDb_Markup_Syntax\Movie_Datasource::__construct
     * @covers IMDb_Markup_Syntax\Movie_Datasource::fetchResponse
     * @covers IMDb_Markup_Syntax\Exceptions\Curl_Exception
     * 
     * @return void
     */
    public function testTimeout()
    {
        //Given
        $imdb = new Movie_Datasource($GLOBALS["movieDatasourceData"]["movie"], 400);

        //When
        $imdb->fetchResponse();
    }

}

?>
