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
 * @license   https://github.com/HenrikRoos/imdb-markup-syntax/blob/master/imdb-markup-syntax.php GPL2
 * @link      https://github.com/HenrikRoos/imdb-markup-syntax imdb-markup-syntax
 */

namespace IMDb_Markup_Syntax\Movie_DatasourceTest;

use IMDb_Markup_Syntax\Movie_Datasource;
use PHPUnit_Framework_TestCase;

require_once dirname(__FILE__) . '/../../Movie_Datasource.php';
require_once 'PHPUnit/Autoload.php';

/**
 * Sub testclass to Movie_DatasourceTest for general tests in Movie_Datasource
 * 
 * @category  Testable
 * @package   Test
 * @author    Henrik Roos <henrik.roos@afternoon.se>
 * @copyright 2013 Henrik Roos
 * @license   https://github.com/HenrikRoos/imdb-markup-syntax/blob/master/imdb-markup-syntax.php GPL2
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
        //When
        new Movie_Datasource(
            //Given
            $GLOBALS["movieDatasourceData"]["incorrect"]
        );
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
        $imdb = new Movie_Datasource($GLOBALS["movieDatasourceData"]["movie"]);
        $imdb->fetchResponse();
        $imdb->response .= "a";

        //When
        $imdb->toDataClass();
    }
    
    /**
     * Negative test, no query data
     * 
     * @expectedException        IMDb_Markup_Syntax\Exceptions\Runtime_Exception
     * @expectedExceptionMessage No query
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
        //When
        $imdb->setRequest("");
    }

}
?>
