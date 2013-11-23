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
 * @license   http://opensource.org/licenses/gpl-3.0.html GPL-3.0
 * @link      https://github.com/HenrikRoos/imdb-markup-syntax imdb-markup-syntax
 */

require_once 'Tag_Processing_Help.php';

/**
 * Sub testclass to Tag_ProcessingTest for method findId in Tag_Processing class
 *
 * @category  Testable
 * @package   Test
 * @author    Henrik Roos <henrik.roos@afternoon.se>
 * @copyright 2013 Henrik Roos
 * @license   http://opensource.org/licenses/gpl-3.0.html GPL-3.0
 * @link      https://github.com/HenrikRoos/imdb-markup-syntax imdb-markup-syntax
 */
class Find_IdTest extends PHPUnit_Framework_TestCase
{
    public $original_content = array(
        'one_positive' => 'Pellentesque viverra luctus est, vel bibendum arcu
                suscipit quis. Quisque congue [IMDb:id(tt0137523)]. Title:
                [imdb:title]',
        'two_positive' => 'Pellentesque viverra luctus est, vel bibendum arcu
                suscipit quis.[IMDb:id(http://www.imdb.com/title/tt0137523/)]
                Quisque congue [IMDb:id(tt0102926)] Title: [imdb:title]
                [IMDb:id(tt0137523)]. Year: [IMDb:year]',
        'no_match'     => 'Pellentesque viverra luctus est, vel bibendum arcu
                suscipit quis. [IMDb:id(http://www.imdb.com/title/tt0137523/)]
                Quisque congue [IMDb:id()] Title: [title] [IMDb:id:tt0137523]
                [IMDb:id:(0137523)] [IMDb:id(tt)]'
    );

    /**
     * One [IMDb:id(xxx)] tag, Positive test
     *
     * @covers Tag_Processing::__construct
     * @covers Tag_Processing::findId
     *
     * @return void
     */
    public function testOnePositive()
    {
        //Given
        $original_content = $this->original_content['one_positive'];
        $expected = array('[IMDb:id(tt0137523)]', 'tt0137523');

        //When
        $obj = new Tag_Processing_Help($original_content);
        $condition = $obj->findId();
        $actual = $obj->tconst_tag;

        //Then
        $this->assertTrue($condition);
        $this->assertSame($expected, $actual);
    }

    /**
     * Two correct [IMDb:id(xxx)] tags, Positive test. Only one is set
     * (first one)
     *
     * @covers Tag_Processing::__construct
     * @covers Tag_Processing::findId
     *
     * @return void
     */
    public function testTwoPositive()
    {
        //Given
        $original_content = $this->original_content['two_positive'];
        $expected = array('[IMDb:id(tt0102926)]', 'tt0102926');

        //When
        $obj = new Tag_Processing_Help($original_content);
        $condition = $obj->findId();
        $actual = $obj->tconst_tag;

        //Then
        $this->assertTrue($condition);
        $this->assertSame($expected, $actual);
    }

    /**
     * Negative test: Under min length of id. <b>tt\d{6}</b>
     *
     * @covers Tag_Processing::__construct
     * @covers Tag_Processing::findId
     *
     * @return void
     */
    public function testMinNegative()
    {
        //Given
        $original_content = '[IMDb:id(tt999999)]';
        $expected = array();

        //When
        $obj = new Tag_Processing_Help($original_content);
        $condition = $obj->findId();
        $actual = $obj->tconst_tag;

        //Then
        $this->assertFalse($condition);
        $this->assertSame($expected, $actual);
    }

    /**
     * Positive test: Min length of id. <b>tt\d{7}</b>
     *
     * @expectedException        Runtime_Exception
     * @expectedExceptionMessage No data for this title id
     *
     * @covers                   Tag_Processing::__construct
     * @covers                   Tag_Processing::findId
     * @covers                   Runtime_Exception
     *
     * @return void
     */
    public function testMinPositive()
    {
        //Given
        $original_content = '[IMDb:id(tt0000000)]';

        //When
        $obj = new Tag_Processing_Help($original_content);
        $obj->findId();
    }

    /**
     * Positive test: Min length of id. <b>tt\d{20}</b>
     *
     * @expectedException        Runtime_Exception
     * @expectedExceptionMessage No data for this title id
     *
     * @covers                   Tag_Processing::__construct
     * @covers                   Tag_Processing::findId
     * @covers                   Runtime_Exception
     *
     * @return void
     */
    public function testMaxPositive()
    {
        //Given
        $original_content = '[IMDb:id(tt99999999999999999999)]';

        //When
        $obj = new Tag_Processing_Help($original_content);
        $obj->findId();
    }

    /**
     * Negative test: Under min length of id. <b>tt\d{21}</b>
     *
     * @covers Tag_Processing::__construct
     * @covers Tag_Processing::findId
     *
     * @return void
     */
    public function testMaxNegative()
    {
        //Given
        $original_content = '[IMDb:id(tt000000000000000000000)]';
        $expected = array();

        //When
        $obj = new Tag_Processing_Help($original_content);
        $condition = $obj->findId();
        $actual = $obj->tconst_tag;

        //Then
        $this->assertFalse($condition);
        $this->assertSame($expected, $actual);
    }

    /**
     * No correct [IMDb:id(xxx)] tags. Alternative test. id not set
     *
     * @covers Tag_Processing::__construct
     * @covers Tag_Processing::findId
     *
     * @return void
     */
    public function testNoMatch()
    {
        //Given
        $original_content = $this->original_content['no_match'];

        //When
        $obj = new Tag_Processing_Help($original_content);
        $condition = $obj->findId();

        //Then
        $this->assertFalse($condition);
        $this->assertEmpty($obj->tconst_tag);
    }

    /**
     * Null input = id not set
     *
     * @covers Tag_Processing::__construct
     * @covers Tag_Processing::findId
     *
     * @return void
     */
    public function testEmpty()
    {
        //Given
        $testdata = null;
        $testdata2 = "";

        //When
        $obj = new Tag_Processing_Help($testdata);
        $obj2 = new Tag_Processing_Help($testdata2);
        $condition = $obj->findId();
        $condition2 = $obj2->findId();

        //Then
        $this->assertFalse($condition);
        $this->assertEmpty($obj->tconst_tag);
        $this->assertFalse($condition2);
        $this->assertEmpty($obj2->tconst_tag);
    }

    /**
     * Negativ test for Exception handler of a PREG_ERROR
     *
     * @expectedException        PCRE_Exception
     * @expectedExceptionMessage PREG_BACKTRACK_LIMIT_ERROR
     *
     * @covers                   Tag_Processing::__construct
     * @covers                   Tag_Processing::findId
     * @covers                   PCRE_Exception
     *
     * @return void
     */
    public function testPregError()
    {
        //Given
        $original_content = 'foobar foobar foobar';
        $custom_id_pattern = '/(?:\D+|<\d+>)*[!?]/';

        //When
        $obj = new Tag_Processing_Help($original_content);
        $obj->custom_id_pattern = $custom_id_pattern;
        $obj->findId();
    }

    /**
     * Negativ test for Exception handler of a compilation failed
     *
     * @expectedException        PCRE_Exception
     * @expectedExceptionMessage Compilation failed
     *
     * @covers                   Tag_Processing::__construct
     * @covers                   Tag_Processing::findId
     * @covers                   PCRE_Exception
     *
     * @return void
     */
    public function testErrorControlOperators()
    {
        //Given
        $original_content = 'foobar foobar foobar';
        $custom_id_pattern = '/(/';

        //When
        $obj = new Tag_Processing_Help($original_content);
        $obj->custom_id_pattern = $custom_id_pattern;
        $obj->findId();
    }

}
