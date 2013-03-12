<?php

namespace IMDb_Markup_Syntax;

use IMDb_Markup_Syntax\Exceptions\PCRE_Exception;
use PHPUnit_Framework_TestCase;

require_once 'PHPUnit/Autoload.php';
require_once dirname(__FILE__) . '/../class-tag-processing.php';
require_once dirname(__FILE__) . '/../Exceptions/class-pcre-exception.php';

/**
 * Testclass (PHPUnit) test for Tag_Processing class.
 * @author Henrik Roos <henrik@afternoon.se>
 * @package Test
 */
class Tag_ProcessingTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var array string of testdata 
     */
    public $testdata;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->testdata = array(
            "one_positive" => "Pellentesque viverra luctus est, vel bibendum arcu suscipit quis.
                Quisque congue [IMDb:tconst(tt0137523)]. Title: [imdb:title]",
            "two_positive" => "Pellentesque viverra luctus est, vel bibendum arcu suscipit quis.
                [IMDb:tconst(http://www.imdb.com/title/tt0137523/)] Quisque congue [IMDb:tconst(tt0102926)]
                Title: [imdb:title] [IMDb:tconst(tt0137523)]. Year: [imdb:year]",
            "no_match" => "Pellentesque viverra luctus est, vel bibendum arcu suscipit quis.
                [IMDb:tconst(http://www.imdb.com/title/tt0137523/)] Quisque congue [IMDb:tconst()]
                Title: [title] [IMDb:tconst:tt0137523] [IMDb:tconst:(0137523)] [IMDb:tconst(tt)]",
        );
    }

    /**
     * One [IMDb:tconst(xxx)] tag, Positive test.
     * @covers IMDb_Markup_Syntax\Tag_Processing::find_tconst
     * @covers IMDb_Markup_Syntax\Tag_Processing::__construct
     */
    public function testFind_tconstOnePositive()
    {
        $obj = new Tag_Processing($this->testdata["one_positive"]);
        $this->assertTrue($obj->find_tconst(), "Id, not found");
        $this->assertEquals("tt0137523", $obj->tconst);
    }

    /**
     * Two correct [IMDb:tconst(xxx)] tags, Positive test. Only one is set (first one).
     * @covers IMDb_Markup_Syntax\Tag_Processing::find_tconst
     * @covers IMDb_Markup_Syntax\Tag_Processing::__construct
     */
    public function testFind_tconstTwoPositive()
    {
        $this->original_content = new Tag_Processing($this->testdata["two_positive"]);
        $this->assertTrue($this->original_content->find_tconst(), "Id, not found");
        $this->assertEquals("tt0102926", $this->original_content->tconst);
    }

    /**
     * No correct [IMDb:tconst(xxx)] tags. Alternative test. tconst not set.
     * @covers IMDb_Markup_Syntax\Tag_Processing::find_tconst
     * @covers IMDb_Markup_Syntax\Tag_Processing::__construct
     */
    public function testFind_tconstNoMatch()
    {
        $obj = new Tag_Processing($this->testdata["no_match"]);
        $this->assertFalse($obj->find_tconst(), "Id is found, not good");
        $this->assertEmpty($obj->tconst);
    }

    /**
     * Null input = tconst not set.
     * @covers IMDb_Markup_Syntax\Tag_Processing::find_tconst
     * @covers IMDb_Markup_Syntax\Tag_Processing::__construct
     */
    public function testFind_tconstEmpty()
    {
        $obj = new Tag_Processing(null);
        $this->assertFalse($obj->find_tconst(), "Id is found, not good");
        $this->assertEmpty($obj->tconst);
        $obj2 = new Tag_Processing("");
        $this->assertFalse($obj2->find_tconst(), "Id is found, not good");
        $this->assertEmpty($obj2->tconst);
    }

    /**
     * Negativ test for Exception handler of a PREG_ERROR
     * @covers IMDb_Markup_Syntax\Tag_Processing::find_tconst
     * @covers IMDb_Markup_Syntax\Exceptions\PCRE_Exception
     * @covers IMDb_Markup_Syntax\Tag_Processing::__construct
     */
    public function testFind_tconstPREG_ERROR()
    {
        $obj = new Tag_Processing("foobar foobar foobar");
        $obj->tconst_pattern = "/(?:\D+|<\d+>)*[!?]/";
        try {
            $this->assertFalse($obj->find_tconst(), "Id is found, not good");
            $this->assertEmpty($obj->tconst);
        } catch (PCRE_Exception $exc) {
            $this->assertEquals($exc->getMessage(), "PREG_BACKTRACK_LIMIT_ERROR");
            return;
        }
        $this->fail('An expected exception has not been raised.');
    }

    /**
     * Negativ test for Exception handler of a Compilation failed
     * @covers IMDb_Markup_Syntax\Tag_Processing::find_tconst
     * @covers IMDb_Markup_Syntax\Exceptions\PCRE_Exception
     * @covers IMDb_Markup_Syntax\Tag_Processing::__construct
     */
    public function testFind_tconstErrorControlOperators()
    {
        $obj = new Tag_Processing("foobar foobar foobar");
        $obj->tconst_pattern = "/(/";
        try {
            $this->assertFalse($obj->find_tconst(), "Id is found, not good");
            $this->assertEmpty($obj->tconst);
        } catch (PCRE_Exception $exc) {
            $this->assertContains("Compilation failed", $exc->getMessage());
            return;
        }
        $this->fail('An expected exception has not been raised.');
    }

    /**
     * Find one tag. Positive test.
     * @covers IMDb_Markup_Syntax\Tag_Processing::find_imdb_tags
     * @covers IMDb_Markup_Syntax\Tag_Processing::__construct
     */
    public function testFind_imdb_tagsOnePositive()
    {
        $obj = new Tag_Processing($this->testdata["one_positive"]);
        $this->assertTrue($obj->find_imdb_tags(), "Not found = not good");
        $this->assertCount(1, $obj->imdb_tags);
        $this->assertEquals("title", $obj->imdb_tags[0]);
    }

    /**
     * Find two tag. Positive test.
     * @covers IMDb_Markup_Syntax\Tag_Processing::find_imdb_tags
     * @covers IMDb_Markup_Syntax\Tag_Processing::__construct
     */
    public function testFind_imdb_tagsTwoPositive()
    {
        $obj = new Tag_Processing($this->testdata["two_positive"]);
        $this->assertTrue($obj->find_imdb_tags(), "Not found = not good");
        $this->assertCount(2, $obj->imdb_tags);
        $this->assertEquals("title", $obj->imdb_tags[0]);
        $this->assertEquals("year", $obj->imdb_tags[1]);
    }

    /**
     * Find zero tag. Alternative test.
     * @covers IMDb_Markup_Syntax\Tag_Processing::find_imdb_tags
     * @covers IMDb_Markup_Syntax\Tag_Processing::__construct
     */
    public function testFind_imdb_tagsNoMatch()
    {
        $obj = new Tag_Processing($this->testdata["no_match"]);
        $this->assertFalse($obj->find_imdb_tags(), "Found = not good");
        $this->assertCount(0, $obj->imdb_tags);
    }

    /**
     * Null input. Alternative test
     * @covers IMDb_Markup_Syntax\Tag_Processing::find_imdb_tags
     * @covers IMDb_Markup_Syntax\Tag_Processing::__construct
     */
    public function testFind_imdb_tagsEmpty()
    {
        $obj = new Tag_Processing(null);
        $this->assertFalse($obj->find_imdb_tags(), "tags is found, not good");
        $this->assertEmpty($obj->imdb_tags);
        $obj2 = new Tag_Processing("");
        $this->assertFalse($obj2->find_imdb_tags(), "tags is found, not good");
        $this->assertEmpty($obj2->imdb_tags);
    }

    /**
     * Negativ test for Exception handler of a PREG_ERROR
     * @covers IMDb_Markup_Syntax\Tag_Processing::find_imdb_tags
     * @covers IMDb_Markup_Syntax\Exceptions\PCRE_Exception
     * @covers IMDb_Markup_Syntax\Tag_Processing::__construct
     */
    public function testFind_imdb_tagsPREG_ERROR()
    {
        $obj = new Tag_Processing("foobar foobar foobar");
        $obj->imdb_tags_pattern = "/(?:\D+|<\d+>)*[!?]/";
        try {
            $this->assertFalse($obj->find_imdb_tags(), "Id is found, not good");
            $this->assertEmpty($obj->imdb_tags);
        } catch (PCRE_Exception $exc) {
            $this->assertEquals($exc->getMessage(), "PREG_BACKTRACK_LIMIT_ERROR");
            return;
        }
        $this->fail('An expected exception has not been raised.');
    }

    /**
     * Negativ test for Exception handler of a Compilation failed
     * @covers IMDb_Markup_Syntax\Tag_Processing::find_imdb_tags
     * @covers IMDb_Markup_Syntax\Exceptions\PCRE_Exception
     * @covers IMDb_Markup_Syntax\Tag_Processing::__construct
     */
    public function testFind_imdb_tagsErrorControlOperators()
    {
        $obj = new Tag_Processing("foobar foobar foobar");
        $obj->imdb_tags_pattern = "/(/";
        try {
            $this->assertFalse($obj->find_imdb_tags(), "imdb is found, not good");
            $this->assertEmpty($obj->imdb_tags);
        } catch (PCRE_Exception $exc) {
            $this->assertContains("Compilation failed", $exc->getMessage());
            return;
        }
        $this->fail('An expected exception has not been raised.');
    }

}
