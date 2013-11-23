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
    public $original_content = array(
        'movie'     => 'tt0137523',
        'tvserie'   => 'tt0402711',
        'videogame' => 'tt1843198',
        'nodata'    => 'tt0000000',
        'incorrect' => 'a b c'
    );

    /**
     * Negativ test, incorrect input to curl_init function
     *
     * @expectedException        Curl_Exception
     * @expectedExceptionMessage curl_init() expects parameter 1 to be string
     * @expectedExceptionCode    2
     *
     * @covers                   Movie_Datasource::__construct
     * @covers                   Movie_Datasource::fetchResponse
     * @covers                   Curl_Exception
     *
     * @return void
     */
    public function testCurlInitException()
    {
        //Given
        $tconst = $this->original_content['nodata'];
        $request = array();

        //When
        $imdb = new Movie_Datasource($tconst);
        $imdb->request = $request;
        $imdb->fetchResponse();
    }

    /**
     * Negative test, incorrect request
     *
     * @expectedException        Curl_Exception
     * @expectedExceptionMessage Could not resolve host: a b c curl_version:
     * @expectedExceptionCode    6
     *
     * @covers                   Movie_Datasource::__construct
     * @covers                   Movie_Datasource::fetchResponse
     * @covers                   Curl_Exception
     *
     * @return void
     */
    public function testIncorrectRequest()
    {
        //Given
        $tconst = $this->original_content['nodata'];
        $request = 'a b c';

        //When
        $imdb = new Movie_Datasource($tconst);
        $imdb->request = $request;
        $imdb->fetchResponse();
    }

    /**
     * Negativ test, timeout exception to imdb api. CURLE_OPERATION_TIMEDOUT = 28
     * error code
     *
     * @expectedException     Curl_Exception
     * @expectedExceptionCode 28
     *
     * @covers                Movie_Datasource::__construct
     * @covers                Movie_Datasource::fetchResponse
     * @covers                Curl_Exception
     *
     * @return void
     */
    public function testTimeout()
    {
        //Given
        $tconst = $this->original_content['movie'];
        $locale = null;
        $timeout = 200;

        //When
        $imdb = new Movie_Datasource($tconst, $locale, $timeout);
        $imdb->fetchResponse();
    }

}
