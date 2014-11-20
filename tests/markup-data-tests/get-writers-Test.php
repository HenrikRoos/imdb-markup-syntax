<?php
/**
 * Testclass to Markup_DataSuite for method getWriters in Markup_Data class
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
 * Testclass to Markup_DataSuite for method getWriters in Markup_Data class
 *
 * @category  Testable
 * @package   Test
 * @author    Henrik Roos <henrik.roos@afternoon.se>
 * @copyright 2014 Henrik Roos
 * @license   http://opensource.org/licenses/gpl-3.0.html GPL-3.0
 * @link      https://github.com/HenrikRoos/imdb-markup-syntax imdb-markup-syntax
 */
class Get_Writers_Test extends PHPUnit_Framework_TestCase {

	/** @var string positive testdata */
	public $testdataPositive;

	/**
	 * Positive test where movie has one writer and no attribute like (nocel)
	 *
	 * @covers Markup_Data::__construct
	 * @covers Markup_Data::get_writers
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
		$data     = new Markup_Data( $imdb->get_data() );
		$expected = '<a href="http://www.imdb.com/name/nm3503431">Bryan Litt</a>';

		//When
		$actual = $data->get_writers();

		//Then
		$this->assertSame( $expected, $actual );
	}

	/**
	 * Positive test where movie has one writer and no attribute like (nocel)
	 *
	 * @covers Markup_Data::__construct
	 * @covers Markup_Data::get_writers_nolink
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
		$data     = new Markup_Data( $imdb->get_data() );
		$expected = 'Bryan Litt';

		//When
		$actual = $data->get_writers_nolink();

		//Then
		$this->assertSame( $expected, $actual );
	}

	/**
	 * Positive test where movie has two writers
	 *
	 * @covers Markup_Data::__construct
	 * @covers Markup_Data::get_writers
	 * @covers Markup_Data::to_summary_string
	 * @covers Markup_Data::to_persons_list
	 * @covers Markup_Data::to_person_string
	 * @covers Markup_Data::to_name_string
	 * @covers Markup_Data::is_not_empty
	 *
	 * @return void
	 */
	public function test_tow_positive() {
		//Given
		$imdb     = new Movie_Datasource( 'tt0137523' );
		$data     = new Markup_Data( $imdb->get_data() );
		$expected = '<a href="http://www.imdb.com/name/nm0657333">Chuck Palahniuk</a> (novel), <a href="http://www.imdb.com/name/nm0880243">Jim Uhls</a> (screenplay)';

		//When
		$actual = $data->get_writers();

		//Then
		$this->assertSame( $expected, $actual );
	}

	/**
	 * Alternative test where movie has no writers
	 *
	 * @covers Markup_Data::__construct
	 * @covers Markup_Data::get_writers
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
		$imdb     = new Movie_Datasource( 'tt1129398' );
		$data     = new Markup_Data( $imdb->get_data() );
		$expected = false;

		//When
		$actual = $data->get_writers();

		//Then
		$this->assertSame( $expected, $actual );
	}

	/**
	 * Negative test: getWriters is empty
	 *
	 * @covers Markup_Data::__construct
	 * @covers Markup_Data::get_writers
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
		$imdb                  = new Movie_Datasource( $this->testdataPositive );
		$data                  = $imdb->get_data();
		$data->writers_summary = [ ];
		$expected              = false;

		//When
		$mdata  = new Markup_Data( $data );
		$actual = $mdata->get_writers();

		//Then
		$this->assertSame( $expected, $actual );
	}

	/**
	 * Set up local testdata
	 *
	 * @return void
	 */
	protected function setup() {
		$this->testdataPositive = 'tt1564043';
	}

}
