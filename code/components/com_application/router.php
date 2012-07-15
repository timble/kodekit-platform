<?php
/**
 * @version     $Id: dispatcher.php 4021 2012-07-13 01:00:23Z johanjanssens $
 * @package     Nooku_Server
 * @subpackage  Application
 * @copyright   Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Application Router Class
.*
 * @author      Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package     Nooku_Server
 * @subpackage  Application
 */
class ComApplicationRouter extends KDispatcherRouterDefault
{
    public function parse($url)
	{
		$vars = array();

		// Get the application
		$app = JFactory::getApplication();

		// Forward to https
		if($app->getCfg('force_ssl') == 2 && strtolower($url->getScheme()) != 'https')
		{
			$url->setScheme('https');
			$app->redirect($url->toString());
		}


		// Get the path
		$path = $url->getPath();

		if($app->getCfg('sef_suffix') && !(substr($path, -9) == 'index.php' || substr($path, -1) == '/'))
		{
			if($suffix = pathinfo($path, PATHINFO_EXTENSION))
			{
				$path = str_replace('.'.$suffix, '', $path);
				$vars['format'] = $suffix;
			}
		}

		//Remove basepath
		$path = substr_replace($path, '', 0, strlen(JURI::base(true)));

		//Remove prefix
		$path = str_replace('index.php', '', $path);

		//Set the route
		$url->setPath(trim($path , '/'));

		$vars += parent::parse($url);

		return $vars;
	}

	public function build($url)
	{
		$url = parent::build($url);

		// Get the path data
		$route = $url->getPath();

		//Add the suffix to the uri
		if($route)
		{
            $app = JFactory::getApplication();

			if($format = $url->getVar('format', 'html'))
			{
			    if($app->getCfg('sef_suffix') && !(substr($route, -9) == 'index.php' || substr($route, -1) == '/'))
			    {
			        $route .= '.'.$format;
			    	$url->delVar('format');
			    }
			    else 
			    {
			        if($format == 'html') {
			            $url->delVar('format');
			        }   
			    }
			}

            //Transform the route
			if($app->getCfg('sef_rewrite')) {
				$route = str_replace('index.php/', '', $route);
			}
		}

		//Add basepath to the uri
		$url->setPath(JURI::base(true).'/'.$route);

		return $url;
	}

	protected function _parseRoute($url)
	{
		$vars   = array();

		$menu  = JFactory::getApplication()->getMenu(true);
		$route = $url->getPath();

		//Get the variables from the uri
		$vars = $url->getQuery(true);

		//Remove the site from the route
		$site  = JFactory::getApplication()->getSite();
		$route = ltrim(str_replace($site, '', $route), '/');

		/*
		 * Parse the application route
		 */
		if(substr($route, 0, 9) == 'component')
		{
			$segments	= explode('/', $route);
			$route      = str_replace('component/'.$segments[1], '', $route);

			$vars['option'] = 'com_'.$segments[1];
			$vars['Itemid'] = null;
		}
		else
		{
			//Need to reverse the array (highest sublevels first)
			$items = array_reverse($menu->getMenu());

			foreach ($items as $item)
			{
				$lenght = strlen($item->route); //get the lenght of the route

				if($lenght > 0 && strpos($route.'/', $item->route.'/') === 0 && $item->type != 'menulink')
				{
					$route   = substr($route, $lenght);

                    $vars['Itemid'] = $item->id;
					$vars['option'] = $item->component;
					
					break;
				}
			}
		}

		// Set the active menu item
		if ( isset($vars['Itemid']) ) {
			$menu->setActive(  $vars['Itemid'] );
		}

		//Set the variables
		$this->setVars($vars);

		/*
		 * Parse the component route
		 */
		if(!empty($route) && isset($this->_vars['option']) )
		{
			$segments = explode('/', $route);
			array_shift($segments);

			// Handle component	route
			$component = preg_replace('/[^A-Z0-9_\.-]/i', '', $this->_vars['option']);

			if (count($segments))
			{
				if ($component != "com_search") { // Cheap fix on searches
					//decode the route segments
					$segments = $this->_decodeSegments($segments);
				}
				else
                {
                   // fix up search for URL
					$total = count($segments);
					for($i=0; $i<$total; $i++) {
						// urldecode twice because it is encoded twice
						$segments[$i] = urldecode(urldecode(stripcslashes($segments[$i])));
					}
				}

                $vars = KService::get('com://site/'.substr($component, 4).'.router')->parseRoute($segments);

				$this->setVars($vars);
			}
		}
		else
		{
			//Set active menu item
			if($item =& $menu->getActive()) {
				$vars = $item->query;
			}
		}

		return $vars;
	}

	protected function _buildRoute($url)
	{
		// Get the route
		$route = $url->getPath();

		// Get the query data
		$query = $url->getQuery(true);

		if(!isset($query['option'])) {
			return;
		}

		$menu = JFactory::getApplication()->getMenu();

		/*
		 * Build the component route
		 */
		$component	= preg_replace('/[^A-Z0-9_\.-]/i', '', $query['option']);
		$tmp 		= '';

		// Use the custom routing handler if it exists
		if (!empty($query))
		{
            $parts = KService::get('com://site/'.substr($component, 4).'.router')->buildRoute($query);

			// encode the route segments
			if ($component != "com_search") { // Cheep fix on searches
				$parts = $this->_encodeSegments($parts);
			}
			else 
			{ 
			    // fix up search for URL
				$total = count($parts);
				for($i=0; $i<$total; $i++) 
				{
					// urlencode twice because it is decoded once after redirect
					$parts[$i] = urlencode(urlencode(stripcslashes($parts[$i])));
				}
			}

			$result = implode('/', $parts);
			$tmp	= ($result != "") ? '/'.$result : '';
		}

		/*
		 * Build the application route
		 */
		$built = false;
		if (isset($query['Itemid']) && !empty($query['Itemid']))
		{
			$item = $menu->getItem($query['Itemid']);

			if (is_object($item) && $query['option'] == $item->component) {
				$tmp = !empty($tmp) ? $item->route.'/'.$tmp : $item->route;
				$built = true;
			}
		}

		if(!$built) {
			$tmp = 'component/'.substr($query['option'], 4).'/'.$tmp;
		}

		//Add the site
	    $site = JFactory::getApplication()->getSite();
	    if($site != 'default' && $site != JURI::getInstance()->getHost()) {
	        $tmp = $site.'/'.$tmp;
	    }
		
		$route .= '/'.$tmp;

		// Unset unneeded query information
		unset($query['Itemid']);
		unset($query['option']);

		//Set query again in the URI
		$url->setQuery($query);
		$url->setPath($route);
	}

	protected function _processParseRules($url)
	{
		// Process the attached parse rules
		$vars = parent::_processParseRules($url);

		if($start = $url->getVar('start'))
		{
			$url->delVar('start');
			$vars['limitstart'] = $start;
		}

		return $vars;
	}

	protected function _processBuildRules($url)
	{
		// Make sure any menu vars are used if no others are specified
		if($url->getVar('Itemid') && count($url->getQuery(true)) == 2)
		{
			$menu = JFactory::getApplication()->getMenu();

			// Get the active menu item
			$itemid = $url->getVar('Itemid');
			$item   = $menu->getItem($itemid);

			$url->setQuery($item->query);
			$url->setVar('Itemid', $itemid);
		}

		// Process the attached build rules
		parent::_processBuildRules($url);

		// Get the path data
		$route = $url->getPath();

		if($route)
		{
			if ($limitstart = $url->getVar('limitstart'))
			{
				$url->setVar('start', (int) $limitstart);
				$url->delVar('limitstart');
			}
		}

		$url->setPath($route);
	}

	protected function _createUrl($url)
	{
		//Create the URI
		$url = parent::_createUrl($url);

		// Set URI defaults
		$menu = JFactory::getApplication()->getMenu();

		// Get the itemid form the URI
		$itemid = $url->getVar('Itemid');

		if(is_null($itemid))
		{
            if($option = $url->getVar('option'))
			{
				$item  = $menu->getItem($this->getVar('Itemid'));
				if(isset($item) && $item->component == $option) {
					$url->setVar('Itemid', $item->id);
				}
			}
			else
			{
				if($option = $this->getVar('option')) {
					$url->setVar('option', $option);
				}

				if($itemid = $this->getVar('Itemid')) {
					$url->setVar('Itemid', $itemid);
				}
			}
		}
		else
		{
			if(!$url->getVar('option'))
			{
				$item  = $menu->getItem($itemid);
				$url->setVar('option', $item->component);
			}
		}

		return $url;
	}
}
