<?php
/**
 * Sub testclass to movie-datasource-tests for general tests in Movie_Datasource
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

require_once 'movie-datasource.php';
require_once 'wp-config.php';

/**
 * Sub testclass to movie-datasource-tests for general tests in Movie_Datasource
 *
 * @category  Testable
 * @package   Test
 * @author    Henrik Roos <henrik.roos@afternoon.se>
 * @copyright 2013 Henrik Roos
 * @license   http://opensource.org/licenses/gpl-3.0.html GPL-3.0
 * @link      https://github.com/HenrikRoos/imdb-markup-syntax imdb-markup-syntax
 */
class General_Test extends PHPUnit_Framework_TestCase {

	public $original_content = [
		'movie'     => 'tt0137523',
		'tvserie'   => 'tt0402711',
		'videogame' => 'tt1843198',
		'nodata'    => 'tt0000000',
		'incorrect' => 'a b c',
	];

	/**
	 * Negativ test, incorrect tconst checked in construct
	 *
	 * @covers Movie_Datasource::__construct
	 * @covers Runtime_Exception
	 *
	 * @return void
	 */
	public function test_incorrect_id() {
		//Given
		$tconst   = $this->original_content['incorrect'];
		$expected = sprintf( __( 'Incorrect tconst %s', 'imdb-markup-syntax' ), $tconst );

		//When
		try {
			new Movie_Datasource( $tconst );
		} //Then
		catch ( Runtime_Exception $exp ) {
			$this->assertSame( $expected, $exp->getMessage() );

			return;
		}

		$this->fail( 'An expected Imdb_Runtime_Exception has not been raised.' );
	}

	/**
	 * Negativ test, incorrect json syntax
	 *
	 * @expectedException        Json_Exception
	 * @expectedExceptionMessage JSON_ERROR_SYNTAX
	 * @expectedExceptionCode    4
	 *
	 * @covers                   Movie_Datasource::__construct
	 * @covers                   Movie_Datasource::to_data_class
	 * @covers                   Json_Exception
	 *
	 * @return void
	 */
	public function test_to_data_class_json_exception() {
		//Given
		$tconst = $this->original_content['movie'];

		//When
		$imdb = new Movie_Datasource( $tconst );
		$imdb->fetch_response();
		$imdb->response .= 'a';
		$imdb->to_data_class();
	}

	/**
	 * Negative test, no query data
	 *
	 * @covers Movie_Datasource::__construct
	 * @covers Movie_Datasource::set_request
	 * @covers Runtime_Exception
	 *
	 * @return void
	 */
	public function test_set_request() {
		//Given
		$request  = '';
		$expected = __( 'Empty query', 'imdb-markup-syntax' );

		//When
		try {
			$imdb = new Movie_Datasource();
			$imdb->set_request( $request );
		} //Then
		catch ( Runtime_Exception $exp ) {
			$this->assertSame( $expected, $exp->getMessage() );

			return;
		}

		$this->fail( 'An expected Imdb_Runtime_Exception has not been raised.' );
	}

}
