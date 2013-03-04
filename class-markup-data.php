<?php

/**
 * PhpDoc: Page-level DocBlock
 * @package imdb-markup-syntax
 */

namespace IMDb_Markup_Syntax;

use stdClass;

/**
 * Markup data tags from INDb data result. Most popular tag in imdb result has a function in this class.
 * @author Henrik Roos <henrik at afternoon.se>
 * @package imdb-markup-syntax
 */
class Markup_Data {

    /** @var stdClass imdb data result */
    private $data;

    /**
     * Create a instans of this class
     * @param stdClass $data IMDb data json class
     */
    public function __construct(stdClass $data) {
        $this->data = $data;
    }

    /**
     * Tabbad one-linaer of all writers in current movie
     * @return string|boolean list of writers as a string
     */
    public function writers_summary() {
        if (isset($this->data->writers_summary) && is_array($this->data->writers_summary)) {
            $named = array_filter($this->data->writers_summary, function($writer) {
                        return isset($writer->name->name);
                    });
            $named_summary = array_map(array($this, "writer_summary"), $named);
            return implode(", ", $named_summary);
        }
        return FALSE;
    }

    /**
     * INPUT:
     * stdClass Object
     * (
     *     [name] => stdClass Object
     *     (
     *        [nconst] => nm0254645
     *        [name] => Ted Elliott
     *     )
     *     [attr] => (characters)
     * )
     * OUTPUT: <a href="http://www.imdb.com/name/nm0254645">Ted Elliott</a> (characters)
     * @param stdClass $writer array item from writers_summary
     * @return string ex <a href="http://www.imdb.com/name/nm0254645">Ted Elliott</a> (characters)
     */
    protected function writer_summary(stdClass $writer) {
        $res = isset($writer->name->nconst) ? "<a href=\"http://www.imdb.com/name/{$writer->name->nconst}\">{$writer->name->name}</a>" : $writer->name->name;
        if (isset($writer->attr)) {
            $res .= " " . $writer->attr;
        }
        return $res;
    }

}

?>
