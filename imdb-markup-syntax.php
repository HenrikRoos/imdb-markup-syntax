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
require_once dirname(__FILE__) . "/Callback_Management.php";

use IMDb_Markup_Syntax\Callback_Management;

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
load_plugin_textdomain("imdb-markup-syntax", false,
    basename(dirname(__FILE__)) . "/languages");

$mgmt = new Callback_Management(get_locale());

// Called by function prior to inserting into or updating the database.
add_filter("wp_insert_post_data", array($mgmt, "filterImdbTags"), null, 2);

//applied to the post title retrieved from the database, prior to printing on the
//screen
add_filter("the_title", array($mgmt, "liveFilterImdbTags"));

//applied to the post content retrieved from the database, prior to printing on the
//screen
add_filter("the_content", array($mgmt, "liveFilterImdbTags"));
?>