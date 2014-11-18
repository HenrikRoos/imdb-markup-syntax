<?php
/**
 * Sub testclass to movie-datasource-tests for method getData in Movie_Datasource class
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

/**
 * Sub testclass to movie-datasource-tests for method getData in Movie_Datasource class
 *
 * @category  Testable
 * @package   Test
 * @author    Henrik Roos <henrik.roos@afternoon.se>
 * @copyright 2013 Henrik Roos
 * @license   http://opensource.org/licenses/gpl-3.0.html GPL-3.0
 * @link      https://github.com/HenrikRoos/imdb-markup-syntax imdb-markup-syntax
 */
class Get_Data_Test extends PHPUnit_Framework_TestCase {

	public $original_content = [
		'movie'     => 'tt0137523',
		'tvserie'   => 'tt0402711',
		'videogame' => 'tt1843198',
		'nodata'    => 'tt0000000',
		'incorrect' => 'a b c',
	];

	/**
	 * Main use case get a movie data, no error
	 *
	 * @covers Movie_Datasource::__construct
	 * @covers Movie_Datasource::set_request
	 * @covers Movie_Datasource::get_data
	 * @covers Movie_Datasource::to_data_class
	 * @covers Movie_Datasource::fetch_response
	 *
	 * @return void
	 */
	public function test_movie() {
		//Given
		$tconst   = $this->original_content['movie'];
		$expected = 'Fight Club';

		//When
		$imdb   = new Movie_Datasource( $tconst );
		$data   = $imdb->get_data();
		$actual = $data->title;

		//Then
		$this->assertSame( $expected, $actual );
	}

	/**
	 * Main use case get a tv serie data, no error
	 *
	 * @covers Movie_Datasource::__construct
	 * @covers Movie_Datasource::set_request
	 * @covers Movie_Datasource::get_data
	 * @covers Movie_Datasource::to_data_class
	 * @covers Movie_Datasource::fetch_response
	 *
	 * @return void
	 */
	public function test_tv_serie() {
		//Given
		$tconst   = $this->original_content['tvserie'];
		$expected = 'Boston Legal';

		//When
		$imdb   = new Movie_Datasource( $tconst );
		$data   = $imdb->get_data();
		$actual = $data->title;

		//Then
		$this->assertSame( $expected, $actual );
	}

	/**
	 * Main use case get a video game data, no error
	 *
	 * @covers Movie_Datasource::__construct
	 * @covers Movie_Datasource::set_request
	 * @covers Movie_Datasource::get_data
	 * @covers Movie_Datasource::to_data_class
	 * @covers Movie_Datasource::fetch_response
	 *
	 * @return void
	 */
	public function test_video_game() {
		//Given
		$tconst   = $this->original_content['videogame'];
		$expected = 'Lego Pirates of the Caribbean: The Video Game';

		//When
		$imdb   = new Movie_Datasource( $tconst );
		$data   = $imdb->get_data();
		$actual = $data->title;

		//Then
		$this->assertSame( $expected, $actual );
	}

	/**
	 * Negativ test, not valid locale
	 *
	 * @expectedException        Runtime_Exception
	 * @expectedExceptionMessage Your locale argument is not valid (RFC 4646)
	 * @expectedExceptionCode    400
	 *
	 * @covers                   Movie_Datasource::__construct
	 * @covers                   Movie_Datasource::set_request
	 * @covers                   Movie_Datasource::get_data
	 * @covers                   Movie_Datasource::to_data_class
	 * @covers                   Movie_Datasource::fetch_response
	 * @covers                   Runtime_Exception
	 *
	 * @return void
	 */
	public function test_not_valid_locale() {
		//Given
		$tconst = $this->original_content['movie'];
		$locale = 'zh_cn';

		//When
		$imdb = new Movie_Datasource( $tconst, $locale );
		$imdb->get_data();
	}

	/**
	 * Negativ test, No data for this title tconst. HTTP 404
	 *
	 * @expectedException        Runtime_Exception
	 * @expectedExceptionMessage No data for this title id
	 * @expectedExceptionCode    404
	 *
	 * @covers                   Movie_Datasource::__construct
	 * @covers                   Movie_Datasource::set_request
	 * @covers                   Movie_Datasource::get_data
	 * @covers                   Movie_Datasource::to_data_class
	 * @covers                   Movie_Datasource::fetch_response
	 * @covers                   Runtime_Exception
	 *
	 * @return void
	 */
	public function test_no_data() {
		//Given
		$tconst = $this->original_content['nodata'];

		//When
		$imdb = new Movie_Datasource( $tconst );
		$imdb->get_data();
	}

	/**
	 * Positive alternative test. Different release date in different countries
	 *
	 * @covers Movie_Datasource::__construct
	 * @covers Movie_Datasource::set_request
	 * @covers Movie_Datasource::get_data
	 * @covers Movie_Datasource::to_data_class
	 * @covers Movie_Datasource::fetch_response
	 * @covers Runtime_Exception
	 *
	 * @return void
	 */
	public function test_alternative_locale() {
		//Given
		$tconst      = $this->original_content['tvserie'];
		$locale      = 'sv_SE';
		$expected    = '2004-10-03';
		$expected_se = '2005-03-21';

		//When
		$imdb    = new Movie_Datasource( $tconst );
		$imdb_se = new Movie_Datasource( $tconst, $locale );

		$data    = $imdb->get_data();
		$data_se = $imdb_se->get_data();

		$actual    = $data->release_date->normal;
		$actual_se = $data_se->release_date->normal;

		//Then
		$this->assertSame( $expected, $actual );
		$this->assertSame( $expected_se, $actual_se );
	}

	/**
	 * Set up before testing
	 *
	 * @return void
	 */
	protected function setup() {
		parent::setUp();
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
