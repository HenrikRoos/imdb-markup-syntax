<?php
/**
 * Testclass to Markup_DataSuite for method getPosterremote in Markup_Data class
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
 * Testclass to Markup_DataSuite for method getPosterremote in Markup_Data class
 *
 * @category  Testable
 * @package   Test
 * @author    Henrik Roos <henrik.roos@afternoon.se>
 * @copyright 2013 Henrik Roos
 * @license   http://opensource.org/licenses/gpl-3.0.html GPL-3.0
 * @link      https://github.com/HenrikRoos/imdb-markup-syntax imdb-markup-syntax
 */
class Get_PosterremoteTest extends PHPUnit_Framework_TestCase
{

    /** @var string positive testdata */
    public $testdataPositive;

    /**
     * Positive test: Get data sucessful
     *
     * @covers Markup_Data::__construct
     * @covers Markup_Data::getPosterremote
     * @covers Markup_Data::getValueValue
     *
     * @return void
     */
    public function testPositive()
    {
        //Given
        $imdb = new Movie_Datasource($this->testdataPositive);
        $data = $imdb->getData();
        $expected = '<a href="http://www.imdb.com/title/tt0137523/">'
            . '<img src="http://ia.media-imdb.com/images/M/MV5BMjIwNTYzMzE1M15BMl'
            . '5BanBnXkFtZTcwOTE5Mzg3OA@@._V1_.jpg" alt="Fight Club" '
            . 'width="200" class="alignnone"/></a>';

        //When
        $mdata = new Markup_Data($data);
        $actual = $mdata->getPosterremote();

        //Then
        $this->assertSame($expected, $actual);
    }

    /**
     * Positive test: Get data sucessful
     *
     * @covers Markup_Data::__construct
     * @covers Markup_Data::getPosterremote_nolink
     * @covers Markup_Data::getValueValue
     *
     * @return void
     */
    public function testPositive_nolink()
    {
        //Given
        $imdb = new Movie_Datasource($this->testdataPositive);
        $data = $imdb->getData();
        $expected = '<img src="http://ia.media-imdb.com/images/M/MV5BMjIwNTYzMzE1M15BMl'
            . '5BanBnXkFtZTcwOTE5Mzg3OA@@._V1_.jpg" alt="Fight Club" '
            . 'width="200" class="alignnone"/>';

        //When
        $mdata = new Markup_Data($data);
        $actual = $mdata->getPosterremote_nolink();

        //Then
        $this->assertSame($expected, $actual);
    }

    /**
     * Negative test: No data is set
     *
     * @covers Markup_Data::__construct
     * @covers Markup_Data::getPosterremote
     * @covers Markup_Data::getValueValue
     *
     * @return void
     */
    public function testNotSet()
    {
        //Given
        $imdb = new Movie_Datasource($this->testdataPositive);
        $data = $imdb->getData();
        unset($data->image);
        $expected = false;

        //When
        $mdata = new Markup_Data($data);
        $actual = $mdata->getPosterremote();

        //Then
        $this->assertSame($expected, $actual);
    }

    /**
     * Negative test: Data is empty
     *
     * @covers Markup_Data::__construct
     * @covers Markup_Data::getPosterremote
     * @covers Markup_Data::getValueValue
     *
     * @return void
     */
    public function testEmpty()
    {
        //Given
        $imdb = new Movie_Datasource($this->testdataPositive);
        $data = $imdb->getData();
        $data->image = '';
        $expected = false;

        //When
        $mdata = new Markup_Data($data);
        $actual = $mdata->getPosterremote();

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
