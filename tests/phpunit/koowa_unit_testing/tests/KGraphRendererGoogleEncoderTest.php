<?php
/**
 * @version     $Id$
 * @package     Koowa_Tests
 * @copyright   Copyright (C) 2007 - 2008 Joomlatools. All rights reserved.
 * @license     GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link        http://www.koowa.org
 */

/**
 * KGraphRendererGoogleEncoderTest
 *
 * @author      Mathias Verraes <mathias@joomlatools.org>
 * @package     Koowa_Tests
 */
class KGraphRendererGoogleEncoderTest extends PHPUnit_Framework_TestCase
{
    /**
     * Provides data for the test 
     */
    public static function provideData()
    {
        $datasets1 = array(array(0,1,2,3.5,4,5), array(-1,6,7,8,9,0));
        $result1   = 't:0,1,2,3.5,4,5|-1,6,7,8,9,0';
        
        $datasets2 = array(array(10,20,30,40,50,100,200));
        $result2   = 't:10,20,30,40,50,100,200';
        
        // data, result
        return array(
            array($datasets1, $result1),
            array($datasets2, $result2)
        );
    }
    
    /**
     * @dataProvider provideData
     */
    public function testTextEncode($datasets, $result)
    {
     
    	$this->assertEquals(
    	    KGraphRendererGoogleEncoder::textEncode($datasets), 
    	    $result);
    }
}