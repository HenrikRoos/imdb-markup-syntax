<?php
/**
 * Testclass to Markup_DataSuite for method getDirectors in Markup_Data class
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
 * Testclass to Markup_DataSuite for method getDirectors in Markup_Data class
 *
 * @category  Testable
 * @package   Test
 * @author    Henrik Roos <henrik.roos@afternoon.se>
 * @copyright 2014 Henrik Roos
 * @license   http://opensource.org/licenses/gpl-3.0.html GPL-3.0
 * @link      https://github.com/HenrikRoos/imdb-markup-syntax imdb-markup-syntax
 */
class Get_Directors_Test extends PHPUnit_Framework_TestCase {

	/** @var string positive testdata */
	public $testdataPositive;

	/**
	 * Positive test: Get data sucessful
	 *
	 * @covers Markup_Data::__construct
	 * @covers Markup_Data::get_directors
	 * @covers Markup_Data::to_summary_string
	 * @covers Markup_Data::to_persons_list
	 * @covers Markup_Data::to_person_string
	 * @covers Markup_Data::to_name_string
	 * @covers Markup_Data::is_not_empty
	 *
	 * @return void
	 */
	public function test_positive() {
		//Given
		$imdb     = new Movie_Datasource( $this->testdataPositive );
		$data     = $imdb->get_data();
		$expected = '<a href="http://www.imdb.com/name/nm0000399">David Fincher</a>';

		//When
		$mdata  = new Markup_Data( $data );
		$actual = $mdata->get_directors();

		//Then
		$this->assertSame( $expected, $actual );
	}

	/**
	 * Positive test: Get data sucessful
	 *
	 * @covers Markup_Data::__construct
	 * @covers Markup_Data::get_directors_nolink
	 * @covers Markup_Data::to_summary_string
	 * @covers Markup_Data::to_persons_list
	 * @covers Markup_Data::to_person_string
	 * @covers Markup_Data::to_name_string
	 * @covers Markup_Data::is_not_empty
	 *
	 * @return void
	 */
	public function test_positive_nolink() {
		//Given
		$imdb     = new Movie_Datasource( $this->testdataPositive );
		$data     = $imdb->get_data();
		$expected = 'David Fincher';

		//When
		$mdata  = new Markup_Data( $data );
		$actual = $mdata->get_directors_nolink();

		//Then
		$this->assertSame( $expected, $actual );
	}

	/**
	 * Negative test: No data is set
	 *
	 * @covers Markup_Data::__construct
	 * @covers Markup_Data::get_directors
	 * @covers Markup_Data::to_summary_string
	 * @covers Markup_Data::to_persons_list
	 * @covers Markup_Data::to_person_string
	 * @covers Markup_Data::to_name_string
	 * @covers Markup_Data::is_not_empty
	 *
	 * @return void
	 */
	public function test_not_set() {
		//Given
		$imdb = new Movie_Datasource( $this->testdataPositive );
		$data = $imdb->get_data();
		unset( $data->directors_summary );
		$expected = false;

		//When
		$mdata  = new Markup_Data( $data );
		$actual = $mdata->get_directors();

		//Then
		$this->assertSame( $expected, $actual );
	}

	/**
	 * Negative test: Data is empty
	 *
	 * @covers Markup_Data::__construct
	 * @covers Markup_Data::get_tconst
	 * @covers Markup_Data::to_summary_string
	 * @covers Markup_Data::to_persons_list
	 * @covers Markup_Data::to_person_string
	 * @covers Markup_Data::to_name_string
	 * @covers Markup_Data::is_not_empty
	 *
	 * @return void
	 */
	public function test_empty() {
		//Given
		$imdb                    = new Movie_Datasource( $this->testdataPositive );
		$data                    = $imdb->get_data();
		$data->directors_summary = '';
		$expected                = false;

		//When
		$mdata  = new Markup_Data( $data );
		$actual = $mdata->get_directors();

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
