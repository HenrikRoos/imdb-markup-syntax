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
 * @license   https://github.com/HenrikRoos/imdb-markup-syntax/blob/master/imdb-markup-syntax.php GPL2
 * @link      https://github.com/HenrikRoos/imdb-markup-syntax imdb-markup-syntax
 */

namespace IMDb_Markup_Syntax\Tag_ProcessingTest;

use IMDb_Markup_Syntax\Tag_Processing;

require_once dirname(__FILE__) . '/../../Tag_Processing.php';

/**
 * Help class for test protected methods in Tag_Processing class
 * Usage this class insted of Tag_Processing
 * <code>$obj = new Tag_Processing($original_content, $locale);</code>
 * <b>Usage insted</b>
 * <code>$obj = new Tag_ProcessingHelp($original_content, $locale);</code>
 * 
 * @category  Testable
 * @package   Test
 * @author    Henrik Roos <henrik.roos@afternoon.se>
 * @copyright 2013 Henrik Roos
 * @license   https://github.com/HenrikRoos/imdb-markup-syntax/blob/master/imdb-markup-syntax.php GPL2
 * @link      https://github.com/HenrikRoos/imdb-markup-syntax imdb-markup-syntax
 */
class Tag_Processing_Help extends Tag_Processing
{

    /**
     * Find imdb data for the tag name
     * 
     * @param string $tag Name of tag to get data for
     * 
     * @return string|boolean Replacement text for the tag name
     */
    public function toDataString($tag)
    {
        return parent::toDataString($tag);
    }

}

?>
