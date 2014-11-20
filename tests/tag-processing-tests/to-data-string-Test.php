<?php
/**
 * Sub testclass to tag-processing-tests for method toDataString in Tag_Processing
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
require_once 'wp-config.php';

/**
 * Sub testclass to tag-processing-tests for method toDataString in Tag_Processing
 * class
 *
 * @category  Testable
 * @package   Test
 * @author    Henrik Roos <henrik.roos@afternoon.se>
 * @copyright 2014 Henrik Roos
 * @license   http://opensource.org/licenses/gpl-3.0.html GPL-3.0
 * @link      https://github.com/HenrikRoos/imdb-markup-syntax imdb-markup-syntax
 */
class To_Data_String_Test extends PHPUnit_Framework_TestCase {

	public $original_content = [
		'one_positive' => 'Pellentesque viverra luctus est, vel bibendum arcu suscipit quis. Quisque congue [IMDb:id(tt0137523)]. Title: [imdb:title]',
		'two_positive' => 'Pellentesque viverra luctus est, vel bibendum arcu suscipit quis.[IMDb:id(http://www.imdb.com/title/tt0137523/)] Quisque congue [IMDb:id(tt0102926)] Title: [imdb:title] [IMDb:id(tt0137523)]. Year: [IMDb:year]',
		'no_match'     => 'Pellentesque viverra luctus est, vel bibendum arcu suscipit quis. [IMDb:id(http://www.imdb.com/title/tt0137523/)] Quisque congue [IMDb:id()] Title: [title] [IMDb:id:tt0137523] [IMDb:id:(0137523)] [IMDb:id(tt)]',
	];

	/**
	 * Positive test. Test maching of a function
	 *
	 * @covers Tag_Processing::__construct
	 * @covers Tag_Processing::to_data_string
	 *
	 * @return void
	 */
	public function test_positive() {
		//Given
		$original_content = $this->original_content['one_positive'];
		$locale           = 'sv_SE';
		$tag              = 'date';
		$expected         = 'Lör 25 Dec 1999';

		//When
		$obj         = new Tag_Processing_Help( $original_content );
		$obj->locale = $locale;
		$condition   = $obj->find_id();
		$actual      = $obj->to_data_string( $tag );

		//Then
		$this->assertTrue( $condition );
		$this->assertSame( $expected, $actual );
	}

	/**
	 * No maching function to the tags.
	 *
	 * @covers Tag_Processing::__construct
	 * @covers Tag_Processing::to_data_string
	 *
	 * @return void
	 */
	public function test_invalid_name() {
		//Given
		$original_content = $this->original_content['one_positive'];
		$tag              = 'öäå';
		$expected         = __( 'Invalid function name', 'imdb-markup-syntax' );

		//When
		try {
			$obj = new Tag_Processing_Help( $original_content );
			$obj->find_id();
			$obj->to_data_string( $tag );
		} //Then
		catch ( Runtime_Exception $exp ) {
			$this->assertSame( $expected, $exp->getMessage() );

			return;
		}

		$this->fail( 'An expected Imdb_Runtime_Exception has not been raised.' );
	}

	/**
	 * No maching function to the tags.
	 *
	 * @covers Tag_Processing::__construct
	 * @covers Tag_Processing::to_data_string
	 *
	 * @return void
	 */
	public function test_no_match() {
		//Given
		$original_content = $this->original_content['one_positive'];
		$tag              = 'is_null';
		$expected         = sprintf( __( '[Tag %s not exists]', 'imdb-markup-syntax' ), $tag );

		//When
		try {
			$obj = new Tag_Processing_Help( $original_content );
			$obj->find_id();
			$obj->to_data_string( $tag );
		} //Then
		catch ( Runtime_Exception $exp ) {
			$this->assertSame( $expected, $exp->getMessage() );

			return;
		}

		$this->fail( 'An expected Imdb_Runtime_Exception has not been raised.' );
	}

	/**
	 * Alternative positive test. Test when wrong capitalize is precent
	 *
	 * @covers Tag_Processing::__construct
	 * @covers Tag_Processing::to_data_string
	 *
	 * @return void
	 */
	public function test_positive_capitalize() {
		//Given
		$original_content = $this->original_content['one_positive'];
		$tag              = 'TiTlE';
		$expected         = '<a href="http://www.imdb.com/title/tt0137523/">Fight Club</a>';

		//When
		$obj       = new Tag_Processing_Help( $original_content );
		$condition = $obj->find_id();
		$actual    = $obj->to_data_string( $tag );

		//Then
		$this->assertTrue( $condition );
		$this->assertSame( $expected, $actual );
	}

}
