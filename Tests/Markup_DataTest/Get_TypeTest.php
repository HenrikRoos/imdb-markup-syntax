<?php
/**
 * Testclass to Markup_DataSuite for method getType in Markup_Data class
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
 * Testclass to Markup_DataSuite for method getType in Markup_Data class
 *
 * @category  Testable
 * @package   Test
 * @author    Henrik Roos <henrik.roos@afternoon.se>
 * @copyright 2013 Henrik Roos
 * @license   http://opensource.org/licenses/gpl-3.0.html GPL-3.0
 * @link      https://github.com/HenrikRoos/imdb-markup-syntax imdb-markup-syntax
 */
class Get_TypeTest extends PHPUnit_Framework_TestCase
{

    /** @var string positive testdata */
    public $testdataPositive;

    /**
     * Positive test: Get data sucessful for a feature
     *
     * @covers Markup_Data::__construct
     * @covers Markup_Data::getType
     * @covers Markup_Data::getValue
     *
     * @return void
     */
    public function testFeaturePositive()
    {
        //Given
        $imdb = new Movie_Datasource('tt0468569');
        $data = $imdb->getData();
        $expected = 'feature';

        //When
        $mdata = new Markup_Data($data);
        $actual = $mdata->getType();

        //Then
        $this->assertSame($expected, $actual);
    }

    /**
     * Positive test: Get data sucessful for a tv-serie
     *
     * @covers Markup_Data::__construct
     * @covers Markup_Data::getType
     * @covers Markup_Data::getValue
     *
     * @return void
     */
    public function testTVSeriePositive()
    {
        //Given
        $imdb = new Movie_Datasource('tt0402711');
        $data = $imdb->getData();
        $expected = 'tv_series';

        //When
        $mdata = new Markup_Data($data);
        $actual = $mdata->getType();

        //Then
        $this->assertSame($expected, $actual);
    }

    /**
     * Negative test: No data is set
     *
     * @covers Markup_Data::__construct
     * @covers Markup_Data::getType
     * @covers Markup_Data::getValue
     *
     * @return void
     */
    public function testNotSet()
    {
        //Given
        $imdb = new Movie_Datasource($this->testdataPositive);
        $data = $imdb->getData();
        unset($data->type);
        $expected = false;

        //When
        $mdata = new Markup_Data($data);
        $actual = $mdata->getType();

        //Then
        $this->assertSame($expected, $actual);
    }

    /**
     * Negative test: Data is empty
     *
     * @covers Markup_Data::__construct
     * @covers Markup_Data::getType
     * @covers Markup_Data::getValue
     *
     * @return void
     */
    public function testEmpty()
    {
        //Given
        $imdb = new Movie_Datasource($this->testdataPositive);
        $data = $imdb->getData();
        $data->type = "";
        $expected = false;

        //When
        $mdata = new Markup_Data($data);
        $actual = $mdata->getType();

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
