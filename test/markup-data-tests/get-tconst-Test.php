<?php
/**
 * Testclass to Markup_DataSuite for for method getTconst in Markup_Data class
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
 * Testclass to Markup_DataSuite for method getTconst in Markup_Data class
 *
 * @category  Testable
 * @package   Test
 * @author    Henrik Roos <henrik.roos@afternoon.se>
 * @copyright 2013 Henrik Roos
 * @license   http://opensource.org/licenses/gpl-3.0.html GPL-3.0
 * @link      https://github.com/HenrikRoos/imdb-markup-syntax imdb-markup-syntax
 */
class Get_Tconst_Test extends PHPUnit_Framework_TestCase {

	/** @var string positive testdata */
	public $testdataPositive;

	/**
	 * Positive test: Get id sucessful
	 *
	 * @covers Markup_Data::__construct
	 * @covers Markup_Data::get_tconst
	 * @covers Markup_Data::get_value
	 *
	 * @return void
	 */
	public function test_positive() {
		//Given
		$imdb     = new Movie_Datasource( $this->testdataPositive );
		$data     = $imdb->get_data();
		$expected = 'tt0137523';

		//When
		$mdata  = new Markup_Data( $data );
		$actual = $mdata->get_tconst();

		//Then
		$this->assertSame( $expected, $actual );
	}

	/**
	 * Negative test: No tconst is set
	 *
	 * @covers Markup_Data::__construct
	 * @covers Markup_Data::get_tconst
	 * @covers Markup_Data::get_value
	 *
	 * @return void
	 */
	public function test_not_set() {
		//Given
		$imdb = new Movie_Datasource( $this->testdataPositive );
		$data = $imdb->get_data();
		unset( $data->tconst );
		$expected = false;

		//When
		$mdata  = new Markup_Data( $data );
		$actual = $mdata->get_tconst();

		//Then
		$this->assertSame( $expected, $actual );
	}

	/**
	 * Negative test: Tconst is empty
	 *
	 * @covers Markup_Data::__construct
	 * @covers Markup_Data::get_tconst
	 * @covers Markup_Data::get_value
	 *
	 * @return void
	 */
	public function test_empty() {
		//Given
		$imdb         = new Movie_Datasource( $this->testdataPositive );
		$data         = $imdb->get_data();
		$data->tconst = '';
		$expected     = false;

		//When
		$mdata  = new Markup_Data( $data );
		$actual = $mdata->get_tconst();

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
