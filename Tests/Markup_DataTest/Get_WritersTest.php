<?php

/**
 * Sub testclass to Markup_DataTest for method getWriters in Markup_Data class
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

namespace IMDb_Markup_Syntax\Markup_DataTest;

use IMDb_Markup_Syntax\Markup_Data;
use IMDb_Markup_Syntax\Movie_Datasource;
use PHPUnit_Framework_TestCase;

require_once dirname(__FILE__) . '/../../Markup_Data.php';
require_once dirname(__FILE__) . '/../../Movie_Datasource.php';
require_once 'PHPUnit/Autoload.php';

/**
 * Sub testclass to Markup_DataTest for method getWriters in Markup_Data class
 * 
 * @category  Testable
 * @package   Test
 * @author    Henrik Roos <henrik.roos@afternoon.se>
 * @copyright 2013 Henrik Roos
 * @license   https://github.com/HenrikRoos/imdb-markup-syntax/blob/master/imdb-markup-syntax.php GPL2
 * @link      https://github.com/HenrikRoos/imdb-markup-syntax imdb-markup-syntax
 */
class Get_WritersTest extends PHPUnit_Framework_TestCase
{

    /**
     * Positive test where movie has two writers
     * 
     * @covers IMDb_Markup_Syntax\Markup_Data::__construct
     * @covers IMDb_Markup_Syntax\Markup_Data::getWriters
     * @covers IMDb_Markup_Syntax\Markup_Data::writer
     * @covers IMDb_Markup_Syntax\Markup_Data::hasName
     * 
     * @return void
     */
    public function testTowPositive()
    {
        //Given
        $imdb = new Movie_Datasource("tt0137523");
        $data = new Markup_Data($imdb->getData());
        $expected = '<a href="http://www.imdb.com/name/nm0657333">Chuck Palahniuk'
            . '</a> (novel), <a href="http://www.imdb.com/name/nm0880243">Jim Uhls'
            . '</a> (screenplay)';

        //When
        $actual = $data->getWriters();

        //Then
        $this->assertSame($expected, $actual);
    }

    /**
     * Positive test where movie has one writer and no attribute like (nocel)
     * 
     * @covers IMDb_Markup_Syntax\Markup_Data::__construct
     * @covers IMDb_Markup_Syntax\Markup_Data::getWriters
     * @covers IMDb_Markup_Syntax\Markup_Data::writer
     * @covers IMDb_Markup_Syntax\Markup_Data::hasName
     * 
     * @return void
     */
    public function testOnePositive()
    {
        //Given
        $imdb = new Movie_Datasource("tt1564043");
        $data = new Markup_Data($imdb->getData());
        $expected = '<a href="http://www.imdb.com/name/nm3503431">Bryan Litt</a>';

        //When
        $actual = $data->getWriters();

        //Then
        $this->assertSame($expected, $actual);
    }

    /**
     * Alternative test where movie has no writers
     * 
     * @covers IMDb_Markup_Syntax\Markup_Data::__construct
     * @covers IMDb_Markup_Syntax\Markup_Data::getWriters
     * @covers IMDb_Markup_Syntax\Markup_Data::writer
     * @covers IMDb_Markup_Syntax\Markup_Data::hasName
     * 
     * @return void
     */
    public function testNoWriter()
    {
        //Given
        $imdb = new Movie_Datasource("tt1129398");
        $data = new Markup_Data($imdb->getData());
        $expected = false;

        //When
        $actual = $data->getWriters();

        //Then
        $this->assertSame($expected, $actual);
    }

}

?>
