<?php
/**
 * @version 	$Id$
 * @category	Koowa
 * @package		Koowa_Template
 * @subpackage 	Filter
 * @copyright	Copyright (C) 2007 - 2010 Johan Janssens and Mathias Verraes. All rights reserved.
 * @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 */

/**
 * Template Filter Factory
 *
 * @author		Johan Janssens <johan@koowa.org>
 * @category	Koowa
 * @package     Koowa_Database
 * @subpackage 	Filter
 */
class KTemplateFilter
{
	/**
	 * Filter modes
	 */
	const MODE_READ  = 'read';
	const MODE_WRITE = 'write'; 
	
	/**
	 * Factory method for KTemplateFilterInterface classes.
	 *
	 * @param	string	Template filter identifier
	 * @param 	object 	An optional KConfig object with configuration options
	 * @return KTemplateFilter
	 */
	public static function factory($identifier, $config = array())
	{		
		try 
		{
			if(is_string($identifier) && strpos($identifier, '.') === false ) {
				$identifier = 'lib.koowa.template.filter.'.trim($identifier);
			} 
			
			$filter = KFactory::tmp($identifier, $config);
			
		} catch(KFactoryAdapterException $e) {
			throw new KTemplateFilterException('Invalid filter: '.$filter);
		}
		
		if(!($filter instanceof KTemplateFilterInterface)) 
		{
			$identifier = $filter->getIdentifier();
			throw new KDatabaseBehaviorException("Template filter $identifier does not implement KTemplateFilterInterface");
		}
		
		return $filter;
	}
}