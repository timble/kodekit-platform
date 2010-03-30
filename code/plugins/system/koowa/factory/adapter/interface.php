<?php
/**
 * @version 	$Id$
 * @category	Koowa
 * @package		Koowa_Factory
 * @subpackage 	Adapter
 * @copyright	Copyright (C) 2007 - 2010 Johan Janssens and Mathias Verraes. All rights reserved.
 * @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 */

/**
 * Factory Adapter Interface
 *
 * @author		Johan Janssens <johan@koowa.org>
 * @category	Koowa
 * @package     Koowa_Factory
 * @subpackage 	Adapter
 */
interface KFactoryAdapterInterface extends KCommandInterface
{
	/**
	 * Create an object instance based on a class identifier
	 *
	 * @param 	mixed 	The class identifier
	 * @param 	object 	An optional KConfig object with configuration options
	 * @return 	object|false 	Return object on success, returns FALSE on failure
	 */
	public function instantiate($identifier, KConfig $config);
}