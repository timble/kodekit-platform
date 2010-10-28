<?php
/**
 * @version 	$Id$
 * @category	Koowa
 * @package		Koowa_Template
 * @subpackage 	Filter
 * @copyright	Copyright (C) 2007 - 2010 Johan Janssens. All rights reserved.
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 */

/**
 * Template Filter Factory
 *
 * @author		Johan Janssens <johan@nooku.org>
 * @category	Koowa
 * @package     Koowa_Database
 * @subpackage 	Filter
 */
class KTemplateFilter
{
	/**
	 * Filter modes
	 */
	const MODE_READ  = 1;
	const MODE_WRITE = 2; 
	
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
				$identifier = 'com.default.template.filter.'.trim($identifier);
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