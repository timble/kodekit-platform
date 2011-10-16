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
		$vars = array();
	    
	    if(JFactory::getApplication()->getCfg('sef'))
	    {
		    $path = trim($uri->getPath(), '/');
		
		    //Remove basepath
		    $path = substr_replace($path, '', 0, strlen(JURI::base(true)));
		
		    //Remove prefix
		    $path = trim(str_replace('index.php', '', $path), '/');
		
		    //Remove suffix
	        if(!empty($path))
		    {
			    if($suffix = pathinfo($path, PATHINFO_EXTENSION))
			    {
				    $path = str_replace('.'.$suffix, '', $path);
				    $vars['format'] = $suffix;
			    }
		    }
		    
		    if(!empty($path))
		    {
		        //Get the segments
	            $segments = explode('/', $path);
	            if(isset($segments[0])) 
	            {
	                $vars['option'] = 'com_'.$segments[0];
	    
	                if(isset($segments[1])) {
	                    $vars['view']   = $segments[1];
	                } else {
	                    $vars['view']   = $segments[0];
	                }
	            }
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
		
		if(JFactory::getApplication()->getCfg('sef'))
	    {
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

	        //Remove index.php from path
	        if(JFactory::getApplication()->getCfg('sef_rewrite')) {
	    	    $path = str_replace('index.php/', '', $path);
	        }
	    
	        //Add format to path
	        if(JFactory::getApplication()->getCfg('sef_suffix') && !empty($path))
		    {
	            if($format = $uri->getVar('format', 'html'))
			    {
			    	if($format != 'html') 
			    	{
			            $path .= '.'.$format;
				        $uri->delVar('format');
			    	}
			    }
		    }
	    
		    //Set query again in the URI
		    $uri->setQuery($query);
		    $uri->setPath($path);
	    }
		
		return $uri;
	}
}