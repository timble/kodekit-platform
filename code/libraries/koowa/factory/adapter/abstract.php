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
	 * @param object  The command context
	 * @return object|false  Return object on success, returns FALSE on failure
	 */
	final public function execute($identifier, KCommandContext $context)
	{
		$result = $this->instantiate($identifier, $context->config);
		return $result;
	}
}