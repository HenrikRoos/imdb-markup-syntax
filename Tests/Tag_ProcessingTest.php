<?php

/**
 * PhpDoc: Page-level DocBlock
 * @package imdb-markup-syntax-test
 */

namespace IMDb_Markup_Syntax\Tests;

use IMDb_Markup_Syntax\Exceptions\PCRE_Exception;
use IMDb_Markup_Syntax\Tag_Processing;
use PHPUnit_Framework_TestCase;

require_once 'PHPUnit/Autoload.php';
require_once dirname(__FILE__) . '/../class-tag-processing.php';
require_once dirname(__FILE__) . '/../Exceptions/class-pcre-exception.php';


/**
 * Testclass (PHPUnit) test for Tag_Processing class.
 * @author Henrik Roos <henrik at afternoon.se>
 * @package imdb-markup-syntax-test
 */
class Tag_ProcessingTest extends PHPUnit_Framework_TestCase {

    /**
     * @var array string of testdata 
     */
    public $testdata;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp() {
        $this->testdata = array(
            "one_positive" => "Pellentesque viverra luctus est, vel bibendum arcu suscipit quis.
                Quisque congue [IMDb:id(tt0137523)]. Title: [imdb:title]",
            "two_positive" => "Pellentesque viverra luctus est, vel bibendum arcu suscipit quis.
                [IMDb:id(http://www.imdb.com/title/tt0137523/)] Quisque congue [IMDb:id(tt0102926)]
                Title: [imdb:title] [IMDb:id(tt0137523)]. Year: [imdb:year]",
            "no_match" => "Pellentesque viverra luctus est, vel bibendum arcu suscipit quis.
                [IMDb:id(http://www.imdb.com/title/tt0137523/)] Quisque congue [IMDb:id()]
                Title: [title] [IMDb:id:tt0137523] [IMDb:id:(0137523)] [IMDb:id(tt)]",
        );
    }

    /**
     * One [IMDb:id(xxx)] tag, Positive test.
     * @covers IMDb_Markup_Syntax\Tag_Processing::find_id
     * @covers IMDb_Markup_Syntax\Tag_Processing::__construct
     */
    public function testFind_idOnePositive() {
        $obj = new Tag_Processing($this->testdata["one_positive"]);
        $this->assertTrue($obj->find_id(), "Id, not found");
        $this->assertEquals("tt0137523", $obj->id);
    }

    /**
     * Two correct [IMDb:id(xxx)] tags, Positive test. Only one is set (first one).
     * @covers IMDb_Markup_Syntax\Tag_Processing::find_id
     * @covers IMDb_Markup_Syntax\Tag_Processing::__construct
     */
    public function testFind_idTwoPositive() {
        $this->original_content = new Tag_Processing($this->testdata["two_positive"]);
        $this->assertTrue($this->original_content->find_id(), "Id, not found");
        $this->assertEquals("tt0102926", $this->original_content->id);
    }

    /**
     * No correct [IMDb:id(xxx)] tags. Alternative test. id not set.
     * @covers IMDb_Markup_Syntax\Tag_Processing::find_id
     * @covers IMDb_Markup_Syntax\Tag_Processing::__construct
     */
    public function testFind_idNoMatch() {
        $obj = new Tag_Processing($this->testdata["no_match"]);
        $this->assertFalse($obj->find_id(), "Id is found, not good");
        $this->assertEmpty($obj->id);
    }

    /**
     * Null input = id not set.
     * @covers IMDb_Markup_Syntax\Tag_Processing::find_id
     * @covers IMDb_Markup_Syntax\Tag_Processing::__construct
     */
    public function testFind_idEmpty() {
        $obj = new Tag_Processing(null);
        $this->assertFalse($obj->find_id(), "Id is found, not good");
        $this->assertEmpty($obj->id);
        $obj2 = new Tag_Processing("");
        $this->assertFalse($obj2->find_id(), "Id is found, not good");
        $this->assertEmpty($obj2->id);
    }

    /**
     * Negativ test for Exception handler of a PREG_ERROR
     * @covers IMDb_Markup_Syntax\Tag_Processing::find_id
     * @covers IMDb_Markup_Syntax\Exceptions\PCRE_Exception
     * @covers IMDb_Markup_Syntax\Tag_Processing::__construct
     */
    public function testFind_idPREG_ERROR() {
        $obj = new Tag_Processing("foobar foobar foobar");
        $obj->id_pattern = "/(?:\D+|<\d+>)*[!?]/";
        try {
            $this->assertFalse($obj->find_id(), "Id is found, not good");
            $this->assertEmpty($obj->id);
        } catch (PCRE_Exception $exc) {
            $this->assertEquals($exc->getMessage(), "PREG_BACKTRACK_LIMIT_ERROR");
            return;
        }
        $this->fail('An expected exception has not been raised.');
    }

    /**
     * Negativ test for Exception handler of a Compilation failed
     * @covers IMDb_Markup_Syntax\Tag_Processing::find_id
     * @covers IMDb_Markup_Syntax\Exceptions\PCRE_Exception
     * @covers IMDb_Markup_Syntax\Tag_Processing::__construct
     */
    public function testFind_idErrorControlOperators() {
        $obj = new Tag_Processing("foobar foobar foobar");
        $obj->id_pattern = "/(/";
        try {
            $this->assertFalse($obj->find_id(), "Id is found, not good");
            $this->assertEmpty($obj->id);
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
    public function testFind_imdb_tagsOnePositive() {
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
    public function testFind_imdb_tagsTwoPositive() {
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
    public function testFind_imdb_tagsNoMatch() {
        $obj = new Tag_Processing($this->testdata["no_match"]);
        $this->assertFalse($obj->find_imdb_tags(), "Found = not good");
        $this->assertCount(0, $obj->imdb_tags);
    }

    /**
     * Null input. Alternative test
     * @covers IMDb_Markup_Syntax\Tag_Processing::find_imdb_tags
     * @covers IMDb_Markup_Syntax\Tag_Processing::__construct
     */
    public function testFind_imdb_tagsEmpty() {
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
    public function testFind_imdb_tagsPREG_ERROR() {
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
    public function testFind_imdb_tagsErrorControlOperators() {
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
