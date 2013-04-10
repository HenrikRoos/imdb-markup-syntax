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
 * @license   http://opensource.org/licenses/gpl-3.0.html GPL-3.0
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
 * @license   http://opensource.org/licenses/gpl-3.0.html GPL-3.0
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
        $tconst = $GLOBALS["movieDatasourceData"]["nodata"];
        $request = array();

        //When
        $imdb = new Movie_Datasource($tconst);
        $imdb->request = $request;
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
        $tconst = $GLOBALS["movieDatasourceData"]["nodata"];
        $request = "a b c";

        //When
        $imdb = new Movie_Datasource($tconst);
        $imdb->request = $request;
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
        $tconst = $GLOBALS["movieDatasourceData"]["movie"];
        $locale = null;
        $timeout = 400;

        //When
        $imdb = new Movie_Datasource($tconst, $locale, $timeout);
        $imdb->fetchResponse();
    }

}

?>
