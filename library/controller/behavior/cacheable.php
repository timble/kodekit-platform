<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2007 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

namespace Nooku\Library;

/**
 * Cacheable Controller Behavior
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Library\Controller
 */
class ControllerBehaviorCacheable extends ControllerBehaviorAbstract
{
	/**
	 * The cached state of the entity
	 * 
	 * @var boolean
	 */
	protected $_output = ''; 
	
	/**
	 * Fetch the unrendered view data from the cache
	 *
	 * @param   CommandContext	$context A command context object
	 * @return 	void	
	 */
	protected function _beforeControllerRender(CommandContext $context)
	{ 
	    $view   = $this->getView();
	    $cache  = JFactory::getCache($this->_getGroup(), 'output');
        $key    = $this->_getKey();
            
        if($data = $cache->get($key))
        {
            $data = unserialize($data);
            
            //Render the view output
            if($view instanceof ViewTemplate)
            {
                $context->result = $view->getTemplate()
                               ->loadString($data['component'], array(), false)
                               ->render();
            } 
            else $context->result = $data['component'];
            
            $this->_output = $context->result;
	    }
	}
	
	/**
	 * Store the unrendered view data in the cache
	 *
	 * @param   CommandContext	$context A command context object
	 * @return 	void
	 */
	protected function _afterControllerRender(CommandContext $context)
	{
	    if(empty($this->_output))
	    {
	        $view   = $this->getView();
	        $cache  = JFactory::getCache($this->_getGroup(), 'output');
	        $key    = $this->_getKey();
	  
	        $data  = array();
	   
	        //Store the un rendered view output
	        if($view instanceof ViewTemplate) {
	            $data['component'] = (string) $view->getTemplate();
	        } else {
	            $data['component'] = $context->result;
	        }
	        
	        $cache->store(serialize($data), $key);
	    }
	}
	
	/**
	 * Return the cached data after read
	 * 
	 * Only if cached data was found return it but allow the chain to continue to allow
	 * processing all the read commands
	 *
	 * @param   CommandContext	A command context object
	 * @return 	void
	 */
	protected function _afterControllerRead(CommandContext $context)
	{ 
	    if(!empty($this->_output)) {
	        $context->result = $this->_output;
	    }
	}
	
	/**
	 * Return the cached data before browse
	 * 
	 * Only if cached data was fetch return it and break the chain to dissallow any
	 * further processing to take place
	 * 
	 * @param   CommandContext	A command context object
	 * @return 	void
	 */
    protected function _beforeControllerBrowse(CommandContext $context)
	{
	    if(!empty($this->_output)) 
	    {
	        $context->result = $this->_output;
	        return false;
	    }
	}
	
	/**
	 * Clean the cache
	 *
	 * @param   CommandContext	A command context object
	 * @return 	boolean
	 */
	protected function _afterControllerAdd(CommandContext $context)
	{
	    $status = $context->result->getStatus();
	    
	    if($status == Database::STATUS_CREATED) {
	         \JFactory::getCache()->clean($this->_getGroup());
	    }
	      
	    return true;
	}
	
	/**
	 * Clean the cache
	 *
	 * @param   CommandContext	A command context object
	 * @return 	boolean
	 */
	protected function _afterControllerDelete(CommandContext $context)
	{
	    $status = $context->result->getStatus();
	    
	    if($status == Database::STATUS_DELETED) {
	        \JFactory::getCache()->clean($this->_getGroup());
	    }
	      
	    return true;
	}
	
	/**
	 * Clean the cache
	 *
	 * @param   CommandContext	A command context object
	 * @return 	boolean
	 */
	protected function _afterControllerEdit(CommandContext $context)
	{
	    $status = $context->result->getStatus();
	    
	    if($status == Database::STATUS_UPDATED) {
	        \JFactory::getCache()->clean($this->_getGroup());
	    }
	      
	    return true;
	}
	
	/**
	 * Generate a cache key
	 * 
	 * The key is based on the layout, format and model state
	 *
	 * @return 	string 
	 */
	protected function _getKey()
	{
	    $view  = $this->getView();
	    $state = $this->getModel()->getState()->getValues();
	    
	    $key = $view->getLayout().'-'.$view->getFormat().':'.md5(http_build_query($state));
	    return $key;
	}
	
	/**
	 * Generate a cache group
	 * 
	 * The group is based on the component identifier
	 *
	 * @return 	string 
	 */
	protected function _getGroup()
	{ 
	    $group = $this->getMixer()->getIdentifier();
	    return $group;
	}
}