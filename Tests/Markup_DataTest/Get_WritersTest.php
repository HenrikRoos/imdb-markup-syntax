<?php
/**
 * Testclass to Markup_DataSuite for method getWriters in Markup_Data class
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
 * Testclass to Markup_DataSuite for method getWriters in Markup_Data class
 *
 * @category  Testable
 * @package   Test
 * @author    Henrik Roos <henrik.roos@afternoon.se>
 * @copyright 2013 Henrik Roos
 * @license   http://opensource.org/licenses/gpl-3.0.html GPL-3.0
 * @link      https://github.com/HenrikRoos/imdb-markup-syntax imdb-markup-syntax
 */
class Get_WritersTest extends PHPUnit_Framework_TestCase
{

    /** @var string positive testdata */
    public $testdataPositive;

    /**
     * Positive test where movie has one writer and no attribute like (nocel)
     *
     * @covers Markup_Data::__construct
     * @covers Markup_Data::getWriters
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
        $data = new Markup_Data($imdb->getData());
        $expected = '<a href="http://www.imdb.com/name/nm3503431">Bryan Litt</a>';

        //When
        $actual = $data->getWriters();

        //Then
        $this->assertSame($expected, $actual);
    }

    /**
     * Positive test where movie has two writers
     *
     * @covers Markup_Data::__construct
     * @covers Markup_Data::getWriters
     * @covers Markup_Data::toSummaryString
     * @covers Markup_Data::toPersonsList
     * @covers Markup_Data::toPersonString
     * @covers Markup_Data::toNameString
     * @covers Markup_Data::isNotEmpty
     *
     * @return void
     */
    public function testTowPositive()
    {
        //Given
        $imdb = new Movie_Datasource('tt0137523');
        $data = new Markup_Data($imdb->getData());
        $expected = '<a href="http://www.imdb.com/name/nm0657333">Chuck Palahniuk'
            . '</a> (novel), <a href="http://www.imdb.com/name/nm0880243">Jim Uhls'
            . '</a> (screenplay)';

        //When
        $actual = $data->getWriters();

        //Then
        $this->assertSame($expected, $actual);
    }

    /**
     * Alternative test where movie has no writers
     *
     * @covers Markup_Data::__construct
     * @covers Markup_Data::getWriters
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
        $imdb = new Movie_Datasource('tt1129398');
        $data = new Markup_Data($imdb->getData());
        $expected = false;

        //When
        $actual = $data->getWriters();

        //Then
        $this->assertSame($expected, $actual);
    }

    /**
     * Negative test: getWriters is empty
     *
     * @covers Markup_Data::__construct
     * @covers Markup_Data::getWriters
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
        $data->writers_summary = array();
        $expected = false;

        //When
        $mdata = new Markup_Data($data);
        $actual = $mdata->getWriters();

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
        $this->testdataPositive = 'tt1564043';
    }

}
