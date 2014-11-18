<?php
/**
 * Testclass to Markup_DataSuite for method getDate in Markup_Data class
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

require_once 'markup-data.php';
require_once 'movie-datasource.php';
require_once 'wp-config.php';

/**
 * Testclass to Markup_DataSuite for method getDate in Markup_Data class
 *
 * @category  Testable
 * @package   Test
 * @author    Henrik Roos <henrik.roos@afternoon.se>
 * @copyright 2013 Henrik Roos
 * @license   http://opensource.org/licenses/gpl-3.0.html GPL-3.0
 * @link      https://github.com/HenrikRoos/imdb-markup-syntax imdb-markup-syntax
 */
class Get_Date_Test extends PHPUnit_Framework_TestCase {

	/** @var string positive testdata */
	public $testdataPositive;

	/**
	 * Positive test: Get data sucessful then release date is set
	 *
	 * @covers Markup_Data::__construct
	 * @covers Markup_Data::get_date
	 * @covers Markup_Data::get_value_value
	 *
	 * @return void
	 */
	public function test_release_date_positive() {
		//Given
		$locale   = 'en_US';
		$expected = 'Fri Jul 18 2008';

		//When
		$imdb   = new Movie_Datasource( $this->testdataPositive, $locale );
		$data   = $imdb->get_data();
		$mdata  = new Markup_Data( $data, null, $locale );
		$actual = $mdata->get_date();

		//Then
		$this->assertSame( $expected, $actual );
	}

	/**
	 * Positive test: Get data sucessful then release date is set in Swedish
	 *
	 * @covers Markup_Data::__construct
	 * @covers Markup_Data::get_date
	 * @covers Markup_Data::get_value_value
	 *
	 * @return void
	 */
	public function test_release_date_positive_swedish() {
		//Given
		$locale   = 'sv_SE';
		$expected = 'Fre 25 Jul 2008';

		//When
		$imdb   = new Movie_Datasource( $this->testdataPositive, $locale );
		$data   = $imdb->get_data();
		$mdata  = new Markup_Data( $data, null, $locale );
		$actual = $mdata->get_date();

		//Then
		$this->assertSame( $expected, $actual );
	}

	/**
	 * Positive test: Get data sucessful then release date is set in French
	 *
	 * @covers Markup_Data::__construct
	 * @covers Markup_Data::get_date
	 * @covers Markup_Data::get_value_value
	 *
	 * @return void
	 */
	public function test_release_date_positive_french() {
		//Given
		$locale   = 'fr_FR';
		$expected = 'Mer 13 aoû 2008';

		//When
		$imdb   = new Movie_Datasource( $this->testdataPositive, $locale );
		$data   = $imdb->get_data();
		$mdata  = new Markup_Data( $data, null, $locale );
		$actual = $mdata->get_date();

		//Then
		$this->assertSame( $expected, $actual );
	}

	/**
	 * Alternative positive test: No release_date is not set but year is set
	 *
	 * @covers Markup_Data::__construct
	 * @covers Markup_Data::get_date
	 * @covers Markup_Data::get_value_value
	 *
	 * @return void
	 */
	public function test_year_alternative() {
		//Given
		$imdb                       = new Movie_Datasource( $this->testdataPositive );
		$data                       = $imdb->get_data();
		$data->release_date->normal = '';
		$expected                   = '2008';

		//When
		$mdata  = new Markup_Data( $data );
		$actual = $mdata->get_date();

		//Then
		$this->assertSame( $expected, $actual );
	}

	/**
	 * Negative test: Data is not set (release date and year)
	 *
	 * @covers Markup_Data::__construct
	 * @covers Markup_Data::get_date
	 * @covers Markup_Data::get_value_value
	 *
	 * @return void
	 */
	public function test_no_set() {
		//Given
		$imdb = new Movie_Datasource( $this->testdataPositive );
		$data = $imdb->get_data();
		unset( $data->release_date );
		unset( $data->year );
		$expected = false;

		//When
		$mdata  = new Markup_Data( $data );
		$actual = $mdata->get_date();

		//Then
		$this->assertSame( $expected, $actual );
	}

	/**
	 * Negative test: Data is empty
	 *
	 * @covers Markup_Data::__construct
	 * @covers Markup_Data::get_date
	 * @covers Markup_Data::get_value_value
	 *
	 * @return void
	 */
	public function test_empty() {
		//Given
		$imdb                       = new Movie_Datasource( $this->testdataPositive );
		$data                       = $imdb->get_data();
		$data->release_date->normal = '';
		$data->year                 = '';
		$expected                   = false;

		//When
		$mdata  = new Markup_Data( $data );
		$actual = $mdata->get_date();

		//Then
		$this->assertSame( $expected, $actual );
	}

	/**
	 * Set up local testdata
	 *
	 * @return void
	 */
	protected function setup() {
		$this->testdataPositive = 'tt0468569';
		setlocale( LC_ALL, '' );
	}

	/**
	 * Clean up after testing.
	 *
	 * @return void
	 */
	protected function teardown() {
		parent::tearDown();
		setlocale( LC_ALL, '' );
	}

}
