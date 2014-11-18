<?php
/**
 * Testclass to Markup_DataSuite for method getCertificate in Markup_Data class
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

/**
 * Testclass to Markup_DataSuite for method getCertificate in Markup_Data class
 *
 * @category  Testable
 * @package   Test
 * @author    Henrik Roos <henrik.roos@afternoon.se>
 * @copyright 2013 Henrik Roos
 * @license   http://opensource.org/licenses/gpl-3.0.html GPL-3.0
 * @link      https://github.com/HenrikRoos/imdb-markup-syntax imdb-markup-syntax
 */
class Get_Certificate_Test extends PHPUnit_Framework_TestCase {

	/** @var string positive testdata */
	public $testdataPositive;

	/**
	 * Positive test: Get data sucessful
	 *
	 * @covers Markup_Data::__construct
	 * @covers Markup_Data::get_certificate
	 * @covers Markup_Data::get_value
	 *
	 * @return void
	 */
	public function test_positive() {
		//Given
		$imdb     = new Movie_Datasource( $this->testdataPositive );
		$data     = $imdb->get_data();
		$expected = 'TV-MA';

		//When
		$mdata  = new Markup_Data( $data );
		$actual = $mdata->get_certificate();

		//Then
		$this->assertSame( $expected, $actual );
	}

	/**
	 * Positive test: Get data sucessful in swedish locale
	 *
	 * @covers Markup_Data::__construct
	 * @covers Markup_Data::get_certificate
	 * @covers Markup_Data::get_value
	 *
	 * @return void
	 */
	public function test_positive_swedish() {
		//Given
		$locale   = 'se_SE';
		$expected = '15';

		//When
		$imdb   = new Movie_Datasource( $this->testdataPositive, $locale );
		$data   = $imdb->get_data();
		$mdata  = new Markup_Data( $data, null, $locale );
		$actual = $mdata->get_certificate();

		//Then
		$this->assertSame( $expected, $actual );
	}

	/**
	 * Positive test: Get data sucessful for a kids movie
	 *
	 * @covers Markup_Data::__construct
	 * @covers Markup_Data::get_certificate
	 * @covers Markup_Data::get_value
	 *
	 * @return void
	 */
	public function test_positive_kids() {
		//Given
		$imdb     = new Movie_Datasource( 'tt0397892' );
		$data     = $imdb->get_data();
		$expected = 'PG';

		//When
		$mdata  = new Markup_Data( $data );
		$actual = $mdata->get_certificate();

		//Then
		$this->assertSame( $expected, $actual );
	}

	/**
	 * Negative test: No data is set
	 *
	 * @covers Markup_Data::__construct
	 * @covers Markup_Data::get_certificate
	 * @covers Markup_Data::get_value
	 *
	 * @return void
	 */
	public function test_not_set() {
		//Given
		$imdb = new Movie_Datasource( $this->testdataPositive );
		$data = $imdb->get_data();
		unset( $data->certificate );
		$expected = false;

		//When
		$mdata  = new Markup_Data( $data );
		$actual = $mdata->get_certificate();

		//Then
		$this->assertSame( $expected, $actual );
	}

	/**
	 * Negative test: Data is empty
	 *
	 * @covers Markup_Data::__construct
	 * @covers Markup_Data::get_certificate
	 * @covers Markup_Data::get_value
	 *
	 * @return void
	 */
	public function test_empty() {
		//Given
		$imdb                           = new Movie_Datasource( $this->testdataPositive );
		$data                           = $imdb->get_data();
		$data->certificate->certificate = '';
		$expected                       = false;

		//When
		$mdata  = new Markup_Data( $data );
		$actual = $mdata->get_certificate();

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
