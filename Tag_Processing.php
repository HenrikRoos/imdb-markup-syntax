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
 * @license   https://github.com/HenrikRoos/imdb-markup-syntax/blob/master/imdb-markup-syntax.php GPL2
 * @link      https://github.com/HenrikRoos/imdb-markup-syntax imdb-markup-syntax
 */

namespace IMDb_Markup_Syntax;

use Exception;
use IMDb_Markup_Syntax\Exceptions\PCRE_Exception;
use IMDb_Markup_Syntax\Exceptions\Runtime_Exception;

require_once dirname(__FILE__) . '/Exceptions/PCRE_Exception.php';
require_once dirname(__FILE__) . '/Exceptions/Runtime_Exception.php';
require_once dirname(__FILE__) . '/Movie_Datasource.php';
require_once dirname(__FILE__) . '/Markup_Data.php';

/**
 * Find and replace imdb tags to movie data from IMDb
 * 
 * @category  Runnable
 * @package   Core
 * @author    Henrik Roos <henrik.roos@afternoon.se>
 * @copyright 2013 Henrik Roos
 * @license   https://github.com/HenrikRoos/imdb-markup-syntax/blob/master/imdb-markup-syntax.php GPL2
 * @link      https://github.com/HenrikRoos/imdb-markup-syntax imdb-markup-syntax
 */
class Tag_Processing
{

    /** @var string Regular expression for find id */
    public $tconst_pattern = "/\[imdb\:id\((tt\d{7,20})\)\]/i";

    /** @var string Regular expression for all imdb tags */
    public $imdb_tags_pattern = "/\[imdb\:([a-z0-9_]{1,40})\]/i";

    /** @var string Original content before filter processing */
    public $original_content;

    /** @var string Replacement content after filter processing */
    public $replacement_content;

    /**
     * @var array Id on current movie.
     * <i>e.g http://www.imdb.com/title/tt0137523/ -> id = tt0137523</i>
     * Syntax: <b>[imdb:id(ttxxxxxxx)]</b>
     * $tconst_tag => array("[imdb:id(tt0137523)]", "tt0137523")
     */
    public $tconst_tag = array();

    /**
     * @var array Multi-array of imdb tags in PREG_SET_ORDER. All imdb tags in
     * current content
     * - $imdb_tags[0] => array("[imdb:xxx]", "xxx")
     * - $imdb_tags[1] => array("[imdb:yyy]", "yyy")
     * - ...
     */
    public $imdb_tags = array();

    /** @var string Localization for data, defualt <i>en_US</i> standard RFC 4646 */
    public $locale;

    /** @var object IMDb:s data object */
    public $data;

    /**
     * Create an object
     * 
     * @param string $original_content Blog post content
     * @param string $locale           Localization for data, defualt <i>en_US</i>
     */
    public function __construct($original_content, $locale = "en_US")
    {
        $this->original_content = $original_content;
        $this->locale = $locale;
    }

    /**
     * Replace **[imdb:id(ttxxxxxxx)]** and **[imdb:xxx]** with imdb data in
     * **replacement_content**.
     * 1) Find first [imdb:id(ttxxxxxxx)] tag in content
     *  a) If not found then return false .{color:crimson}
     * 2) Get data from IMDb API
     *  a) If no data then throw an Exception, catched by this .{color:crimson}
     *     method and replace imdb:id tag widh error message .{color:crimson}
     * 3) Find all imdb data tags and replace tags with imdb data
     *  a) If no data found then false usage as replaced text .{color:darkorange}
     *  b) If Exception then getMessage() usage as replaced text .{color:crimson}
     * 
     * @return boolean|int False if no id or tags is present or number of
     * replacements performed 
     */
    public function tagsReplace()
    {
        $this->replacement_content = $this->original_content;
        try {
            if (!$this->findId()) {
                return false;
            }
            //Nice, id is found, use it
            $replaceId = $this->tconst_tag[0];
            //Try parse imdb tags
            $this->findImdbTags();
        } catch (Exception $exc) {
            $replaceId = $exc->getMessage();
        }

        //Replace [imdb:id(ttxxxxxxx)] in replacement_content with $replaceId
        $count = 0;
        $this->replacement_content = str_replace(
            $replaceId, "", $this->replacement_content, $count
        );

        //Replace [imdb:xxx] with imdb data
        $num = 0;
        foreach ($this->imdb_tags as $imdb_tag) {
            try {
                $replace = $this->toDataString($imdb_tag[1]);
            } catch (Exception $exc) {
                $replace = $exc->getMessage();
            }
            $this->replacement_content = str_replace(
                $imdb_tag[0], $replace, $this->replacement_content, $num
            );
            $count += $num;
        }

        return $count;
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
    public function findId()
    {
        $match = array();
        $isOk = @preg_match($this->tconst_pattern, $this->original_content, $match);

        if ($isOk === false) {
            throw new PCRE_Exception();
        }
        if (empty($match)) {
            $this->tconst = array();
            return false;
        }
        $imdb = new Movie_Datasource($match[1], $this->locale);
        $this->data = new Markup_Data($imdb->getData());
        $this->tconst_tag = $match;

        return $this->data->getTconst() == true;
    }

    /**
     * Find and store alltags in imdb_tags array. Syntax: <b>[imdb:xxx]</b>
     * 
     * @return boolean False if no match true if find
     * 
     * @throws PCRE_Exception If a PCRE error occurs or patten compilation failed
     */
    public function findImdbTags()
    {
        $match = array();
        $isOk = @preg_match_all(
            $this->imdb_tags_pattern, $this->original_content, $match, PREG_SET_ORDER
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
        if (@preg_match("/^[a-z0-9_]+$/i", $tag) == 0) {
            throw new Runtime_Exception(null, "Invalid function name");
        }
        $fname = "get" . ucfirst(strtolower($tag));
        if (!method_exists($this->data, $fname)) {
            throw new Runtime_Exception(null, "[Tag {$tag} not exists]");
        }
        return $this->data->$fname();
    }

}

?>
