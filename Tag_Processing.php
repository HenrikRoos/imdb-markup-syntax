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

use IMDb;
use IMDb_Markup_Syntax\Exceptions\PCRE_Exception;

require_once dirname(__FILE__) . '/Exceptions/PCRE_Exception.php';

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
    public $tconst_pattern = "/\[imdb\:id\((tt\d+)\)\]/i";

    /** @var string Regular expression for all imdb tags */
    public $imdb_tags_pattern = "/\[imdb\:([a-z0-9]+)\]/i";

    /** @var string Original content before this filter processing */
    public $original_content;

    /**
     * @var string Id on current movie.
     * <i>e.g http://www.imdb.com/title/tt0137523/ -> id = tt0137523</i>
     * Syntax: <b>[imdb:id(ttxxxxxxx)]</b>
     */
    public $tconst;

    /** @var IMDb object IMDb:s data object */
    public $data;

    /** @var array of imdb tags. All imdb tags in current content */
    public $imdb_tags = array();

    /**
     * Create an object
     * 
     * @param string $original_content Blog post content
     */
    public function __construct($original_content)
    {
        $this->original_content = $original_content;
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
            $this->tconst = null;
            return false;
        }
        $this->tconst = $match[1];
        return true;
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
            $this->imdb_tags_pattern, $this->original_content, $match
        );
        if ($isOk === false) {
            throw new PCRE_Exception();
        }
        $this->imdb_tags = $match[1];
        return !empty($match[1]);
    }

}

?>
