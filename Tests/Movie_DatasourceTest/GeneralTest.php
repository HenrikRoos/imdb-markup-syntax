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
use IMDb_Markup_Syntax\Exceptions\Runtime_Exception;

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
     * @covers IMDb_Markup_Syntax\Movie_Datasource::__construct
     * @covers IMDb_Markup_Syntax\Exceptions\Runtime_Exception
     * 
     * @return void
     */
    public function testIncorrectId()
    {
        //Given
        $tconst = $GLOBALS["movieDatasourceData"]["incorrect"];
        $expected
            = sprintf(__("Incorrect tconst %s", "imdb-markup-syntax"), $tconst);

        //When
        try {
            new Movie_Datasource($tconst);
        }

        //Then
        catch (Runtime_Exception $exp) {
            $this->assertSame($expected, $exp->getMessage());
            return;
        }

        $this->fail("An expected Runtime_Exception has not been raised.");
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
     * @covers IMDb_Markup_Syntax\Movie_Datasource::__construct
     * @covers IMDb_Markup_Syntax\Movie_Datasource::setRequest
     * @covers IMDb_Markup_Syntax\Exceptions\Runtime_Exception
     * 
     * @return void
     */
    public function testSetRequest()
    {
        //Given
        $request = "";
        $expected = __("Empty query", "imdb-markup-syntax");

        //When
        try {
            $imdb = new Movie_Datasource();
            $imdb->setRequest($request);
        }

        //Then
        catch (Runtime_Exception $exp) {
            $this->assertSame($expected, $exp->getMessage());
            return;
        }

        $this->fail("An expected Runtime_Exception has not been raised.");
    }

}

?>
