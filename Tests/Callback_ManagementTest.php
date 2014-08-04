<?php
/**
 * Testclass to Callback_Management class
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

require_once 'Callback_Management.php';
require_once 'wp-config.php';
require_once 'wp-admin/includes/image.php';

/**
 * Testclass to Callback_Management class
 *
 * @category  Testable
 * @package   Test
 * @author    Henrik Roos <henrik.roos@afternoon.se>
 * @copyright 2013 Henrik Roos
 * @license   http://opensource.org/licenses/gpl-3.0.html GPL-3.0
 * @link      https://github.com/HenrikRoos/imdb-markup-syntax imdb-markup-syntax
 */
class Callback_ManagementTest extends PHPUnit_Framework_TestCase
{

    /**
     * Positive test and alternative test; attatch image to not existens post
     *
     * @covers Callback_Management::filterImdbTags
     * @covers Callback_Management::tagsReplace
     * @covers Callback_Management::__construct
     * @covers Markup_Data::getPoster
     * @covers Markup_Data::getPoster_nolink
     * @covers Media_Library_Handler
     *
     * @return void
     */
    public function testFilterImdbTags()
    {
        //Given
        $post = array(
            'post_title'    => 'My post',
            'post_content'  => 'This is my post.',
            'post_status'   => 'publish',
            'post_author'   => 1,
            'post_category' => array(8, 39)
        );
        $post_id = wp_insert_post($post);
        $mgmt = new Callback_Management();
        $postarr = array("ID" => $post_id);
        $data = array(
            'post_title'   => 'ÖÄÅ öäå congue [IMDb:id(tt0137523)][imdb:title]',
            'post_content' => 'Pellentesque viverra luctus est, vel bibendum arcu '
                . 'suscipit quis. ÖÄÅ öäå Quisque congue. '
                . '[IMDb:id(tt0137523)]Title: [imdb:title] Poster: [imdb:poster]'
                . '[IMDb-a:id(tt1206543)]Title: [imdb-a:title] Poster: '
                . '[imdb-a:poster_nolink]'
        );
        $expected = array(
            'post_title'   => 'ÖÄÅ öäå congue '
                . '<a href="http://www.imdb.com/title/tt0137523/">Fight Club</a>',
            'post_content' => '/Pellentesque viverra luctus est, vel bibendum arcu '
                . 'suscipit quis. ÖÄÅ öäå Quisque congue. '
                . 'Title: <a href="http:\/\/www\.imdb\.com\/title\/tt0137523\/">'
                . 'Fight Club<\/a> Poster: '
                . '<a href="http:\/\/www\.imdb\.com\/title\/tt0137523\/" '
                . 'title="Fight Club"><img width="\d+" height="\d+" '
                . 'src="http:\/\/.+\/uploads\/201\d\/\d\d'
                . '\/tt0137523\d*-\d+x\d+.jpg" class="alignnone size-medium '
                . 'wp-post-image" alt="Fight Club".+\/><\/a>'
                . 'Title: <a href="http:\/\/www\.imdb\.com\/title\/tt1206543\/">'
                . 'Out of the Furnace<\/a> Poster: '
                . '<img width="\d+" height="\d+" '
                . 'src="http:\/\/.+\/uploads\/201\d\/\d\d'
                . '\/tt1206543\d*-\d+x\d+.jpg" class="alignnone size-medium '
                . 'wp-post-image" alt="Out of the Furnace".+\/>/'
        );
        $expected_after = array(
            'post_title'   => 'ÖÄÅ öäå congue '
                . '<a href="http://www.imdb.com/title/tt0137523/">Fight Club</a>',
            'post_content' => '/Pellentesque viverra luctus est, vel bibendum arcu '
                . 'suscipit quis. ÖÄÅ öäå Quisque congue. '
                . 'Title: <a href="http:\/\/www\.imdb\.com\/title\/tt0137523\/">'
                . 'Fight Club<\/a> Poster: '
                . 'Can\'t set thumbnail to the Post ID \d+/'
        );

        //When
        $actual = $mgmt->filterImdbTags($data, $postarr);
        $delete = wp_delete_post($post_id, true);
        //Alternative test. Attatch image to not existens post
        $actual_after = $mgmt->filterImdbTags($data, $postarr);

        //Then
        $this->assertSame($expected['post_title'], $actual['post_title']);
        $this->assertRegExp($expected['post_content'], $actual['post_content']);
        $this->assertRegExp(
            $expected_after['post_content'], $actual_after['post_content']
        );
        $this->assertTrue($delete !== false);
    }

    /**
     * Positive test
     *
     * @covers Callback_Management::liveFilterImdbTags
     * @covers Callback_Management::tagsReplace
     * @covers Callback_Management::__construct
     *
     * @return void
     */
    public function testLiveFilterImdbTags()
    {
        //Given
        $mgmt = new Callback_Management();
        $content = 'Pellentesque viverra luctus est, vel bibendum arcu '
            . 'suscipit quis. ÖÄÅ öäå Quisque congue[IMDblive:id(tt0137523)]. '
            . 'Posterremote: [imdblive:posterremote]';
        $expected = 'Pellentesque viverra luctus est, vel bibendum arcu '
            . 'suscipit quis. ÖÄÅ öäå Quisque congue. '
            . 'Posterremote: <a href="http://www.imdb.com/title/tt0137523/">'
            . '<img src="http://ia.media-imdb.com/images/M/'
            . 'MV5BMjIwNTYzMzE1M15BMl5BanBnXkFtZTcwOTE5Mzg3OA@@._V1_.jpg" '
            . 'alt="Fight Club" width="200" class="alignnone"/></a>';

        //When
        $actual = $mgmt->liveFilterImdbTags($content);

        //Then
        $this->assertSame($expected, $actual);
    }

    /**
     * Positive test
     *
     * @covers Callback_Management::getSubPrefixHints
     * @return void
     */
    public function testGetSubPrefixHints()
    {
        //Given
        $mgmt = new Callback_Management();
        $content = 'Pellentesque viverra luctus est, vel bibendum arcu '
            . 'suscipit quis. ÖÄÅ öäå Quisque congue[IMDblive:id(tt0137523)]. '
            . 'Posterremote: [imdblive-a:posterremote] [imdblive-z:posterremote]'
            . '[imdblive-z:posterremote] [imdblive-aa:posterremote]'
            . '[imdblive-f0001:posterremote] [imdblive-fightclub:posterremote]';
        $prefix = 'imdblive';
        $expected = array('imdblive', 'imdblive-a', 'imdblive-z', 'imdblive-aa', 'imdblive-f0001', 'imdblive-fightclub');

        //When
        $actual = $mgmt->getSubPrefixHints($content, $prefix);

        //Then
        $this->assertSame($expected, $actual);
    }

    /**
     * Negative test
     *
     * @expectedException        PCRE_Exception
     * @expectedExceptionMessage preg_match_all(): Compilation failed: missing )
     *
     * @covers Callback_Management::getSubPrefixHints
     * @covers PCRE_Exception
     *
     * @return void
     */
    public function testGetSubPrefixHintsPCREException()
    {
        //Given
        $mgmt = new Callback_Management();
        $content = 'Pellentesque viverra luctus est, vel bibendum arcu '
            . 'suscipit quis. ÖÄÅ öäå Quisque congue[IMDblive:id(tt0137523)]. '
            . 'Posterremote: [imdblive-a:posterremote] [imdblive-z:posterremote]';
        $prefix = '(';

        //When
        $mgmt->getSubPrefixHints($content, $prefix);
    }

}
