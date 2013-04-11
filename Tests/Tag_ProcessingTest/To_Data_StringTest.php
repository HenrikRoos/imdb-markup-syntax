<?php

/**
 * Sub testclass to Tag_ProcessingTest for method toDataString in Tag_Processing
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

require_once "PHPUnit/Autoload.php";
require_once dirname(__FILE__) . "/Tag_Processing_Help.php";

/**
 * Sub testclass to Tag_ProcessingTest for method toDataString in Tag_Processing
 * class
 * 
 * @category  Testable
 * @package   Test
 * @author    Henrik Roos <henrik.roos@afternoon.se>
 * @copyright 2013 Henrik Roos
 * @license   http://opensource.org/licenses/gpl-3.0.html GPL-3.0
 * @link      https://github.com/HenrikRoos/imdb-markup-syntax imdb-markup-syntax
 */
class To_Data_StringTest extends PHPUnit_Framework_TestCase
{

    /**
     * Positive test. Test maching of a function
     * 
     * @covers IMDb_Markup_Syntax\Tag_Processing::__construct
     * @covers IMDb_Markup_Syntax\Tag_Processing::toDataString
     * 
     * @return void
     */
    public function testPositive()
    {
        //Given
        $original_content = $GLOBALS["tagProcessingData"]["one_positive"];
        $locale = "sv_SE";
        $tag = "date";
        $expected = "1999-12-25";

        //When
        $obj = new Tag_Processing_Help($original_content);
        $obj->locale = $locale;
        $condition = $obj->findId();
        $actual = $obj->toDataString($tag);

        //Then
        $this->assertTrue($condition);
        $this->assertSame($expected, $actual);
    }

    /**
     * No maching function to the tags.
     * 
     * @expectedException        IMDb_Markup_Syntax\Exceptions\Runtime_Exception
     * @expectedExceptionMessage Invalid function name
     * 
     * @covers IMDb_Markup_Syntax\Tag_Processing::__construct
     * @covers IMDb_Markup_Syntax\Tag_Processing::toDataString
     * 
     * @return void
     */
    public function testInvalidName()
    {
        //Given
        $original_content = $GLOBALS["tagProcessingData"]["one_positive"];
        $tag = "öäå";

        //When
        $obj = new Tag_Processing_Help($original_content);
        $obj->findId();
        $obj->toDataString($tag);
    }

    /**
     * No maching function to the tags.
     * 
     * @expectedException        IMDb_Markup_Syntax\Exceptions\Runtime_Exception
     * @expectedExceptionMessage [Tag is_null not exists]
     * 
     * @covers IMDb_Markup_Syntax\Tag_Processing::__construct
     * @covers IMDb_Markup_Syntax\Tag_Processing::toDataString
     * 
     * @return void
     */
    public function testNoMatch()
    {
        //Given
        $original_content = $GLOBALS["tagProcessingData"]["one_positive"];
        $tag = "is_null";

        //When
        $obj = new Tag_Processing_Help($original_content);
        $obj->findId();
        $obj->toDataString($tag);
    }

    /**
     * Alternative positive test. Test when wrong capitalize is precent
     * 
     * @covers IMDb_Markup_Syntax\Tag_Processing::__construct
     * @covers IMDb_Markup_Syntax\Tag_Processing::toDataString
     * 
     * @return void
     */
    public function testPositiveCapitalize()
    {
        //Given
        $original_content = $GLOBALS["tagProcessingData"]["one_positive"];
        $tag = "TiTlE";
        $expected = "Fight Club";

        //When
        $obj = new Tag_Processing_Help($original_content);
        $condition = $obj->findId();
        $actual = $obj->toDataString($tag);

        //Then
        $this->assertTrue($condition);
        $this->assertSame($expected, $actual);
    }

}

?>
