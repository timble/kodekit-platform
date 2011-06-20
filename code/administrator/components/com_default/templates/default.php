<?php
/**
 * @version     $Id$
 * @category	Nooku
 * @package     Nooku_Components
 * @subpackage  Default
 * @copyright   Copyright (C) 2007 - 2010 Johan Janssens. All rights reserved.
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Default Template
.*
 * @author      Johan Janssens <johan@nooku.org>
 * @category    Nooku
 * @package     Nooku_Components
 * @subpackage  Default
 */
class ComDefaultTemplateDefault extends KTemplateDefault
{ 
	/**
	 * Load a template by path -- first look in the templates folder for an override
	 * 
	 * This function tries to get the template from the cache. If it cannot be found 
	 * the template file will be loaded from the file system.
	 *
	 * @param   string 	The template path
	 * @param	array	An associative array of data to be extracted in local template scope
	 * @return KTemplateAbstract
	 */
	public function loadFile($path, $data = array(), $process = true)
	{
	    //Load from cache or cache the template
	    $cache = KFactory::tmp('lib.joomla.cache', array('template', 'output'));
		
	    //Set the lifetime to 0 to make sure cache isn't garbage collected.
	    $cache->setLifeTime(0);    
	       
	    $identifier = md5($path); 
	     
	    if ($template = $cache->get($identifier)) 
	    {
		    // store the path
		    $this->_path = $path;
		    
	        $this->loadString($template, $data, $process);
	    } 
	    else parent::loadFile($path, $data, $process);
	    
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
	    $override .= str_replace(array(JPATH_BASE.'/components', '/views'), '', $path);
	     
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
	 * Parse the template
	 * 
	 * This function implements a caching mechanism when reading the template. If
	 * the tempplate cannot be found in the cache it will be filtered and stored in
	 * the cache. Otherwise it will be loaded from the cache and returned directly.
	 *
	 * @return string	The filtered data
	 */
	public function parse()
	{	
	    $cache = KFactory::tmp('lib.joomla.cache', array('template', 'output'));
		
	    //Set the lifetime to 0 to make sure cache isn't garbage collected.
	    $cache->setLifeTime(0);
	     
	    $identifier = md5($this->_path);
	     
	    if (!$template = $cache->get($identifier)) 
	    {
	        $template = parent::parse();
	            
	        //Store the object in the cache
		   	$cache->store($template, $identifier);
	    }
	    
	    return $template;
	}
    
    /**
     * Load a template helper
     * 
     * This function merges the elements of the attached view model state with the parameters passed to the helper
     * so that the values of one are appended to the end of the previous one. 
     * 
     * If the view state have the same string keys, then the parameter value for that key will overwrite the state.
     *
     * @param   string  Name of the helper, dot separated including the helper function to call
     * @param   mixed   Parameters to be passed to the helper
     * @return  string  Helper output
     */
    public function renderHelper($identifier, $params = array())
    {
        $view = $this->getView();
        
        if(KInflector::isPlural($view->getName())) 
        {
            if($state = $view->getModel()->getState()) {
                $params = array_merge( $state->getData(), $params);
            }
        } 
        else 
        {
            if($item = $view->getModel()->getItem()) {
                $params = array_merge( $item->getData(), $params);
            }
        }   
        
        return parent::renderHelper($identifier, $params);
    }
}