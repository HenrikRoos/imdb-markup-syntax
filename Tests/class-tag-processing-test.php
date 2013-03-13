<?php

namespace IMDbMarkupSyntax;

use IMDbMarkupSyntax\Exceptions\PCREException;
use PHPUnit_Framework_TestCase;

require_once 'PHPUnit/Autoload.php';
require_once dirname(__FILE__) . '/../class-tag-processing.php';
require_once dirname(__FILE__) . '/../Exceptions/class-pcre-exception.php';

/**
 * Testclass (PHPUnit) test for TagProcessing class.
 * @author Henrik Roos <henrik@afternoon.se>
 * @package Test
 */
class TagProcessingTest extends PHPUnit_Framework_TestCase
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
     * @covers IMDbMarkupSyntax\TagProcessing::findTconst
     * @covers IMDbMarkupSyntax\TagProcessing::__construct
     */
    public function testFindTconstOnePositive()
    {
        $obj = new TagProcessing($this->testdata["one_positive"]);
        $this->assertTrue($obj->findTconst(), "Id, not found");
        $this->assertEquals("tt0137523", $obj->tconst);
    }

    /**
     * Two correct [IMDb:tconst(xxx)] tags, Positive test. Only one is set (first one).
     * @covers IMDbMarkupSyntax\TagProcessing::findTconst
     * @covers IMDbMarkupSyntax\TagProcessing::__construct
     */
    public function testFindTconstTwoPositive()
    {
        $this->original_content = new TagProcessing($this->testdata["two_positive"]);
        $this->assertTrue($this->original_content->findTconst(), "Id, not found");
        $this->assertEquals("tt0102926", $this->original_content->tconst);
    }

    /**
     * No correct [IMDb:tconst(xxx)] tags. Alternative test. tconst not set.
     * @covers IMDbMarkupSyntax\TagProcessing::findTconst
     * @covers IMDbMarkupSyntax\TagProcessing::__construct
     */
    public function testFindTconstNoMatch()
    {
        $obj = new TagProcessing($this->testdata["no_match"]);
        $this->assertFalse($obj->findTconst(), "Id is found, not good");
        $this->assertEmpty($obj->tconst);
    }

    /**
     * Null input = tconst not set.
     * @covers IMDbMarkupSyntax\TagProcessing::findTconst
     * @covers IMDbMarkupSyntax\TagProcessing::__construct
     */
    public function testFindTconstEmpty()
    {
        $obj = new TagProcessing(null);
        $this->assertFalse($obj->findTconst(), "Id is found, not good");
        $this->assertEmpty($obj->tconst);
        $obj2 = new TagProcessing("");
        $this->assertFalse($obj2->findTconst(), "Id is found, not good");
        $this->assertEmpty($obj2->tconst);
    }

    /**
     * Negativ test for Exception handler of a PREG_ERROR
     * @covers IMDbMarkupSyntax\TagProcessing::findTconst
     * @covers IMDbMarkupSyntax\Exceptions\PCREException
     * @covers IMDbMarkupSyntax\TagProcessing::__construct
     */
    public function testFindTconstPREG_ERROR()
    {
        $obj = new TagProcessing("foobar foobar foobar");
        $obj->tconst_pattern = "/(?:\D+|<\d+>)*[!?]/";
        try {
            $this->assertFalse($obj->findTconst(), "Id is found, not good");
            $this->assertEmpty($obj->tconst);
        } catch (PCREException $exc) {
            $this->assertEquals($exc->getMessage(), "PREG_BACKTRACK_LIMIT_ERROR");
            return;
        }
        $this->fail('An expected exception has not been raised.');
    }

    /**
     * Negativ test for Exception handler of a Compilation failed
     * @covers IMDbMarkupSyntax\TagProcessing::findTconst
     * @covers IMDbMarkupSyntax\Exceptions\PCREException
     * @covers IMDbMarkupSyntax\TagProcessing::__construct
     */
    public function testFindTconstErrorControlOperators()
    {
        $obj = new TagProcessing("foobar foobar foobar");
        $obj->tconst_pattern = "/(/";
        try {
            $this->assertFalse($obj->findTconst(), "Id is found, not good");
            $this->assertEmpty($obj->tconst);
        } catch (PCREException $exc) {
            $this->assertContains("Compilation failed", $exc->getMessage());
            return;
        }
        $this->fail('An expected exception has not been raised.');
    }

    /**
     * Find one tag. Positive test.
     * @covers IMDbMarkupSyntax\TagProcessing::findImdbTags
     * @covers IMDbMarkupSyntax\TagProcessing::__construct
     */
    public function testFindImdbTagsOnePositive()
    {
        $obj = new TagProcessing($this->testdata["one_positive"]);
        $this->assertTrue($obj->findImdbTags(), "Not found = not good");
        $this->assertCount(1, $obj->imdb_tags);
        $this->assertEquals("title", $obj->imdb_tags[0]);
    }

    /**
     * Find two tag. Positive test.
     * @covers IMDbMarkupSyntax\TagProcessing::findImdbTags
     * @covers IMDbMarkupSyntax\TagProcessing::__construct
     */
    public function testFindImdbTagsTwoPositive()
    {
        $obj = new TagProcessing($this->testdata["two_positive"]);
        $this->assertTrue($obj->findImdbTags(), "Not found = not good");
        $this->assertCount(2, $obj->imdb_tags);
        $this->assertEquals("title", $obj->imdb_tags[0]);
        $this->assertEquals("year", $obj->imdb_tags[1]);
    }

    /**
     * Find zero tag. Alternative test.
     * @covers IMDbMarkupSyntax\TagProcessing::findImdbTags
     * @covers IMDbMarkupSyntax\TagProcessing::__construct
     */
    public function testFindImdbTagsNoMatch()
    {
        $obj = new TagProcessing($this->testdata["no_match"]);
        $this->assertFalse($obj->findImdbTags(), "Found = not good");
        $this->assertCount(0, $obj->imdb_tags);
    }

    /**
     * Null input. Alternative test
     * @covers IMDbMarkupSyntax\TagProcessing::findImdbTags
     * @covers IMDbMarkupSyntax\TagProcessing::__construct
     */
    public function testFindImdbTagsEmpty()
    {
        $obj = new TagProcessing(null);
        $this->assertFalse($obj->findImdbTags(), "tags is found, not good");
        $this->assertEmpty($obj->imdb_tags);
        $obj2 = new TagProcessing("");
        $this->assertFalse($obj2->findImdbTags(), "tags is found, not good");
        $this->assertEmpty($obj2->imdb_tags);
    }

    /**
     * Negativ test for Exception handler of a PREG_ERROR
     * @covers IMDbMarkupSyntax\TagProcessing::findImdbTags
     * @covers IMDbMarkupSyntax\Exceptions\PCREException
     * @covers IMDbMarkupSyntax\TagProcessing::__construct
     */
    public function testFindImdbTagsPREG_ERROR()
    {
        $obj = new TagProcessing("foobar foobar foobar");
        $obj->imdb_tags_pattern = "/(?:\D+|<\d+>)*[!?]/";
        try {
            $this->assertFalse($obj->findImdbTags(), "Id is found, not good");
            $this->assertEmpty($obj->imdb_tags);
        } catch (PCREException $exc) {
            $this->assertEquals($exc->getMessage(), "PREG_BACKTRACK_LIMIT_ERROR");
            return;
        }
        $this->fail('An expected exception has not been raised.');
    }

    /**
     * Negativ test for Exception handler of a Compilation failed
     * @covers IMDbMarkupSyntax\TagProcessing::findImdbTags
     * @covers IMDbMarkupSyntax\Exceptions\PCREException
     * @covers IMDbMarkupSyntax\TagProcessing::__construct
     */
    public function testFindImdbTagsErrorControlOperators()
    {
        $obj = new TagProcessing("foobar foobar foobar");
        $obj->imdb_tags_pattern = "/(/";
        try {
            $this->assertFalse($obj->findImdbTags(), "imdb is found, not good");
            $this->assertEmpty($obj->imdb_tags);
        } catch (PCREException $exc) {
            $this->assertContains("Compilation failed", $exc->getMessage());
            return;
        }
        $this->fail('An expected exception has not been raised.');
    }

}
