<?php
/**
 * @version     $Id: toolbar.php 4313 2011-10-23 21:49:58Z johanjanssens $
 * @category	Koowa
 * @package     Koowa_Mixin
 * @copyright   Copyright (C) 2007 - 2010 Johan Janssens. All rights reserved.
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Behavior Mixin Class
 *  
 * @author      Johan Janssens <johan@nooku.org>
 * @category    Koowa
 * @package     Koowa_Mixin
 */
class KMixinBehavior extends KMixinAbstract
{  
	/**
	 * List of behaviors
	 * 
	 * Associative array of behaviors, where key holds the behavior identifier string
	 * and the value is an identifier object.
	 * 
	 * @var	array
	 */
	protected $_behaviors = array();
	
	/**
	 * Constructor
	 *
	 * @param 	object 	An optional KConfig object with configuration options.
	 */
	public function __construct(KConfig $config)
	{
		parent::__construct($config);
		
	    //Add the toolbars
        if(!empty($config->behaviors)) 
        {
            $behaviors = (array) KConfig::unbox($config->behaviors);
            
            foreach($behaviors as $key => $value) 
            {
                if(is_numeric($key)) {
                    $this->addBehavior($value);
                } else {
                    $this->addBehavior($key, $value);
                }
            }
        } 
	}
	
	/**
     * Initializes the default configuration for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param 	object 	An optional KConfig object with configuration options.
     * @return void
     */
    protected function _initialize(KConfig $config)
    {
    	parent::_initialize($config);
    	
        $config->append(array(
    		'behaviors' => array(),
        ));
    }
    
	/**
     * Check if a behavior exists
     *
     * @param 	string	The name of the behavior
     * @return  boolean	TRUE if the behavior exists, FALSE otherwise
     */
	public function hasBehavior($behavior)
	{ 
	    return isset($this->_behaviors[$behavior]); 
	}
	
	/**
     * Add one or more behaviors to the controller
     *
     * @param   array Array of one or more behaviors to add
     * @param	array An optional associative array of configuration settings
     * @return  KObject	The mixer object
     */
    public function addBehavior($behavior, $config = array())
    {  
        if (!($behavior instanceof KBehaviorInterface)) { 
           $behavior = $this->getBehavior($behavior);
        }
                
        //Add the behaviors
        $this->_behaviors[$behavior->getIdentifier()->name] = $behavior;
            
        //Enqueue the behavior
        $this->getCommandChain()->enqueue($behavior);
        
        return $this;
    }
   
	/**
     * Get a behavior by identifier
     *
     * @return KControllerBehaviorAbstract
     */
    public function getBehavior($behavior, $config = array())
    {
       if(!($behavior instanceof KServiceIdentifier))
       {
            //Create the complete identifier if a partial identifier was passed
           if(is_string($behavior) && strpos($behavior, '.') === false )
           {
               $identifier = clone $this->getIdentifier();
               $identifier->path  = array($identifier->path[0], 'behavior');
               $identifier->name  = $behavior;
           }
           else $identifier = $this->getIdentifier($behavior);
       }
           
       if(!isset($this->_behaviors[$identifier->name])) 
       {
           $behavior = $this->getService($identifier, array_merge($config, array('mixer' => $this->getMixer())));
           
           //Check the behavior interface
		   if(!($behavior instanceof KBehaviorInterface)) {
			   throw new KBehaviorException("Behavior $identifier does not implement KBehaviorInterface");
		   }
       } 
       else $behavior = $this->_behaviors[$identifier->name];
       
       return $behavior;
    }
    
    /**
     * Gets the behaviors of the table
     *
     * @return array    An asscociate array of table behaviors, keys are the behavior names
     */
    public function getBehaviors()
    {
        return $this->_behaviors;
    }
}