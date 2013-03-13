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

namespace IMDbMarkupSyntax;

use IMDb;
use IMDbMarkupSyntax\Exceptions\PCREException;

require_once dirname(__FILE__) . '/Exceptions/class-pcre-exception.php';

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
class TagProcessing
{

    /** @var string Regular expression for find tconst */
    public $tconst_pattern = "/\[imdb\:tconst\((tt\d+)\)\]/i";

    /** @var string Regular expression for all imdb tags */
    public $imdb_tags_pattern = "/\[imdb\:([a-z0-9]+)\]/i";

    /** @var string Original content before this filter processing */
    public $original_content;

    /**
     * @var string Id on current movie.
     * <i>e.g http://www.imdb.com/title/tt0137523/ -> tconst = tt0137523</i>
     * Syntax: <b>[imdb:tconst(ttxxxxxxx)]</b>
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
     * Find and store it in tconst. Syntax: <b>[imdb:tconst(ttxxxxxxx)]</b>.
     * <i>e.g. "Nunc non diam sit [imdb:tconst(tt0137523)]nulla sem tempus magna" ->
     * tconst = tt0137523</i>
     * 
     * @return boolean False if no match true if find a tconst
     * 
     * @throws PCREException If a PCRE error occurs or patten compilation failed
     */
    public function findTconst()
    {
        $match = array();
        $isOk = @preg_match($this->tconst_pattern, $this->original_content, $match);

        if ($isOk === false) {
            throw new PCREException();
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
     * @throws PCREException If a PCRE error occurs or patten compilation failed
     */
    public function findImdbTags()
    {
        $match = array();
        $isOk = @preg_match_all($this->imdb_tags_pattern, $this->original_content,
                $match
        );
        if ($isOk === false) {
            throw new PCREException();
        }
        $this->imdb_tags = $match[1];
        return !empty($match[1]);
    }

}

?>
