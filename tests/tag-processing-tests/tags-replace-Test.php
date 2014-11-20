<?php
/**
 * Sub testclass to tag-processing-tests for method tagsReplace in Tag_Processing
 * class
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

require_once 'tag-processing-help.php';
require_once 'wp-config.php';

/**
 * Sub testclass to tag-processing-tests for method tagsReplace in Tag_Processing
 * class
 *
 * @category  Testable
 * @package   Test
 * @author    Henrik Roos <henrik.roos@afternoon.se>
 * @copyright 2013 Henrik Roos
 * @license   http://opensource.org/licenses/gpl-3.0.html GPL-3.0
 * @link      https://github.com/HenrikRoos/imdb-markup-syntax imdb-markup-syntax
 */
class Tags_Replace_Test extends PHPUnit_Framework_TestCase {

	/** @var string Simple positive testdata with one id and one imdb tag */
	public $positive_data = 'Pellentesque viverra luctus est, vel bibendum arcu suscipit quis. ÖÄÅ öäå Quisque congue[IMDb:id(tt0137523)]. Title: [imdb:title]';

	/** @var string Simple positive testdata with one id and one imdb tag */
	public $positive_mix_data = 'Pellentesque viverra luctus est, vel bibendum arcu suscipit quis.[IMDb:id(http://www.imdb.com/title/tt0137523/)] Quisque congue [IMDb:id(tt0102926)] Title: [imdb:title] [IMDb:id(tt0137523)]. Year: [IMDb:years] [imdb:date] [imdb:cast] [imdb:title] [ImDB: writer ] [imdb:$$] [imdb:qwsazxcderrfvbgtyhnmjujdjhfksjhdfkjshdkfjhsakdjfhksjadhfkjsadf]';

	/**
	 * Replace one imdb tag and delete mandatory id. Positive test
	 *
	 * @covers Tag_Processing::__construct
	 * @covers Tag_Processing::tags_replace
	 * @covers Tag_Processing::get_replacement_content
	 *
	 * @return void
	 */
	public function test_one_positive() {
		//Given
		$original_content = $this->positive_data;
		$expected_content = 'Pellentesque viverra luctus est, vel bibendum arcu suscipit quis. ÖÄÅ öäå Quisque congue. Title: <a href="http://www.imdb.com/title/tt0137523/">Fight Club</a>';
		$expected_count   = 2;

		//When
		$obj            = new Tag_Processing_Help( $original_content );
		$actual_count   = $obj->tags_replace();
		$actual_content = $obj->get_replacement_content();

		//Then
		$this->assertSame( $expected_content, $actual_content );
		$this->assertSame( $expected_count, $actual_count );
	}

	/**
	 * Replace one imdb tag and delete mandatory id. Positive test
	 *
	 * @covers Tag_Processing::__construct
	 * @covers Tag_Processing::tags_replace
	 * @covers Tag_Processing::get_replacement_content
	 *
	 * @return void
	 */
	public function test_locale_positive() {
		//Given
		$original_content = '[IMDb:locale(es)][IMDb:id(tt0317219)][imdb:date]';
		$expected_content = 'Thu Jul  6 2006';
		$expected_count   = 3;

		//When
		$obj            = new Tag_Processing_Help( $original_content );
		$actual_count   = $obj->tags_replace();
		$actual_content = $obj->get_replacement_content();

		//Then
		$this->assertSame( $expected_content, $actual_content );
		$this->assertSame( $expected_count, $actual_count );
	}

	/**
	 * Replace one imdb tag and delete mandatory id. Positive test
	 *
	 * @covers Tag_Processing::__construct
	 * @covers Tag_Processing::tags_replace
	 * @covers Tag_Processing::get_replacement_content
	 *
	 * @return void
	 */
	public function test_mixed_positive() {
		//Given
		$original_content = $this->positive_mix_data;
		$expected_content = 'Pellentesque viverra luctus est, vel bibendum arcu suscipit quis.[IMDb:id(http://www.imdb.com/title/tt0137523/)] Quisque congue  Title: <a href="http://www.imdb.com/title/tt0102926/">The Silence of the Lambs</a> [IMDb:id(tt0137523)]. Year: ' . sprintf( __( '[Tag %s not exists]', 'imdb-markup-syntax' ), 'years' ) . ' Thu Feb 14 1991 <a href="http://www.imdb.com/name/nm0000149">Jodie Foster</a> Clarice Starling, <a href="http://www.imdb.com/name/nm0000164">Anthony Hopkins</a> Dr. Hannibal Lecter, <a href="http://www.imdb.com/name/nm0095029">Lawrence A. Bonney</a> FBI Instructor, <a href="http://www.imdb.com/name/nm0501435">Kasi Lemmons</a> Ardelia Mapp <a href="http://www.imdb.com/title/tt0102926/">The Silence of the Lambs</a> [ImDB: writer ] [imdb:$$] [imdb:qwsazxcderrfvbgtyhnmjujdjhfksjhdfkjshdkfjhsakdjfhksjadhfkjsadf]';
		$expected_count   = 6;

		//When
		$obj            = new Tag_Processing_Help( $original_content );
		$actual_count   = $obj->tags_replace();
		$actual_content = $obj->get_replacement_content();

		//Then
		$this->assertSame( $expected_content, $actual_content );
		$this->assertSame( $expected_count, $actual_count );
	}

	/**
	 * Replace one imdb tag and delete mandatory id. Positive test
	 *
	 * @covers Tag_Processing::__construct
	 * @covers Tag_Processing::tags_replace
	 * @covers Tag_Processing::get_replacement_content
	 *
	 * @return void
	 */
	public function test_prefix_positive() {
		//Given
		$prefix           = 'abc';
		$original_content = 'Pellentesque viverra luctus est, vel bibendum arcu suscipit quis. ÖÄÅ öäå Quisque congue[abc:id(tt0137523)]. Title: [abc:title] [imdb:date]';
		$expected_content = 'Pellentesque viverra luctus est, vel bibendum arcu suscipit quis. ÖÄÅ öäå Quisque congue. Title: <a href="http://www.imdb.com/title/tt0137523/">Fight Club</a> [imdb:date]';
		$expected_count   = 2;

		//When
		$obj            = new Tag_Processing_Help( $original_content );
		$obj->prefix    = $prefix;
		$actual_count   = $obj->tags_replace();
		$actual_content = $obj->get_replacement_content();

		//Then
		$this->assertSame( $expected_content, $actual_content );
		$this->assertSame( $expected_count, $actual_count );
	}

	/**
	 * No data for this title id. Alternative test.
	 *
	 * @covers Tag_Processing::__construct
	 * @covers Tag_Processing::tags_replace
	 * @covers Tag_Processing::get_replacement_content
	 *
	 * @return void
	 */
	public function test_no_id_match() {
		//Given
		$original_content = 'Pellentesque viverra luctus est, vel bibendum arcu suscipit quis. ÖÄÅ öäå Quisque congue[IMDb:id(tt0137523)].';
		$expected_content = 'Pellentesque viverra luctus est, vel bibendum arcu suscipit quis. ÖÄÅ öäå Quisque congue' . __( '[No imdb tags found]', 'imdb-markup-syntax' ) . '.';
		$expected_count   = 1;

		//When
		$obj            = new Tag_Processing_Help( $original_content );
		$actual_count   = $obj->tags_replace();
		$actual_content = $obj->get_replacement_content();

		//Then
		$this->assertSame( $expected_content, $actual_content );
		$this->assertSame( $expected_count, $actual_count );
	}

	/**
	 * No imdb tags just id. Alternative test.
	 *
	 * @covers Tag_Processing::__construct
	 * @covers Tag_Processing::tags_replace
	 *
	 * @return void
	 */
	public function test_no_imdb_match() {
		//Given
		$original_content = 'Pellentesque viverra luctus est, vel bibendum arcu suscipit quis. [IMDb:id(http://www.imdb.com/title/tt0137523/)] Quisque congue [IMDb:id()] Title: [title] [IMDb:id:tt0137523] [IMDb:id:(0137523)] [IMDb:id(tt)]';
		$expected         = false;

		//When
		$obj    = new Tag_Processing_Help( $original_content );
		$actual = $obj->tags_replace();

		//Then
		$this->assertSame( $expected, $actual );
	}

	/**
	 * Test when no id or imdb tags is empty. Alternative positive test
	 *
	 * @covers Tag_Processing::__construct
	 * @covers Tag_Processing::tags_replace
	 *
	 * @return void
	 */
	public function test_empty() {
		//Given
		$original_content = '';
		$expected         = false;

		//When
		$obj    = new Tag_Processing_Help( $original_content );
		$actual = $obj->tags_replace();

		//Then
		$this->assertSame( $expected, $actual );
	}

	/**
	 * PCRE Exception test. Alternative test.
	 *
	 * @covers Tag_Processing::__construct
	 * @covers Tag_Processing::tags_replace
	 * @covers Tag_Processing::get_replacement_content
	 *
	 * @return void
	 */
	public function test_preg_error() {
		//Given
		$original_content  = $this->positive_data;
		$custom_id_pattern = '/(?:\D+|<\d+>)*[!?]/';
		$expected_content  = 'Pellentesque viverra luctus est, vel bibendum arcu suscipit quis. ÖÄÅ öäå Quisque congue[PREG_BACKTRACK_LIMIT_ERROR(tt0137523)]. Title: [imdb:title]';
		$expected_count    = 1;

		//When
		$obj                    = new Tag_Processing_Help( $original_content );
		$obj->custom_id_pattern = $custom_id_pattern;
		$actual_count           = $obj->tags_replace();
		$actual_content         = $obj->get_replacement_content();

		//Then
		$this->assertSame( $expected_content, $actual_content );
		$this->assertSame( $expected_count, $actual_count );
	}

	/**
	 * PCRE Exception test with no imdb:id tags. Alternative test.
	 *
	 * @covers Tag_Processing::__construct
	 * @covers Tag_Processing::tags_replace
	 * @covers Tag_Processing::get_replacement_content
	 *
	 * @return void
	 */
	public function test_preg_error_no_match() {
		//Given
		$original_content  = 'Pellentesque viverra luctus est';
		$custom_id_pattern = '/(?:\D+|<\d+>)*[!?]/';
		$expected_content  = 'Pellentesque viverra luctus est';
		$expected_count    = 0;

		//When
		$obj                    = new Tag_Processing_Help( $original_content );
		$obj->custom_id_pattern = $custom_id_pattern;
		$actual_count           = $obj->tags_replace();
		$actual_content         = $obj->get_replacement_content();

		//Then
		$this->assertSame( $expected_content, $actual_content );
		$this->assertSame( $expected_count, $actual_count );
	}

	/**
	 * No data for this title id. Alternative test.
	 *
	 * @covers Tag_Processing::__construct
	 * @covers Tag_Processing::tags_replace
	 * @covers Tag_Processing::get_replacement_content
	 *
	 * @return void
	 */
	public function test_datasource_exception() {
		//Given
		$original_content = $this->positive_data;
		$timeout          = 200;
		$expected_content = '/Pellentesque viverra luctus est, vel bibendum arcu suscipit quis. ÖÄÅ öäå Quisque congue\[Operation timed out after \d+ milliseconds with \d+ bytes received curl_version: [\d\.]+\]. Title: \[imdb:title\]/';
		$expected_count   = 1;

		//When
		$obj            = new Tag_Processing_Help( $original_content );
		$obj->timeout   = $timeout;
		$actual_count   = $obj->tags_replace();
		$actual_content = $obj->get_replacement_content();

		//Then
		$this->assertRegExp( $expected_content, $actual_content );
		$this->assertSame( $expected_count, $actual_count );
	}

}