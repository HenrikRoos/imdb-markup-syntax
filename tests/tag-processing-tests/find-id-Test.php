<?php
/**
 * Sub testclass to tag-processing-tests for method findId in Tag_Processing class
 *
 * PHP version 5
 *
 * @category  Testable
 * @package   Test
 * @author    Henrik Roos <henrik.roos@afternoon.se>
 * @copyright 2014 Henrik Roos
 * @license   http://opensource.org/licenses/gpl-3.0.html GPL-3.0
 * @link      https://github.com/HenrikRoos/imdb-markup-syntax imdb-markup-syntax
 */

require_once 'tag-processing-help.php';

/**
 * Sub testclass to tag-processing-tests for method findId in Tag_Processing class
 *
 * @category  Testable
 * @package   Test
 * @author    Henrik Roos <henrik.roos@afternoon.se>
 * @copyright 2014 Henrik Roos
 * @license   http://opensource.org/licenses/gpl-3.0.html GPL-3.0
 * @link      https://github.com/HenrikRoos/imdb-markup-syntax imdb-markup-syntax
 */
class Find_Id_Test extends PHPUnit_Framework_TestCase {

	public $original_content = [
		'one_positive' => 'Pellentesque viverra luctus est, vel bibendum arcu suscipit quis. Quisque congue [IMDb:id(tt0137523)]. Title: [imdb:title]',
		'two_positive' => 'Pellentesque viverra luctus est, vel bibendum arcu suscipit quis.[IMDb:id(http://www.imdb.com/title/tt0137523/)] Quisque congue [IMDb:id(tt0102926)] Title: [imdb:title] [IMDb:id(tt0137523)]. Year: [IMDb:year]',
		'no_match'     => 'Pellentesque viverra luctus est, vel bibendum arcu suscipit quis. [IMDb:id(http://www.imdb.com/title/tt0137523/)] Quisque congue [IMDb:id()] Title: [title] [IMDb:id:tt0137523] [IMDb:id:(0137523)] [IMDb:id(tt)]',
	];

	/**
	 * One [IMDb:id(xxx)] tag, Positive test
	 *
	 * @covers Tag_Processing::__construct
	 * @covers Tag_Processing::find_id
	 *
	 * @return void
	 */
	public function test_one_positive() {
		//Given
		$original_content = $this->original_content['one_positive'];
		$expected         = [ '[IMDb:id(tt0137523)]', 'tt0137523' ];

		//When
		$obj       = new Tag_Processing_Help( $original_content );
		$condition = $obj->find_id();
		$actual    = $obj->tconst_tag;

		//Then
		$this->assertTrue( $condition );
		$this->assertSame( $expected, $actual );
	}

	/**
	 * Two correct [IMDb:id(xxx)] tags, Positive test. Only one is set
	 * (first one)
	 *
	 * @covers Tag_Processing::__construct
	 * @covers Tag_Processing::find_id
	 *
	 * @return void
	 */
	public function test_two_positive() {
		//Given
		$original_content = $this->original_content['two_positive'];
		$expected         = [ '[IMDb:id(tt0102926)]', 'tt0102926' ];

		//When
		$obj       = new Tag_Processing_Help( $original_content );
		$condition = $obj->find_id();
		$actual    = $obj->tconst_tag;

		//Then
		$this->assertTrue( $condition );
		$this->assertSame( $expected, $actual );
	}

	/**
	 * Negative test: Under min length of id. <b>tt\d{6}</b>
	 *
	 * @covers Tag_Processing::__construct
	 * @covers Tag_Processing::find_id
	 *
	 * @return void
	 */
	public function test_min_negative() {
		//Given
		$original_content = '[IMDb:id(tt999999)]';
		$expected         = [ ];

		//When
		$obj       = new Tag_Processing_Help( $original_content );
		$condition = $obj->find_id();
		$actual    = $obj->tconst_tag;

		//Then
		$this->assertFalse( $condition );
		$this->assertSame( $expected, $actual );
	}

	/**
	 * Positive test: Min length of id. <b>tt\d{7}</b>
	 *
	 * @expectedException        Runtime_Exception
	 * @expectedExceptionMessage No data for this title id
	 *
	 * @covers                   Tag_Processing::__construct
	 * @covers                   Tag_Processing::find_id
	 * @covers                   Runtime_Exception
	 *
	 * @return void
	 */
	public function test_min_positive() {
		//Given
		$original_content = '[IMDb:id(tt0000000)]';

		//When
		$obj = new Tag_Processing_Help( $original_content );
		$obj->find_id();
	}

	/**
	 * Positive test: Min length of id. <b>tt\d{20}</b>
	 *
	 * @expectedException        Runtime_Exception
	 * @expectedExceptionMessage No data for this title id
	 *
	 * @covers                   Tag_Processing::__construct
	 * @covers                   Tag_Processing::find_id
	 * @covers                   Runtime_Exception
	 *
	 * @return void
	 */
	public function test_max_positive() {
		//Given
		$original_content = '[IMDb:id(tt99999999999999999999)]';

		//When
		$obj = new Tag_Processing_Help( $original_content );
		$obj->find_id();
	}

	/**
	 * Negative test: Under min length of id. <b>tt\d{21}</b>
	 *
	 * @covers Tag_Processing::__construct
	 * @covers Tag_Processing::find_id
	 *
	 * @return void
	 */
	public function test_max_negative() {
		//Given
		$original_content = '[IMDb:id(tt000000000000000000000)]';
		$expected         = [ ];

		//When
		$obj       = new Tag_Processing_Help( $original_content );
		$condition = $obj->find_id();
		$actual    = $obj->tconst_tag;

		//Then
		$this->assertFalse( $condition );
		$this->assertSame( $expected, $actual );
	}

	/**
	 * No correct [IMDb:id(xxx)] tags. Alternative test. id not set
	 *
	 * @covers Tag_Processing::__construct
	 * @covers Tag_Processing::find_id
	 *
	 * @return void
	 */
	public function test_no_match() {
		//Given
		$original_content = $this->original_content['no_match'];

		//When
		$obj       = new Tag_Processing_Help( $original_content );
		$condition = $obj->find_id();

		//Then
		$this->assertFalse( $condition );
		$this->assertEmpty( $obj->tconst_tag );
	}

	/**
	 * Null input = id not set
	 *
	 * @covers Tag_Processing::__construct
	 * @covers Tag_Processing::find_id
	 *
	 * @return void
	 */
	public function test_empty() {
		//Given
		$testdata  = null;
		$testdata2 = '';

		//When
		$obj        = new Tag_Processing_Help( $testdata );
		$obj2       = new Tag_Processing_Help( $testdata2 );
		$condition  = $obj->find_id();
		$condition2 = $obj2->find_id();

		//Then
		$this->assertFalse( $condition );
		$this->assertEmpty( $obj->tconst_tag );
		$this->assertFalse( $condition2 );
		$this->assertEmpty( $obj2->tconst_tag );
	}

	/**
	 * Negativ test for Exception handler of a PREG_ERROR
	 *
	 * @expectedException        PCRE_Exception
	 * @expectedExceptionMessage PREG_BACKTRACK_LIMIT_ERROR
	 *
	 * @covers                   Tag_Processing::__construct
	 * @covers                   Tag_Processing::find_id
	 * @covers                   PCRE_Exception
	 *
	 * @return void
	 */
	public function test_preg_error() {
		//Given
		$original_content  = 'foobar foobar foobar';
		$custom_id_pattern = '/(?:\D+|<\d+>)*[!?]/';

		//When
		$obj                    = new Tag_Processing_Help( $original_content );
		$obj->custom_id_pattern = $custom_id_pattern;
		$obj->find_id();
	}

	/**
	 * Negativ test for Exception handler of a compilation failed
	 *
	 * @expectedException        PCRE_Exception
	 * @expectedExceptionMessage Compilation failed
	 *
	 * @covers                   Tag_Processing::__construct
	 * @covers                   Tag_Processing::find_id
	 * @covers                   PCRE_Exception
	 *
	 * @return void
	 */
	public function test_error_control_operators() {
		//Given
		$original_content  = 'foobar foobar foobar';
		$custom_id_pattern = '/(/';

		//When
		$obj                    = new Tag_Processing_Help( $original_content );
		$obj->custom_id_pattern = $custom_id_pattern;
		$obj->find_id();
	}

}
