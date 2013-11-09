<?php

/**
 * Sub testclass to Tag_ProcessingTest for method toDataString in Tag_Processing
 * class
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

namespace IMDb_Markup_Syntax\Tag_ProcessingTest;

use IMDb_Markup_Syntax\Exceptions\Imdb_Runtime_Exception;
use PHPUnit_Framework_TestCase;

require_once 'Tag_Processing_Help.php';
require_once 'wp-includes/l10n.php';

/**
 * Sub testclass to Tag_ProcessingTest for method toDataString in Tag_Processing
 * class
 *
 * @category  Testable
 * @package   Test
 * @author    Henrik Roos <henrik.roos@afternoon.se>
 * @copyright 2013 Henrik Roos
 * @license   http://opensource.org/licenses/gpl-3.0.html GPL-3.0
 * @link      https://github.com/HenrikRoos/imdb-markup-syntax imdb-markup-syntax
 */
class To_Data_StringTest extends PHPUnit_Framework_TestCase
{
    public $original_content = array(
        'one_positive' => 'Pellentesque viverra luctus est, vel bibendum arcu
                suscipit quis. Quisque congue [IMDb:id(tt0137523)]. Title:
                [imdb:title]',
        'two_positive' => 'Pellentesque viverra luctus est, vel bibendum arcu
                suscipit quis.[IMDb:id(http://www.imdb.com/title/tt0137523/)]
                Quisque congue [IMDb:id(tt0102926)] Title: [imdb:title]
                [IMDb:id(tt0137523)]. Year: [IMDb:year]',
        'no_match'     => 'Pellentesque viverra luctus est, vel bibendum arcu
                suscipit quis. [IMDb:id(http://www.imdb.com/title/tt0137523/)]
                Quisque congue [IMDb:id()] Title: [title] [IMDb:id:tt0137523]
                [IMDb:id:(0137523)] [IMDb:id(tt)]'
    );

    /**
     * Positive test. Test maching of a function
     *
     * @covers IMDb_Markup_Syntax\Tag_Processing::__construct
     * @covers IMDb_Markup_Syntax\Tag_Processing::toDataString
     *
     * @return void
     */
    public function testPositive()
    {
        //Given
        $original_content = $this->original_content['one_positive'];
        $locale = 'sv_SE';
        $tag = 'date';
        $expected = 'Lör 25 Dec 1999';

        //When
        $obj = new Tag_Processing_Help($original_content);
        $obj->locale = $locale;
        $condition = $obj->findId();
        $actual = $obj->toDataString($tag);

        //Then
        $this->assertTrue($condition);
        $this->assertSame($expected, $actual);
    }

    /**
     * No maching function to the tags.
     *
     * @covers IMDb_Markup_Syntax\Tag_Processing::__construct
     * @covers IMDb_Markup_Syntax\Tag_Processing::toDataString
     *
     * @return void
     */
    public function testInvalidName()
    {
        //Given
        $original_content = $this->original_content['one_positive'];
        $tag = 'öäå';
        $expected = __('Invalid function name', 'imdb-markup-syntax');

        //When
        try {
            $obj = new Tag_Processing_Help($original_content);
            $obj->findId();
            $obj->toDataString($tag);
        } //Then
        catch (Imdb_Runtime_Exception $exp) {
            $this->assertSame($expected, $exp->getMessage());
            return;
        }

        $this->fail('An expected Imdb_Runtime_Exception has not been raised.');
    }

    /**
     * No maching function to the tags.
     *
     * @covers IMDb_Markup_Syntax\Tag_Processing::__construct
     * @covers IMDb_Markup_Syntax\Tag_Processing::toDataString
     *
     * @return void
     */
    public function testNoMatch()
    {
        //Given
        $original_content = $this->original_content['one_positive'];
        $tag = 'is_null';
        $expected = sprintf(__('[Tag %s not exists]', 'imdb-markup-syntax'), $tag);

        //When
        try {
            $obj = new Tag_Processing_Help($original_content);
            $obj->findId();
            $obj->toDataString($tag);
        } //Then
        catch (Imdb_Runtime_Exception $exp) {
            $this->assertSame($expected, $exp->getMessage());
            return;
        }

        $this->fail('An expected Imdb_Runtime_Exception has not been raised.');
    }

    /**
     * Alternative positive test. Test when wrong capitalize is precent
     *
     * @covers IMDb_Markup_Syntax\Tag_Processing::__construct
     * @covers IMDb_Markup_Syntax\Tag_Processing::toDataString
     *
     * @return void
     */
    public function testPositiveCapitalize()
    {
        //Given
        $original_content = $this->original_content['one_positive'];
        $tag = 'TiTlE';
        $expected = '<a href="http://www.imdb.com/title/tt0137523/">Fight Club</a>';

        //When
        $obj = new Tag_Processing_Help($original_content);
        $condition = $obj->findId();
        $actual = $obj->toDataString($tag);

        //Then
        $this->assertTrue($condition);
        $this->assertSame($expected, $actual);
    }

}

?>
