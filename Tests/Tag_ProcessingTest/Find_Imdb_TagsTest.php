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
        //When
        $obj = new Tag_Processing(
            //Given
            $GLOBALS['tagProcessingData']["one_positive"]
        );

        //Then
        $this->assertTrue($obj->findImdbTags(), "Not found = not good");
        $this->assertCount(1, $obj->imdb_tags);
        $this->assertSame("title", $obj->imdb_tags[0]);
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
        //When
        $obj = new Tag_Processing(
            //Given
            $GLOBALS['tagProcessingData']["two_positive"]
        );

        //Then
        $this->assertTrue($obj->findImdbTags(), "Not found = not good");
        $this->assertCount(2, $obj->imdb_tags);
        $this->assertSame("title", $obj->imdb_tags[0]);
        $this->assertSame("year", $obj->imdb_tags[1]);
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
        //When
        $obj = new Tag_Processing(
            //Given
            $GLOBALS['tagProcessingData']["no_match"]
        );

        //Then
        $this->assertFalse($obj->findImdbTags(), "Found = not good");
        $this->assertCount(0, $obj->imdb_tags);
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

        //Then
        $this->assertFalse($obj->findImdbTags(), "tags is found, not good");
        $this->assertEmpty($obj->imdb_tags);
        $this->assertFalse($obj2->findImdbTags(), "tags is found, not good");
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
        //When
        $obj = new Tag_Processing(
            //Given
            "foobar foobar foobar"
        );
        //Given
        $obj->imdb_tags_pattern = "/(?:\D+|<\d+>)*[!?]/";

        //Then
        $this->assertFalse($obj->findImdbTags(), "Id is found, not good");
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
        //When
        $obj = new Tag_Processing(
            //Given
            "foobar foobar foobar"
        );
        //Given
        $obj->imdb_tags_pattern = "/(/";

        //Then
        $this->assertFalse($obj->findImdbTags(), "imdb is found, not good");
    }

}

?>
