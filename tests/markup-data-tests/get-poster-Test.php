<?php
/**
 * Testclass to Markup_DataSuite for method getPoster in Markup_Data class
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
require_once 'wp-config.php';
require_once 'wp-admin/includes/image.php';

/**
 * Testclass to Markup_DataSuite for method getPoster in Markup_Data class
 *
 * @category  Testable
 * @package   Test
 * @author    Henrik Roos <henrik.roos@afternoon.se>
 * @copyright 2014 Henrik Roos
 * @license   http://opensource.org/licenses/gpl-3.0.html GPL-3.0
 * @link      https://github.com/HenrikRoos/imdb-markup-syntax imdb-markup-syntax
 */
class Get_Poster_Test extends PHPUnit_Framework_TestCase {

	/** @var string positive testdata */
	public $testdataPositive = 'tt0111161';

	/**
	 * Negative test: No image
	 *
	 * @covers Markup_Data::__construct
	 * @covers Markup_Data::get_poster
	 * @covers Markup_Data::get_value_value
	 * @covers Media_Library_Handler
	 *
	 * @return void
	 */
	public function test_no_image() {
		//Given
		$imdb             = new Movie_Datasource( $this->testdataPositive );
		$data             = $imdb->get_data();
		$data->image->url = 'https://news.google.se';
		$post_id          = 1;
		$expected         = __( 'No valid displayable image', 'imdb-markup-syntax' );

		//When
		try {
			$mdata = new Markup_Data( $data, $post_id );
			$mdata->get_poster();
		} //Then
		catch ( Runtime_Exception $exp ) {
			$this->assertSame( $expected, $exp->getMessage() );

			return;
		}

		$this->fail( 'An expected Imdb_Runtime_Exception has not been raised.' );
	}

	/**
	 * Negative test: post_id is not an integer
	 *
	 * @covers Markup_Data::__construct
	 * @covers Markup_Data::get_poster
	 * @covers Markup_Data::get_value_value
	 * @covers Media_Library_Handler
	 *
	 * @return void
	 */
	public function test_id_not_int() {
		//Given
		$imdb     = new Movie_Datasource( $this->testdataPositive );
		$data     = $imdb->get_data();
		$post_id  = 'xx';
		$expected = __( 'Post ID must be an integer', 'imdb-markup-syntax' );

		//When
		try {
			$mdata = new Markup_Data( $data, $post_id );
			$mdata->get_poster();
		} //Then
		catch ( Runtime_Exception $exp ) {
			$this->assertSame( $expected, $exp->getMessage() );

			return;
		}

		$this->fail( 'An expected Imdb_Runtime_Exception has not been raised.' );
	}

	/**
	 * Negative test: Incorrect URL
	 *
	 * @covers Media_Library_Handler
	 *
	 * @return void
	 */
	public function test_incorrect_url() {
		//Given
		$post_id    = 1;
		$remote_url = 'x';
		$filename   = 'y';
		$expected   = __( 'Remote URL must be an validate URL', 'imdb-markup-syntax' );

		//When
		try {
			new Media_Library_Handler( $post_id, $remote_url, $filename );
		} //Then
		catch ( Runtime_Exception $exp ) {
			$this->assertSame( $expected, $exp->getMessage() );

			return;
		}

		$this->fail( 'An expected Imdb_Runtime_Exception has not been raised.' );
	}

	/**
	 * Negative test: Incorrect URL
	 *
	 * @covers Media_Library_Handler
	 * @covers WP_Exception
	 *
	 * @return void
	 */
	public function test_failure_download() {
		//Given
		$post_id    = 1;
		$remote_url = 'http://www.austingunter.com/wp-content/uploads/2012/11/failure-poster.jpg';
		$filename   = 'y';
		$expected   = __( 'A valid URL was not provided.' );

		//When
		try {
			$lib             = new Media_Library_Handler( $post_id, $remote_url, $filename );
			$lib->remote_url = 'x';
			$lib->get_html( 'a', 'b' );
		} //Then
		catch ( WP_Exception $exp ) {
			$this->assertSame( $expected, $exp->getMessage() );

			return;
		}

		$this->fail( 'An expected Imdb_Runtime_Exception has not been raised.' );
	}

	/**
	 * Negative test: Incorrect Filename
	 *
	 * @covers Media_Library_Handler
	 *
	 * @return void
	 */
	public function test_failure_filename() {
		//Given
		$post_id    = 1;
		$remote_url = 'http://www.austingunter.com/wp-content/uploads/2012/11/failure-poster.jpg';
		$filename   = 'y';
		$expected   = __( 'Empty filename' );

		//When
		try {
			$lib           = new Media_Library_Handler( $post_id, $remote_url, $filename );
			$lib->fileanme = '';
			$lib->get_html( 'a', 'b' );
		} //Then
		catch ( Runtime_Exception $exp ) {
			$this->assertSame( $expected, $exp->getMessage() );

			return;
		}

		$this->fail( 'An expected Imdb_Runtime_Exception has not been raised.' );
	}

	/**
	 * Negative test: No data is set
	 *
	 * @covers Markup_Data::__construct
	 * @covers Markup_Data::get_poster
	 * @covers Markup_Data::get_value_value
	 *
	 * @return void
	 */
	public function test_not_set() {
		//Given
		$imdb = new Movie_Datasource( $this->testdataPositive );
		$data = $imdb->get_data();
		unset( $data->image->url );
		$expected = false;

		//When
		$mdata  = new Markup_Data( $data );
		$actual = $mdata->get_poster();

		//Then
		$this->assertSame( $expected, $actual );
	}

	/**
	 * Negative test: No data is set
	 *
	 * @covers Markup_Data::__construct
	 * @covers Markup_Data::get_poster_nolink
	 * @covers Markup_Data::get_value_value
	 *
	 * @return void
	 */
	public function test_not_set_nolink() {
		//Given
		$imdb = new Movie_Datasource( $this->testdataPositive );
		$data = $imdb->get_data();
		unset( $data->image->url );
		$expected = false;

		//When
		$mdata  = new Markup_Data( $data );
		$actual = $mdata->get_poster_nolink();

		//Then
		$this->assertSame( $expected, $actual );
	}

	/**
	 * Negative test: Incorrect URL
	 *
	 * @covers Media_Library_Handler
	 *
	 * @return void
	 */
	public function test_failure_metadata() {
		//Given
		$post_id    = 1;
		$remote_url = 'http://www.austingunter.com/wp-content/uploads/2012/11/failure-poster.jpg';
		$filename   = 'y';
		$expected   = __( 'Can\'t set thumbnail to the Post ID' );

		//When
		try {
			$lib = new Media_Library_Handler( $post_id, $remote_url, $filename );
			$lib->remote_url .= 'x';
			$lib->get_html( 'a', 'b' );
		} //Then
		catch ( Runtime_Exception $exp ) {
			$this->assertStringStartsWith( $expected, $exp->getMessage() );

			return;
		}

		$this->fail( 'An expected Imdb_Runtime_Exception has not been raised.' );
	}

	/**
	 * Negative test: Data is empty
	 *
	 * @covers Markup_Data::__construct
	 * @covers Markup_Data::get_poster
	 * @covers Markup_Data::get_value_value
	 *
	 * @return void
	 */
	public function test_empty() {
		//Given
		$imdb             = new Movie_Datasource( $this->testdataPositive );
		$data             = $imdb->get_data();
		$data->image->url = '';
		$expected         = false;

		//When
		$mdata  = new Markup_Data( $data );
		$actual = $mdata->get_poster();

		//Then
		$this->assertSame( $expected, $actual );
	}

}
