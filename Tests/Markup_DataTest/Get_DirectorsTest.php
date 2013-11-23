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
     * @covers Markup_Data::__construct
     * @covers Markup_Data::getDirectors
     * @covers Markup_Data::toSummaryString
     * @covers Markup_Data::toPersonsList
     * @covers Markup_Data::toPersonString
     * @covers Markup_Data::toNameString
     * @covers Markup_Data::isNotEmpty
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
     * @covers Markup_Data::__construct
     * @covers Markup_Data::getDirectors
     * @covers Markup_Data::toSummaryString
     * @covers Markup_Data::toPersonsList
     * @covers Markup_Data::toPersonString
     * @covers Markup_Data::toNameString
     * @covers Markup_Data::isNotEmpty
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
     * @covers Markup_Data::__construct
     * @covers Markup_Data::getTconst
     * @covers Markup_Data::toSummaryString
     * @covers Markup_Data::toPersonsList
     * @covers Markup_Data::toPersonString
     * @covers Markup_Data::toNameString
     * @covers Markup_Data::isNotEmpty
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
