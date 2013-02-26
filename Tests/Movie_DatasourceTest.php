<?php

require_once 'PHPUnit/Autoload.php';
require_once dirname(__FILE__) . '/../class-movie-datasource.php';

/**
 * Testclass (PHPUnit) test for Movie_Datasource class
 */
class Movie_DatasourceTest extends PHPUnit_Framework_TestCase {

    /**
     * Main use case get a movie data no error
     * @covers Movie_Datasource::getMovie
     */
    public function testGetMoviePositive() {
        $obj = new Movie_Datasource("tt0137523");
        $response = $obj->getMovie();
        $this->assertArrayHasKey("id", $response);
    }

}
