<?php
/**
 * Collections and management of callbacks from plugin-filter and plugin-actions
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

require_once 'tag-processing.php';
require_once 'pcre-exception.php';

/**
 * Collections and management of callbacks from plugin-filter and plugin-actions
 *
 * @category  Runnable
 * @package   Core
 * @author    Henrik Roos <henrik.roos@afternoon.se>
 * @copyright 2014 Henrik Roos
 * @license   http://opensource.org/licenses/gpl-3.0.html GPL-3.0
 * @link      https://github.com/HenrikRoos/imdb-markup-syntax imdb-markup-syntax
 */
class Callback_Management {

	/** @var string Localization for data, standard RFC 4646 */
	public $locale;

	/**
	 * Create an intsans and set localization for data
	 *
	 * @param string $locale Localization for data, standard RFC 4646
	 */
	public function __construct( $locale = '' ) {
		$this->locale = $locale;
	}

	/**
	 * Replace **[imdb:id(ttxxxxxxx)]** and **[imdb:xxx]** with imdb data
	 * Call by *wp_insert_post_data* filter hook.
	 *
	 * @param array $data Sanitized post data
	 * @param array $postarr Raw post data.
	 *
	 * @return array Update $data
	 */
	public function filter_imdb_tags( $data, $postarr ) {
		$post_id = $postarr['ID'];

		$content              = $data['post_content'];
		$data['post_content'] = $this->tags_replace( $content, 'imdb', $post_id );

		$title              = $data['post_title'];
		$data['post_title'] = $this->tags_replace( $title, 'imdb', $post_id );

		return $data;
	}

	/**
	 * Replace **[xxx:id(ttyyyy)]** and **[xxx:yyy]** with imdb data
	 *
	 * @param string $content Content widh tags
	 * @param string $prefix Starting tagname
	 * @param int $post_id Current post_id use by poster
	 *
	 * @return string content with replaced tags
	 */
	protected function tags_replace( $content, $prefix, $post_id = 0 ) {
		$content    = $this->convert_one_off_to_sub_prefix( $content, $prefix );
		$prefixsubs = $this->get_sub_prefix_hints( $content, $prefix );

		foreach ( $prefixsubs as $prefixsub ) {
			$imdb         = new Tag_Processing( $content, $post_id );
			$imdb->locale = $this->locale;
			$imdb->prefix = $prefixsub;
			$imdb->tags_replace();
			$content = $imdb->get_replacement_content();
		}

		return $content;
	}

	/**
	 * Convert one-off tags (with IDs embedded into the tag directly) to subprefixed tags
	 *
	 * @param string $content Content widh tags
	 * @param string $prefix Starting tagname
	 *
	 * @throws PCRE_Exception
	 * @since 2.1
	 *
	 * @return string content with one-off tags converted
	 */
	public function convert_one_off_to_sub_prefix( $content, $prefix ) {
		$match   = array();
		$pattern = '/\[' . $prefix . ':([a-z0-9_]{1,40})\((tt\d{7,20})\)\]/i';
		$isOk    = @preg_match_all( $pattern, $content, $match );

		if ( $isOk === false ) {
			throw new PCRE_Exception();
		}

		$tagList = array_map( 'strtolower', $match[1] );
		foreach ( array_keys( $tagList, 'id' ) as $idIndex ) {
			unset( $match[2][ $idIndex ] );
		}
		$idList = array_values( array_unique( array_map( 'strtolower', $match[2] ) ) );

		foreach ( $idList as $id ) {
			$pattern       = '/\[' . $prefix . ':([a-z0-9_]{1,40})\(' . $id . '\)\]/i';
			$replace       = '[' . $prefix . '-' . $id . ':$1]';
			$replaceResult = preg_replace( $pattern, $replace, $content );
			if ( $replaceResult === null ) {
				throw new PCRE_Exception();
			} else {
				$content = $replaceResult;
			}

			$content = "[{$prefix}-{$id}:id({$id})]" . $content;
		}

		return str_ireplace( '[' . $prefix . ':id]', '', $content );
	}

	/**
	 * Seek for tags with multi prefix syntax e.g imdb-a, imdb- , ... imdb-z.
	 * **SubPrefix Syntax: xxx-[a-z]**
	 *
	 * @param string $content Content widh tags
	 * @param string $prefix Starting tagname
	 *
	 * @throws PCRE_Exception
	 * @since 2.0
	 *
	 * @return array list of all sub prefix
	 */
	public function get_sub_prefix_hints( $content, $prefix ) {
		$match   = array();
		$pattern = '/\[(' . $prefix . '(-[a-z][a-z0-9]*)?):/i';
		$isOk    = @preg_match_all( $pattern, $content, $match );

		if ( $isOk === false ) {
			throw new PCRE_Exception();
		}

		return array_values( array_unique( array_map( 'strtolower', $match[1] ) ) );
	}

	/**
	 * Replace **[imdblive:id(ttxxxxxxx)]** and **[imdblive:xxx]** with imdb data
	 * Call by *the_content* or *the_title* filter hook
	 *
	 * @param string $content content widh tags
	 *
	 * @return string content with replaced tags
	 */
	public function live_filter_imdb_tags( $content ) {
		return $this->tags_replace( $content, 'imdblive' );
	}

}
