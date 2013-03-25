<?php

/**
 * Sub testclass to Movie_DatasourceTest for method getData in Movie_Datasource class
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

namespace IMDb_Markup_Syntax\Movie_DatasourceTest;

use IMDb_Markup_Syntax\Movie_Datasource;
use PHPUnit_Framework_TestCase;

require_once dirname(__FILE__) . '/../../Movie_Datasource.php';
require_once 'PHPUnit/Autoload.php';

/**
 * Sub testclass to Movie_DatasourceTest for method getData in Movie_Datasource class
 * 
 * @category  Testable
 * @package   Test
 * @author    Henrik Roos <henrik.roos@afternoon.se>
 * @copyright 2013 Henrik Roos
 * @license   https://github.com/HenrikRoos/imdb-markup-syntax/blob/master/imdb-markup-syntax.php GPL2
 * @link      https://github.com/HenrikRoos/imdb-markup-syntax imdb-markup-syntax
 */
class Get_DataTest extends PHPUnit_Framework_TestCase
{

    /**
     * Main use case get a movie data, no error
     * 
     * @covers IMDb_Markup_Syntax\Movie_Datasource::__construct
     * @covers IMDb_Markup_Syntax\Movie_Datasource::getData
     * 
     * @return void
     */
    public function testMovie()
    {
        //Given
        $imdb = new Movie_Datasource($GLOBALS["movieDatasourceData"]["movie"]);
        //When
        $movie = $imdb->getData();
        //Then
        $this->assertSame("Fight Club", $movie->title);
    }

    /**
     * Main use case get a tv serie data, no error
     * 
     * @covers IMDb_Markup_Syntax\Movie_Datasource::__construct
     * @covers IMDb_Markup_Syntax\Movie_Datasource::getData
     * 
     * @return void
     */
    public function testTvSerie()
    {
        //Given
        $imdb = new Movie_Datasource($GLOBALS["movieDatasourceData"]["tvserie"]);
        //When
        $movie = $imdb->getData();
        //Then
        $this->assertSame("Boston Legal", $movie->title);
    }

    /**
     * Main use case get a video game data, no error
     * 
     * @covers IMDb_Markup_Syntax\Movie_Datasource::__construct
     * @covers IMDb_Markup_Syntax\Movie_Datasource::getData
     * 
     * @return void
     */
    public function testVideoGame()
    {
        //Given
        $imdb = new Movie_Datasource($GLOBALS["movieDatasourceData"]["videogame"]);
        //When
        $movie = $imdb->getData();
        //Then
        $this->assertSame(
            "Lego Pirates of the Caribbean: The Video Game", $movie->title
        );
    }

    /**
     * Negativ test, No data for this title tconst. HTTP 404
     * 
     * @expectedException        IMDb_Markup_Syntax\Exceptions\Runtime_Exception
     * @expectedExceptionMessage No data for this title id
     * @expectedExceptionCode    404
     * 
     * @covers IMDb_Markup_Syntax\Movie_Datasource::__construct
     * @covers IMDb_Markup_Syntax\Movie_Datasource::getData
     * @covers IMDb_Markup_Syntax\Exceptions\Runtime_Exception
     * 
     * @return void
     */
    public function testNoData()
    {
        //Given
        $imdb = new Movie_Datasource($GLOBALS["movieDatasourceData"]["nodata"]);
        //When
        $imdb->getData();
    }

}

?>
