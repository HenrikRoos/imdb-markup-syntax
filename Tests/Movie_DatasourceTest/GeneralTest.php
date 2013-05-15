<?php

/**
 * Sub testclass to Movie_DatasourceTest for general tests in Movie_Datasource
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

require_once dirname(__FILE__) . "/../../Movie_Datasource.php";
require_once "PHPUnit/Autoload.php";

/**
 * Sub testclass to Movie_DatasourceTest for general tests in Movie_Datasource
 * 
 * @category  Testable
 * @package   Test
 * @author    Henrik Roos <henrik.roos@afternoon.se>
 * @copyright 2013 Henrik Roos
 * @license   http://opensource.org/licenses/gpl-3.0.html GPL-3.0
 * @link      https://github.com/HenrikRoos/imdb-markup-syntax imdb-markup-syntax
 */
class GeneralTest extends PHPUnit_Framework_TestCase
{

    /**
     * Negativ test, incorrect tconst checked in construct
     * 
     * @expectedException        IMDb_Markup_Syntax\Exceptions\Runtime_Exception
     * @expectedExceptionMessage Incorrect tconst
     * 
     * @covers IMDb_Markup_Syntax\Movie_Datasource::__construct
     * @covers IMDb_Markup_Syntax\Exceptions\Runtime_Exception
     * 
     * @return void
     */
    public function testIncorrectId()
    {
        //Given
        $tconst = $GLOBALS["movieDatasourceData"]["incorrect"];

        //When
        new Movie_Datasource($tconst);
    }

    /**
     * Negativ test, incorrect json syntax
     * 
     * @expectedException        IMDb_Markup_Syntax\Exceptions\Json_Exception
     * @expectedExceptionMessage JSON_ERROR_SYNTAX
     * @expectedExceptionCode    4
     * 
     * @covers IMDb_Markup_Syntax\Movie_Datasource::__construct
     * @covers IMDb_Markup_Syntax\Movie_Datasource::toDataClass
     * @covers IMDb_Markup_Syntax\Exceptions\Json_Exception
     * 
     * @return void
     */
    public function testToDataClassJsonException()
    {
        //Given
        $tconst = $GLOBALS["movieDatasourceData"]["movie"];

        //When
        $imdb = new Movie_Datasource($tconst);
        $imdb->fetchResponse();
        $imdb->response .= "a";
        $imdb->toDataClass();
    }

    /**
     * Negative test, no query data
     * 
     * @expectedException        IMDb_Markup_Syntax\Exceptions\Runtime_Exception
     * @expectedExceptionMessage Empty query
     * 
     * @covers IMDb_Markup_Syntax\Movie_Datasource::__construct
     * @covers IMDb_Markup_Syntax\Movie_Datasource::setRequest
     * @covers IMDb_Markup_Syntax\Exceptions\Runtime_Exception
     * 
     * @return void
     */
    public function testSetRequest()
    {
        //Given
        $imdb = new Movie_Datasource();
        $request = "";

        //When
        $imdb->setRequest($request);
    }

}

?>
