<?php
/**
 * @version     $Id$
 * @category	Nooku
 * @package     Nooku_Modules
 * @subpackage  Default
 * @copyright   Copyright (C) 2007 - 2010 Johan Janssens. All rights reserved.
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Default Module View
.*
 * @author      Johan Janssens <johan@nooku.org>
 * @category    Nooku
 * @package     Nooku_Modules
 * @subpackage  Default
 */
class ModDefaultTemplate extends KTemplateDefault
{ 
	/**
	 * Load a template by path
	 * 
	 * This function tries to get the template from the cache. If it cannot be found 
	 * the template file will be loaded from the file system.
	 *
	 * @param   string 	The template path
	 * @param	array	An associative array of data to be extracted in local template scope
	 * @return KTemplateAbstract
	 */
	public function loadFile($path, $data = array())
	{
	    //Load from cache or cache the template
	    $cache = KFactory::tmp('lib.joomla.cache', array('template', 'output'));
		
	    //Set the lifetime to 0 to make sure cache isn't garbage collected.
	    $cache->setLifeTime(0);
	    
	    $identifier = md5($path); 
	     
	    if ($template = $cache->get($identifier)) {
		    $this->loadString($template, $data, $path);
	    } else {
	        parent::loadFile($path, $data);
	    }
	    
		return $this;
	}
	
	/**
	 * Searches for the file
	 * 
	 * This function first tries to find a template override, if no override exists
	 * it will try to find the default template
	 *
	 * @param	string	The file path to look for.
	 * @return	mixed	The full path and file name for the target file, or FALSE
	 * 					if the file is not found
	 */
	public function findFile($path)
	{
	    $template  = KFactory::get('lib.joomla.application')->getTemplate();
        $override  = JPATH_THEMES.'/'.$template.'/html';
	    $override .= str_replace(array(JPATH_BASE.'/modules'), '', $path);
	     
	    //Try to load the template override
	    $result = parent::findFile($override);
	    
	    if($result === false) 
	    {
	        //If the path doesn't contain the /tmpl/ folder add it
	        if(strpos($path, '/tmpl/') === false) {
	            $path = dirname($path).'/tmpl/'.basename($path);
	        }
	      
	        $result = parent::findFile($path);
	    } 
	    
	    return $result;
	}

	/**
	 * Pass the data through the filter chain and perform
	 * 
	 * This function implements a caching mechanism when reading the template. If
	 * the tempplate cannot be found in the cache it will be filtered and stored in
	 * the cache. Otherwise it will be loaded from the cache and returned directly.
	 *
	 * @param string	The filter mode
	 * @return string	The filtered data
	 */
	public function filter($mode = KTemplateFilter::MODE_READ)
	{	
	    if($mode == KTemplateFilter::MODE_READ)
        {
            $cache = KFactory::tmp('lib.joomla.cache', array('template', 'output'));
		
		    //Set the lifetime to 0 to make sure cache isn't garbage collected.
	        $cache->setLifeTime(0);
	    
	        $identifier = md5($this->_path);
	    
	        if (!$template = $cache->get($identifier)) 
	        {
	            $template = parent::filter($mode);
	            
	            //Store the object in the cache
		   	    $cache->store($template, $identifier);
	        }
        }
        else $template = parent::filter($mode);
	    
	    return $template;
	}
}