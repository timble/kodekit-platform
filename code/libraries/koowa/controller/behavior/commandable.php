<?php
/**
 * @version		$Id$
 * @category	Koowa
 * @package		Koowa_Controller
 * @subpackage	Command
 * @copyright	Copyright (C) 2007 - 2010 Johan Janssens. All rights reserved.
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link     	http://www.nooku.org
 */

/**
 * Commandable Controller Behavior Class
 *
 * @author		Johan Janssens <johan@nooku.org>
 * @category	Koowa
 * @package     Koowa_Controller
 * @subpackage	Behavior
 */
class KControllerBehaviorCommandable extends KControllerBehaviorAbstract
{  
	/**
	 * Toolbar object or identifier (APP::com.COMPONENT.model.NAME)
	 *
	 * @var	string|object
	 */
	protected $_toolbar;
	
	/**
	 * Constructor
	 *
	 * @param 	object 	An optional KConfig object with configuration options.
	 */
	public function __construct(KConfig $config)
	{
		parent::__construct($config);

		// Set the view identifier
		$this->_toolbar = $config->toolbar;
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
    	$config->append(array(
    		'toolbar'	 => null,
    	    'priority'   => KCommand::PRIORITY_LOW,
        ));
 
        parent::_initialize($config);
    }
    	
	/**
	 * Get the view object attached to the controller
	 *
	 * @throws  KControllerException if the view cannot be found.
	 * @return	KControllerToolbarAbstract 
	 */
    public function getToolbar()
    { 
        if(!$this->_toolbar instanceof KControllerToolbarAbstract)
		{	   
		    //Make sure we have a view identifier
		    if(!($this->_toolbar instanceof KIdentifier)) {
		        $this->setToolbar($this->_toolbar);
			}
		
			$config = array(
			    'controller' => $this->getMixer()
			);
			
			$this->_toolbar = KFactory::tmp($this->_toolbar, $config);
		}    
         
        return $this->_toolbar;
    }
	
	/**
	 * Method to set a toolbar object attached to the controller
	 *
	 * @param	mixed	An object that implements KObjectIdentifiable, an object that
	 *                  implements KIdentifierInterface or valid identifier string
	 * @throws	KControllerBehaviorException	If the identifier is not a view identifier
	 * @return	KControllerToolbarAbstract 
	 */
    public function setToolbar($toolbar)
    {
        if(!($toolbar instanceof KControllerToolbarAbstract))
		{
			if(is_string($toolbar) && strpos($toolbar, '.') === false ) 
		    {
			    $identifier         = clone $this->_identifier;
                $identifier->path   = array('controller', 'toolbar');
                $identifier->name   = $toolbar;
			}
			else $identifier = KFactory::identify($toolbar);
			
			if($identifier->path[1] != 'toolbar') {
				throw new KControllerBehaviorException('Identifier: '.$identifier.' is not a toolbar identifier');
			}

			$toolbar = $identifier;
		}
		
		$this->_toolbar = $toolbar;
        
        return $this;
    }
    
    /**
	 * Check if the controller has a toolbar
	 *
	 * @return	boolean	TRUE if a toolbar exists, otherwise FALSE
	 */
    public function hasToolbar()
    { 
        return isset($this->_toolbar);
    }
    
    /**
	 * Set the toolbar 
	 *
	 * @param   KCommandContext	A command context object
	 * @return 	boolean
	 */
    protected function _beforeGet(KCommandContext $context)
    {
        $view = $this->getView();
        
        //Set the toolbar name based on the view name
        $this->setToolbar($view->getName());
        
        //Allow getting the toolbar from the view
        $this->getView()->mixin($this);
    }
     
    /**
	 * Add default toolbar commands
	 * .
	 * @param	KCommandContext	A command context object
	 */
    protected function _afterBrowse(KCommandContext $contex)
    {     
        if($this->hasToolbar()) {
            $this->getToolbar()->addCommand('new');
            $this->getToolbar()->addCommand('delete');
        }
    }
}