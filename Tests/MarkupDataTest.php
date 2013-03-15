<?php

/**
 * Testclass (PHPUnit) test for MarkupData class
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

namespace IMDbMarkupSyntax;

use PHPUnit_Framework_TestCase;

require_once 'PHPUnit/Autoload.php';
require_once dirname(__FILE__) . '/../MarkupData.php';
require_once dirname(__FILE__) . '/../MovieDatasource.php';

/**
 * Testclass (PHPUnit) test for MarkupData class
 * 
 * @category  Testable
 * @package   Test
 * @author    Henrik Roos <henrik.roos@afternoon.se>
 * @copyright 2013 Henrik Roos
 * @license   https://github.com/HenrikRoos/imdb-markup-syntax/blob/master/imdb-markup-syntax.php GPL2
 * @link      https://github.com/HenrikRoos/imdb-markup-syntax imdb-markup-syntax
 */
class MarkupDataTest extends PHPUnit_Framework_TestCase
{

    /**
     * Test get an id sucessful and not on no data
     *
     * @covers IMDbMarkupSyntax\MarkupData::__construct
     * @covers IMDbMarkupSyntax\MarkupData::getTconst
     * 
     * @return void
     */
    public function testGetTconst()
    {
        $imdb = new MovieDatasource("tt0137523");
        $data = $imdb->getData();
        $mdata = new MarkupData($data);
        $expected = "???";
        $actual = $mdata->getTconst();
        $this->assertEquals($expected, $actual);
    }

    /**
     * @covers IMDbMarkupSyntax\MarkupData::getTitle
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
     * @covers IMDbMarkupSyntax\MarkupData::getType
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
     * @covers IMDbMarkupSyntax\MarkupData::getGenres
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
     * @covers IMDbMarkupSyntax\MarkupData::getReleaseDate
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
     * @covers IMDbMarkupSyntax\MarkupData::getRuntime
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
     * @covers IMDbMarkupSyntax\MarkupData::getRating
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
     * @covers IMDbMarkupSyntax\MarkupData::getVotes
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
     * @covers IMDbMarkupSyntax\MarkupData::getPlot
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
     * @covers IMDbMarkupSyntax\MarkupData::getTagline
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
     * @covers IMDbMarkupSyntax\MarkupData::getCast
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
     * @covers IMDbMarkupSyntax\MarkupData::__construct
     * @covers IMDbMarkupSyntax\MarkupData::getWriters
     * @covers IMDbMarkupSyntax\MarkupData::writer
     * 
     * @return void
     */
    public function testGetWritersTowPositive()
    {
        $imdb = new MovieDatasource("tt0137523");
        $data = new MarkupData($imdb->getData());
        $expected = '<a href="http://www.imdb.com/name/nm0657333">Chuck Palahniuk'
            . '</a> (novel), <a href="http://www.imdb.com/name/nm0880243">Jim Uhls'
            . '</a> (screenplay)';
        $actual = $data->getWriters();
        $this->assertEquals($expected, $actual);
    }

    /**
     * Positive test where movie has one writer and no attribute like (nocel)
     * 
     * @covers IMDbMarkupSyntax\MarkupData::__construct
     * @covers IMDbMarkupSyntax\MarkupData::getWriters
     * @covers IMDbMarkupSyntax\MarkupData::writer
     * 
     * @return void
     */
    public function testGetWritersOnePositive()
    {
        $imdb = new MovieDatasource("tt1564043");
        $data = new MarkupData($imdb->getData());
        $expected = '<a href="http://www.imdb.com/name/nm3503431">Bryan Litt</a>';
        $actual = $data->getWriters();
        $this->assertEquals($expected, $actual);
    }

    /**
     * Alternative test where movie has no writers
     * 
     * @covers IMDbMarkupSyntax\MarkupData::__construct
     * @covers IMDbMarkupSyntax\MarkupData::getWriters
     * @covers IMDbMarkupSyntax\MarkupData::writer
     * 
     * @return void
     */
    public function testGetWritersNoWriter()
    {
        $imdb = new MovieDatasource("tt1129398");
        $data = new MarkupData($imdb->getData());
        $expected = false;
        $actual = $data->getWriters();
        $this->assertEquals($expected, $actual);
    }

    // </editor-fold>

    /**
     * @covers IMDbMarkupSyntax\MarkupData::getDirectors
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
     * @covers IMDbMarkupSyntax\MarkupData::getCertificate
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
     * @covers IMDbMarkupSyntax\MarkupData::getPoster
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
