<?php

/**
 * Sub testclass to Tag_ProcessingTest for method findImdbTags in Tag_Processing
 * class
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

namespace IMDb_Markup_Syntax\Tag_ProcessingTest;

use PHPUnit_Framework_TestCase;

require_once dirname(__FILE__) . "/Tag_Processing_Help.php";
require_once "PHPUnit/Autoload.php";

/**
 * Sub testclass to Tag_ProcessingTest for method findImdbTags in Tag_Processing
 * class
 * 
 * @category  Testable
 * @package   Test
 * @author    Henrik Roos <henrik.roos@afternoon.se>
 * @copyright 2013 Henrik Roos
 * @license   http://opensource.org/licenses/gpl-3.0.html GPL-3.0
 * @link      https://github.com/HenrikRoos/imdb-markup-syntax imdb-markup-syntax
 */
class Find_Imdb_TagsTest extends PHPUnit_Framework_TestCase
{

    /**
     * Find one tag. Positive test
     * 
     * @covers IMDb_Markup_Syntax\Tag_Processing::findImdbTags
     * @covers IMDb_Markup_Syntax\Tag_Processing::__construct
     * 
     * @return void
     */
    public function testOnePositive()
    {
        //Given
        $original_content = $GLOBALS["tagProcessingData"]["one_positive"];
        $expectedCount = 1;
        $expected = array(
            array("[imdb:title]", "title")
        );

        //When
        $obj = new Tag_Processing_Help($original_content);
        $condition = $obj->findImdbTags();
        $haystack = $obj->imdb_tags;
        $actual = $obj->imdb_tags;

        //Then
        $this->assertTrue($condition);
        $this->assertCount($expectedCount, $haystack);
        $this->assertSame($expected, $actual);
    }

    /**
     * Find two tag. Positive test
     * 
     * @covers IMDb_Markup_Syntax\Tag_Processing::findImdbTags
     * @covers IMDb_Markup_Syntax\Tag_Processing::__construct
     * 
     * @return void
     */
    public function testTwoPositive()
    {
        //Given
        $original_content = $GLOBALS["tagProcessingData"]["two_positive"];
        $expectedCount = 2;
        $expected = array(
            array("[imdb:title]", "title"),
            array("[IMDb:year]", "year")
        );

        //When
        $obj = new Tag_Processing_Help($original_content);
        $condition = $obj->findImdbTags();
        $haystack = $obj->imdb_tags;
        $actual = $obj->imdb_tags;

        //Then
        $this->assertTrue($condition);
        $this->assertCount($expectedCount, $haystack);
        $this->assertSame($expected, $actual);
    }

    /**
     * Negative test: Under min length of id. <b>[a-z0-9]{0}</b>
     * 
     * @covers IMDb_Markup_Syntax\Tag_Processing::__construct
     * @covers IMDb_Markup_Syntax\Tag_Processing::findImdbTags
     * 
     * @return void
     */
    public function testMinNegative()
    {
        //Given
        $original_content = "[imdb:]";
        $expected = array();

        //When
        $obj = new Tag_Processing_Help($original_content);
        $condition = $obj->findImdbTags();
        $actual = $obj->imdb_tags;

        //Then
        $this->assertFalse($condition);
        $this->assertSame($expected, $actual);
    }

    /**
     * Positive test: Min length of id. <b>[a-z0-9]{1}</b>
     * 
     * @covers IMDb_Markup_Syntax\Tag_Processing::__construct
     * @covers IMDb_Markup_Syntax\Tag_Processing::findImdbTags
     * 
     * @return void
     */
    public function testMinPositive()
    {
        //Given
        $original_content = "[imdb:a]";
        $expected = array(array("[imdb:a]", "a"));

        //When
        $obj = new Tag_Processing_Help($original_content);
        $condition = $obj->findImdbTags();
        $actual = $obj->imdb_tags;

        //Then
        $this->assertTrue($condition);
        $this->assertSame($expected, $actual);
    }

    /**
     * Positive test: Min length of id. <b>[a-z0-9]{40}</b>
     * 
     * @covers IMDb_Markup_Syntax\Tag_Processing::__construct
     * @covers IMDb_Markup_Syntax\Tag_Processing::findImdbTags
     * 
     * @return void
     */
    public function testMaxPositive()
    {
        //Given
        $original_content = "[imdb:abcdefghijklmnopqrstuvxyzABCDEFGHIJ0123_]";
        $expected = array(array("[imdb:abcdefghijklmnopqrstuvxyzABCDEFGHIJ0123_]",
                "abcdefghijklmnopqrstuvxyzABCDEFGHIJ0123_"
        ));

        //When
        $obj = new Tag_Processing_Help($original_content);
        $condition = $obj->findImdbTags();
        $actual = $obj->imdb_tags;

        //Then
        $this->assertTrue($condition);
        $this->assertSame($expected, $actual);
    }

    /**
     * Negative test: Under min length of id. <b>[a-z0-9]{41}</b>
     * 
     * @covers IMDb_Markup_Syntax\Tag_Processing::__construct
     * @covers IMDb_Markup_Syntax\Tag_Processing::findId
     * 
     * @return void
     */
    public function testMaxNegative()
    {
        //Given
        $original_content = "[imdb:abcdefghijklmnopqrstuvxyzABCDEFGHIJ0123_a]";
        $expected = array();

        //When
        $obj = new Tag_Processing_Help($original_content);
        $condition = $obj->findImdbTags();
        $actual = $obj->imdb_tags;

        //Then
        $this->assertFalse($condition);
        $this->assertSame($expected, $actual);
    }

    /**
     * Find zero tag. Alternative test
     * 
     * @covers IMDb_Markup_Syntax\Tag_Processing::findImdbTags
     * @covers IMDb_Markup_Syntax\Tag_Processing::__construct
     * 
     * @return void
     */
    public function testNoMatch()
    {
        //Given
        $original_content = $GLOBALS["tagProcessingData"]["no_match"];
        $expectedCount = 0;

        //When
        $obj = new Tag_Processing_Help($original_content);
        $condition = $obj->findImdbTags();
        $haystack = $obj->imdb_tags;

        //Then
        $this->assertFalse($condition);
        $this->assertCount($expectedCount, $haystack);
    }

    /**
     * Null input. Alternative test
     * 
     * @covers IMDb_Markup_Syntax\Tag_Processing::findImdbTags
     * @covers IMDb_Markup_Syntax\Tag_Processing::__construct
     * 
     * @return void
     */
    public function testEmpty()
    {
        //Given
        $testdata = null;
        $testdata2 = "";

        //When
        $obj = new Tag_Processing_Help($testdata);
        $obj2 = new Tag_Processing_Help($testdata2);
        $condition = $obj->findImdbTags();
        $condition2 = $obj2->findImdbTags();

        //Then
        $this->assertFalse($condition);
        $this->assertEmpty($obj->imdb_tags);
        $this->assertFalse($condition2);
        $this->assertEmpty($obj2->imdb_tags);
    }

    /**
     * Negativ test for Exception handler of a PREG_ERROR
     * 
     * @expectedException        IMDb_Markup_Syntax\Exceptions\PCRE_Exception
     * @expectedExceptionMessage PREG_BACKTRACK_LIMIT_ERROR
     * 
     * @covers IMDb_Markup_Syntax\Tag_Processing::__construct
     * @covers IMDb_Markup_Syntax\Tag_Processing::findImdbTags
     * @covers IMDb_Markup_Syntax\Exceptions\PCRE_Exception
     * 
     * @return void
     */
    public function testPregError()
    {
        //Given
        $original_content = "foobar foobar foobar";
        $custom_tags_pattern = "/(?:\D+|<\d+>)*[!?]/";

        //When
        $obj = new Tag_Processing_Help($original_content);
        $obj->custom_tags_pattern = $custom_tags_pattern;
        $obj->findImdbTags();
    }

    /**
     * Negativ test for Exception handler of a Compilation failed
     * 
     * @expectedException        IMDb_Markup_Syntax\Exceptions\PCRE_Exception
     * @expectedExceptionMessage Compilation failed
     * 
     * @covers IMDb_Markup_Syntax\Tag_Processing::findImdbTags
     * @covers IMDb_Markup_Syntax\Exceptions\PCRE_Exception
     * @covers IMDb_Markup_Syntax\Tag_Processing::__construct
     * 
     * @return void
     */
    public function testErrorControlOperators()
    {
        //Given
        $original_content = "foobar foobar foobar";
        $custom_tags_pattern = "/(/";

        //When
        $obj = new Tag_Processing_Help($original_content);
        $obj->custom_tags_pattern = $custom_tags_pattern;
        $obj->findImdbTags();
    }

}

?>
