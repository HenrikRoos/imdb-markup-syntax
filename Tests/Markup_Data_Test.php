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

use PHPUnit_Framework_TestCase;

require_once 'PHPUnit/Autoload.php';
require_once dirname(__FILE__) . '/../Markup_Data.php';
require_once dirname(__FILE__) . '/../Movie_Datasource.php';

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
class Markup_Data_Test extends PHPUnit_Framework_TestCase
{

    /**
     * Test get an id sucessful and not on no data
     *
     * @covers IMDb_Markup_Syntax\Markup_Data::__construct
     * @covers IMDb_Markup_Syntax\Markup_Data::getTconst
     * 
     * @return void
     */
    public function testGetTconst()
    {
        $imdb = new Movie_Datasource("tt0137523");
        $data = $imdb->getData();
        $mdata = new Markup_Data($data);
        $expected = "???";
        $actual = $mdata->getTconst();
        $this->assertEquals($expected, $actual);
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

    // <editor-fold defaultstate="collapsed" desc="testGetWriters">
    /**
     * Positive test where movie has two writers
     * 
     * @covers IMDb_Markup_Syntax\Markup_Data::__construct
     * @covers IMDb_Markup_Syntax\Markup_Data::getWriters
     * @covers IMDb_Markup_Syntax\Markup_Data::writer
     * 
     * @return void
     */
    public function testGetWritersTowPositive()
    {
        $imdb = new Movie_Datasource("tt0137523");
        $data = new Markup_Data($imdb->getData());
        $expected = '<a href="http://www.imdb.com/name/nm0657333">Chuck Palahniuk'
            . '</a> (novel), <a href="http://www.imdb.com/name/nm0880243">Jim Uhls'
            . '</a> (screenplay)';
        $actual = $data->getWriters();
        $this->assertEquals($expected, $actual);
    }

    /**
     * Positive test where movie has one writer and no attribute like (nocel)
     * 
     * @covers IMDb_Markup_Syntax\Markup_Data::__construct
     * @covers IMDb_Markup_Syntax\Markup_Data::getWriters
     * @covers IMDb_Markup_Syntax\Markup_Data::writer
     * 
     * @return void
     */
    public function testGetWritersOnePositive()
    {
        $imdb = new Movie_Datasource("tt1564043");
        $data = new Markup_Data($imdb->getData());
        $expected = '<a href="http://www.imdb.com/name/nm3503431">Bryan Litt</a>';
        $actual = $data->getWriters();
        $this->assertEquals($expected, $actual);
    }

    /**
     * Alternative test where movie has no writers
     * 
     * @covers IMDb_Markup_Syntax\Markup_Data::__construct
     * @covers IMDb_Markup_Syntax\Markup_Data::getWriters
     * @covers IMDb_Markup_Syntax\Markup_Data::writer
     * 
     * @return void
     */
    public function testGetWritersNoWriter()
    {
        $imdb = new Movie_Datasource("tt1129398");
        $data = new Markup_Data($imdb->getData());
        $expected = false;
        $actual = $data->getWriters();
        $this->assertEquals($expected, $actual);
    }

    // </editor-fold>

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
