<?php

/**
 * Test suite (PHPUnit) test for Markup_Data tests
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
require_once dirname(__FILE__) . '/Markup_DataTest/Get_CastTest.php';
require_once dirname(__FILE__) . '/Markup_DataTest/Get_CertificateTest.php';
require_once dirname(__FILE__) . '/Markup_DataTest/Get_DirectorsTest.php';
require_once dirname(__FILE__) . '/Markup_DataTest/Get_GenresTest.php';
require_once dirname(__FILE__) . '/Markup_DataTest/Get_PlotTest.php';
require_once dirname(__FILE__) . '/Markup_DataTest/Get_PosterTest.php';
require_once dirname(__FILE__) . '/Markup_DataTest/Get_RatingTest.php';
require_once dirname(__FILE__) . '/Markup_DataTest/Get_DateTest.php';
require_once dirname(__FILE__) . '/Markup_DataTest/Get_RuntimeTest.php';
require_once dirname(__FILE__) . '/Markup_DataTest/Get_TaglineTest.php';
require_once dirname(__FILE__) . '/Markup_DataTest/Get_TconstTest.php';
require_once dirname(__FILE__) . '/Markup_DataTest/Get_TitleTest.php';
require_once dirname(__FILE__) . '/Markup_DataTest/Get_TypeTest.php';
require_once dirname(__FILE__) . '/Markup_DataTest/Get_VotesTest.php';
require_once dirname(__FILE__) . '/Markup_DataTest/Get_WritersTest.php';

/**
 * Test suite (PHPUnit) test for Markup_Data tests
 * 
 * @category  Testable
 * @package   Test
 * @author    Henrik Roos <henrik.roos@afternoon.se>
 * @copyright 2013 Henrik Roos
 * @license   https://github.com/HenrikRoos/imdb-markup-syntax/blob/master/imdb-markup-syntax.php GPL2
 * @link      https://github.com/HenrikRoos/imdb-markup-syntax imdb-markup-syntax
 */
class Markup_DataSuite extends PHPUnit_Framework_TestSuite
{

    /**
     * Definition tests for Markup_Data suite
     * 
     * @return IMDb_Markup_Syntax\Movie_DatasourceSuite
     */
    public static function suite()
    {
        $suite = new self();
        $namespace = "IMDb_Markup_Syntax\Markup_DataTest";

        $suite->addTestSuite($namespace . "\Get_CastTest");
        $suite->addTestSuite($namespace . "\Get_CertificateTest");
        $suite->addTestSuite($namespace . "\Get_DateTest");
        $suite->addTestSuite($namespace . "\Get_DirectorsTest");
        $suite->addTestSuite($namespace . "\Get_GenresTest");
        $suite->addTestSuite($namespace . "\Get_PlotTest");
        $suite->addTestSuite($namespace . "\Get_PosterTest");
        $suite->addTestSuite($namespace . "\Get_RatingTest");
        $suite->addTestSuite($namespace . "\Get_RuntimeTest");
        $suite->addTestSuite($namespace . "\Get_TaglineTest");
        $suite->addTestSuite($namespace . "\Get_TconstTest");
        $suite->addTestSuite($namespace . "\Get_TitleTest");
        
        
        $suite->addTestSuite($namespace . "\Get_WritersTest");
        return $suite;
    }

}
