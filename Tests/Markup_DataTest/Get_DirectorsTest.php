<?php

/**
 * Testclass to Markup_DataSuite for method getDirectors in Markup_Data class
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

namespace IMDb_Markup_Syntax\Markup_DataTest;

use IMDb_Markup_Syntax\Markup_Data;
use IMDb_Markup_Syntax\Movie_Datasource;
use PHPUnit_Framework_TestCase;

require_once dirname(__FILE__) . '/../../Markup_Data.php';
require_once dirname(__FILE__) . '/../../Movie_Datasource.php';
require_once 'PHPUnit/Autoload.php';

/**
 * Testclass to Markup_DataSuite for method getDirectors in Markup_Data class
 *
 * @category  Testable
 * @package   Test
 * @author    Henrik Roos <henrik.roos@afternoon.se>
 * @copyright 2013 Henrik Roos
 * @license   http://opensource.org/licenses/gpl-3.0.html GPL-3.0
 * @link      https://github.com/HenrikRoos/imdb-markup-syntax imdb-markup-syntax
 */
class Get_DirectorsTest extends PHPUnit_Framework_TestCase
{

    /** @var string positive testdata */
    public $testdataPositive;

    /**
     * Positive test: Get data sucessful
     *
     * @covers IMDb_Markup_Syntax\Markup_Data::__construct
     * @covers IMDb_Markup_Syntax\Markup_Data::getDirectors
     * @covers IMDb_Markup_Syntax\Markup_Data::toSummaryString
     * @covers IMDb_Markup_Syntax\Markup_Data::toPersonsList
     * @covers IMDb_Markup_Syntax\Markup_Data::toPersonString
     * @covers IMDb_Markup_Syntax\Markup_Data::toNameString
     * @covers IMDb_Markup_Syntax\Markup_Data::isNotEmpty
     *
     * @return void
     */
    public function testPositive()
    {
        //Given
        $imdb = new Movie_Datasource($this->testdataPositive);
        $data = $imdb->getData();
        $expected = '<a href="http://www.imdb.com/name/nm0000399">David Fincher</a>';

        //When
        $mdata = new Markup_Data($data);
        $actual = $mdata->getDirectors();

        //Then
        $this->assertSame($expected, $actual);
    }

    /**
     * Negative test: No data is set
     *
     * @covers IMDb_Markup_Syntax\Markup_Data::__construct
     * @covers IMDb_Markup_Syntax\Markup_Data::getDirectors
     * @covers IMDb_Markup_Syntax\Markup_Data::toSummaryString
     * @covers IMDb_Markup_Syntax\Markup_Data::toPersonsList
     * @covers IMDb_Markup_Syntax\Markup_Data::toPersonString
     * @covers IMDb_Markup_Syntax\Markup_Data::toNameString
     * @covers IMDb_Markup_Syntax\Markup_Data::isNotEmpty
     *
     * @return void
     */
    public function testNotSet()
    {
        //Given
        $imdb = new Movie_Datasource($this->testdataPositive);
        $data = $imdb->getData();
        unset($data->directors_summary);
        $expected = false;

        //When
        $mdata = new Markup_Data($data);
        $actual = $mdata->getDirectors();

        //Then
        $this->assertSame($expected, $actual);
    }

    /**
     * Negative test: Data is empty
     *
     * @covers IMDb_Markup_Syntax\Markup_Data::__construct
     * @covers IMDb_Markup_Syntax\Markup_Data::getTconst
     * @covers IMDb_Markup_Syntax\Markup_Data::toSummaryString
     * @covers IMDb_Markup_Syntax\Markup_Data::toPersonsList
     * @covers IMDb_Markup_Syntax\Markup_Data::toPersonString
     * @covers IMDb_Markup_Syntax\Markup_Data::toNameString
     * @covers IMDb_Markup_Syntax\Markup_Data::isNotEmpty
     *
     * @return void
     */
    public function testEmpty()
    {
        //Given
        $imdb = new Movie_Datasource($this->testdataPositive);
        $data = $imdb->getData();
        $data->directors_summary = '';
        $expected = false;

        //When
        $mdata = new Markup_Data($data);
        $actual = $mdata->getDirectors();

        //Then
        $this->assertSame($expected, $actual);
    }

    /**
     * Set up local testdata
     *
     * @return void
     */
    protected function setUp()
    {
        $this->testdataPositive = 'tt0137523';
    }

}

?>
