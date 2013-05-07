<?php

/**
 * Testclass to Markup_DataSuite for method getPoster in Markup_Data class
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
require_once dirname(__FILE__) . "/../../../../../wp-config.php";
require_once "PHPUnit/Autoload.php";

/**
 * Testclass to Markup_DataSuite for method getPoster in Markup_Data class
 * 
 * @category  Testable
 * @package   Test
 * @author    Henrik Roos <henrik.roos@afternoon.se>
 * @copyright 2013 Henrik Roos
 * @license   http://opensource.org/licenses/gpl-3.0.html GPL-3.0
 * @link      https://github.com/HenrikRoos/imdb-markup-syntax imdb-markup-syntax
 */
class Get_PosterTest extends PHPUnit_Framework_TestCase
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
     * @covers IMDb_Markup_Syntax\Markup_Data::getPoster
     * @covers IMDb_Markup_Syntax\Markup_Data::getValueValue
     * @covers IMDb_Markup_Syntax\Media_Library_Handler
     * 
     * @return void
     */
    public function testPositive()
    {

        //Given
        $imdb = new Movie_Datasource($this->testdataPositive);
        $data = $imdb->getData();
        $pattern = "/\<a href=\"http:\/\/www\.imdb\.com\/title\/tt0137523\/\" "
            . "title=\"Fight Club\"\>\<img width=\"201\" height=\"300\" "
            . "src=\"http:\/\/localhost\/wordpress\/wp-content\/uploads\/2013\/05\/"
            . "tt0137523\d+-201x300\.jpg\" class=\"alignleft size\-medium "
            . "wp\-post\-image\" alt=\"Fight Club\" \/\>\<\/a\>/";
        $post_id = 56;

        //When
        $mdata = new Markup_Data($data, $post_id);
        $actual = $mdata->getPoster();

        //Then
        $this->assertRegExp($pattern, $actual);
    }

    /**
     * Positive test: Get data sucessful
     *
     * @covers IMDb_Markup_Syntax\Markup_Data::__construct
     * @covers IMDb_Markup_Syntax\Markup_Data::getPoster
     * @covers IMDb_Markup_Syntax\Markup_Data::getValueValue
     * @covers IMDb_Markup_Syntax\Media_Library_Handler
     * 
     * @expectedException        IMDb_Markup_Syntax\Exceptions\Runtime_Exception
     * @expectedExceptionMessage No valid displayable image
     * 
     * @return void
     */
    public function testNoImage()
    {

        //Given
        $imdb = new Movie_Datasource($this->testdataPositive);
        $data = $imdb->getData();
        $data->image->url = "https://news.google.se";
        $post_id = 56;

        //When
        $mdata = new Markup_Data($data, $post_id);
        $mdata->getPoster();
    }

    /**
     * Negative test: No data is set
     *
     * @covers IMDb_Markup_Syntax\Markup_Data::__construct
     * @covers IMDb_Markup_Syntax\Markup_Data::getPoster
     * @covers IMDb_Markup_Syntax\Markup_Data::getValueValue
     * 
     * @return void
     */
    public function testNotSet()
    {
        //Given
        $imdb = new Movie_Datasource($this->testdataPositive);
        $data = $imdb->getData();
        unset($data->image->url);
        $expected = false;

        //When
        $mdata = new Markup_Data($data);
        $actual = $mdata->getPoster();

        //Then
        $this->assertSame($expected, $actual);
    }

    /**
     * Negative test: Data is empty
     *
     * @covers IMDb_Markup_Syntax\Markup_Data::__construct
     * @covers IMDb_Markup_Syntax\Markup_Data::getPoster
     * @covers IMDb_Markup_Syntax\Markup_Data::getValueValue
     * 
     * @return void
     */
    public function testEmpty()
    {
        //Given
        $imdb = new Movie_Datasource($this->testdataPositive);
        $data = $imdb->getData();
        $data->image->url = "";
        $expected = false;

        //When
        $mdata = new Markup_Data($data);
        $actual = $mdata->getPoster();

        //Then
        $this->assertSame($expected, $actual);
    }

}

?>
