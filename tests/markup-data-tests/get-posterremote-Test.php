<?php
/**
 * Testclass to Markup_DataSuite for method getPosterremote in Markup_Data class
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

require_once 'markup-data.php';
require_once 'movie-datasource.php';

/**
 * Testclass to Markup_DataSuite for method getPosterremote in Markup_Data class
 *
 * @category  Testable
 * @package   Test
 * @author    Henrik Roos <henrik.roos@afternoon.se>
 * @copyright 2014 Henrik Roos
 * @license   http://opensource.org/licenses/gpl-3.0.html GPL-3.0
 * @link      https://github.com/HenrikRoos/imdb-markup-syntax imdb-markup-syntax
 */
class Get_Posterremote_Test extends PHPUnit_Framework_TestCase {

	/** @var string positive testdata */
	public $testdataPositive;

	/**
	 * Positive test: Get data sucessful
	 *
	 * @covers Markup_Data::__construct
	 * @covers Markup_Data::get_posterremote
	 * @covers Markup_Data::get_value_value
	 *
	 * @return void
	 */
	public function test_positive() {
		//Given
		$imdb     = new Movie_Datasource( $this->testdataPositive );
		$data     = $imdb->get_data();
		$expected = '<a href="http://www.imdb.com/title/tt0137523/"><img src="http://ia.media-imdb.com/images/M/MV5BMjIwNTYzMzE1M15BMl5BanBnXkFtZTcwOTE5Mzg3OA@@._V1_.jpg" alt="Fight Club" width="200" class="alignnone"/></a>';

		//When
		$mdata  = new Markup_Data( $data );
		$actual = $mdata->get_posterremote();

		//Then
		$this->assertSame( $expected, $actual );
	}

	/**
	 * Positive test: Get data sucessful
	 *
	 * @covers Markup_Data::__construct
	 * @covers Markup_Data::get_posterremote_nolink
	 * @covers Markup_Data::get_value_value
	 *
	 * @return void
	 */
	public function test_positive_nolink() {
		//Given
		$imdb     = new Movie_Datasource( $this->testdataPositive );
		$data     = $imdb->get_data();
		$expected = '<img src="http://ia.media-imdb.com/images/M/MV5BMjIwNTYzMzE1M15BMl5BanBnXkFtZTcwOTE5Mzg3OA@@._V1_.jpg" alt="Fight Club" width="200" class="alignnone"/>';

		//When
		$mdata  = new Markup_Data( $data );
		$actual = $mdata->get_posterremote_nolink();

		//Then
		$this->assertSame( $expected, $actual );
	}

	/**
	 * Negative test: No data is set
	 *
	 * @covers Markup_Data::__construct
	 * @covers Markup_Data::get_posterremote
	 * @covers Markup_Data::get_value_value
	 *
	 * @return void
	 */
	public function test_not_set() {
		//Given
		$imdb = new Movie_Datasource( $this->testdataPositive );
		$data = $imdb->get_data();
		unset( $data->image );
		$expected = false;

		//When
		$mdata  = new Markup_Data( $data );
		$actual = $mdata->get_posterremote();

		//Then
		$this->assertSame( $expected, $actual );
	}

	/**
	 * Negative test: No data is set
	 *
	 * @covers Markup_Data::__construct
	 * @covers Markup_Data::get_posterremote_nolink
	 * @covers Markup_Data::get_value_value
	 *
	 * @return void
	 */
	public function test_not_set_nolink() {
		//Given
		$imdb = new Movie_Datasource( $this->testdataPositive );
		$data = $imdb->get_data();
		unset( $data->image );
		$expected = false;

		//When
		$mdata  = new Markup_Data( $data );
		$actual = $mdata->get_posterremote_nolink();

		//Then
		$this->assertSame( $expected, $actual );
	}

	/**
	 * Negative test: Data is empty
	 *
	 * @covers Markup_Data::__construct
	 * @covers Markup_Data::get_posterremote
	 * @covers Markup_Data::get_value_value
	 *
	 * @return void
	 */
	public function test_empty() {
		//Given
		$imdb        = new Movie_Datasource( $this->testdataPositive );
		$data        = $imdb->get_data();
		$data->image = '';
		$expected    = false;

		//When
		$mdata  = new Markup_Data( $data );
		$actual = $mdata->get_posterremote();

		//Then
		$this->assertSame( $expected, $actual );
	}

	/**
	 * Set up local testdata
	 *
	 * @return void
	 */
	protected function setup() {
		$this->testdataPositive = 'tt0137523';
	}

}
