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
 * @license   https://github.com/HenrikRoos/imdb-markup-syntax/blob/master/imdb-markup-syntax.php GPL2
 * @link      https://github.com/HenrikRoos/imdb-markup-syntax imdb-markup-syntax
 */

namespace IMDb_Markup_Syntax\Tag_ProcessingTest;

use IMDb_Markup_Syntax\Tag_Processing;
use PHPUnit_Framework_TestCase;

require_once dirname(__FILE__) . '/../../Tag_Processing.php';
require_once 'PHPUnit/Autoload.php';

/**
 * Sub testclass to Tag_ProcessingTest for method findImdbTags in Tag_Processing
 * class
 * 
 * @category  Testable
 * @package   Test
 * @author    Henrik Roos <henrik.roos@afternoon.se>
 * @copyright 2013 Henrik Roos
 * @license   https://github.com/HenrikRoos/imdb-markup-syntax/blob/master/imdb-markup-syntax.php GPL2
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
        $expected = "title";

        //When
        $obj = new Tag_Processing($original_content);
        $condition = $obj->findImdbTags();
        $haystack = $obj->imdb_tags;
        $actual = $obj->imdb_tags[0];

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
        $expected = array("title", "year");

        //When
        $obj = new Tag_Processing($original_content);
        $condition = $obj->findImdbTags();
        $haystack = $obj->imdb_tags;
        $actual = $obj->imdb_tags;

        //Then
        $this->assertTrue($condition);
        $this->assertCount($expectedCount, $haystack);
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
        $obj = new Tag_Processing($original_content);
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
        $obj = new Tag_Processing($testdata);
        $obj2 = new Tag_Processing($testdata2);
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
        $imdb_tags_pattern = "/(?:\D+|<\d+>)*[!?]/";

        //When
        $obj = new Tag_Processing($original_content);
        $obj->imdb_tags_pattern = $imdb_tags_pattern;
        $condition = $obj->findImdbTags();

        //Then
        $this->assertFalse($condition);
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
        $imdb_tags_pattern = "/(/";

        //When
        $obj = new Tag_Processing($original_content);
        $obj->imdb_tags_pattern = $imdb_tags_pattern;
        $condition = $obj->findImdbTags();

        //Then
        $this->assertFalse($condition);
    }

}

?>
