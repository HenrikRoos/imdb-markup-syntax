<?php

/**
 * Find and replace imdb tags to movie data from IMDb
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

namespace IMDb_Markup_Syntax;

use Exception;
use IMDb_Markup_Syntax\Exceptions\PCRE_Exception;
use IMDb_Markup_Syntax\Exceptions\Runtime_Exception;

require_once dirname(__FILE__) . "/Exceptions/PCRE_Exception.php";
require_once dirname(__FILE__) . "/Exceptions/Runtime_Exception.php";
require_once dirname(__FILE__) . "/Movie_Datasource.php";
require_once dirname(__FILE__) . "/Markup_Data.php";

/**
 * Find and replace imdb tags to movie data from IMDb
 * 
 * @category  Runnable
 * @package   Core
 * @author    Henrik Roos <henrik.roos@afternoon.se>
 * @copyright 2013 Henrik Roos
 * @license   http://opensource.org/licenses/gpl-3.0.html GPL-3.0
 * @link      https://github.com/HenrikRoos/imdb-markup-syntax imdb-markup-syntax
 */
class Tag_Processing
{

    /**
     * @var string core syntax in tags. *e.g. prefix = imdb => [imdb:date]
     * prefix = abc => [abc:date]*
     */
    public $prefix = "imdb";

    /**
     * @var string Regular expression for id. This is a subset of pattern:
     * **[{$this->prefix}:{$this->id_pattern}]**
     */
    protected $id_pattern = "id\((tt\d{7,20})\)";

    /**
     * @var string Regular expression for imdb tags. This is a subset of pattern:
     * **[{$this->prefix}:{$this->imdb_tags_pattern}]**
     */
    protected $imdb_tags_pattern = "([a-z0-9_]{1,40})";

    /**
     * @var string Regular expression for id. If this is set when override
     * **[{$this->prefix}:{$this->id_pattern}]** with this.
     * e.g. <i>/\[my_id\:[a-z]+\]/i"</i>
     */
    protected $custom_id_pattern = "";

    /**
     * @var string Regular expression for imdb tags. If this is set when override
     * **[{$this->prefix}:{$this->imdb_tags_pattern}]** with this.
     * e.g. <i>/\[my_prefix\:[a-z]+\]/i"</i>
     */
    protected $custom_tags_pattern = "";

    /** @var string Original content before filter processing */
    public $original_content;

    /** @var string Replacement content after filter processing */
    private $_replacement_content = "";

    /**
     * @var array Id on current movie.
     * <i>e.g http://www.imdb.com/title/tt0137523/ -> id = tt0137523</i>
     * Syntax: <b>[imdb:id(ttxxxxxxx)]</b>
     * $tconst_tag => array("[imdb:id(tt0137523)]", "tt0137523")
     */
    protected $tconst_tag = array();

    /**
     * @var array Multi-array of imdb tags in PREG_SET_ORDER. All imdb tags in
     * current content
     * - $imdb_tags[0] => array("[imdb:xxx]", "xxx")
     * - $imdb_tags[1] => array("[imdb:yyy]", "yyy")
     * - ...
     */
    protected $imdb_tags = array();

    /** @var string Localization for data, standard RFC 4646 */
    public $locale = "";

    /** @var int The maximum number of milliseconds to allow execute to imdb. */
    public $timeout = 0;

    /** @var int Current post_id use by poster * */
    public $post_id;

    /** @var object IMDb:s data object */
    protected $data;

    /**
     * Create an object
     * 
     * @param string $original_content Blog post content
     * @param int    $post_id          Current post_id use by poster
     */
    public function __construct($original_content, $post_id = 0)
    {
        $this->original_content = $original_content;
        $this->post_id = $post_id;
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
    public function tagsReplace()
    {
        $this->_replacement_content = $this->original_content;
        $count = 0;
        try {
            if (!$this->findId()) {
                return false;
            }
            //Try parse imdb tags
            $this->findImdbTags();
        } catch (PCRE_Exception $exc) {
            //Some fishy PCRE Exception try find [imdb:id with str_replace insted
            //and diplay this error. If not found then just return false
            $this->_replacement_content = str_ireplace(
                "[{$this->prefix}:id", "[" . $exc->getMessage(),
                $this->_replacement_content, $count
            );
            return $count;
        } catch (Exception $exc) {
            $this->_replacement_content = str_replace(
                $this->tconst_tag[0], "[" . $exc->getMessage() . "]",
                $this->_replacement_content, $count
            );
            return $count;
        }

        //Replace [imdb:xxx] with imdb data
        $num = 0;
        foreach ($this->imdb_tags as $imdb_tag) {
            try {
                $replace = $this->toDataString($imdb_tag[1]);
            } catch (Exception $exc) {
                $replace = $exc->getMessage();
            }
            $this->_replacement_content = str_replace(
                $imdb_tag[0], $replace, $this->_replacement_content, $num
            );
            $count += $num;
        }

        if ($count > 0) {
            //Delete [imdb:id(ttxxxxxxx)] in replacement_content
            $this->_replacement_content = str_replace(
                $this->tconst_tag[0], "", $this->_replacement_content, $num
            );
        } else {
            $this->_replacement_content = str_replace(
                $this->tconst_tag[0],
                __("[No imdb tags found]", "imdb-markup-syntax"),
                $this->_replacement_content, $num
            );
        }

        return $count + $num;
    }

    /**
     * Get the replacment blog post content. Tags is replcesed by data or error
     * message
     * 
     * @return string Replacment blog post content
     */
    public function getReplacementContent()
    {
        return $this->_replacement_content;
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
    protected function findId()
    {
        $match = array();
        $pattern = empty($this->custom_id_pattern)
            ? "/\[{$this->prefix}\:{$this->id_pattern}\]/i"
            : $this->custom_id_pattern;
        $isOk = @preg_match($pattern, $this->original_content, $match);

        if ($isOk === false) {
            throw new PCRE_Exception();
        }
        if (empty($match)) {
            $this->tconst = array();
            return false;
        }
        $this->tconst_tag = $match;

        $imdb = new Movie_Datasource($match[1], $this->locale, $this->timeout);
        $this->data = new Markup_Data(
            $imdb->getData(), $this->post_id, $this->locale, $this->prefix
        );
        return $this->data->getTconst() == true;
    }

    /**
     * Find and store alltags in imdb_tags array. Syntax: <b>[imdb:xxx]</b>
     *
     * @return boolean False if no match true if find
     * 
     * @throws PCRE_Exception If a PCRE error occurs or patten compilation failed
     */
    protected function findImdbTags()
    {
        $match = array();
        $pattern = empty($this->custom_tags_pattern)
            ? "/\[{$this->prefix}\:{$this->imdb_tags_pattern}\]/i"
            : $this->custom_tags_pattern;
        $isOk = @preg_match_all(
            $pattern, $this->original_content, $match, PREG_SET_ORDER
        );
        if ($isOk === false) {
            throw new PCRE_Exception();
        }
        if (empty($match)) {
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
     * @return string|boolean Replacement text for the tag name
     */
    protected function toDataString($tag)
    {
        if (@preg_match("/^{$this->imdb_tags_pattern}$/i", $tag) == 0) {
            throw new Runtime_Exception(
                __("Invalid function name", "imdb-markup-syntax")
            );
        }
        $fname = "get" . ucfirst(strtolower($tag));
        if (!method_exists($this->data, $fname)) {
            throw new Runtime_Exception(
                sprintf(__("[Tag %s not exists]", "imdb-markup-syntax"), $tag)
            );
        }
        return $this->data->$fname();
    }

}

?>
