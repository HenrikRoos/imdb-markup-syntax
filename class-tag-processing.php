<?php

namespace IMDb_Markup_Syntax;

use IMDb;
use IMDb_Markup_Syntax\Exceptions\PCRE_Exception;

require_once dirname(__FILE__) . '/Exceptions/class-pcre-exception.php';

/**
 * Find and replace imdb tags to movie data from IMDb
 * @author Henrik Roos <henrik@afternoon.se>
 * @package Core
 */
class Tag_Processing
{

    /** @var string regular expression for find tconst */
    public $tconst_pattern = "/\[imdb\:tconst\((tt\d+)\)\]/i";

    /** @var string regular expression for all imdb tags */
    public $imdb_tags_pattern = "/\[imdb\:([a-z0-9]+)\]/i";

    /** @var string Original content before this filter processing */
    public $original_content;

    /**
     * @var string Id on current movie. <i>e.g http://www.imdb.com/title/tt0137523/ -> tconst = tt0137523</i>
     * Syntax: <b>[imdb:tconst(ttxxxxxxx)]</b>
     */
    public $tconst;

    /** @var IMDb object IMDb:s data object */
    public $data;

    /** @var array of imdb tags. All imdb tags in current content */
    public $imdb_tags = array();

    /**
     * Create an object
     * @param string $original_content
     */
    public function __construct($original_content)
    {
        $this->original_content = $original_content;
    }

    /**
     * Find and store it in $tconst. Syntax: <b>[imdb:tconst(ttxxxxxxx)]</b>.
     * <i>e.g. "Nunc non diam sit [imdb:tconst(tt0137523)] nulla sem tempus magna" -> tconst = tt0137523</i>
     * @return boolean false if no match TRUE if find a tconst
     * @throws PCRE_Exception if a PCRE error occurs or patten compilation failed
     */
    public function find_tconst()
    {
        $matches = array();
        if (@preg_match($this->tconst_pattern, $this->original_content, $matches) === false) {
            throw new PCRE_Exception();
        }
        if (empty($matches)) {
            $this->tconst = null;
            return false;
        }
        $this->tconst = $matches[1];
        return TRUE;
    }

    /**
     * Find and store alltags in imdb_tags array. Syntax: <b>[imdb:xxx]</b> 
     * @return boolean false if no match TRUE if find 
     * @throws PCRE_Exception if a PCRE error occurs or patten compilation failed
     */
    public function find_imdb_tags()
    {
        $matches = array();
        if (@preg_match_all($this->imdb_tags_pattern, $this->original_content, $matches) === false) {
            throw new PCRE_Exception();
        }
        $this->imdb_tags = $matches[1];
        return !empty($matches[1]);
    }

}

?>
