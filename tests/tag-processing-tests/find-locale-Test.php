<?php
/**
 * Sub testclass to tag-processing-tests for method find_locale in Tag_Processing class
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
 * Sub testclass to tag-processing-tests for method find_locale in Tag_Processing class
 *
 * @category  Testable
 * @package   Test
 * @author    Henrik Roos <henrik.roos@afternoon.se>
 * @copyright 2014 Henrik Roos
 * @license   http://opensource.org/licenses/gpl-3.0.html GPL-3.0
 * @link      https://github.com/HenrikRoos/imdb-markup-syntax imdb-markup-syntax
 */
class Find_Locale_Test extends PHPUnit_Framework_TestCase {

	public $original_content = [
		'one_positive' => 'Pellentesque [IMDb:id(tt0137523)] [IMDb:locale(fr_FR)]. Title: [imdb:title]',
		'two_positive' => 'Pellentesque [IMDb:id(tt0137523)] [IMDb:locale(fr_FR)] [IMDb:locale(de)] Quisque congue [IMDb:id()] Title: [title] [IMDb:id:tt0137523] [IMDb:id:(0137523)] [IMDb:id(tt)]',
		'no_match'     => 'Pellentesque [IMDb:id(tt0137523)] [IMDb:locale(f)] Quisque congue [IMDb:id()] Title: [title] [IMDb:id:tt0137523] [IMDb:id:(0137523)] [IMDb:id(tt)]',
	];

	/**
	 * One [IMDb:locale(xxx)] tag, Positive test
	 *
	 * @covers Tag_Processing::__construct
	 * @covers Tag_Processing::find_locale
	 *
	 * @return void
	 */
	public function test_one_positive() {
		//Given
		$original_content = $this->original_content['one_positive'];
		$expected         = 'fr_FR';
		$expected_tag     = [ '[IMDb:locale(fr_FR)]', 'fr_FR' ];

		//When
		$obj        = new Tag_Processing_Help( $original_content );
		$condition  = $obj->find_locale();
		$actual     = $obj->locale;
		$actual_tag = $obj->locale_tag;

		//Then
		$this->assertTrue( $condition );
		$this->assertSame( $expected, $actual );
		$this->assertSame( $expected_tag, $actual_tag );
	}

	/**
	 * Two correct [IMDb:locale(xxx)] tags, Positive test. Only one is set
	 * (first one)
	 *
	 * @covers Tag_Processing::__construct
	 * @covers Tag_Processing::find_locale
	 *
	 * @return void
	 */
	public function test_two_positive() {
		//Given
		$original_content = $this->original_content['two_positive'];
		$expected         = 'fr_FR';

		//When
		$obj       = new Tag_Processing_Help( $original_content );
		$condition = $obj->find_locale();
		$actual    = $obj->locale;

		//Then
		$this->assertTrue( $condition );
		$this->assertSame( $expected, $actual );
	}

	/**
	 * No correct [IMDb:locale(xxx)] tags. Alternative test. locale not set
	 *
	 * @covers Tag_Processing::__construct
	 * @covers Tag_Processing::find_locale
	 *
	 * @return void
	 */
	public function test_no_match() {
		//Given
		$original_content = $this->original_content['no_match'];

		//When
		$obj       = new Tag_Processing_Help( $original_content );
		$condition = $obj->find_locale();

		//Then
		$this->assertFalse( $condition );
		$this->assertEmpty( $obj->tconst_tag );
	}

	/**
	 * Null input = id not set
	 *
	 * @covers Tag_Processing::__construct
	 * @covers Tag_Processing::find_locale
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
		$condition  = $obj->find_locale();
		$condition2 = $obj2->find_locale();

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
	 * @covers                   Tag_Processing::find_locale
	 * @covers                   PCRE_Exception
	 *
	 * @return void
	 */
	public function test_preg_error() {
		//Given
		$original_content      = 'foobar foobar foobar';
		$custom_locale_pattern = '/(?:\D+|<\d+>)*[!?]/';

		//When
		$obj                        = new Tag_Processing_Help( $original_content );
		$obj->custom_locale_pattern = $custom_locale_pattern;
		$obj->find_locale();
	}

	/**
	 * Negativ test for Exception handler of a compilation failed
	 *
	 * @expectedException        PCRE_Exception
	 * @expectedExceptionMessage Compilation failed
	 *
	 * @covers                   Tag_Processing::__construct
	 * @covers                   Tag_Processing::find_locale
	 * @covers                   PCRE_Exception
	 *
	 * @return void
	 */
	public function test_error_control_operators() {
		//Given
		$original_content      = 'foobar foobar foobar';
		$custom_locale_pattern = '/(/';

		//When
		$obj                        = new Tag_Processing_Help( $original_content );
		$obj->custom_locale_pattern = $custom_locale_pattern;
		$obj->find_locale();
	}

}
