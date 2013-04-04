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

    /** @var string Simple positive testdata with one id and one imdb tag */
    public $positive_testdata = "Pellentesque viverra luctus est, vel bibendum arcu
            suscipit quis. ÖÄÅ öäå Quisque congue[IMDb:id(tt0137523)]. Title:
            [imdb:title]";

    /** @var string Simple positive testdata with one id and one imdb tag */
    public $positive_mix_testdata = "Pellentesque viverra luctus est, vel bibendum
            arcu suscipit quis.[IMDb:id(http://www.imdb.com/title/tt0137523/)]
            Quisque congue [IMDb:id(tt0102926)] Title: [imdb:title]
            [IMDb:id(tt0137523)]. Year: [IMDb:year] [imdb:date] [imdb:cast]
            [imdb:title] [ImDB: writer ] [imdb:$$]
            [imdb:qwsazxcderrfvbgtyhnmjujdjhfksjhdfkjshdkfjhsakdjfhksjadhfkjsadf]";

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
        $original_content = $this->positive_testdata;
        $expected_content = "Pellentesque viverra luctus est, vel bibendum arcu
            suscipit quis. ÖÄÅ öäå Quisque congue. Title:
            Fight Club";
        $expected_count = 2;

        //When
        $obj = new Tag_Processing($original_content);
        $obj->findId();
        $obj->findImdbTags();
        $actual_count = $obj->tagsReplace();
        $actual_content = $obj->replacement_content;

        //Then
        $this->assertSame($expected_count, $actual_count);
        $this->assertSame($expected_content, $actual_content);
    }

    /**
     * Replace one imdb tag and delete mandatory id. Positive test
     *
     * @covers IMDb_Markup_Syntax\Tag_Processing::__construct 
     * @covers IMDb_Markup_Syntax\Tag_Processing::tagsReplace 
     * 
     * @return void
     */
    public function testMixedPositive()
    {
        //Given
        $original_content = $this->positive_mix_testdata;
        $expected_content = "???";
        $expected_count = 7;

        //When
        $obj = new Tag_Processing($original_content);
        $obj->findId();
        $obj->findImdbTags();
        $actual_count = $obj->tagsReplace();
        $actual_content = $obj->replacement_content;

        //Then
        $this->assertSame($expected_count, $actual_count);
        $this->assertSame($expected_content, $actual_content);
    }

    /**
     * Test when no id or imdb tags is empty. Alternative positive test
     * 
     * @covers IMDb_Markup_Syntax\Tag_Processing::__construct 
     * @covers IMDb_Markup_Syntax\Tag_Processing::tagsReplace
     * 
     * @return void
     */
    public function testEmpty()
    {
        //Given
        $original_content = $this->positive_testdata;
        $expected = false;

        //When
        $obj = new Tag_Processing($original_content);
        $actual = $obj->tagsReplace();

        //Then
        $this->assertSame($expected, $actual);
    }

}

?>
