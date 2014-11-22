<?php
/**
 * Find and replace imdb tags to movie data from IMDb
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

require_once 'movie-datasource.php';
require_once 'markup-data.php';
require_once 'pcre-exception.php';
require_once 'runtime-exception.php';

/**
 * Find and replace imdb tags to movie data from IMDb
 *
 * @category  Runnable
 * @package   Core
 * @author    Henrik Roos <henrik.roos@afternoon.se>
 * @copyright 2014 Henrik Roos
 * @license   http://opensource.org/licenses/gpl-3.0.html GPL-3.0
 * @link      https://github.com/HenrikRoos/imdb-markup-syntax imdb-markup-syntax
 */
class Tag_Processing {

	/**
	 * @var string core syntax in tags. *e.g. prefix = imdb => [imdb:date]
	 * prefix = abc => [abc:date]*
	 */
	public $prefix = 'imdb';
	/** @var string Original content before filter processing */
	public $original_content;
	/** @var string Localization for data, standard RFC 4646 */
	public $locale = '';
	/** @var int The maximum number of milliseconds to allow execute to imdb. */
	public $timeout = 0;
	/** @var int Current post_id use by poster * */
	public $post_id;
	/**
	 * @var string Regular expression for id. This is a subset of pattern:
	 * **[{$this->prefix}:{$this->id_pattern}]**
	 */
	protected $id_pattern = 'id\((tt\d{7,20})\)';
	/**
	 * @var string Regular expression for locale e.g fr-FR. This is a subset of pattern:
	 * **[{$this->prefix}:{$this->id_pattern}]**
	 */
	protected $locale_pattern = 'locale\(([a-z]{2}_?[a-z]{0,2})\)';
	/**
	 * @var string Regular expression for imdb tags. This is a subset of pattern:
	 * **[{$this->prefix}:{$this->imdb_tags_pattern}]**
	 */
	protected $imdb_tags_pattern = '([a-z0-9_]{1,40})';
	/**
	 * @var string Regular expression for id. If this is set when override
	 * **[{$this->prefix}:{$this->id_pattern}]** with this.
	 * e.g. <i>/\[my_id\:[a-z]+\]/i"</i>
	 */
	protected $custom_id_pattern = '';
	/**
	 * @var string Regular expression for locale. If this is set when override
	 * **[{$this->prefix}:{$this->locale_pattern}]** with this.
	 * e.g. <i>/\[my_id\:[a-z]+\]/i"</i>
	 */
	protected $custom_locale_pattern = '';
	/**
	 * @var string Regular expression for imdb tags. If this is set when override
	 * **[{$this->prefix}:{$this->imdb_tags_pattern}]** with this.
	 * e.g. <i>/\[my_prefix\:[a-z]+\]/i"</i>
	 */
	protected $custom_tags_pattern = '';
	/**
	 * @var array Id on current movie.
	 * <i>e.g http://www.imdb.com/title/tt0137523/ -> id = tt0137523</i>
	 * Syntax: <b>[imdb:id(ttxxxxxxx)]</b>
	 * $tconst_tag => array("[imdb:id(tt0137523)]", "tt0137523")
	 */
	protected $tconst_tag = array();
	/**
	 * @var array locale on current movie.
	 * Syntax: <b>[imdb:locale(fr-FR)]</b>
	 * $locale_tag => array("[imdb:locale(tt0137523)]", "fr-FR")
	 */
	protected $locale_tag = array();
	/**
	 * @var array Multi-array of imdb tags in PREG_SET_ORDER. All imdb tags in
	 * current content
	 * - $imdb_tags[0] => array("[imdb:xxx]", "xxx")
	 * - $imdb_tags[1] => array("[imdb:yyy]", "yyy")
	 * - ...
	 */
	protected $imdb_tags = array();
	/** @var object IMDb:s data object */
	protected $data;
	/** @var string Replacement content after filter processing */
	private $_replacement_content = '';

	/**
	 * Create an object
	 *
	 * @param string $original_content Blog post content
	 * @param int $post_id Current post_id use by poster
	 */
	public function __construct( $original_content, $post_id = 0 ) {
		$this->original_content = $original_content;
		$this->post_id          = $post_id;
	}

	/**
	 * Replace **[imdb:id(ttxxxxxxx)]** and **[imdb:xxx]** with imdb data in
	 * **replacement_content**.
	 * 1) Find first [imdb:id(ttxxxxxxx)] tag in content
	 *  a) If not found then return false
	 *  b) If PCRE Exception: try replace *imdb:id* with *getMessage()*
	 * 2) Get data from IMDb API
	 *  a) If Datasource Exception: replace *imdb:id* with getMessage()
	 * 3) Find all imdb data tags and replace tags with imdb data
	 *  a) If no data found then false usage as replaced text
	 *  b) If Exception then getMessage() usage as replaced text
	 *  c) If not match replace id tag with "no imdb tags found"
	 *
	 * @return boolean|int False if no id or tags is present or number of
	 * replacements performed
	 */
	public function tags_replace() {
		$this->_replacement_content = $this->original_content;
		$count                      = 0;
		try {
			$this->find_locale();
			if ( ! $this->find_id() ) {
				return false;
			}
			$this->find_imdb_tags();
		} catch ( PCRE_Exception $exc ) {
			//Some fishy PCRE Exception try find [imdb:id with str_replace insted
			//and diplay this error. If not found then just return false
			$this->_replacement_content = str_ireplace(
				'[' . $this->prefix . ':id', '[' . $exc->getMessage(),
				$this->_replacement_content, $count
			);

			return $count;
		} catch ( Exception $exc ) {
			$this->_replacement_content = str_replace(
				$this->tconst_tag[0], '[' . $exc->getMessage() . ']',
				$this->_replacement_content, $count
			);

			return $count;
		}

		//Replace [imdb:xxx] with imdb data
		$num = 0;
		foreach ( $this->imdb_tags as $imdb_tag ) {
			try {
				$replace = $this->to_data_string( $imdb_tag[1] );
			} catch ( Exception $exc ) {
				$replace = $exc->getMessage();
			}
			$this->_replacement_content = str_replace(
				$imdb_tag[0], $replace, $this->_replacement_content, $num
			);
			$count += $num;
		}

		if ( $count > 0 ) {
			//Delete [imdb:id(ttxxxxxxx)] in replacement_content
			$this->_replacement_content = str_replace(
				$this->tconst_tag[0], '', $this->_replacement_content, $num
			);
			$count += $num;

			//Delete [imdb:locale(ttxxxxxxx)] in replacement_content
			$this->_replacement_content = str_replace(
				$this->locale_tag[0], '', $this->_replacement_content, $num
			);
		} else {
			$this->_replacement_content = str_replace(
				$this->tconst_tag[0],
				__( '[No imdb tags found]', 'imdb-markup-syntax' ),
				$this->_replacement_content, $num
			);
		}

		return $count + $num;
	}

	/**
	 * Find and store it in id. Syntax: <b>[imdb:id(ttxxxxxxx)]</b>.
	 * <i>e.g. "Nunc non diam sit [imdb:id(tt0137523)]nulla sem tempus magna" ->
	 * id = tt0137523</i>
	 *
	 * @return boolean False if no match true if find a id
	 *
	 * @throws PCRE_Exception If a PCRE error occurs or patten compilation failed
	 */
	protected function find_id() {
		$match   = array();
		$pattern = empty( $this->custom_id_pattern )
			? '/\[' . $this->prefix . '\:' . $this->id_pattern . '\]/i'
			: $this->custom_id_pattern;
		$isOk    = @preg_match( $pattern, $this->original_content, $match );

		if ( $isOk === false ) {
			throw new PCRE_Exception();
		}
		if ( empty( $match ) ) {
			$this->tconst_tag = array();

			return false;
		}
		$this->tconst_tag = $match;

		$imdb       = new Movie_Datasource( $match[1], $this->locale, $this->timeout );
		$this->data = new Markup_Data(
			$imdb->get_data(), $this->post_id, $this->locale, $this->prefix
		);

		return $this->data->get_tconst() == true;
	}

	/**
	 * Find after local override for locale.
	 *
	 * @return boolean False if no match true if match
	 *
	 * @throws PCRE_Exception If a PCRE error occurs or patten compilation failed
	 */
	protected function find_locale() {
		$match   = array();
		$pattern = empty( $this->custom_locale_pattern )
			? '/\[' . $this->prefix . '\:' . $this->locale_pattern . '\]/i'
			: $this->custom_locale_pattern;
		$isOk    = @preg_match( $pattern, $this->original_content, $match );

		if ( $isOk === false ) {
			throw new PCRE_Exception();
		}
		if ( empty( $match ) ) {
			$this->locale_tag = array();

			return false;
		}
		$this->locale_tag = $match;
		$this->locale     = $match[1];

		return true;
	}

	/**
	 * Find and store alltags in imdb_tags array. Syntax: <b>[imdb:xxx]</b>
	 *
	 * @return boolean False if no match true if find
	 *
	 * @throws PCRE_Exception If a PCRE error occurs or patten compilation failed
	 */
	protected function find_imdb_tags() {
		$match   = array();
		$pattern = empty( $this->custom_tags_pattern )
			? '/\[' . $this->prefix . '\:' . $this->imdb_tags_pattern . '\]/i'
			: $this->custom_tags_pattern;
		$isOk    = @preg_match_all(
			$pattern, $this->original_content, $match, PREG_SET_ORDER
		);
		if ( $isOk === false ) {
			throw new PCRE_Exception();
		}
		if ( empty( $match ) ) {
			$this->imdb_tags = array();

			return false;
		}
		$this->imdb_tags = $match;

		return true;
	}

	/**
	 * Find imdb data for the tag name
	 *
	 * @param string $tag Name of tag to get data for
	 *
	 * @throws Runtime_Exception
	 * @return string|boolean Replacement text for the tag name
	 */
	protected function to_data_string( $tag ) {
		if ( @preg_match( '/^' . $this->imdb_tags_pattern . '$/i', $tag ) == 0 ) {
			throw new Runtime_Exception(
				__( 'Invalid function name', 'imdb-markup-syntax' )
			);
		}
		$fname = 'get_' . ucfirst( strtolower( $tag ) );
		if ( ! method_exists( $this->data, $fname ) ) {
			throw new Runtime_Exception(
				sprintf( __( '[Tag %s not exists]', 'imdb-markup-syntax' ), $tag )
			);
		}

		return $this->data->$fname();
	}

	/**
	 * Get the replacment blog post content. Tags is replcesed by data or error
	 * message
	 *
	 * @return string Replacment blog post content
	 */
	public function get_replacement_content() {
		return $this->_replacement_content;
	}

}
