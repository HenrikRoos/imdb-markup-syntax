<?php

/**
 * Test suite (PHPUnit) test for Movie_Datasource tests
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

namespace IMDb_Markup_Syntax;

use PHPUnit_Framework_TestSuite;

require_once 'PHPUnit/Autoload.php';
require_once dirname(__FILE__) . '/../Movie_Datasource.php';
require_once dirname(__FILE__) . '/Movie_DatasourceTest/GeneralTest.php';
require_once dirname(__FILE__) . '/Movie_DatasourceTest/Fetch_ResponseTest.php';
require_once dirname(__FILE__) . '/Movie_DatasourceTest/Get_DataTest.php';

/**
 * Test suite (PHPUnit) test for Movie_Datasource tests
 * 
 * @category  Testable
 * @package   Test
 * @author    Henrik Roos <henrik.roos@afternoon.se>
 * @copyright 2013 Henrik Roos
 * @license   https://github.com/HenrikRoos/imdb-markup-syntax/blob/master/imdb-markup-syntax.php GPL2
 * @link      https://github.com/HenrikRoos/imdb-markup-syntax imdb-markup-syntax
 */
class Movie_DatasourceSuite extends PHPUnit_Framework_TestSuite
{

    /**
     * Sets up testdata in superglobal $GLOBALS['movieDatasourceData']
     *
     * @return void
     */
    protected function setUp()
    {
        $GLOBALS["movieDatasourceData"] = array(
            "movie" => "tt0137523",
            "tvserie" => "tt0402711",
            "videogame" => "tt1843198",
            "nodata" => "tt0000000",
            "incorrect" => "a b c"
        );
    }

    /**
     * Definition tests for Movie_Datasource suite
     * 
     * @return \IMDb_Markup_Syntax\Movie_DatasourceSuite
     */
    public static function suite()
    {
        $suite = new self();
        $suite->addTestSuite(
            "IMDb_Markup_Syntax\Movie_DatasourceTest\GeneralTest"
        );
        $suite->addTestSuite(
            "IMDb_Markup_Syntax\Movie_DatasourceTest\Fetch_ResponseTest"
        );
        $suite->addTestSuite(
            "IMDb_Markup_Syntax\Movie_DatasourceTest\Get_DataTest"
        );
        return $suite;
    }

    /**
     * Unset testdata in superblobal $GLOBALS['tagProcessingData'] variable
     * 
     * @return void
     */
    protected function tearDown()
    {
        unset($GLOBALS["movieDatasourceData"]);
    }

}
