<?php
/**
 * @version		$Id$
 * @package		Koowa_Tests
 * @copyright	Copyright (C) 2007 - 2008 Joomlatools. All rights reserved.
 * @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.koowa.org
 */

/**
 * KoowaTest
 *
 * @author		Mathias Verraes <mathias@joomlatools.org>
 * @package		Koowa_Tests
 */
class KoowaTest extends PHPUnit_Framework_TestCase
{
	public function testKoowaExists()
	{
		$this->assertTrue(class_exists('Koowa'));
	}

	public function testImportExists()
	{
		$this->assertTrue(method_exists('Koowa', 'import'));
	}
}