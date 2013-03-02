<?php

/**
 * PhpDoc: Page-level DocBlock
 * @package imdb-markup-syntax
 */

namespace IMDb_Markup_Syntax;

use IMDb_Markup_Syntax\Exceptions\Error_Runtime_Exception;
use stdClass;

require_once dirname(__FILE__) . '/../Exceptions/class-error-runtime-exception.php';

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
        if (isEmpty($data)) {
            throw new Error_Runtime_Exception("data is empty");
        }
        $this->data = $data;
    }

    /**
     * Tabbad one-linaer of all writers in current movie
     * @return string|boolean list of writers as a string
     */
    public function writers_summary() {
        if (is_array($this->data->writers_summary)) {
            $a = array_map("writer_summary", $this->data->writers_summary);
            return implode(", ", $a);
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
     * @return string|boolean string if $writer->name is a object else false
     */
    protected function writer_summary(stdClass $writer) {
        if (is_object($writer->name)) {
            return "<a href=\"http://www.imdb.com/name/{$writer->name->nconst}\">{$writer->name->name}</a> {$writer->name->attr}";
        }
        return FALSE;
    }

}

?>
