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

require_once dirname(__FILE__) . '/Tag_Processing.php';

/**
 * Plugin Name: IMDb Markup Syntax
 * Plugin URI: https://github.com/HenrikRoos/imdb-markup-syntax
 * Description: A brief description of the Plugin.
 * Version: 1.0
 * Author: Henrik Roos
 * Author URI: http://URI_Of_The_Plugin_Author
 * License: http://opensource.org/licenses/gpl-3.0.html GPL-3.0
 */

/**
 * Replace **[imdb:id(ttxxxxxxx)]** and **[imdb:xxx]** with imdb data
 * 
 * @param string $content content widh tags
 * @return string content with replaced tags
 */
function filter_imdb_tags($content)
{
    $imdb = new Tag_Processing($content, get_locale());
    $imdb->tagsReplace();
    return $imdb->getReplacementContent();
}

//applied to post content prior to saving it in the database
add_filter("content_save_pre", "filter_imdb_tags");

//applied to post title prior to saving it in the database
add_filter("title_save_pre", "filter_imdb_tags");
?>