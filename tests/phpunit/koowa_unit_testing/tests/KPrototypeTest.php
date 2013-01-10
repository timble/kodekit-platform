<?php
/**
 * @version     $Id$
 * @package     Koowa_Tests
 * @copyright   Copyright (C) 2007 - 2008 Joomlatools. All rights reserved.
 * @license     GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link        http://www.koowa.org
 */

/**
 * Prototype, use this as a template file for new tests
 *
 * @author      Mathias Verraes <mathias@joomlatools.org>
 * @package     Koowa_Tests
 */
class KPrototypeTest extends PHPUnit_Framework_TestCase
{
    /**
     * Set up the test
     */
    public function setup()
    {

    }

    /**
     * Tear down the test
     */
    public function teardown()
    {

    }

    /**
     * Provides data for the test
     */
    public static function provideData()
    {
        // $item1, $item2
        return array(
            array('item', 'item')
        );
    }

    /**
     * @dataProvider provideData
     */
    public function testPrototype($item1, $item2)
    {
    	$this->assertEquals($item1, $item2);
    }
}


