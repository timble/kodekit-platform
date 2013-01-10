<?php
/**
 * @version		$Id$
 * @package		Koowa_Tests
 * @copyright	Copyright (C) 2007 - 2008 Joomlatools. All rights reserved.
 * @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.koowa.org
 */

/**
 * KFactoryTest
 *
 * @author		Mathias Verraes <mathias@joomlatools.org>
 * @package		Koowa_Tests
 */
class KFactoryTest extends PHPUnit_Framework_TestCase
{
	public function setUp()
	{
	}

	public function testKFactoryExists()
	{
		$this->assertTrue(class_exists('KFactory'));
	}

	public function testNonExistantClass()
	{
		$this->setExpectedException('KException');
		KFactory::getInstance(array('type'=>'Nonexistant', 'prefix'=>'Prefix', 'suffix'=>'Suffix'));
	}

}