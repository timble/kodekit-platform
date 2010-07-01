<?php
/**
 * @version		$Id: default.php 1982 2010-05-09 00:21:45Z johanjanssens $
 * @category	Koowa
 * @package		Koowa_Template
 * @subpackage	Helper
 * @copyright	Copyright (C) 2007 - 2010 Johan Janssens and Mathias Verraes. All rights reserved.
 * @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.koowa.org
 */

/**
 * Template Helper Class
 *
 * @author		Johan Janssens <johan@koowa.org>
 * @category	Koowa
 * @package		Koowa_Template
 * @subpackage	Helper
 */
class KTemplateHelper 
{
	/**
	 * Factory method for KTemplateHelperInterface classes.
	 *
	 * @param	string 	Template helper indentifier
	 * @param 	object 	An optional KConfig object with configuration options
	 * @return KTemplateHelperAbstract
	 */
	public static function factory($identifier, $config = array())
	{		
		//Create the template helper
		try 
		{
			if(is_string($identifier) && strpos($identifier, '.') === false ) {
				$identifier = 'lib.koowa.template.helper.'.trim($identifier);
			} 
			
			$helper = KFactory::get($identifier, $config);
			
		} catch(KFactoryAdapterException $e) {
			throw new KTemplateHelperException('Invalid identifier: '.$identifier);
		}
		
		//Check the behavior interface
		if(!($helper instanceof KTemplateHelperInterface)) 
		{
			$identifier = $helper->getIdentifier();
			throw new KTemplateHelperException("Template helper $helper does not implement KTemplateHelperInterface");
		}
		
		return $helper;
	}
}