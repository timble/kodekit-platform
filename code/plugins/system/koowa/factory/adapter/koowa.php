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
 * KFactoryAdpater for the Koowa framework
 *
 * @author		Johan Janssens <johan@joomlatools.org>
 * @category	Koowa
 * @package     Koowa_Factory
 * @subpackage 	Adapter
 * @uses 		KInflector
 */
class KFactoryAdapterKoowa extends KFactoryAdapterAbstract
{
	/**
	 * Create an instance of a class based on a class identifier
	 *
	 * @param mixed  $string 	The class identifier
	 * @param array  $options 	An optional associative array of configuration settings.
	 * @return object
	 */
	public function createInstance($identifier, array $options)
	{
		$instance = false;
		
		$parts = explode('.', $identifier);
		if($parts[0] == 'lib' && $parts[1] == 'koowa') 
		{
			unset($parts[0]);
			unset($parts[1]);
		
			$classname = 'K'.KInflector::implode($parts);
		
			if (!class_exists($classname))
			{
				$suffix = array_pop($parts);
				$options['name'] = array(
               		'prefix'    => 'k',
					'base'      => KInflector::implode($parts),
					'suffix'    => $suffix                       
               	);
                        
				$classname = 'K'.KInflector::implode($parts).'Default';
				if (!class_exists($classname)) {
					throw new KFactoryAdapterException("Could't create instance for $identifier");
				}
			}	
				
			$instance = new $classname($options);
		}
		
		return $instance;
	}
}