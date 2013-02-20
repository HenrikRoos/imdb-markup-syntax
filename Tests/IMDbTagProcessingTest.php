<?php

require_once dirname(__FILE__) . '/../class-imdb-tag-processing.php';
require_once dirname(__FILE__) . '/../class-pcre-exception.php';

/**
 * Testclass for unit test for IMDb_Tag_Processing class.
 */
class IMDbTagProcessingTest extends PHPUnit_Framework_TestCase {

    /**
     * @var array string of testdata 
     */
    public $testdata;

    /**
     * @var IMDb_Tag_Processing
     */
    protected $obj;

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
                Title: [imdb:title] [IMDb:id(tt0137523)]",
            "no_match" => "Pellentesque viverra luctus est, vel bibendum arcu suscipit quis.
                [IMDb:id(http://www.imdb.com/title/tt0137523/)] Quisque congue [IMDb:id()]
                Title: [imdb:title] [IMDb:id:tt0137523]",
        );
    }

    /**
     * One [IMDb:id(xxx)] tag, Positive test.
     * @covers IMDb_Tag_Processing::find_id
     */
    public function testFind_idOnePositive() {
        $this->obj = new IMDb_Tag_Processing($this->testdata["one_positive"]);
        $this->assertTrue($this->obj->find_id(), "Id, not found");
        $this->assertEquals("tt0137523", $this->obj->id);
    }

    /**
     * Two correct [IMDb:id(xxx)] tags, Positive test. Only one is set (first one).
     * @covers IMDb_Tag_Processing::find_id
     */
    public function testFind_idTwoPositive() {
        $this->original_content = new IMDb_Tag_Processing($this->testdata["two_positive"]);
        $this->assertTrue($this->original_content->find_id(), "Id, not found");
        $this->assertEquals("tt0102926", $this->original_content->id);
    }

    /**
     * No correct [IMDb:id(xxx)] tags, Positive test. id not set.
     * @covers IMDb_Tag_Processing::find_id
     */
    public function testFind_idNoMatch() {
        $this->obj = new IMDb_Tag_Processing($this->testdata["no_match"]);
        $this->assertFalse($this->obj->find_id(), "Id is found, not good");
        $this->assertEmpty($this->obj->id);
    }

    /**
     * Null input = id not set.
     * @covers IMDb_Tag_Processing::find_id
     */
    public function testFind_idNull() {
        $this->obj = new IMDb_Tag_Processing(null);
        $this->assertFalse($this->obj->find_id(), "Id is found, not good");
        $this->assertEmpty($this->obj->id);
    }

    /**
     * Negativ test for Exception handler of a PREG_ERROR
     */
    public function testFind_id_PREG_ERROR() {
        $this->obj = new IMDb_Tag_Processing("foobar foobar foobar");
        $this->obj->id_pattern = "/(?:\D+|<\d+>)*[!?]/";
        try {
            $this->assertFalse($this->obj->find_id(), "Id is found, not good");
            $this->assertEmpty($this->obj->id);
        } catch (PCRE_Exception $exc) {
            $this->assertEquals($exc->getMessage(), "PREG_BACKTRACK_LIMIT_ERROR");
            return;
        }
        $this->fail('An expected exception has not been raised.');
    }

    /**
     * Negativ test for Exception handler of a Compilation failed
     */
    public function testFind_id_Error_Control_Operators() {
        $this->obj = new IMDb_Tag_Processing("foobar foobar foobar");
        $this->obj->id_pattern = "/(/";
        try {
            $this->assertFalse($this->obj->find_id(), "Id is found, not good");
            $this->assertEmpty($this->obj->id);
        } catch (PCRE_Exception $exc) {
            $this->assertContains("Compilation failed", $exc->getMessage());
            return;
        }
        $this->fail('An expected exception has not been raised.');
    }

}
