<?php

/**
 * Main file for IMDb Markup Syntax WordPress Plugin
 * 
 * PHP version 5
 * 
 * @category  Runnable
 * @package   Core
 * @author    Henrik Roos <henrik.roos@afternoon.se>
 * @copyright 2013 Henrik Roos
 * @license   http://opensource.org/licenses/gpl-3.0.html GPL-3.0
 * @link      https://github.com/HenrikRoos/imdb-markup-syntax imdb-markup-syntax
 */
use IMDb_Markup_Syntax\Tag_Processing;

require_once dirname(__FILE__) . "/Tag_Processing.php";

/**
 * Plugin Name: IMDb Markup Syntax
 * Plugin URI: https://github.com/HenrikRoos/imdb-markup-syntax
 * Description: xxxxxx
 * Version: 1.0
 * Author: Henrik Roos
 * Author URI: http://www.linkedin.com/pub/henrik-roos/28/148/348
 * License: GPL-3.0
 * License URI: http://opensource.org/licenses/gpl-3.0.html
 */

/**
 * Replace **[imdb:id(ttxxxxxxx)]** and **[imdb:xxx]** with imdb data
 * 
 * @param string $content content widh tags
 * 
 * @return string content with replaced tags
 */
function filterImdbTags($content)
{
    $imdb = new Tag_Processing($content);
    $imdb->locale = get_locale();
    $imdb->tagsReplace();
    return $imdb->getReplacementContent();
}

/**
 * Replace **[imdblive:id(ttxxxxxxx)]** and **[imdblive:xxx]** with imdb data
 * 
 * @param string $content content widh tags
 * 
 * @return string content with replaced tags
 */
function liveFilterImdbTags($content)
{
    $imdb = new Tag_Processing($content);
    $imdb->locale = get_locale();
    $imdb->prefix = "imdblive";
    $imdb->tagsReplace();
    return $imdb->getReplacementContent();
}

//applied to post title prior to saving it in the database
add_filter("title_save_pre", "filterImdbTags");

//applied to post content prior to saving it in the database
add_filter("content_save_pre", "filterImdbTags");

//applied to the post title retrieved from the database, prior to printing on the
//screen
add_filter("the_title", "liveFilterImdbTags");

//applied to the post content retrieved from the database, prior to printing on the
//screen
add_filter("the_content", "liveFilterImdbTags");
?>