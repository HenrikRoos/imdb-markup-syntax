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
use IMDb_Markup_Syntax\Media_Library_Handler;
use IMDb_Markup_Syntax\Movie_Datasource;
use PHPUnit_Framework_TestCase;

require_once dirname(__FILE__) . "/../../Markup_Data.php";
require_once dirname(__FILE__) . "/../../Movie_Datasource.php";
require_once dirname(__FILE__) . "/../../Media_Library_Handler.php";
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
        $this->testdataPositive = "tt0468569";
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
        $post_id = 56;

        //When
        $mdata = new Markup_Data($data, $post_id);
        $title = $mdata->getValue("title");
        $pattern = "/\<a href=\"http:\/\/www\.imdb\.com\/title\/"
            . "{$this->testdataPositive}\/\" "
            . "title=\"{$title}\"\>\<img width=\"20\d\" height=\"300\" "
            . "src=\"http:\/\/localhost\/wordpress\/wp-content\/uploads"
            . "\/201\d\/\d\d\/"
            . "{$this->testdataPositive}\d*-20\dx300\.jpg\" class=\"alignleft "
            . "size\-medium wp\-post\-image\" alt=\"{$title}\" \/\>\<\/a\>/";
        $actual = $mdata->getPoster();

        //Then
        $this->assertRegExp($pattern, $actual);
    }

    /**
     * Negative test: No image
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
     * Negative test: post_id is not an integer
     *
     * @covers IMDb_Markup_Syntax\Markup_Data::__construct
     * @covers IMDb_Markup_Syntax\Markup_Data::getPoster
     * @covers IMDb_Markup_Syntax\Markup_Data::getValueValue
     * @covers IMDb_Markup_Syntax\Media_Library_Handler
     * 
     * @expectedException        IMDb_Markup_Syntax\Exceptions\Runtime_Exception
     * @expectedExceptionMessage post_id must be an integer
     * 
     * @return void
     */
    public function testIdNotInt()
    {
        //Given
        $imdb = new Movie_Datasource($this->testdataPositive);
        $data = $imdb->getData();
        $post_id = "xx";

        //When
        $mdata = new Markup_Data($data, $post_id);
        $mdata->getPoster();
    }

    /**
     * Negative test: Incorrect URL
     *
     * @covers IMDb_Markup_Syntax\Media_Library_Handler
     * 
     * @expectedException        IMDb_Markup_Syntax\Exceptions\Runtime_Exception
     * @expectedExceptionMessage remote_url must be an URL
     * 
     * @return void
     */
    public function testIncorrectUrl()
    {
        //Given
        $post_id = 1;
        $remote_url = "x";
        $filename = "y";

        //When
        new Media_Library_Handler($post_id, $remote_url, $filename);
    }

    /**
     * Negative test: Incorrect URL
     *
     * @covers IMDb_Markup_Syntax\Media_Library_Handler
     * 
     * @expectedException        IMDb_Markup_Syntax\Exceptions\Runtime_Exception
     * @expectedExceptionMessage Can't update attachment metadata
     * 
     * @return void
     */
    public function testFailureMetadata()
    {
        //Given
        $post_id = 1;
        $remote_url = "http://www.austingunter.com/wp-content/uploads/2012/11/"
            . "failure-poster.jpg";
        $filename = "y";

        //When
        $lib = new Media_Library_Handler($post_id, $remote_url, $filename);
        $lib->remote_url .= "x";
        $lib->getHtml("a", "b");
    }

    /**
     * Negative test: Incorrect URL
     *
     * @covers IMDb_Markup_Syntax\Media_Library_Handler
     * @covers IMDb_Markup_Syntax\Exceptions\WP_Exception
     * 
     * @expectedException        IMDb_Markup_Syntax\Exceptions\WP_Exception
     * @expectedExceptionMessage A valid URL was not provided.
     * 
     * @return void
     */
    public function testFailureDownload()
    {
        //Given
        $post_id = 1;
        $remote_url = "http://www.austingunter.com/wp-content/uploads/2012/11/"
            . "failure-poster.jpg";
        $filename = "y";

        //When
        $lib = new Media_Library_Handler($post_id, $remote_url, $filename);
        $lib->remote_url = "x";
        $lib->getHtml("a", "b");
    }

    /**
     * Negative test: Incorrect URL
     *
     * @covers IMDb_Markup_Syntax\Media_Library_Handler
     * 
     * @expectedException        IMDb_Markup_Syntax\Exceptions\Runtime_Exception
     * @expectedExceptionMessage Empty filename
     * 
     * @return void
     */
    public function testFailureFilename()
    {
        //Given
        $post_id = 1;
        $remote_url = "http://www.austingunter.com/wp-content/uploads/2012/11/"
            . "failure-poster.jpg";
        $filename = "y";

        //When
        $lib = new Media_Library_Handler($post_id, $remote_url, $filename);
        $lib->fileanme = "";
        $lib->getHtml("a", "b");
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
