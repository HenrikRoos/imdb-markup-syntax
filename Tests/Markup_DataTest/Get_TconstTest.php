<?php

/**
 * Testclass to Markup_DataSuite for for method getTconst in Markup_Data class
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
 * Testclass to Markup_DataSuite for method getTconst in Markup_Data class
 * 
 * @category  Testable
 * @package   Test
 * @author    Henrik Roos <henrik.roos@afternoon.se>
 * @copyright 2013 Henrik Roos
 * @license   http://opensource.org/licenses/gpl-3.0.html GPL-3.0
 * @link      https://github.com/HenrikRoos/imdb-markup-syntax imdb-markup-syntax
 */
class Get_TconstTest extends PHPUnit_Framework_TestCase
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
     * Positive test: Get id sucessful
     *
     * @covers IMDb_Markup_Syntax\Markup_Data::__construct
     * @covers IMDb_Markup_Syntax\Markup_Data::getTconst
     * @covers IMDb_Markup_Syntax\Markup_Data::getValue
     * 
     * @return void
     */
    public function testPositive()
    {
        //Given
        $imdb = new Movie_Datasource($this->testdataPositive);
        $data = $imdb->getData();
        $expected = "tt0137523";

        //When
        $mdata = new Markup_Data($data);
        $actual = $mdata->getTconst();

        //Then
        $this->assertSame($expected, $actual);
    }

    /**
     * Negative test: No tconst is set
     *
     * @covers IMDb_Markup_Syntax\Markup_Data::__construct
     * @covers IMDb_Markup_Syntax\Markup_Data::getTconst
     * @covers IMDb_Markup_Syntax\Markup_Data::getValue
     * 
     * @return void
     */
    public function testNotSet()
    {
        //Given
        $imdb = new Movie_Datasource($this->testdataPositive);
        $data = $imdb->getData();
        unset($data->tconst);
        $expected = false;

        //When
        $mdata = new Markup_Data($data);
        $actual = $mdata->getTconst();

        //Then
        $this->assertSame($expected, $actual);
    }

    /**
     * Negative test: Tconst is empty
     *
     * @covers IMDb_Markup_Syntax\Markup_Data::__construct
     * @covers IMDb_Markup_Syntax\Markup_Data::getTconst
     * @covers IMDb_Markup_Syntax\Markup_Data::getValue
     * 
     * @return void
     */
    public function testEmpty()
    {
        //Given
        $imdb = new Movie_Datasource($this->testdataPositive);
        $data = $imdb->getData();
        $data->tconst = "";
        $expected = false;

        //When
        $mdata = new Markup_Data($data);
        $actual = $mdata->getTconst();

        //Then
        $this->assertSame($expected, $actual);
    }

}

?>
