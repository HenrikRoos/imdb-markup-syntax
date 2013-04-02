<?php

/**
 * Sub testclass to Tag_ProcessingTest for method findId in Tag_Processing class
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

require_once 'PHPUnit/Autoload.php';
require_once dirname(__FILE__) . '/../../Tag_Processing.php';

/**
 * Sub testclass to Tag_ProcessingTest for method findId in Tag_Processing class
 * 
 * @category  Testable
 * @package   Test
 * @author    Henrik Roos <henrik.roos@afternoon.se>
 * @copyright 2013 Henrik Roos
 * @license   https://github.com/HenrikRoos/imdb-markup-syntax/blob/master/imdb-markup-syntax.php GPL2
 * @link      https://github.com/HenrikRoos/imdb-markup-syntax imdb-markup-syntax
 */
class Find_IdTest extends PHPUnit_Framework_TestCase
{

    /**
     * One [IMDb:id(xxx)] tag, Positive test
     * 
     * @covers IMDb_Markup_Syntax\Tag_Processing::__construct
     * @covers IMDb_Markup_Syntax\Tag_Processing::findId
     * 
     * @return void
     */
    public function testOnePositive()
    {
        //Given
        $original_content = $GLOBALS["tagProcessingData"]["one_positive"];
        $expected = "tt0137523";

        //When
        $obj = new Tag_Processing($original_content);
        $condition = $obj->findId();
        $actual = $obj->tconst;

        //Then
        $this->assertTrue($condition);
        $this->assertSame($expected, $actual);
    }

    /**
     * Two correct [IMDb:id(xxx)] tags, Positive test. Only one is set
     * (first one)
     * 
     * @covers IMDb_Markup_Syntax\Tag_Processing::__construct
     * @covers IMDb_Markup_Syntax\Tag_Processing::findId
     * 
     * @return void
     */
    public function testTwoPositive()
    {
        //Given
        $original_content = $GLOBALS["tagProcessingData"]["two_positive"];
        $expected = "tt0102926";

        //When
        $obj = new Tag_Processing($original_content);
        $condition = $obj->findId();
        $actual = $obj->tconst;

        //Then
        $this->assertTrue($condition);
        $this->assertSame($expected, $actual);
    }

    /**
     * No correct [IMDb:id(xxx)] tags. Alternative test. id not set
     * 
     * @covers IMDb_Markup_Syntax\Tag_Processing::__construct
     * @covers IMDb_Markup_Syntax\Tag_Processing::findId
     * 
     * @return void
     */
    public function testNoMatch()
    {
        //Given
        $original_content = $GLOBALS["tagProcessingData"]["no_match"];

        //When
        $obj = new Tag_Processing($original_content);
        $condition = $obj->findId();

        //Then
        $this->assertFalse($condition);
        $this->assertEmpty($obj->tconst);
    }

    /**
     * Null input = id not set
     * 
     * @covers IMDb_Markup_Syntax\Tag_Processing::__construct
     * @covers IMDb_Markup_Syntax\Tag_Processing::findId
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
        $condition = $obj->findId();
        $condition2 = $obj2->findId();

        //Then
        $this->assertFalse($condition);
        $this->assertEmpty($obj->tconst);
        $this->assertFalse($condition2);
        $this->assertEmpty($obj2->tconst);
    }

    /**
     * Negativ test for Exception handler of a PREG_ERROR
     * 
     * @expectedException        IMDb_Markup_Syntax\Exceptions\PCRE_Exception
     * @expectedExceptionMessage PREG_BACKTRACK_LIMIT_ERROR
     * 
     * @covers IMDb_Markup_Syntax\Tag_Processing::__construct
     * @covers IMDb_Markup_Syntax\Tag_Processing::findId
     * @covers IMDb_Markup_Syntax\Exceptions\PCRE_Exception
     * 
     * @return void
     */
    public function testPregError()
    {
        //Given
        $original_content = "foobar foobar foobar";
        $tconst_pattern = "/(?:\D+|<\d+>)*[!?]/";

        //When
        $obj = new Tag_Processing($original_content);
        $obj->tconst_pattern = $tconst_pattern;
        $condition = $obj->findId();

        //Then
        $this->assertFalse($condition);
    }

    /**
     * Negativ test for Exception handler of a compilation failed
     * 
     * @expectedException        IMDb_Markup_Syntax\Exceptions\PCRE_Exception
     * @expectedExceptionMessage Compilation failed
     * 
     * @covers IMDb_Markup_Syntax\Tag_Processing::__construct
     * @covers IMDb_Markup_Syntax\Tag_Processing::findId
     * @covers IMDb_Markup_Syntax\Exceptions\PCRE_Exception
     * 
     * @return void
     */
    public function testErrorControlOperators()
    {
        //Given
        $original_content = "foobar foobar foobar";
        $tconst_pattern = "/(/";

        //When
        $obj = new Tag_Processing($original_content);
        $obj->tconst_pattern = $tconst_pattern;
        $condition = $obj->findId();

        //Then
        $this->assertFalse($condition);
    }

}

?>
