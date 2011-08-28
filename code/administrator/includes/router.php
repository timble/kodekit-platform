<?php
/**
 * @version     $Id$
 * @category    Nooku
 * @package     Nooku_Server
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Class to create and parse routes
 *
 * @author      Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @category    Nooku
 * @package     Nooku_Server
 */
class JRouterAdministrator extends JRouter
{
	/**
	 * Function to convert a route to an internal URI
	 *
	 * @return array
	 */
	public function parse($uri)
	{
		$path = trim($uri->getPath(), '/');
		
		//Remove basepath
		$path = substr_replace($path, '', 0, strlen(JURI::base(true)));
		
		//Remove prefix
		$path = str_replace('index.php', '', $path);
		
		//Get the segments
	    $segments = explode('/', $path);
	    
	    $vars = array();
	    if(isset($segments[1])) 
	    {
	        $vars['option'] = 'com_'.$segments[1];
	    
	        if(isset($segments[2])) {
	            $vars['view']   = $segments[2];
	        } else {
	            $vars['view']   = $segments[1];
	        }
	    }
	    
	    return $vars;
	}

	/**
	 * Function to convert an internal URI to a route
	 *
	 * @param	string	$string	The internal URL
	 * @return	string	The absolute search engine friendly URL
	 */
	public function build($url)
	{
		//Create the URI object
		$uri = $this->_createURI($url);
		
		$query = $uri->getQuery(true);
	    $path  = $uri->getPath();
	    
	    $segments = array();
	    if(isset($query['option'])) 
	    {
	        $segments[] = substr($query['option'], 4);
	        unset($query['option']); 

	        if(isset($query['view'])) 
	        {
	            if($query['view'] != $segments[0]) {
	                $segments[] = $query['view'];
	            }
	            
	            unset($query['view']);  
	        }
	    }
	    
	    $path = JURI::base(true).'/index.php/'.implode('/', $segments);
	    
		//Set query again in the URI
		$uri->setQuery($query);
		$uri->setPath($path);
		
		return $uri;
	}
}