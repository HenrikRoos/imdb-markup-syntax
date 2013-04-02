<?php

/**
 * Sub testclass to Tag_ProcessingTest for method tagsReplace in Tag_Processing
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

require_once 'PHPUnit/Autoload.php';
require_once dirname(__FILE__) . '/../../Tag_Processing.php';

/**
 * Sub testclass to Tag_ProcessingTest for method tagsReplace in Tag_Processing
 * class
 * 
 * @category  Testable
 * @package   Test
 * @author    Henrik Roos <henrik.roos@afternoon.se>
 * @copyright 2013 Henrik Roos
 * @license   https://github.com/HenrikRoos/imdb-markup-syntax/blob/master/imdb-markup-syntax.php GPL2
 * @link      https://github.com/HenrikRoos/imdb-markup-syntax imdb-markup-syntax
 */
class Tags_ReplaceTest extends PHPUnit_Framework_TestCase
{

    /**
     * Replace one imdb tag and delete mandatory id. Positive test
     *
     * @covers IMDb_Markup_Syntax\Tag_Processing::__construct 
     * @covers IMDb_Markup_Syntax\Tag_Processing::tagsReplace 
     * 
     * @return void
     */
    public function testOnePositive()
    {
        //Given
        $original_content = "Pellentesque viverra luctus est, vel bibendum arcu
                suscipit quis. ÖÄÅ öäå Quisque congue[IMDb:id(tt0137523)]. Title:
                [imdb:title]";
        $expected_content = "Pellentesque viverra luctus est, vel bibendum arcu
                suscipit quis. ÖÄÅ öäå Quisque congue. Title:
                Fight Club";
        $expected_count = 2;

        //When
        $obj = new Tag_Processing($original_content);
        $findId_before = $obj->findId();
        $findImdbTags_before = $obj->findImdbTags();
        $actual_count = $obj->tagsReplace();
        $actual_content = $obj->replacement_content;
        
        $obj->original_content = $obj->replacement_content;
        $findId_after = $obj->findId();
        $findImdbTags_after = $obj->findImdbTags();
        $actual_count_after = $obj->tagsReplace();
        
        //Then
        $this->assertTrue($findId_before);
        $this->assertTrue($findImdbTags_before);
        $this->assertSame($expected_count, $actual_count);
        $this->assertSame($expected_content, $actual_content);
        
        $this->assertFalse($findId_after);
        $this->assertFalse($findImdbTags_after);
        $this->assertFalse($actual_count_after);
    }

}
?>
