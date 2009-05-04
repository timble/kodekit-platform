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
 * Abstract Factory Adpater
 *
 * @author		Johan Janssens <johan@koowa.org>
 * @category	Koowa
 * @package     Koowa_Factory
 * @subpackage 	Adapter
 */
abstract class KFactoryAdapterAbstract extends KObject implements KPatternCommandInterface, KFactoryAdapterInterface
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