<?php
/**
 * @version 	$Id:factory.php 46 2008-03-01 18:39:32Z mjaz $
 * @category	Koowa
 * @package		Koowa_Factory
 * @subpackage 	Adapter
 * @copyright	Copyright (C) 2007 - 2009 Joomlatools. All rights reserved.
 * @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 */

/**
 * KFactoryAdpater for the Joomla! framework
 *
 * @author		Johan Janssens <johan@joomlatools.org>
 * @category	Koowa
 * @package     Koowa_Factory
 * @subpackage 	Adapter
 */
interface KFactoryAdapterInterface
{
	/**
	 * Create an object instance based on a class identifier
	 *
	 * @param mixed  $string 	The class identifier
	 * @param array  $options 	An optional associative array of configuration settings.
	 *
	 * @return object
	 */
	public function createInstance($identifier, array $options);
}