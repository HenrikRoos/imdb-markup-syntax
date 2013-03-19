<?php

/**
 * Testclass (PHPUnit) test for Markup_Data class
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
require_once dirname(__FILE__) . '/Markup_DataTest/Get_TconstTest.php';
require_once dirname(__FILE__) . '/Markup_DataTest/Get_WritersTest.php';

/**
 * Testclass (PHPUnit) test for Markup_Data class
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
     * Definition tests for Movie_Datasource suite
     * 
     * @return \IMDb_Markup_Syntax\Movie_DatasourceSuite
     */
    public static function suite()
    {
        $suite = new self();
        $suite->addTestSuite(
            "IMDb_Markup_Syntax\Markup_DataTest\Get_TconstTest"
        );
        $suite->addTestSuite(
            "IMDb_Markup_Syntax\Markup_DataTest\Get_WritersTest"
        );
        return $suite;
    }

    /**
     * @covers IMDb_Markup_Syntax\Markup_Data::getTitle
     * @todo   Implement testGetTitle().
     */
    public function testGetTitle()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    /**
     * @covers IMDb_Markup_Syntax\Markup_Data::getType
     * @todo   Implement testGetType().
     */
    public function testGetType()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    /**
     * @covers IMDb_Markup_Syntax\Markup_Data::getGenres
     * @todo   Implement testGetGenres().
     */
    public function testGetGenres()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    /**
     * @covers IMDb_Markup_Syntax\Markup_Data::getReleaseDate
     * @todo   Implement testGetReleaseDate().
     */
    public function testGetReleaseDate()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    /**
     * @covers IMDb_Markup_Syntax\Markup_Data::getRuntime
     * @todo   Implement testGetRuntime().
     */
    public function testGetRuntime()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    /**
     * @covers IMDb_Markup_Syntax\Markup_Data::getRating
     * @todo   Implement testGetRating().
     */
    public function testGetRating()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    /**
     * @covers IMDb_Markup_Syntax\Markup_Data::getVotes
     * @todo   Implement testGetVotes().
     */
    public function testGetVotes()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    /**
     * @covers IMDb_Markup_Syntax\Markup_Data::getPlot
     * @todo   Implement testGetPlot().
     */
    public function testGetPlot()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    /**
     * @covers IMDb_Markup_Syntax\Markup_Data::getTagline
     * @todo   Implement testGetTagline().
     */
    public function testGetTagline()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    /**
     * @covers IMDb_Markup_Syntax\Markup_Data::getCast
     * @todo   Implement testGetCast().
     */
    public function testGetCast()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    /**
     * @covers IMDb_Markup_Syntax\Markup_Data::getDirectors
     * @todo   Implement testGetDirectors().
     */
    public function testGetDirectors()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    /**
     * @covers IMDb_Markup_Syntax\Markup_Data::getCertificate
     * @todo   Implement testGetCertificate().
     */
    public function testGetCertificate()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    /**
     * @covers IMDb_Markup_Syntax\Markup_Data::getPoster
     * @todo   Implement testGetPoster().
     */
    public function testGetPoster()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

}
