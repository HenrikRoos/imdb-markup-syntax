<?php

/**
 * Find and replace imdb tags to movie data from IMDb
 * @package imdb-markup-syntax
 * @author Henrik Roos <henrik at afternoon.se>
 */
class IMDb_Tag_Processing {

    /** @var string regular expression for find id */
    public $id_pattern = "/\[imdb\:id\(([a-z0-9]+)\)\]/i";

    /** @var string regular expression for all imdb tags */
    public $imdb_tags_pattern = "/\[imdb\:([a-z0-9]+)\]/i";

    /**
     * Original content before this filter processing
     * @var string
     */
    public $original_content;

    /**
     * Id on current movie. ex http://www.imdb.com/title/tt0137523/ ->
     * id = tt0137523
     * Syntax: [imdb:id(xxxx)] t ex [imdb:id(tt0137523)]
     * @var string
     */
    public $id;

    /**
     * IMDb:s data object
     * @var IMDb object
     */
    public $data;

    /**
     * All imdb tags in current content
     * @var array of imdb tags
     */
    public $imdb_tags = array();

    /**
     * Create an object
     * @param type $original_content
     */
    function __construct($original_content) {
        $this->original_content = $original_content;
    }

    /**
     * Find and store it in $id. ex: "ksdkasd [imdb:id(tt0137523)] dmf,sdm" -> id = tt0137523
     * @return boolean FALSE if no match TRUE if find a id
     * @throws PCRE_Exception if a PCRE error occurs or patten compilation failed
     */
    function find_id() {
        $matches = array();
        if (@preg_match($this->id_pattern, $this->original_content, $matches) === FALSE) {
            throw new PCRE_Exception();
        }
        if (empty($matches)) {
            $this->id = null;
            return FALSE;
        }
        $this->id = $matches[1];
        return TRUE;
    }

    /**
     * Find and store all [imdb:xxx] tags in imdb_tags array
     * @return boolean FALSE if no match TRUE if find 
     * @throws PCRE_Exception if a PCRE error occurs or patten compilation failed
     */
    function find_imdb_tags() {
        $matches = array();
        if (@preg_match_all($this->imdb_tags_pattern, $this->original_content, $matches) === FALSE) {
            throw new PCRE_Exception();
        }
        $this->imdb_tags = $matches[1];
        return !empty($matches[1]);
    }

}

?>
