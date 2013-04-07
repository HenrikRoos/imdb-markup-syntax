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
    public $positive_data = "Pellentesque viverra luctus est, vel bibendum arcu
            suscipit quis. ÖÄÅ öäå Quisque congue[IMDb:id(tt0137523)]. Title:
            [imdb:title]";

    /** @var string Simple positive testdata with one id and one imdb tag */
    public $positive_mix_data = "Pellentesque viverra luctus est, vel bibendum
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
        $original_content = $this->positive_data;
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
        $this->assertSame($expected_content, $actual_content);
        $this->assertSame($expected_count, $actual_count);
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
        $original_content = $this->positive_mix_data;
        $expected_content = "Pellentesque viverra luctus est, vel bibendum
            arcu suscipit quis.[IMDb:id(http://www.imdb.com/title/tt0137523/)]
            Quisque congue  Title: The Silence of the Lambs
            [IMDb:id(tt0137523)]. Year: [Tag year not exists] 1991-02-14 "
            . "<a href=\"http://www.imdb.com/name/nm0000149\">Jodie Foster</a> "
            . "Clarice Starling\n<a href=\"http://www.imdb.com/name/nm0000164\">"
            . "Anthony Hopkins</a> Dr. Hannibal Lecter\n"
            . "<a href=\"http://www.imdb.com/name/nm0095029\">Lawrence A. Bonney</a>"
            . " FBI Instructor\n<a href=\"http://www.imdb.com/name/nm0501435\">"
            . "Kasi Lemmons</a> Ardelia Mapp
            The Silence of the Lambs [ImDB: writer ] [imdb:$$]
            [imdb:qwsazxcderrfvbgtyhnmjujdjhfksjhdfkjshdkfjhsakdjfhksjadhfkjsadf]";
        $expected_count = 6;

        //When
        $obj = new Tag_Processing($original_content);
        $obj->findId();
        $obj->findImdbTags();
        $actual_count = $obj->tagsReplace();
        $actual_content = $obj->replacement_content;

        //Then
        $this->assertSame($expected_content, $actual_content);
        $this->assertSame($expected_count, $actual_count);
    }

    /**
     * No data for this title id. Alternative test.
     * 
     * @covers IMDb_Markup_Syntax\Tag_Processing::__construct
     * @covers IMDb_Markup_Syntax\Tag_Processing::tagsReplace
     * 
     * @return void
     */
    public function testNoIdMatch()
    {
        //Given
        $original_content = "Pellentesque viverra luctus est, vel bibendum arcu
            suscipit quis. ÖÄÅ öäå Quisque congue[IMDb:id(tt0137523)].";
        $expected_content = "Pellentesque viverra luctus est, vel bibendum arcu
            suscipit quis. ÖÄÅ öäå Quisque congue[No imdb tags found].";
        $expected_count = 1;

        //When
        $obj = new Tag_Processing($original_content);
        $actual_count = $obj->tagsReplace();
        $actual_content = $obj->replacement_content;

        //Then
        $this->assertSame($expected_content, $actual_content);
        $this->assertSame($expected_count, $actual_count);
    }
    
        /**
     * No imdb tags just id. Alternative test.
     * 
     * @covers IMDb_Markup_Syntax\Tag_Processing::__construct
     * @covers IMDb_Markup_Syntax\Tag_Processing::tagsReplace
     * 
     * @return void
     */
    public function testNoImdbMatch()
    {
        //Given
        $original_content = "Pellentesque viverra luctus est, vel bibendum arcu
                suscipit quis. [IMDb:id(http://www.imdb.com/title/tt0137523/)]
                Quisque congue [IMDb:id()] Title: [title] [IMDb:id:tt0137523]
                [IMDb:id:(0137523)] [IMDb:id(tt)]";
        $expected = false;

        //When
        $obj = new Tag_Processing($original_content);
        $actual = $obj->tagsReplace();

        //Then
        $this->assertSame($expected, $actual);
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
        $original_content = "";
        $expected = false;

        //When
        $obj = new Tag_Processing($original_content);
        $actual = $obj->tagsReplace();

        //Then
        $this->assertSame($expected, $actual);
    }

    /**
     * PCRE Exception test. Alternative test.
     * 
     * @covers IMDb_Markup_Syntax\Tag_Processing::__construct
     * @covers IMDb_Markup_Syntax\Tag_Processing::tagsReplace
     * 
     * @return void
     */
    public function testPregError()
    {
        //Given
        $original_content = $this->positive_data;
        $tconst_pattern = "/(?:\D+|<\d+>)*[!?]/";
        $expected_content = "Pellentesque viverra luctus est, vel bibendum arcu
            suscipit quis. ÖÄÅ öäå Quisque "
            . "congue[PREG_BACKTRACK_LIMIT_ERROR(tt0137523)]. Title:
            [imdb:title]";
        $expected_count = 1;

        //When
        $obj = new Tag_Processing($original_content);
        $obj->tconst_pattern = $tconst_pattern;
        $actual_count = $obj->tagsReplace();
        $actual_content = $obj->replacement_content;

        //Then
        $this->assertSame($expected_content, $actual_content);
        $this->assertSame($expected_count, $actual_count);
    }
    
        /**
     * PCRE Exception test with no imdb:id tags. Alternative test.
     * 
     * @covers IMDb_Markup_Syntax\Tag_Processing::__construct
     * @covers IMDb_Markup_Syntax\Tag_Processing::tagsReplace
     * 
     * @return void
     */
    public function testPregErrorNoMatch()
    {
        //Given
        $original_content = "Pellentesque viverra luctus est";
        $tconst_pattern = "/(?:\D+|<\d+>)*[!?]/";
        $expected_content = "Pellentesque viverra luctus est";
        $expected_count = 0;

        //When
        $obj = new Tag_Processing($original_content);
        $obj->tconst_pattern = $tconst_pattern;
        $actual_count = $obj->tagsReplace();
        $actual_content = $obj->replacement_content;

        //Then
        $this->assertSame($expected_content, $actual_content);
        $this->assertSame($expected_count, $actual_count);
    }

    /**
     * No data for this title id. Alternative test.
     * 
     * @covers IMDb_Markup_Syntax\Tag_Processing::__construct
     * @covers IMDb_Markup_Syntax\Tag_Processing::tagsReplace
     * 
     * @return void
     */
    public function testDatasourceException()
    {
        //Given
        $original_content = $this->positive_data;
        $timeout = 200;
        $expected_content = "Pellentesque viverra luctus est, vel bibendum arcu
            suscipit quis. ÖÄÅ öäå Quisque congue[SSL connection timeout]. Title:
            [imdb:title]";
        $expected_count = 1;

        //When
        $obj = new Tag_Processing($original_content, null, $timeout);
        $actual_count = $obj->tagsReplace();
        $actual_content = $obj->replacement_content;

        //Then
        $this->assertSame($expected_content, $actual_content);
        $this->assertSame($expected_count, $actual_count);
    }

}

?>
