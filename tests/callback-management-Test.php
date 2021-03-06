<?php
/**
 * Testclass to Callback_Management class
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

require_once 'callback-management.php';
require_once 'wp-config.php';
require_once 'wp-admin/includes/image.php';

/**
 * Testclass to Callback_Management class
 *
 * @category  Testable
 * @package   Test
 * @author    Henrik Roos <henrik.roos@afternoon.se>
 * @copyright 2014 Henrik Roos
 * @license   http://opensource.org/licenses/gpl-3.0.html GPL-3.0
 * @link      https://github.com/HenrikRoos/imdb-markup-syntax imdb-markup-syntax
 */
class Callback_Management_Test extends PHPUnit_Framework_TestCase {

	/**
	 * Positive test and alternative test; attatch image to not existens post
	 *
	 * @covers Callback_Management::filter_imdb_tags
	 * @covers Callback_Management::tags_replace
	 * @covers Callback_Management::__construct
	 * @covers Markup_Data::get_poster
	 * @covers Markup_Data::get_poster_nolink
	 * @covers Media_Library_Handler
	 *
	 * @return void
	 */
	public function test_filter_imdb_tags() {
		//Given
		$post           = [
			'post_title'    => 'My post',
			'post_content'  => 'This is my post.',
			'post_status'   => 'publish',
			'post_author'   => 1,
			'post_category' => [ 8, 39 ],
		];
		$post_id        = wp_insert_post( $post );
		$mgmt           = new Callback_Management();
		$postarr        = [ 'ID' => $post_id ];
		$data           = [
			'post_title'   => 'ÖÄÅ öäå congue [IMDb:id(tt0137523)][imdb:title]',
			'post_content' => 'Pellentesque viverra luctus est, vel bibendum arcu suscipit quis. ÖÄÅ öäå Quisque congue. [IMDb:id(tt0137523)]Title: [imdb:title] Poster: [imdb:poster][IMDb-a:id(tt1206543)]Title: [imdb-a:title] Poster: [imdb-a:poster_nolink]',
		];
		$expected       = [
			'post_title'   => 'ÖÄÅ öäå congue <a href="http://www.imdb.com/title/tt0137523/">Fight Club</a>',
			'post_content' => '/Pellentesque viverra luctus est, vel bibendum arcu suscipit quis. ÖÄÅ öäå Quisque congue. Title: <a href="http:\/\/www\.imdb\.com\/title\/tt0137523\/">Fight Club<\/a> Poster: <a href="http:\/\/www\.imdb\.com\/title\/tt0137523\/" title="Fight Club"><img width="\d+" height="\d+" src="http:\/\/.+\/uploads\/201\d\/\d\d\/tt0137523\d*-\d+x\d+.jpg" class="alignnone size-medium wp-post-image" alt="Fight Club".+\/><\/a>Title: <a href="http:\/\/www\.imdb\.com\/title\/tt1206543\/">Out of the Furnace<\/a> Poster: <img width="\d+" height="\d+" src="http:\/\/.+\/uploads\/201\d\/\d\d\/tt1206543\d*-\d+x\d+.jpg" class="alignnone size-medium wp-post-image" alt="Out of the Furnace".+\/>/',
		];
		$expected_after = [
			'post_title'   => 'ÖÄÅ öäå congue <a href="http://www.imdb.com/title/tt0137523/">Fight Club</a>',
			'post_content' => '/Pellentesque viverra luctus est, vel bibendum arcu suscipit quis. ÖÄÅ öäå Quisque congue. Title: <a href="http:\/\/www\.imdb\.com\/title\/tt0137523\/">Fight Club<\/a> Poster: Can\'t set thumbnail to the Post ID \d+/',
		];

		//When
		$actual = $mgmt->filter_imdb_tags( $data, $postarr );
		$delete = wp_delete_post( $post_id, true );
		//Alternative test. Attatch image to not existens post
		$actual_after = $mgmt->filter_imdb_tags( $data, $postarr );

		//Then
		$this->assertSame( $expected['post_title'], $actual['post_title'] );
		$this->assertRegExp( $expected['post_content'], $actual['post_content'] );
		$this->assertRegExp(
			$expected_after['post_content'], $actual_after['post_content']
		);
		$this->assertTrue( $delete !== false );
	}

	/**
	 * Positive test
	 *
	 * @covers Callback_Management::live_filter_imdb_tags
	 * @covers Callback_Management::tags_replace
	 * @covers Callback_Management::__construct
	 *
	 * @return void
	 */
	public function test_live_filter_imdb_tags() {
		//Given
		$mgmt     = new Callback_Management();
		$content  = 'Pellentesque viverra luctus est, vel bibendum arcu suscipit quis. ÖÄÅ öäå Quisque congue[IMDblive:id(tt0137523)]. Posterremote: [imdblive:posterremote]';
		$expected = 'Pellentesque viverra luctus est, vel bibendum arcu suscipit quis. ÖÄÅ öäå Quisque congue. Posterremote: <a href="http://www.imdb.com/title/tt0137523/"><img src="http://ia.media-imdb.com/images/M/MV5BMjIwNTYzMzE1M15BMl5BanBnXkFtZTcwOTE5Mzg3OA@@._V1_.jpg" alt="Fight Club" width="200" class="alignnone"/></a>';

		//When
		$actual = $mgmt->live_filter_imdb_tags( $content );

		//Then
		$this->assertSame( $expected, $actual );
	}

	/**
	 * Positive test
	 *
	 * @covers Callback_Management::get_sub_prefix_hints
	 * @return void
	 */
	public function test_get_sub_prefix_hints() {
		//Given
		$mgmt     = new Callback_Management();
		$content  = 'Pellentesque viverra luctus est, vel bibendum arcu suscipit quis. ÖÄÅ öäå Quisque congue[IMDblive:id(tt0137523)]. Posterremote: [imdblive-a:posterremote] [imdblive-z:posterremote] [imdblive-z:posterremote] [imdblive-aa:posterremote] [imdblive-f0001:posterremote] [imdblive-fightclub:posterremote]';
		$prefix   = 'imdblive';
		$expected = [
			'imdblive',
			'imdblive-a',
			'imdblive-z',
			'imdblive-aa',
			'imdblive-f0001',
			'imdblive-fightclub',
		];

		//When
		$actual = $mgmt->get_sub_prefix_hints( $content, $prefix );

		//Then
		$this->assertSame( $expected, array_values( $actual ) );
	}

	/**
	 * Positive test
	 *
	 * @covers Callback_Management::convert_one_off_to_sub_prefix
	 * @return void
	 */
	public function test_convert_one_off_to_sub_prefix() {
		//Given
		$mgmt     = new Callback_Management();
		$content  = 'Pellentesque viverra luctus est, vel bibendum arcu suscipit quis. ÖÄÅ öäå Quisque congue. Posterremote: [imdb:posterremote(tt0137523)]';
		$expected = '[imdb-tt0137523:id(tt0137523)]Pellentesque viverra luctus est, vel bibendum arcu suscipit quis. ÖÄÅ öäå Quisque congue. Posterremote: [imdb-tt0137523:posterremote]';
		$prefix   = 'imdb';

		//When
		$actual = $mgmt->convert_one_off_to_sub_prefix( $content, $prefix );

		//Then
		$this->assertSame( $expected, $actual );
	}

	/**
	 * Negative test
	 *
	 * @expectedException        PCRE_Exception
	 * @expectedExceptionMessage preg_match_all(): Compilation failed: missing )
	 *
	 * @covers Callback_Management::get_sub_prefix_hints
	 * @covers PCRE_Exception
	 *
	 * @return void
	 */
	public function test_get_sub_prefix_hints_pcre_exception() {
		//Given
		$mgmt    = new Callback_Management();
		$content = 'Pellentesque viverra luctus est, vel bibendum arcu suscipit quis. ÖÄÅ öäå Quisque congue[IMDblive:id(tt0137523)]. Posterremote: [imdblive-a:posterremote] [imdblive-z:posterremote]';
		$prefix  = '(';

		//When
		$mgmt->get_sub_prefix_hints( $content, $prefix );
	}

}
