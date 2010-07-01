<?php
/**
* @version		$Id$
* @category		Koowa
* @package      Koowa_Filter
* @copyright    Copyright (C) 2007 - 2010 Johan Janssens and Mathias Verraes. All rights reserved.
* @license      GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
* @link 		http://www.koowa.org
*/

/**
 * Filter Factory
 *
 * @author		Johan Janssens <johan@koowa.org>
 * @category	Koowa
 * @package     Koowa_Filter
 */
class KFilter
{
	/**
	 * Factory method for KFilterInterface classes.
	 *
	 * @param	string 	Filter indentifier
	 * @param 	object 	An optional KConfig object with configuration options
	 * @return KFilterAbstract
	 */
	public static function factory($identifier, $config = array())
	{		
		//Get the filter(s) we need to create
		$filters = (array) $identifier;

		//Create the filter chain
		$filter  = array_shift($filters);
		$filter = self::_createFilter($filter, $config);
		
		foreach($filters as $name) {
			$filter->addFilter(self::_createFilter($name, $config));
		}
		
		return $filter;
	}

	/**
	 * Create a filter based on it's name
	 * 
	 * If the filter is not an identifier this function will create it directly
	 * instead of going through the KFactory identification process.
	 *
	 * @param 	string	Filter identifier
	 * @throws	KFilterException	When the filter could not be found
	 * @return  KFilterInterface
	 */
	protected static function _createFilter($filter, $config)
	{
		$filter = trim($filter);
		
		try 
		{
			if(is_string($filter) && strpos($filter, '.') === false ) 
			{
				$filter = 'KFilter'.ucfirst($filter);
				
				if(!$config instanceof KConfig) {
					$config = new KConfig($config);
				}
				
				$filter = new $filter($config);
				
			} else $filter = KFactory::tmp($filter, $config);
			
		} catch(KFactoryAdapterException $e) {
			throw new KFilterException('Invalid filter: '.$filter);
		}
		
		return $filter;
	}
}