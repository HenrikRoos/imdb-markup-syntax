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
 * @license   http://opensource.org/licenses/gpl-3.0.html GPL-3.0
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
 * @license   http://opensource.org/licenses/gpl-3.0.html GPL-3.0
 * @link      https://github.com/HenrikRoos/imdb-markup-syntax imdb-markup-syntax
 */
class Get_DataTest extends PHPUnit_Framework_TestCase
{

    /**
     * Main use case get a movie data, no error
     * 
     * @covers IMDb_Markup_Syntax\Movie_Datasource::__construct
     * @covers IMDb_Markup_Syntax\Movie_Datasource::setRequest
     * @covers IMDb_Markup_Syntax\Movie_Datasource::getData
     * @covers IMDb_Markup_Syntax\Movie_Datasource::toDataClass
     * @covers IMDb_Markup_Syntax\Movie_Datasource::fetchResponse
     * 
     * @return void
     */
    public function testMovie()
    {
        //Given
        $tconst = $GLOBALS["movieDatasourceData"]["movie"];
        $expected = "Fight Club";

        //When
        $imdb = new Movie_Datasource($tconst);
        $data = $imdb->getData();
        $actual = $data->title;

        //Then
        $this->assertSame($expected, $actual);
    }

    /**
     * Main use case get a tv serie data, no error
     * 
     * @covers IMDb_Markup_Syntax\Movie_Datasource::__construct
     * @covers IMDb_Markup_Syntax\Movie_Datasource::setRequest
     * @covers IMDb_Markup_Syntax\Movie_Datasource::getData
     * @covers IMDb_Markup_Syntax\Movie_Datasource::toDataClass
     * @covers IMDb_Markup_Syntax\Movie_Datasource::fetchResponse
     * 
     * @return void
     */
    public function testTvSerie()
    {
        //Given
        $tconst = $GLOBALS["movieDatasourceData"]["tvserie"];
        $expected = "Boston Legal";

        //When
        $imdb = new Movie_Datasource($tconst);
        $data = $imdb->getData();
        $actual = $data->title;

        //Then
        $this->assertSame($expected, $actual);
    }

    /**
     * Main use case get a video game data, no error
     * 
     * @covers IMDb_Markup_Syntax\Movie_Datasource::__construct
     * @covers IMDb_Markup_Syntax\Movie_Datasource::setRequest
     * @covers IMDb_Markup_Syntax\Movie_Datasource::getData
     * @covers IMDb_Markup_Syntax\Movie_Datasource::toDataClass
     * @covers IMDb_Markup_Syntax\Movie_Datasource::fetchResponse
     * 
     * @return void
     */
    public function testVideoGame()
    {
        //Given
        $tconst = $GLOBALS["movieDatasourceData"]["videogame"];
        $expected = "Lego Pirates of the Caribbean: The Video Game";

        //When
        $imdb = new Movie_Datasource($tconst);
        $data = $imdb->getData();
        $actual = $data->title;

        //Then
        $this->assertSame($expected, $actual);
    }

    /**
     * Negativ test, No data for this title tconst. HTTP 404
     * 
     * @expectedException        IMDb_Markup_Syntax\Exceptions\Runtime_Exception
     * @expectedExceptionMessage No data for this title id
     * @expectedExceptionCode    404
     * 
     * @covers IMDb_Markup_Syntax\Movie_Datasource::__construct
     * @covers IMDb_Markup_Syntax\Movie_Datasource::setRequest
     * @covers IMDb_Markup_Syntax\Movie_Datasource::getData
     * @covers IMDb_Markup_Syntax\Movie_Datasource::toDataClass
     * @covers IMDb_Markup_Syntax\Movie_Datasource::fetchResponse
     * @covers IMDb_Markup_Syntax\Exceptions\Runtime_Exception
     * 
     * @return void
     */
    public function testNoData()
    {
        //Given
        $tconst = $GLOBALS["movieDatasourceData"]["nodata"];

        //When
        $imdb = new Movie_Datasource($tconst);
        $imdb->getData();
    }

    /**
     * Positive alternative test. Different release date in different countries
     * 
     * @covers IMDb_Markup_Syntax\Movie_Datasource::__construct
     * @covers IMDb_Markup_Syntax\Movie_Datasource::setRequest
     * @covers IMDb_Markup_Syntax\Movie_Datasource::getData
     * @covers IMDb_Markup_Syntax\Movie_Datasource::toDataClass
     * @covers IMDb_Markup_Syntax\Movie_Datasource::fetchResponse
     * @covers IMDb_Markup_Syntax\Exceptions\Runtime_Exception
     * 
     * @return void
     */
    public function testAlternativeLocale()
    {
        //Given
        $tconst = $GLOBALS["movieDatasourceData"]["tvserie"];
        $locale = "sv_SE";
        $expected = "2004-10-03";
        $expected_se = "2005-03-21";

        //When
        $imdb = new Movie_Datasource($tconst);
        $imdb_se = new Movie_Datasource($tconst, $locale);

        $data = $imdb->getData();
        $data_se = $imdb_se->getData();

        $actual = $data->release_date->normal;
        $actual_se = $data_se->release_date->normal;

        //Then
        $this->assertSame($expected, $actual);
        $this->assertSame($expected_se, $actual_se);
    }

}

?>
