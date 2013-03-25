<?php

/**
 * Test suite (PHPUnit) test for Tag_Processing tests
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

namespace IMDb_Markup_Syntax;

use PHPUnit_Framework_TestSuite;

require_once dirname(__FILE__) . '/Tag_ProcessingTest/Find_IdTest.php';
require_once dirname(__FILE__) . '/Tag_ProcessingTest/Find_Imdb_TagsTest.php';
require_once 'PHPUnit/Autoload.php';

/**
 * Test suite (PHPUnit) test for Tag_Processing tests
 * 
 * @category  Testable
 * @package   Test
 * @author    Henrik Roos <henrik.roos@afternoon.se>
 * @copyright 2013 Henrik Roos
 * @license   https://github.com/HenrikRoos/imdb-markup-syntax/blob/master/imdb-markup-syntax.php GPL2
 * @link      https://github.com/HenrikRoos/imdb-markup-syntax imdb-markup-syntax
 */
class Tag_ProcessingSuite extends PHPUnit_Framework_TestSuite
{

    /**
     * Sets up testdata in superglobal $GLOBALS['tagProcessingData']
     *
     * @return void
     */
    protected function setUp()
    {
        $GLOBALS["tagProcessingData"] = array(
            "one_positive" => "Pellentesque viverra luctus est, vel bibendum arcu
                suscipit quis. Quisque congue [IMDb:id(tt0137523)]. Title:
                [imdb:title]",
            "two_positive" => "Pellentesque viverra luctus est, vel bibendum arcu
                suscipit quis.[IMDb:id(http://www.imdb.com/title/tt0137523/)]
                Quisque congue [IMDb:id(tt0102926)] Title: [imdb:title]
                [IMDb:id(tt0137523)]. Year: [imdb:year]",
            "no_match" => "Pellentesque viverra luctus est, vel bibendum arcu
                suscipit quis. [IMDb:id(http://www.imdb.com/title/tt0137523/)]
                Quisque congue [IMDb:id()] Title: [title] [IMDb:id:tt0137523]
                [IMDb:id:(0137523)] [IMDb:id(tt)]",
        );
    }

    /**
     * Definition tests for Tag_Processing suite
     * 
     * @return IMDb_Markup_Syntax\Tag_ProcessingSuite
     */
    public static function suite()
    {
        $suite = new self();
        $suite->addTestSuite("IMDb_Markup_Syntax\Tag_ProcessingTest\Find_IdTest");
        $suite->addTestSuite(
            "IMDb_Markup_Syntax\Tag_ProcessingTest\Find_Imdb_TagsTest"
        );
        return $suite;
    }

    /**
     * Unset testdata in superblobal $GLOBALS['tagProcessingData'] variable
     * 
     * @return void
     */
    protected function tearDown()
    {
        unset($GLOBALS['tagProcessingData']);
    }

}
