<?php
/**
 * @version     $Id$
 * @category	Koowa
 * @package     Koowa_Document
 * @subpackage 	Html
 * @copyright   Copyright (C) 2007 - 2009 Joomlatools. All rights reserved.
 * @license     GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link        http://www.koowa.org
 */

/**
 * Module renderer
 *
 * @author		Johan Janssens <johan@joomlatools.org>
 * @category	Koowa
 * @package		Koowa_Document
 * @subpackage	Html
 * @uses 		KFactory
 */
class KDocumentHtmlRendererModule extends KDocumentRenderer
{
	/**
	 * Renders a module script and returns the results as a string
	 *
	 * @param string 	$name		The name of the module to render
	 * @param array 	$params		Associative array of values
	 * @return string	The output of the script
	 */
	public function render( $module, array $params = array(), $content = null )
	{
		if (!is_object($module))
		{
			$title	= isset($params['title']) ? $params['title'] : null;

			$module = JModuleHelper::getModule($module, $title);

			if (!is_object($module))
			{
				if (is_null($content)) {
					return '';
				}
				
				// Render a temp module if data was pushed in through the buffer
				$tmp = $module;
				$module = new stdClass();
				$module->params = null;
				$module->module = $tmp;
				$module->id = 0;
				$module->user = 0;
			}
		}

		// get the user and configuration object
		$user = KFactory::get('lib.joomla.user');
		$conf = KFactory::get('lib.joomla.config');

		// set the module content
		if (!is_null($content)) {
			$module->content = $content;
		}

		//get module parameters
		$mod_params = new JParameter( $module->params );

		$contents = '';
		if ($mod_params->get('cache', 0) && $conf->getValue( 'config.caching' ))
		{
			$cache = KFactory::tmp('lib.joomla.cache', array($module->module));

			$cache->setLifeTime( $mod_params->get( 'cache_time', $conf->getValue( 'config.cachetime' ) * 60 ) );
			$cache->setCacheValidation(true);

			return $cache->get( array('JModuleHelper', 'renderModule'), array( $module, $params ), $module->id. $user->get('aid', 0) );
		}
		
		return JModuleHelper::renderModule($module, $params);
	}
}