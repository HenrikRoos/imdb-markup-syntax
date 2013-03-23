<?php

/**
 * Testclass to Markup_DataSuite for method getCast in Markup_Data class
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
 * Testclass to Markup_DataSuite for method getCast in Markup_Data class
 * 
 * @category  Testable
 * @package   Test
 * @author    Henrik Roos <henrik.roos@afternoon.se>
 * @copyright 2013 Henrik Roos
 * @license   https://github.com/HenrikRoos/imdb-markup-syntax/blob/master/imdb-markup-syntax.php GPL2
 * @link      https://github.com/HenrikRoos/imdb-markup-syntax imdb-markup-syntax
 */
class Get_CastTest extends PHPUnit_Framework_TestCase
{

    /** @var string positive testdata */
    public $testdataPositive;

    /**
     * Set up local testdata
     * 
     * @return void
     */
    protected function setUp()
    {
        $this->testdataPositive = "tt0137523";
    }

    /**
     * Positive test: Get data sucessful
     *
     * @covers IMDb_Markup_Syntax\Markup_Data::__construct
     * @covers IMDb_Markup_Syntax\Markup_Data::getCast
     * @covers IMDb_Markup_Syntax\Markup_Data::toSummaryString
     * @covers IMDb_Markup_Syntax\Markup_Data::toPersonsList
     * @covers IMDb_Markup_Syntax\Markup_Data::toPersonString
     * @covers IMDb_Markup_Syntax\Markup_Data::toNameString
     * @covers IMDb_Markup_Syntax\Markup_Data::isNotEmpty
     * 
     * @return void
     */
    public function testPositive()
    {
        //Given
        $imdb = new Movie_Datasource($this->testdataPositive);
        $data = $imdb->getData();
        $expected = <<<DATA
<a href="http://www.imdb.com/name/nm0000093">Brad Pitt</a> Tyler Durden
<a href="http://www.imdb.com/name/nm0001570">Edward Norton</a> The Narrator
<a href="http://www.imdb.com/name/nm0000307">Helena Bonham Carter</a> Marla Singer
<a href="http://www.imdb.com/name/nm0001533">Meat Loaf</a> (as Meat Loaf Aday) Robert 'Bob' Paulson
DATA;
        //When
        $mdata = new Markup_Data($data);
        $actual = $mdata->getCast();

        //Then
        $this->assertSame($expected, $actual);
    }

    /**
     * Negative test: No data is set
     *
     * @covers IMDb_Markup_Syntax\Markup_Data::__construct
     * @covers IMDb_Markup_Syntax\Markup_Data::getCast
     * @covers IMDb_Markup_Syntax\Markup_Data::toSummaryString
     * @covers IMDb_Markup_Syntax\Markup_Data::toPersonsList
     * @covers IMDb_Markup_Syntax\Markup_Data::toPersonString
     * @covers IMDb_Markup_Syntax\Markup_Data::toNameString
     * @covers IMDb_Markup_Syntax\Markup_Data::isNotEmpty
     * 
     * @return void
     */
    public function testNotSet()
    {
        //Given
        $imdb = new Movie_Datasource($this->testdataPositive);
        $data = $imdb->getData();
        unset($data->cast_summary);
        $expected = false;

        //When
        $mdata = new Markup_Data($data);
        $actual = $mdata->getCast();

        //Then
        $this->assertSame($expected, $actual);
    }

    /**
     * Negative test: Data is empty
     *
     * @covers IMDb_Markup_Syntax\Markup_Data::__construct
     * @covers IMDb_Markup_Syntax\Markup_Data::getCast
     * @covers IMDb_Markup_Syntax\Markup_Data::toSummaryString
     * @covers IMDb_Markup_Syntax\Markup_Data::toPersonsList
     * @covers IMDb_Markup_Syntax\Markup_Data::toPersonString
     * @covers IMDb_Markup_Syntax\Markup_Data::toNameString
     * @covers IMDb_Markup_Syntax\Markup_Data::isNotEmpty
     * 
     * @return void
     */
    public function testEmpty()
    {
        //Given
        $imdb = new Movie_Datasource($this->testdataPositive);
        $data = $imdb->getData();
        $data->cast_summary = array($data->cast_summary[0]);
        unset($data->cast_summary[0]->name->nconst);
        $data->cast_summary[0]->char = "";
        $data->cast_summary[0]->name->name = " ";
        $expected = false;

        //When
        $mdata = new Markup_Data($data);
        $actual = $mdata->getCast();

        //Then
        $this->assertSame($expected, $actual);
    }

}

?>
