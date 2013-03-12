<?php

namespace IMDb_Markup_Syntax;

use PHPUnit_Framework_TestCase;

require_once 'PHPUnit/Autoload.php';
require_once dirname(__FILE__) . '/../class-markup-data.php';
require_once dirname(__FILE__) . '/../class-movie-datasource.php';

/**
 * Testclass (PHPUnit) test for Markup_Data class
 * @author Henrik Roos <henrik@afternoon.se>
 * @package Test
 */
class Markup_DataTest extends PHPUnit_Framework_TestCase
{

    /**
     * Positive test where movie has two writers
     * @covers IMDb_Markup_Syntax\Markup_Data::__construct
     * @covers IMDb_Markup_Syntax\Markup_Data::writers
     * @covers IMDb_Markup_Syntax\Markup_Data::writer
     */
    public function testWritersTowPositive()
    {
        $imdb = new Movie_Datasource("tt0137523");
        $data = new Markup_Data($imdb->getData());
        $expected = '<a href="http://www.imdb.com/name/nm0657333">Chuck Palahniuk</a> (novel), '
                . '<a href="http://www.imdb.com/name/nm0880243">Jim Uhls</a> (screenplay)';
        $actual = $data->writers();
        $this->assertEquals($expected, $actual);
    }

    /**
     * Positive test where movie has one writer and no attribute like (nocel)
     * @covers IMDb_Markup_Syntax\Markup_Data::__construct
     * @covers IMDb_Markup_Syntax\Markup_Data::writers
     * @covers IMDb_Markup_Syntax\Markup_Data::writer
     */
    public function testWritersOnePositive()
    {
        $imdb = new Movie_Datasource("tt1564043");
        $data = new Markup_Data($imdb->getData());
        $expected = '<a href="http://www.imdb.com/name/nm3503431">Bryan Litt</a>';
        $actual = $data->writers();
        $this->assertEquals($expected, $actual);
    }

    /**
     * Alternative test where movie has no writers
     * @covers IMDb_Markup_Syntax\Markup_Data::__construct
     * @covers IMDb_Markup_Syntax\Markup_Data::writers
     * @covers IMDb_Markup_Syntax\Markup_Data::writer
     */
    public function testWritersNoWriter()
    {
        $imdb = new Movie_Datasource("tt1129398");
        $data = new Markup_Data($imdb->getData());
        $expected = false;
        $actual = $data->writers();
        $this->assertEquals($expected, $actual);
    }

}
