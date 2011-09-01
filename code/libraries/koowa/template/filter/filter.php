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
	 * @param	mixed 	An object that implements KObjectIdentifiable, an object that
	 *                  implements KIdentifierInterface or valid identifier string
	 * @param 	object 	An optional KConfig object with configuration options
	 * @return KTemplateFilter
	 */
	public static function factory($filter, $config = array())
	{		
	    //Create the behavior
	    if(!($filter instanceof KTemplateFilterInterface))
		{   
		    if(is_string($filter) && strpos($filter, '.') === false ) {
		       $filter = 'com:default.template.filter.'.trim($filter);
		    }    
			
		    $filter = KFactory::get($filter, $config);
		    
		    if(!($filter instanceof KTemplateFilterInterface)) 
		    {
			    $identifier = $filter->getIdentifier();
			    throw new KDatabaseBehaviorException("Template filter $identifier does not implement KTemplateFilterInterface");
		    }
		}
	    
		return $filter;
	}
}