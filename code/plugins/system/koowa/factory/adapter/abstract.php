<?php
/**
 * @version 	$Id$
 * @category	Koowa
 * @package		Koowa_Factory
 * @subpackage 	Adapter
 * @copyright	Copyright (C) 2007 - 2009 Johan Janssens and Mathias Verraes. All rights reserved.
 * @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 */

/**
 * Abstract Factory Adapter
 *
 * @author		Johan Janssens <johan@koowa.org>
 * @category	Koowa
 * @package     Koowa_Factory
 * @subpackage 	Adapter
 */
abstract class KFactoryAdapterAbstract extends KObject implements KFactoryAdapterInterface
{
	/**
	 * Generic Command handler
	 *
	 * @param string  The command name
	 * @param mixed   The command arguments
	 * @return object|false  Return object on success, returns FALSE on failure
	 */
	final public function execute($name, $args)
	{
		$result = $this->instantiate($name, $args);
		return $result;
	}
}