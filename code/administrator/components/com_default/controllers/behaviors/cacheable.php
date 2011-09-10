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
	 * List of modules to cache
	 *
	 * @var	array
	 */
	protected $_modules;
	
	/**
	 * The cached state of the resource
	 * 
	 * @var boolean
	 */
	protected $_output = ''; 
	
	/**
	 * Constructor
	 *
	 * @param 	object 	An optional KConfig object with configuration options.
	 */
	public function __construct(KConfig $config)
	{
		parent::__construct($config);

		// Set the view identifier
		$this->_modules = KConfig::toData($config->modules);
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
        $config->append(array(
            'modules'	=> array('toolbar', 'title', 'submenu')
	  	));
	  	
    	parent::_initialize($config);
   	}
	
	/**
	 * Fetch the unrendered view data from the cache
	 *
	 * @param   KCommandContext	A command context object
	 * @return 	void	
	 */
	protected function _beforeGet(KCommandContext $context)
	{ 
	    $view   = $this->getView();
	    $cache  = JFactory::getCache($this->_getGroup(), 'output');
        $key    = $this->_getKey();
        
        if($data = $cache->get($key))
        {
            $data = unserialize($data);
            
            //Render the view output
            if($view instanceof KViewTemplate) 
            {
                $context->result = $view->getTemplate()
                               ->loadString($data['component'], array(), false)
                               ->render();
            } 
            else $context->result = $data['component'];
            
            //Render the modules
            if(isset($data['modules']))
            {
                foreach($data['modules'] as $name => $content) {
                    JFactory::getDocument()->setBuffer($content, 'modules', $name);
                }
            }  
 
            //Dequeue the commandable behavior from the chain
            if($commandable = $this->getBehavior('commandable')) {
                $this->getCommandChain()->dequeue($commandable);
            }
           
            $this->_output = $context->result;
	    }
	}
	
	/**
	 * Store the unrendered view data in the cache
	 *
	 * @param   KCommandContext	A command context object
	 * @return 	void
	 */
	protected function _afterGet(KCommandContext $context)
	{
	    if(empty($this->_output))
	    {
	        $view   = $this->getView();
	        $cache  = JFactory::getCache($this->_getGroup(), 'output');
	        $key    = $this->_getKey();

	        $data  = array();
	   
	        //Store the unrendered view output
	        if($view instanceof KViewTemplate) 
	        {
	            $data['component'] = (string) $view->getTemplate();
	        
	            $buffer = JFactory::getDocument()->getBuffer();
	            if(isset($buffer['modules'])) {       
	                $data['modules'] = array_intersect_key($buffer['modules'], array_flip($this->_modules));
	            }
	        } 
	        else $data['component'] = $context->result;
	        
	        $cache->store(serialize($data), $key);
	    }
	}
	
	/**
	 * Return the cached data after read
	 * 
	 * Only if cached data was found return it but allow the chain to continue to allow
	 * processing all the read commands
	 *
	 * @param   KCommandContext	A command context object
	 * @return 	void
	 */
	protected function _afterRead(KCommandContext $context)
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
	 * @param   KCommandContext	A command context object
	 * @return 	void
	 */
    protected function _beforeBrowse(KCommandContext $context)
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
	 * @param   KCommandContext	A command context object
	 * @return 	boolean
	 */
	protected function _afterAdd(KCommandContext $context)
	{
	    $status = $context->result->getStatus();
	    
	    if($status == KDatabase::STATUS_CREATED) {
	         JFactory::getCache()->clean($this->_getGroup());
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
	        JFactory::getCache()->clean($this->_getGroup());
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
	        JFactory::getCache()->clean($this->_getGroup());
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
	    $state = $this->getModel()->getState()->toArray();
	    
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