<?php

class IMDb_Tag_Processing {

    /**
     * @var string regular expression for find id
     */
    public $id_pattern = "/\[imdb\:id\(([a-z0-9]+)\)\]/i";

    /**
     * Original meddelandet som det ser ut innan denna prosess påbörjats
     * @var string
     */
    public $original_content;

    /**
     * Id på den filem som data återvisar. t ex http://www.imdb.com/title/tt0137523/ ->
     * id = tt0137523
     * Syntax: [imdb:id(xxxx)] t ex [imdb:id(tt0137523)]
     * @var string
     */
    public $id;

    /**
     * Filmens data från IMDb:s databas
     * @var IMDb
     */
    public $data;

    /**
     * Alla uppmerkarade imdbtaggar.
     * @var array of imdb tags
     */
    public $imdb_tags = array();

    /**
     * Skapa en instans av klassen
     * @param type $original_content
     */
    function __construct($original_content) {
        $this->original_content = $original_content;
    }

    /**
     * Letar efer id och sätter det i $id. t ex: "ksdkasd [imdb:id(tt0137523)] dmf,sdm" ->
     * id = tt0137523
     * @return boolean FALSE if no match TRUE if find a id
     * @throws PCRE_Exception if a PCRE error occurs
     */
    function find_id() {
        $matches = array();
        if (@preg_match($this->id_pattern, $this->original_content, $matches) === FALSE) {
            throw new PCRE_Exception();
        }
        if (empty($matches)) {
            return FALSE;
        }
        $this->id = $matches[1];
        return TRUE;
    }

}

?>
