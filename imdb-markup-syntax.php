<?php
/**
 * Main file for IMDb Markup Syntax WordPress Plugin
 *
 * PHP version 5
 *
 * @category  Runnable
 * @package   Core
 * @author    Henrik Roos <henrik.roos@afternoon.se>
 * @copyright 2014 Henrik Roos
 * @license   http://opensource.org/licenses/gpl-3.0.html GPL-3.0
 * @link      https://github.com/HenrikRoos/imdb-markup-syntax imdb-markup-syntax
 */

require_once 'callback-management.php';

/**
 * Plugin Name: IMDb Markup Syntax
 * Plugin URI: http://wordpress.org/plugins/imdb-markup-syntax/
 * Description: Add <strong>IMDb</strong> syntax functionallity in your post. Enter simple tags and this plugin replace with IMBb data direct from <a href="http://app.imdb.com">IMDb Mobile Applications</a>
 * Version: 2.5
 * Author: Henrik Roos
 * Author URI: http://www.linkedin.com/pub/henrik-roos/28/148/348
 * License: GPL-3.0
 * License URI: http://opensource.org/licenses/gpl-3.0.html
 */
load_plugin_textdomain(
	'imdb-markup-syntax', false, basename( dirname( __FILE__ ) ) . '/languages'
);

$mgmt = new Callback_Management( get_locale() );

// Called by function prior to inserting into or updating the database.
add_filter( 'wp_insert_post_data', array( $mgmt, 'filter_imdb_tags' ), null, 2 );

//applied to the post title retrieved from the database, prior to printing on the screen
add_filter( 'the_title', array( $mgmt, 'live_filter_imdb_tags' ) );

//applied to the post content retrieved from the database, prior to printing on the screen
add_filter( 'the_content', array( $mgmt, 'live_filter_imdb_tags' ) );
