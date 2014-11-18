<?php
/**
 * Testclass to Markup_DataSuite for method getPlot in Markup_Data class
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
 * Testclass to Markup_DataSuite for method getPlot in Markup_Data class
 *
 * @category  Testable
 * @package   Test
 * @author    Henrik Roos <henrik.roos@afternoon.se>
 * @copyright 2013 Henrik Roos
 * @license   http://opensource.org/licenses/gpl-3.0.html GPL-3.0
 * @link      https://github.com/HenrikRoos/imdb-markup-syntax imdb-markup-syntax
 */
class Get_Plot_Test extends PHPUnit_Framework_TestCase {

	/** @var string positive testdata */
	public $testdataPositive = 'tt0137523';

	/**
	 * Positive test: Get data sucessful
	 *
	 * @covers Markup_Data::__construct
	 * @covers Markup_Data::get_plot
	 * @covers Markup_Data::get_value
	 *
	 * @return void
	 */
	public function test_positive() {
		//Given
		$imdb     = new Movie_Datasource( $this->testdataPositive );
		$data     = $imdb->get_data();
		$expected = 'An insomniac office worker looking for a way to change his life crosses paths with a devil-may-care soap maker and they form an underground fight club that evolves into something much, much more...';

		//When
		$mdata  = new Markup_Data( $data );
		$actual = $mdata->get_plot();

		//Then
		$this->assertSame( $expected, $actual );
	}

	/**
	 * Negative test: No data is set
	 *
	 * @covers Markup_Data::__construct
	 * @covers Markup_Data::get_plot
	 * @covers Markup_Data::get_value
	 *
	 * @return void
	 */
	public function test_not_set() {
		//Given
		$imdb = new Movie_Datasource( $this->testdataPositive );
		$data = $imdb->get_data();
		unset( $data->plot );
		$expected = false;

		//When
		$mdata  = new Markup_Data( $data );
		$actual = $mdata->get_plot();

		//Then
		$this->assertSame( $expected, $actual );
	}

	/**
	 * Negative test: Data is empty
	 *
	 * @covers Markup_Data::__construct
	 * @covers Markup_Data::get_plot
	 * @covers Markup_Data::get_value
	 *
	 * @return void
	 */
	public function test_empty() {
		//Given
		$imdb                = new Movie_Datasource( $this->testdataPositive );
		$data                = $imdb->get_data();
		$data->plot->outline = '';
		$expected            = false;

		//When
		$mdata  = new Markup_Data( $data );
		$actual = $mdata->get_plot();

		//Then
		$this->assertSame( $expected, $actual );
	}
}
