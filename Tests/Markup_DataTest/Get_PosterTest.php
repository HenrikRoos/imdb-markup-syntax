<?php
/**
 * Testclass to Markup_DataSuite for method getPoster in Markup_Data class
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
 * Testclass to Markup_DataSuite for method getPoster in Markup_Data class
 *
 * @category  Testable
 * @package   Test
 * @author    Henrik Roos <henrik.roos@afternoon.se>
 * @copyright 2013 Henrik Roos
 * @license   http://opensource.org/licenses/gpl-3.0.html GPL-3.0
 * @link      https://github.com/HenrikRoos/imdb-markup-syntax imdb-markup-syntax
 */
class Get_PosterTest extends PHPUnit_Framework_TestCase
{

    /** @var string positive testdata */
    public $testdataPositive = 'tt0468569';

    /**
     * Positive test: Get data sucessful
     *
     * @covers Markup_Data::__construct
     * @covers Markup_Data::getPoster
     * @covers Markup_Data::getValueValue
     * @covers Media_Library_Handler
     *
     * @return void
     */
    public function testPositive()
    {
        //Given
        $imdb = new Movie_Datasource($this->testdataPositive);
        $data = $imdb->getData();
        $post = array(
            'post_title'    => 'Get_PosterTest',
            'post_content'  => 'This is my Get_PosterTest post.',
            'post_category' => array(8, 39)
        );

        //When
        $post_id = wp_insert_post($post, true);
        $mdata = new Markup_Data($data, $post_id);
        $title = $mdata->getValue('title');
        $pattern = '/\<a href="http:\/\/www\.imdb\.com\/title\/'
            . $this->testdataPositive . '\/" '
            . 'title="' . $title . '"\>\<img width="20\d" height="300" '
            . 'src="http:\/\/.+\/uploads'
            . '\/201\d\/\d\d\/'
            . $this->testdataPositive . '\d*-20\dx300\.jpg" class="alignnone '
            . 'size\-medium wp\-post\-image" alt="' . $title . '".+\/\>\<\/a\>/';
        $actual = $mdata->getPoster();
        $delete = wp_delete_post($post_id, true);

        //Then
        $this->assertRegExp($pattern, $actual);
        $this->assertTrue($delete !== false);
    }

    /**
     * Negative test: No image
     *
     * @covers Markup_Data::__construct
     * @covers Markup_Data::getPoster
     * @covers Markup_Data::getValueValue
     * @covers Media_Library_Handler
     *
     * @return void
     */
    public function testNoImage()
    {
        //Given
        $imdb = new Movie_Datasource($this->testdataPositive);
        $data = $imdb->getData();
        $data->image->url = 'https://news.google.se';
        $post_id = 1;
        $expected = __('No valid displayable image', 'imdb-markup-syntax');

        //When
        try {
            $mdata = new Markup_Data($data, $post_id);
            $mdata->getPoster();
        } //Then
        catch (Runtime_Exception $exp) {
            $this->assertSame($expected, $exp->getMessage());
            return;
        }

        $this->fail('An expected Imdb_Runtime_Exception has not been raised.');
    }

    /**
     * Negative test: post_id is not an integer
     *
     * @covers Markup_Data::__construct
     * @covers Markup_Data::getPoster
     * @covers Markup_Data::getValueValue
     * @covers Media_Library_Handler
     *
     * @return void
     */
    public function testIdNotInt()
    {
        //Given
        $imdb = new Movie_Datasource($this->testdataPositive);
        $data = $imdb->getData();
        $post_id = 'xx';
        $expected = __('Post ID must be an integer', 'imdb-markup-syntax');

        //When
        try {
            $mdata = new Markup_Data($data, $post_id);
            $mdata->getPoster();
        } //Then
        catch (Runtime_Exception $exp) {
            $this->assertSame($expected, $exp->getMessage());
            return;
        }

        $this->fail('An expected Imdb_Runtime_Exception has not been raised.');
    }

    /**
     * Negative test: Incorrect URL
     *
     * @covers Media_Library_Handler
     *
     * @return void
     */
    public function testIncorrectUrl()
    {
        //Given
        $post_id = 1;
        $remote_url = 'x';
        $filename = 'y';
        $expected = __('Remote URL must be an validate URL', 'imdb-markup-syntax');

        //When
        try {
            new Media_Library_Handler($post_id, $remote_url, $filename);
        } //Then
        catch (Runtime_Exception $exp) {
            $this->assertSame($expected, $exp->getMessage());
            return;
        }

        $this->fail('An expected Imdb_Runtime_Exception has not been raised.');
    }

    /**
     * Negative test: Incorrect URL
     *
     * @covers Media_Library_Handler
     * @covers WP_Exception
     *
     * @return void
     */
    public function testFailureDownload()
    {
        //Given
        $post_id = 1;
        $remote_url = 'http://www.austingunter.com/wp-content/uploads/2012/11/'
            . 'failure-poster.jpg';
        $filename = 'y';
        $expected = __('A valid URL was not provided.');

        //When
        try {
            $lib = new Media_Library_Handler($post_id, $remote_url, $filename);
            $lib->remote_url = 'x';
            $lib->getHtml('a', 'b');
        } //Then
        catch (WP_Exception $exp) {
            $this->assertSame($expected, $exp->getMessage());
            return;
        }

        $this->fail('An expected Imdb_Runtime_Exception has not been raised.');
    }

    /**
     * Negative test: Incorrect Filename
     *
     * @covers Media_Library_Handler
     *
     * @return void
     */
    public function testFailureFilename()
    {
        //Given
        $post_id = 1;
        $remote_url = 'http://www.austingunter.com/wp-content/uploads/2012/11/'
            . 'failure-poster.jpg';
        $filename = 'y';
        $expected = __('Empty filename');

        //When
        try {
            $lib = new Media_Library_Handler($post_id, $remote_url, $filename);
            $lib->fileanme = '';
            $lib->getHtml('a', 'b');
        } //Then
        catch (Runtime_Exception $exp) {
            $this->assertSame($expected, $exp->getMessage());
            return;
        }

        $this->fail('An expected Imdb_Runtime_Exception has not been raised.');
    }

    /**
     * Negative test: No data is set
     *
     * @covers Markup_Data::__construct
     * @covers Markup_Data::getPoster
     * @covers Markup_Data::getValueValue
     *
     * @return void
     */
    public function testNotSet()
    {
        //Given
        $imdb = new Movie_Datasource($this->testdataPositive);
        $data = $imdb->getData();
        unset($data->image->url);
        $expected = false;

        //When
        $mdata = new Markup_Data($data);
        $actual = $mdata->getPoster();

        //Then
        $this->assertSame($expected, $actual);
    }

    /**
     * Negative test: Incorrect URL
     *
     * @covers Media_Library_Handler
     *
     * @return void
     */
    public function testFailureMetadata()
    {
        //Given
        $post_id = 1;
        $remote_url = 'http://www.austingunter.com/wp-content/uploads/2012/11/'
            . 'failure-poster.jpg';
        $filename = 'y';
        $expected = __('Can\'t set thumbnail to the Post ID');

        //When
        try {
            $lib = new Media_Library_Handler($post_id, $remote_url, $filename);
            $lib->remote_url .= 'x';
            $lib->getHtml('a', 'b');
        } //Then
        catch (Runtime_Exception $exp) {
            $this->assertStringStartsWith($expected, $exp->getMessage());
            return;
        }

        $this->fail('An expected Imdb_Runtime_Exception has not been raised.');
    }

    /**
     * Negative test: Data is empty
     *
     * @covers Markup_Data::__construct
     * @covers Markup_Data::getPoster
     * @covers Markup_Data::getValueValue
     *
     * @return void
     */
    public function testEmpty()
    {
        //Given
        $imdb = new Movie_Datasource($this->testdataPositive);
        $data = $imdb->getData();
        $data->image->url = '';
        $expected = false;

        //When
        $mdata = new Markup_Data($data);
        $actual = $mdata->getPoster();

        //Then
        $this->assertSame($expected, $actual);
    }

}
