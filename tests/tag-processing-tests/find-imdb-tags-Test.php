<?php
/**
 * Sub testclass to tag-processing-tests for method findImdbTags in Tag_Processing
 * class
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
 * Sub testclass to tag-processing-tests for method findImdbTags in Tag_Processing
 * class
 *
 * @category  Testable
 * @package   Test
 * @author    Henrik Roos <henrik.roos@afternoon.se>
 * @copyright 2014 Henrik Roos
 * @license   http://opensource.org/licenses/gpl-3.0.html GPL-3.0
 * @link      https://github.com/HenrikRoos/imdb-markup-syntax imdb-markup-syntax
 */
class Find_Imdb_Tags_Test extends PHPUnit_Framework_TestCase {

	public $original_content = [
		'one_positive' => 'Pellentesque viverra luctus est, vel bibendum arcu suscipit quis. Quisque congue [IMDb:id(tt0137523)]. Title: [imdb:title]',
		'two_positive' => 'Pellentesque viverra luctus est, vel bibendum arcu suscipit quis.[IMDb:id(http://www.imdb.com/title/tt0137523/)] Quisque congue [IMDb:id(tt0102926)] Title: [imdb:title] [IMDb:id(tt0137523)]. Year: [IMDb:year]',
		'no_match'     => 'Pellentesque viverra luctus est, vel bibendum arcu suscipit quis. [IMDb:id(http://www.imdb.com/title/tt0137523/)] Quisque congue [IMDb:id()] Title: [title] [IMDb:id:tt0137523] [IMDb:id:(0137523)] [IMDb:id(tt)]',
	];

	/**
	 * Find one tag. Positive test
	 *
	 * @covers Tag_Processing::find_imdb_tags
	 * @covers Tag_Processing::__construct
	 *
	 * @return void
	 */
	public function test_one_positive() {
		//Given
		$original_content = $this->original_content['one_positive'];
		$expectedCount    = 1;
		$expected         = [ [ '[imdb:title]', 'title' ] ];

		//When
		$obj       = new Tag_Processing_Help( $original_content );
		$condition = $obj->find_imdb_tags();
		$haystack  = $obj->imdb_tags;
		$actual    = $obj->imdb_tags;

		//Then
		$this->assertTrue( $condition );
		$this->assertCount( $expectedCount, $haystack );
		$this->assertSame( $expected, $actual );
	}

	/**
	 * Find two tag. Positive test
	 *
	 * @covers Tag_Processing::find_imdb_tags
	 * @covers Tag_Processing::__construct
	 *
	 * @return void
	 */
	public function test_two_positive() {
		//Given
		$original_content = $this->original_content['two_positive'];
		$expectedCount    = 2;
		$expected         = [
			[ '[imdb:title]', 'title' ],
			[ '[IMDb:year]', 'year' ],
		];

		//When
		$obj       = new Tag_Processing_Help( $original_content );
		$condition = $obj->find_imdb_tags();
		$haystack  = $obj->imdb_tags;
		$actual    = $obj->imdb_tags;

		//Then
		$this->assertTrue( $condition );
		$this->assertCount( $expectedCount, $haystack );
		$this->assertSame( $expected, $actual );
	}

	/**
	 * Negative test: Under min length of id. <b>[a-z0-9]{0}</b>
	 *
	 * @covers Tag_Processing::__construct
	 * @covers Tag_Processing::find_imdb_tags
	 *
	 * @return void
	 */
	public function test_min_negative() {
		//Given
		$original_content = '[imdb:]';
		$expected         = [ ];

		//When
		$obj       = new Tag_Processing_Help( $original_content );
		$condition = $obj->find_imdb_tags();
		$actual    = $obj->imdb_tags;

		//Then
		$this->assertFalse( $condition );
		$this->assertSame( $expected, $actual );
	}

	/**
	 * Positive test: Min length of id. <b>[a-z0-9]{1}</b>
	 *
	 * @covers Tag_Processing::__construct
	 * @covers Tag_Processing::find_imdb_tags
	 *
	 * @return void
	 */
	public function test_min_positive() {
		//Given
		$original_content = '[imdb:a]';
		$expected         = [ [ '[imdb:a]', 'a' ] ];

		//When
		$obj       = new Tag_Processing_Help( $original_content );
		$condition = $obj->find_imdb_tags();
		$actual    = $obj->imdb_tags;

		//Then
		$this->assertTrue( $condition );
		$this->assertSame( $expected, $actual );
	}

	/**
	 * Positive test: Min length of id. <b>[a-z0-9]{40}</b>
	 *
	 * @covers Tag_Processing::__construct
	 * @covers Tag_Processing::find_imdb_tags
	 *
	 * @return void
	 */
	public function test_max_positive() {
		//Given
		$original_content = '[imdb:abcdefghijklmnopqrstuvxyzABCDEFGHIJ0123_]';
		$expected         = [
			[
				'[imdb:abcdefghijklmnopqrstuvxyzABCDEFGHIJ0123_]',
				'abcdefghijklmnopqrstuvxyzABCDEFGHIJ0123_',
			],
		];

		//When
		$obj       = new Tag_Processing_Help( $original_content );
		$condition = $obj->find_imdb_tags();
		$actual    = $obj->imdb_tags;

		//Then
		$this->assertTrue( $condition );
		$this->assertSame( $expected, $actual );
	}

	/**
	 * Negative test: Under min length of id. <b>[a-z0-9]{41}</b>
	 *
	 * @covers Tag_Processing::__construct
	 * @covers Tag_Processing::find_id
	 *
	 * @return void
	 */
	public function test_max_negative() {
		//Given
		$original_content = '[imdb:abcdefghijklmnopqrstuvxyzABCDEFGHIJ0123_a]';
		$expected         = [ ];

		//When
		$obj       = new Tag_Processing_Help( $original_content );
		$condition = $obj->find_imdb_tags();
		$actual    = $obj->imdb_tags;

		//Then
		$this->assertFalse( $condition );
		$this->assertSame( $expected, $actual );
	}

	/**
	 * Find zero tag. Alternative test
	 *
	 * @covers Tag_Processing::find_imdb_tags
	 * @covers Tag_Processing::__construct
	 *
	 * @return void
	 */
	public function test_no_match() {
		//Given
		$original_content = $this->original_content['no_match'];
		$expectedCount    = 0;

		//When
		$obj       = new Tag_Processing_Help( $original_content );
		$condition = $obj->find_imdb_tags();
		$haystack  = $obj->imdb_tags;

		//Then
		$this->assertFalse( $condition );
		$this->assertCount( $expectedCount, $haystack );
	}

	/**
	 * Null input. Alternative test
	 *
	 * @covers Tag_Processing::find_imdb_tags
	 * @covers Tag_Processing::__construct
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
		$condition  = $obj->find_imdb_tags();
		$condition2 = $obj2->find_imdb_tags();

		//Then
		$this->assertFalse( $condition );
		$this->assertEmpty( $obj->imdb_tags );
		$this->assertFalse( $condition2 );
		$this->assertEmpty( $obj2->imdb_tags );
	}

	/**
	 * Negativ test for Exception handler of a PREG_ERROR
	 *
	 * @expectedException        PCRE_Exception
	 * @expectedExceptionMessage PREG_BACKTRACK_LIMIT_ERROR
	 *
	 * @covers                   Tag_Processing::__construct
	 * @covers                   Tag_Processing::find_imdb_tags
	 * @covers                   PCRE_Exception
	 *
	 * @return void
	 */
	public function test_preg_error() {
		//Given
		$original_content    = 'foobar foobar foobar';
		$custom_tags_pattern = '/(?:\D+|<\d+>)*[!?]/';

		//When
		$obj                      = new Tag_Processing_Help( $original_content );
		$obj->custom_tags_pattern = $custom_tags_pattern;
		$obj->find_imdb_tags();
	}

	/**
	 * Negativ test for Exception handler of a Compilation failed
	 *
	 * @expectedException        PCRE_Exception
	 * @expectedExceptionMessage Compilation failed
	 *
	 * @covers                   Tag_Processing::find_imdb_tags
	 * @covers                   Tag_Processing::__construct
	 * @covers                   PCRE_Exception
	 *
	 * @return void
	 */
	public function test_error_control_operators() {
		//Given
		$original_content    = 'foobar foobar foobar';
		$custom_tags_pattern = '/(/';

		//When
		$obj                      = new Tag_Processing_Help( $original_content );
		$obj->custom_tags_pattern = $custom_tags_pattern;
		$obj->find_imdb_tags();
	}

}
