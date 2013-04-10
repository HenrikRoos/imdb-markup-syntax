<?php

/**
 * Testclass to Markup_DataSuite for method getDate in Markup_Data class
 * 
 * PHP version 5
 * 
 * @category  Testable
 * @package   Test
 * @author    Henrik Roos <henrik.roos@afternoon.se>
 * @copyright 2013 Henrik Roos
 * @license   http://opensource.org/licenses/gpl-3.0.html GPL-3.0
 * @link      https://github.com/HenrikRoos/imdb-markup-syntax imdb-markup-syntax
 */

namespace IMDb_Markup_Syntax\Markup_DataTest;

use IMDb_Markup_Syntax\Markup_Data;
use IMDb_Markup_Syntax\Movie_Datasource;
use PHPUnit_Framework_TestCase;

require_once dirname(__FILE__) . "/../../Markup_Data.php";
require_once dirname(__FILE__) . "/../../Movie_Datasource.php";
require_once "PHPUnit/Autoload.php";

/**
 * Testclass to Markup_DataSuite for method getDate in Markup_Data class
 * 
 * @category  Testable
 * @package   Test
 * @author    Henrik Roos <henrik.roos@afternoon.se>
 * @copyright 2013 Henrik Roos
 * @license   http://opensource.org/licenses/gpl-3.0.html GPL-3.0
 * @link      https://github.com/HenrikRoos/imdb-markup-syntax imdb-markup-syntax
 */
class Get_DateTest extends PHPUnit_Framework_TestCase
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
        $this->testdataPositive = "tt0468569";
    }

    /**
     * Positive test: Get data sucessful then release date is set
     *
     * @covers IMDb_Markup_Syntax\Markup_Data::__construct
     * @covers IMDb_Markup_Syntax\Markup_Data::getDate
     * @covers IMDb_Markup_Syntax\Markup_Data::getValueValue
     * 
     * @return void
     */
    public function testReleaseDatePositive()
    {
        //Given
        $imdb = new Movie_Datasource($this->testdataPositive);
        $data = $imdb->getData();
        $expected = "2008-07-18";

        //When
        $mdata = new Markup_Data($data);
        $actual = $mdata->getDate();

        //Then
        $this->assertSame($expected, $actual);
    }

    /**
     * Alternative positive test: No release_date is not set but year is set
     *
     * @covers IMDb_Markup_Syntax\Markup_Data::__construct
     * @covers IMDb_Markup_Syntax\Markup_Data::getDate
     * @covers IMDb_Markup_Syntax\Markup_Data::getValueValue
     * 
     * @return void
     */
    public function testYearAlternative()
    {
        //Given
        $imdb = new Movie_Datasource($this->testdataPositive);
        $data = $imdb->getData();
        $data->release_date->normal = "";
        $expected = "2008";

        //When
        $mdata = new Markup_Data($data);
        $actual = $mdata->getDate();

        //Then
        $this->assertSame($expected, $actual);
    }

    /**
     * Negative test: Data is not set (release date and year)
     *
     * @covers IMDb_Markup_Syntax\Markup_Data::__construct
     * @covers IMDb_Markup_Syntax\Markup_Data::getDate
     * @covers IMDb_Markup_Syntax\Markup_Data::getValueValue
     * 
     * @return void
     */
    public function testNoSet()
    {
        //Given
        $imdb = new Movie_Datasource($this->testdataPositive);
        $data = $imdb->getData();
        unset($data->release_date);
        unset($data->year);
        $expected = false;

        //When
        $mdata = new Markup_Data($data);
        $actual = $mdata->getDate();

        //Then
        $this->assertSame($expected, $actual);
    }

    /**
     * Negative test: Data is empty
     *
     * @covers IMDb_Markup_Syntax\Markup_Data::__construct
     * @covers IMDb_Markup_Syntax\Markup_Data::getDate
     * @covers IMDb_Markup_Syntax\Markup_Data::getValueValue
     * 
     * @return void
     */
    public function testEmpty()
    {
        //Given
        $imdb = new Movie_Datasource($this->testdataPositive);
        $data = $imdb->getData();
        $data->release_date->normal = "";
        $data->year = "";
        $expected = false;

        //When
        $mdata = new Markup_Data($data);
        $actual = $mdata->getDate();

        //Then
        $this->assertSame($expected, $actual);
    }

}

?>
