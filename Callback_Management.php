<?php

/**
 * Collections and management of callbacks from plugin-filter and plugin-actions
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

use IMDb_Markup_Syntax\Exceptions\PCRE_Exception;

require_once dirname(__FILE__) . "/Tag_Processing.php";
require_once dirname(__FILE__) . "/Exceptions/PCRE_Exception.php";

/**
 * Collections and management of callbacks from plugin-filter and plugin-actions
 * 
 * @category  Runnable
 * @package   Core
 * @author    Henrik Roos <henrik.roos@afternoon.se>
 * @copyright 2013 Henrik Roos
 * @license   http://opensource.org/licenses/gpl-3.0.html GPL-3.0
 * @link      https://github.com/HenrikRoos/imdb-markup-syntax imdb-markup-syntax
 */
class Callback_Management
{

    /** @var string Localization for data, standard RFC 4646 */
    public $locale;

    /**
     * Create an intsans and set localization for data
     * 
     * @param string $locale Localization for data, standard RFC 4646
     */
    public function __construct($locale = "")
    {
        $this->locale = $locale;
    }

    /**
     * Replace **[imdb:id(ttxxxxxxx)]** and **[imdb:xxx]** with imdb data
     * Call by *wp_insert_post_data* filter hook.
     * 
     * @param array $data    Sanitized post data
     * @param array $postarr Raw post data.
     * 
     * @return array Update $data
     */
    public function filterImdbTags($data, $postarr)
    {
        $post_id = $postarr["ID"];

        $content = $data["post_content"];
        $data["post_content"] = $this->tagsReplace($content, "imdb", $post_id);

        $title = $data["post_title"];
        $data["post_title"] = $this->tagsReplace($title, "imdb", $post_id);

        return $data;
    }

    /**
     * Replace **[imdblive:id(ttxxxxxxx)]** and **[imdblive:xxx]** with imdb data
     * Call by *the_content* or *the_title* filter hook
     * 
     * @param string $content content widh tags
     * 
     * @return string content with replaced tags
     */
    public function liveFilterImdbTags($content)
    {
        return $this->tagsReplace($content, "imdblive");
    }

    /**
     * Seek for tags with multi prefix syntax e.g imdb-a, imdb-b, ... imdb-z.
     * **SubPrefix Syntax: xxx-[a-z]**
     * 
     * @param string $content Content widh tags
     * @param string $prefix  Starting tagname
     * 
     * @since 2.0
     * 
     * @return array list of all sub prefix
     */
    public function getSubPrefixHints($content, $prefix)
    {
        $match = array();
        $pattern = "/\[({$prefix}(-[a-z])?):/i";
        $isOk = @preg_match_all($pattern, $content, $match);

        if ($isOk === false) {
            throw new PCRE_Exception();
        }
        return $match[1];
    }

    /**
     * Replace **[xxx:id(ttyyyy)]** and **[xxx:yyy]** with imdb data
     * 
     * @param string $content Content widh tags
     * @param string $prefix  Starting tagname
     * @param int    $post_id Current post_id use by poster
     * 
     * @return string content with replaced tags
     */
    protected function tagsReplace($content, $prefix, $post_id = 0)
    {
        $imdb = new Tag_Processing($content, $post_id);
        $imdb->locale = $this->locale;
        $imdb->prefix = $prefix;
        $imdb->tagsReplace();
        return $imdb->getReplacementContent();
    }

}

?>
