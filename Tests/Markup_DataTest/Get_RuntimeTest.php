<?php

/**
 * Testclass to Markup_DataSuite for method getRuntime in Markup_Data class
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
 * Testclass to Markup_DataSuite for method getRuntime in Markup_Data class
 * 
 * @category  Testable
 * @package   Test
 * @author    Henrik Roos <henrik.roos@afternoon.se>
 * @copyright 2013 Henrik Roos
 * @license   http://opensource.org/licenses/gpl-3.0.html GPL-3.0
 * @link      https://github.com/HenrikRoos/imdb-markup-syntax imdb-markup-syntax
 */
class Get_RuntimeTest extends PHPUnit_Framework_TestCase
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
        $this->testdataPositive = "tt1806963";
        setlocale(LC_ALL, "");
    }

    /**
     * Clean up after testing.
     * 
     * @return void
     */
    protected function tearDown()
    {
        parent::tearDown();
        setlocale(LC_ALL, "");
    }

    /**
     * Positive test: Get data sucessful
     *
     * @covers IMDb_Markup_Syntax\Markup_Data::__construct
     * @covers IMDb_Markup_Syntax\Markup_Data::getRuntime
     * @covers IMDb_Markup_Syntax\Markup_Data::getValueValue
     * @covers IMDb_Markup_Syntax\Markup_Data::numberFormatLocale
     * 
     * @return void
     */
    public function testPositive()
    {
        //Given
        $expected = "5700";

        //When
        $imdb = new Movie_Datasource($this->testdataPositive);
        $data = $imdb->getData();
        $mdata = new Markup_Data($data);
        $actual = $mdata->getRuntime();

        //Then
        $this->assertSame($expected, $actual);
    }

    /**
     * Positive test: Get data sucessful for Swedish language
     *
     * @covers IMDb_Markup_Syntax\Markup_Data::__construct
     * @covers IMDb_Markup_Syntax\Markup_Data::getRuntime
     * @covers IMDb_Markup_Syntax\Markup_Data::getValueValue
     * @covers IMDb_Markup_Syntax\Markup_Data::numberFormatLocale
     * 
     * @return void
     */
    public function testPositiveSwedish()
    {
        //Given
        $locale = "sv_SE";
        $expected = "5 700";

        //When
        $imdb = new Movie_Datasource($this->testdataPositive, $locale);
        $data = $imdb->getData();
        $mdata = new Markup_Data($data, $locale);
        $actual = $mdata->getRuntime();

        //Then
        $this->assertSame($expected, $actual);
    }

    /**
     * Negative test: No data is set
     *
     * @covers IMDb_Markup_Syntax\Markup_Data::__construct
     * @covers IMDb_Markup_Syntax\Markup_Data::getRuntime
     * @covers IMDb_Markup_Syntax\Markup_Data::getValueValue
     * @covers IMDb_Markup_Syntax\Markup_Data::numberFormatLocale
     * 
     * @return void
     */
    public function testNotSet()
    {
        //Given
        $imdb = new Movie_Datasource($this->testdataPositive);
        $data = $imdb->getData();
        unset($data->runtime->time);
        $expected = false;

        //When
        $mdata = new Markup_Data($data);
        $actual = $mdata->getRuntime();

        //Then
        $this->assertSame($expected, $actual);
    }

    /**
     * Negative test: Data is empty
     *
     * @covers IMDb_Markup_Syntax\Markup_Data::__construct
     * @covers IMDb_Markup_Syntax\Markup_Data::getRuntime
     * @covers IMDb_Markup_Syntax\Markup_Data::getValueValue
     * @covers IMDb_Markup_Syntax\Markup_Data::numberFormatLocale
     * 
     * @return void
     */
    public function testEmpty()
    {
        //Given
        $imdb = new Movie_Datasource($this->testdataPositive);
        $data = $imdb->getData();
        $data->runtime->time = 0;
        $expected = false;

        //When
        $mdata = new Markup_Data($data);
        $actual = $mdata->getRuntime();

        //Then
        $this->assertSame($expected, $actual);
    }

}

?>
