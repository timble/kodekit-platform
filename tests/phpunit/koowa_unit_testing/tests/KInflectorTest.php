<?php
/**
 * @version     $Id$
 * @package     Koowa_Tests
 * @copyright   Copyright (C) 2007 - 2008 Joomlatools. All rights reserved.
 * @license     GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link        http://www.koowa.org
 */

/**
 * KInflectorTest
 *
 * @author      Mathias Verraes <mathias@joomlatools.org>
 * @package     Koowa_Tests
 */
class KInflectorTest extends PHPUnit_Framework_TestCase
{
    /**
     * Provides data for the test
     */
    public static function provideNames()
    {
        // classified, separator, split, exploded, camelized, underscored
        return array(
            array(
                'PrefixBaseSuffix',
                'base',
                array('prefix'=>'prefix', 'base'=>'base', 'suffix'=>'suffix'),
                array('prefix', 'base', 'suffix'),
                'PrefixBaseSuffix',
                'prefix_base_suffix'
                ),
            array(
                'BaseSuffix',
                'base',
                array('prefix'=>'', 'base'=>'base', 'suffix'=>'suffix'),
                array('base', 'suffix'),
                'BaseSuffix',
                'base_suffix'
                ),
            array(
                'PrefixBase',
                'base',
                array('prefix'=>'prefix', 'base'=>'base', 'suffix'=>''),
                array('prefix', 'base'),
                'PrefixBase',
                'prefix_base'
                ),
            array(
                'Base',
                'base',
                array('prefix'=>'', 'base'=>'base', 'suffix'=>''),
                array('base'),
                'Base',
                'base'
                ),
            array(
                'PrefixDoubleBaseSuffix',
                'doublebase',
                array('prefix'=>'prefix', 'base'=>'doublebase', 'suffix'=>'suffix'),
                array('prefix', 'double', 'base', 'suffix'),
                'PrefixDoubleBaseSuffix',
                'prefix_double_base_suffix'
                )
        );
    }

    /**
     * Provides data for the test
     */
    public static function providePlurals()
    {
        // singular, plural
        return array(
            array('person',     'people'),
            array('item',       'items'),
            array('aircraft',   'aircraft'),
            array('cannon',     'cannon'),
            array('deer',       'deer'),
            array('quiz',       'quizzes'),
            array('child',      'children'),
            array('foot',       'feet'),
            array('suffix',     'suffices'),
            array('dish',       'dishes'),
            array('tomato',     'tomatoes'),
            array('hero',       'heroes'),
            array('cherry',     'cherries'),
            array('monkey',     'monkeys'),
            array('calf',       'calves'),
            array('knife',      'knives'),
            array('moose',      'moose'),
            array('swine',      'swine'),
            array('woman',      'women'),
            array('alumna',     'alumnae'),
            array('vertex',     'vertices'),
            array('crisis',     'crises'),
            array('addendum',   'addenda'),
            array('genus',      'genera')
        );
    }

    /**
     * @dataProvider provideNames
     */
    public function testUnderscoredToCamelize($classified, $separator, $split, $exploded, $camelized, $underscored)
    {
    	$this->assertEquals(KInflector::camelize($underscored), $camelized);
    }

    /**
     * @dataProvider provideNames
     */
    public function testCamelizeToUnderscored($classified, $separator, $split, $exploded, $camelized, $underscored)
    {
        $this->assertEquals(KInflector::underscore($camelized), $underscored);
    }

    /**
     * @dataProvider provideNames
     */
    public function testUnderscoreToClassify($classified, $separator, $split, $exploded, $camelized, $underscored)
    {
        $this->assertEquals(KInflector::classify($underscored), $classified);
    }

    /**
     * @dataProvider provideNames
     */
    public function testClassifyToUnderscore($classified, $separator, $split, $exploded, $camelized, $underscored)
    {
        $this->assertEquals(KInflector::underscore($classified), $underscored);
    }

    /**
     * @dataProvider provideNames
     */
    public function testExplode($classified, $separator, $split, $exploded)
    {
        $this->assertEquals( KInflector::explode($classified), $exploded);
    }

    public function testgetPart()
    {
        $this->markTestIncomplete('Not implemented');
    }

    public function testHumanize()
    {
        $this->markTestIncomplete('Not implemented');
    }

    /**
     * @dataProvider providePlurals
     */
    public function testIsPlural($singular, $plural)
    {
        $this->assertTrue(KInflector::isPlural($plural));
    }

    /**
     * @dataProvider providePlurals
     */
    public function testIsSingular($singular, $plural)
    {
        $this->assertTrue(KInflector::isSingular($singular));
    }

    /**
     * @dataProvider providePlurals
     */
    public function testPluralize($singular, $plural)
    {
        $this->assertEquals(KInflector::pluralize($singular), $plural);
    }

    /**
     * @dataProvider providePlurals
     */
    public function testSingularize($singular, $plural)
    {
        $this->assertEquals(KInflector::singularize($plural), $singular);
    }

    /**
     * @dataProvider provideNames
     */
    public function testSplit($classified, $separator, $split, $exploded)
    {
        $this->assertEquals( KInflector::split($separator, $classified), $split);
    }

    public function testTableize()
    {
        $this->markTestIncomplete('Not implemented');
    }

    public function testUnderscore()
    {
        $this->markTestIncomplete('Not implemented');
    }

    public function testVariablize()
    {
        $this->markTestIncomplete('Not implemented');
    }

}


