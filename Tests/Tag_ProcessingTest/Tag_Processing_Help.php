<?php

/**
 * Help class for test protected methods in Tag_Processing class
 *
 * PHP version 5
 *
 * @category  Testable
 * @package   Test
 * @author    Henrik Roos <henrik.roos@afternoon.se>
 * @copyright 2013 Henrik Roos
 * @license   http://opensource.org/licenses/gpl-3.0.html GPL-3.0
 * @link      https://github.com/HenrikRoos/imdb-markup-syntax imdb-markup-syntax
 */

namespace IMDb_Markup_Syntax\Tag_ProcessingTest;

use IMDb_Markup_Syntax\Exceptions\PCRE_Exception;
use IMDb_Markup_Syntax\Tag_Processing;

require_once dirname(__FILE__) . '/../../Tag_Processing.php';

/**
 * Help class for test protected methods in Tag_Processing class
 * Usage this class insted of Tag_Processing
 * <code>
 * $obj = new Tag_Processing($original_content, $locale, $timeout);
 * </code>
 * **Usage insted**
 * <code>
 * $obj = new Tag_Processing_Help($original_content, $locale, $timeout);
 * </code>
 *
 * @category  Testable
 * @package   Test
 * @author    Henrik Roos <henrik.roos@afternoon.se>
 * @copyright 2013 Henrik Roos
 * @license   http://opensource.org/licenses/gpl-3.0.html GPL-3.0
 * @link      https://github.com/HenrikRoos/imdb-markup-syntax imdb-markup-syntax
 */
class Tag_Processing_Help extends Tag_Processing
{

    /**
     * Public override
     *
     * @var string Regular expression for id. If this is set when override
     * **[{$this->prefix}:{$this->id_pattern}]** with this.
     * e.g. <i>/\[my_id\:[a-z]+\]/i"</i>
     */
    public $custom_id_pattern = '';
    /**
     * Public override
     *
     * @var string Regular expression for imdb tags. If this is set when override
     * **[{$this->prefix}:{$this->imdb_tags_pattern}]** with this.
     * e.g. <i>/\[my_prefix\:[a-z]+\]/i"</i>
     */
    public $custom_tags_pattern = '';
    /**
     * Public override
     *
     * @var array Id on current movie.
     */
    public $tconst_tag = array();
    /**
     * Public override
     *
     * @var array Multi-array of imdb tags in PREG_SET_ORDER. All imdb tags in
     * current content
     */
    public $imdb_tags = array();

    /**
     * Public override
     *
     * @param string $tag Name of tag to get data for
     *
     * @return string|boolean Replacement text for the tag name
     */
    public function toDataString($tag)
    {
        return parent::toDataString($tag);
    }

    /**
     * Public override
     *
     * @return boolean False if no match true if find a id
     *
     * @throws PCRE_Exception If a PCRE error occurs or patten compilation failed
     */
    public function findId()
    {
        return parent::findId();
    }

    /**
     * Public override
     *
     * @return boolean False if no match true if find
     *
     * @throws PCRE_Exception If a PCRE error occurs or patten compilation failed
     */
    public function findImdbTags()
    {
        return parent::findImdbTags();
    }

}

?>
