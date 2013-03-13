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
require_once dirname(__FILE__) . '/../class-markup-data.php';
require_once dirname(__FILE__) . '/../class-movie-datasource.php';

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
     * Positive test where movie has two writers
     * 
     * @covers IMDbMarkupSyntax\MarkupData::__construct
     * @covers IMDbMarkupSyntax\MarkupData::writers
     * @covers IMDbMarkupSyntax\MarkupData::writer
     * 
     * @return void
     */
    public function testWritersTowPositive()
    {
        $imdb = new MovieDatasource("tt0137523");
        $data = new MarkupData($imdb->getData());
        $expected = '<a href="http://www.imdb.com/name/nm0657333">Chuck Palahniuk'
            . '</a> (novel), <a href="http://www.imdb.com/name/nm0880243">Jim Uhls'
            . '</a> (screenplay)';
        $actual = $data->writers();
        $this->assertEquals($expected, $actual);
    }

    /**
     * Positive test where movie has one writer and no attribute like (nocel)
     * 
     * @covers IMDbMarkupSyntax\MarkupData::__construct
     * @covers IMDbMarkupSyntax\MarkupData::writers
     * @covers IMDbMarkupSyntax\MarkupData::writer
     * 
     * @return void
     */
    public function testWritersOnePositive()
    {
        $imdb = new MovieDatasource("tt1564043");
        $data = new MarkupData($imdb->getData());
        $expected = '<a href="http://www.imdb.com/name/nm3503431">Bryan Litt</a>';
        $actual = $data->writers();
        $this->assertEquals($expected, $actual);
    }

    /**
     * Alternative test where movie has no writers
     * 
     * @covers IMDbMarkupSyntax\MarkupData::__construct
     * @covers IMDbMarkupSyntax\MarkupData::writers
     * @covers IMDbMarkupSyntax\MarkupData::writer
     * 
     * @return void
     */
    public function testWritersNoWriter()
    {
        $imdb = new MovieDatasource("tt1129398");
        $data = new MarkupData($imdb->getData());
        $expected = false;
        $actual = $data->writers();
        $this->assertEquals($expected, $actual);
    }

}
