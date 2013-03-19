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
     * @covers IMDb_Markup_Syntax\Tag_Processing::findId
     * @covers IMDb_Markup_Syntax\Tag_Processing::__construct
     * 
     * @return void
     */
    public function testOnePositive()
    {
        //When
        $obj = new Tag_Processing(
            //Given
            $GLOBALS["tagProcessingData"]["one_positive"]
        );

        //Then
        $this->assertTrue($obj->findId(), "Id, not found");
        $this->assertSame("tt0137523", $obj->tconst);
    }

    /**
     * Two correct [IMDb:id(xxx)] tags, Positive test. Only one is set
     * (first one)
     * 
     * @covers IMDb_Markup_Syntax\Tag_Processing::findId
     * @covers IMDb_Markup_Syntax\Tag_Processing::__construct
     * 
     * @return void
     */
    public function testTwoPositive()
    {
        //When
        $this->original_content = new Tag_Processing(
            //Given
            $GLOBALS["tagProcessingData"]["two_positive"]
        );

        //Then
        $this->assertTrue($this->original_content->findId(), "Id, not found");
        $this->assertSame("tt0102926", $this->original_content->tconst);
    }

    /**
     * No correct [IMDb:id(xxx)] tags. Alternative test. id not set
     * 
     * @covers IMDb_Markup_Syntax\Tag_Processing::findId
     * @covers IMDb_Markup_Syntax\Tag_Processing::__construct
     * 
     * @return void
     */
    public function testNoMatch()
    {
        //When
        $obj = new Tag_Processing(
            //Given
            $GLOBALS["tagProcessingData"]["no_match"]
        );

        //Then
        $this->assertFalse($obj->findId(), "Id is found, not good");
        $this->assertEmpty($obj->tconst);
    }

    /**
     * Null input = id not set
     * 
     * @covers IMDb_Markup_Syntax\Tag_Processing::findId
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
        $this->assertFalse($obj->findId(), "Id is found, not good");
        $this->assertEmpty($obj->tconst);
        $this->assertFalse($obj2->findId(), "Id is found, not good");
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
        //When
        $obj = new Tag_Processing(
            //Given
            "foobar foobar foobar"
        );
        //Given
        $obj->tconst_pattern = "/(?:\D+|<\d+>)*[!?]/";

        //Then
        $this->assertFalse($obj->findId(), "Id is found, not good");
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
        //When
        $obj = new Tag_Processing(
            //Given
            "foobar foobar foobar"
        );
        //Given
        $obj->tconst_pattern = "/(/";

        //Then
        $this->assertFalse($obj->findId(), "Id is found, not good");
    }

}

?>
