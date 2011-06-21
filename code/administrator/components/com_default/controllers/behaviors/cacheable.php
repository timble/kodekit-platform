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
 * Default Controller Cacheable Behavior
 *
 * @author      Johan Janssens <johan@nooku.org>
 * @category    Nooku
 * @package     Nooku_Components
 * @subpackage  Default
 */
class ComDefaultControllerBehaviorCacheable extends KControllerBehaviorAbstract
{
	/**
     * Constructor
     *
     * @param   object  An optional KConfig object with configuration options
     */
    public function __construct(KConfig $config)
    { 
        parent::__construct($config);
           
        $this->registerCallback('before.get' , array($this,  'fetchView'));
        $this->registerCallback('after.get'  , array($this,  'storeView'));
    }
	
	/**
     * Initializes the options for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param 	object 	An optional KConfig object with configuration options
     * @return void
     */
	protected function _initialize(KConfig $config)
    {
    	//Allow higher priority before read and browse actions to fire.
        $config->append(array(
			'priority'   => KCommand::PRIORITY_LOW,
	  	));
	  	
    	parent::_initialize($config);
   	}
	
	/**
	 * Fetch the unrendered view data from the cache
	 *
	 * @param   KCommandContext	A command context object
	 * @return 	void	
	 */
	public function fetchView(KCommandContext $context)
	{
	    $view   = $this->getView();
	    $cache  = KFactory::get('lib.joomla.cache', array($this->_getGroup(), 'output'));
        $key    = $this->_getKey();
	    
        if($result = $cache->get($key))
        {
            //Render the view output
            if($view instanceof KViewTemplate) 
            {
                $result = $view->getTemplate()
                               ->loadString($result, array(), false)
                               ->render();
            }
            
            $context->result = $result; 

            //Prevent data re-caching
            $this->unregisterCallback('after.get'  , array($this,  'storeView'));
            
            //Prevent data re-fetching
            $this->registerCallback('before.read'   , create_function('', 'return false;'));
            $this->registerCallback('before.browse' , create_function('', 'return false;'));
	    }
	}
	
	/**
	 * Store the unrendered view data in the cache
	 *
	 * @param   KCommandContext	A command context object
	 * @return 	void
	 */
	public function storeView(KCommandContext $context)
	{
	    $view   = $this->getView();
	    $cache  = KFactory::tmp('lib.joomla.cache', array($this->_getGroup(), 'output'));
	    $key    = $this->_getKey();   
	    
	    //Store the unrendered view output
	    if($view instanceof KViewTemplate) {
	        $result = (string) $view->getTemplate();
	    } else {
	        $result = $context->result;
	    }
	       
	    //Get the unrendered view data 	    
	    $cache->store((string) $result, $key);
	}
	
	/**
	 * Clean the cache
	 *
	 * @param   KCommandContext	A command context object
	 * @return 	boolean
	 */
	protected function _afterAdd(KCommandContext $context)
	{
	    $status = $context->result->getStatus();
	    
	    if($status == KDatabase::STATUS_CREATED) {
	         KFactory::tmp('lib.joomla.cache')->clean($this->_getGroup());
	    }
	      
	    return true;
	}
	
	/**
	 * Clean the cache
	 *
	 * @param   KCommandContext	A command context object
	 * @return 	boolean
	 */
	protected function _afterDelete(KCommandContext $context)
	{
	    $status = $context->result->getStatus();
	    
	    if($status == KDatabase::STATUS_DELETED) {
	        KFactory::tmp('lib.joomla.cache')->clean($this->_getGroup());
	    }
	      
	    return true;
	}
	
	/**
	 * Clean the cache
	 *
	 * @param   KCommandContext	A command context object
	 * @return 	boolean
	 */
	protected function _afterEdit(KCommandContext $context)
	{
	    $status = $context->result->getStatus();
	    
	    if($status == KDatabase::STATUS_UPDATED) {
	        KFactory::tmp('lib.joomla.cache')->clean($this->_getGroup());
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
	    $state = $this->getModel()->getState();
	   
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
	    $group = $this->_mixer->getIdentifier();
	    return $group;
	}
}