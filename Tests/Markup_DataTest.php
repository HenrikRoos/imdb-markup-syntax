<?php

/**
 * PhpDoc: Page-level DocBlock
 * @package imdb-markup-syntax-test
 */

namespace IMDb_Markup_Syntax\Tests;

use IMDb_Markup_Syntax\Markup_Data;
use IMDb_Markup_Syntax\Movie_Datasource;
use PHPUnit_Framework_TestCase;

require_once 'PHPUnit/Autoload.php';
require_once dirname(__FILE__) . '/../class-markup-data.php';
require_once dirname(__FILE__) . '/../class-movie-datasource.php';

/**
 * Testclass (PHPUnit) test for Markup_Data class
 * @author Henrik Roos <henrik at afternoon.se>
 * @package imdb-markup-syntax-test
 */
class Markup_DataTest extends PHPUnit_Framework_TestCase {

    /**
     * Positive test where movie has two writers
     * @covers IMDb_Markup_Syntax\Markup_Data::__construct
     * @covers IMDb_Markup_Syntax\Markup_Data::writers_summary
     * @covers IMDb_Markup_Syntax\Markup_Data::writer_summary
     */
    public function testWriters_summaryTowPositive() {
        $imdb = new Movie_Datasource("tt0137523");
        $data = new Markup_Data($imdb->getData());
        $expected = '<a href="http://www.imdb.com/name/nm0657333">Chuck Palahniuk</a> (novel), <a href="http://www.imdb.com/name/nm0880243">Jim Uhls</a> (screenplay)';
        $actual = $data->writers_summary();
        $this->assertEquals($expected, $actual);
    }

    /**
     * Positive test where movie has one writer
     * @covers IMDb_Markup_Syntax\Markup_Data::__construct
     * @covers IMDb_Markup_Syntax\Markup_Data::writers_summary
     * @covers IMDb_Markup_Syntax\Markup_Data::writer_summary
     */
    public function testWriters_summaryOnePositive() {
        $imdb = new Movie_Datasource("tt1564043");
        $data = new Markup_Data($imdb->getData());
        $expected = '<a href="http://www.imdb.com/name/nm3503431">Bryan Litt</a>';
        $actual = $data->writers_summary();
        $this->assertEquals($expected, $actual);
    }

    /**
     * Alternative test where movie has no writers
     * @covers IMDb_Markup_Syntax\Markup_Data::__construct
     * @covers IMDb_Markup_Syntax\Markup_Data::writers_summary
     * @covers IMDb_Markup_Syntax\Markup_Data::writer_summary
     */
    public function testWriters_summaryNoWriter() {
        $imdb = new Movie_Datasource("tt1129398");
        $data = new Markup_Data($imdb->getData());
        $expected = FALSE;
        $actual = $data->writers_summary();
        $this->assertEquals($expected, $actual);
    }

}
