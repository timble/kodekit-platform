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
	 * @param string  The object identifier
	 * @param mixed   The command arguments
	 * @return object|false  Return object on success, returns FALSE on failure
	 */
	final public function execute($identifier, $args)
	{
		// We accept either a string or an identifier object.
		if(!($identifier instanceof KFactoryIdentifierInterface)) {
			$identifier = new KFactoryIdentifierDefault($identifier);
		}
		
		$result = $this->instantiate($identifier, $args);
		return $result;
	}
}