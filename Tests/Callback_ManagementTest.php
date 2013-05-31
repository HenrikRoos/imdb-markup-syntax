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

namespace IMDb_Markup_Syntax;

use PHPUnit_Framework_TestCase;

require_once dirname(__FILE__) . "/../../../../wp-config.php";
require_once dirname(__FILE__) . "/../Callback_Management.php";
require_once "PHPUnit/Autoload.php";

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
     * @covers IMDb_Markup_Syntax\Callback_Management::filterImdbTags
     * @covers IMDb_Markup_Syntax\Callback_Management::tagsReplace
     * @covers IMDb_Markup_Syntax\Callback_Management::__construct
     * @covers IMDb_Markup_Syntax\Media_Library_Handler
     * 
     * @return void
     */
    public function testFilterImdbTags()
    {
        //Given
        $post = array(
            "post_title" => "My post",
            "post_content" => "This is my post.",
            "post_status" => "publish",
            "post_author" => 1,
            "post_category" => array(8, 39)
        );
        $post_id = wp_insert_post($post);
        $mgmt = new Callback_Management();
        $postarr = array("ID" => $post_id);
        $data = array(
            "post_title" => "ÖÄÅ öäå congue [IMDb:id(tt0137523)][imdb:title]",
            "post_content" => "Pellentesque viverra luctus est, vel bibendum arcu "
            . "suscipit quis. ÖÄÅ öäå Quisque congue[IMDb:id(tt0137523)]. "
            . "Title: [imdb:title] Poster: [imdb:poster]"
        );
        $expected = array(
            "post_title" => "ÖÄÅ öäå congue "
            . "<a href=\"http://www.imdb.com/title/tt0137523/\">Fight Club</a>",
            "post_content" => "/Pellentesque viverra luctus est, vel bibendum arcu "
            . "suscipit quis. ÖÄÅ öäå Quisque congue. "
            . "Title: <a href=\"http:\/\/www\.imdb\.com\/title\/tt0137523\/\">"
            . "Fight Club<\/a> Poster: "
            . "<a href=\"http:\/\/www\.imdb\.com\/title\/tt0137523\/\" "
            . "title=\"Fight Club\"><img width=\"20\d\" height=\"300\" "
            . "src=\"http:\/\/.+\/uploads\/201\d\/\d\d"
            . "\/tt0137523\d*-20\dx300.jpg\" class=\"alignnone size-medium "
            . "wp-post-image\" alt=\"Fight Club\".+\/><\/a>/"
        );
        $expected_after = array(
            "post_title" => "ÖÄÅ öäå congue "
            . "<a href=\"http://www.imdb.com/title/tt0137523/\">Fight Club</a>",
            "post_content" => "/Pellentesque viverra luctus est, vel bibendum arcu "
            . "suscipit quis. ÖÄÅ öäå Quisque congue. "
            . "Title: <a href=\"http:\/\/www\.imdb\.com\/title\/tt0137523\/\">"
            . "Fight Club<\/a> Poster: "
            . "Can't set thumbnail to the Post ID \d+/"
        );

        //When
        $actual = $mgmt->filterImdbTags($data, $postarr);
        $delete = wp_delete_post($post_id, true);
        //Alternative test. Attatch image to not existens post
        $actual_after = $mgmt->filterImdbTags($data, $postarr);

        //Then
        $this->assertSame($expected["post_title"], $actual["post_title"]);
        $this->assertRegExp($expected["post_content"], $actual["post_content"]);
        $this->assertRegExp(
            $expected_after["post_content"], $actual_after["post_content"]
        );
        $this->assertTrue($delete !== false);
    }

    /**
     * Positive test
     * 
     * @covers IMDb_Markup_Syntax\Callback_Management::liveFilterImdbTags
     * @covers IMDb_Markup_Syntax\Callback_Management::tagsReplace
     * @covers IMDb_Markup_Syntax\Callback_Management::__construct
     * 
     * @return void
     */
    public function testLiveFilterImdbTags()
    {
        //Given
        $mgmt = new Callback_Management();
        $content = "Pellentesque viverra luctus est, vel bibendum arcu "
            . "suscipit quis. ÖÄÅ öäå Quisque congue[IMDblive:id(tt0137523)]. "
            . "Posterremote: [imdblive:posterremote]";
        $expected = "Pellentesque viverra luctus est, vel bibendum arcu "
            . "suscipit quis. ÖÄÅ öäå Quisque congue. "
            . "Posterremote: <a href=\"http://www.imdb.com/title/tt0137523/\">"
            . "<img src=\"http://ia.media-imdb.com/images/M/"
            . "MV5BMjIwNTYzMzE1M15BMl5BanBnXkFtZTcwOTE5Mzg3OA@@._V1_.jpg\" "
            . "alt=\"Fight Club\" width=\"200\" class=\"alignnone\"/></a>";

        //When
        $actual = $mgmt->liveFilterImdbTags($content);

        //Then
        $this->assertSame($expected, $actual);
    }

}
