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
 * Commandable Controller Behavior Class
 *
 * @author      Johan Janssens <johan@nooku.org>
 * @category    Nooku
 * @package     Nooku_Components
 * @subpackage  Default
 */
class ComDefaultControllerBehaviorCommandable  extends KControllerBehaviorCommandable
{  
	/**
	 * Menubar object or identifier (APP::com.COMPONENT.model.NAME)
	 *
	 * @var	string|object
	 */
	protected $_menubar;
	
	/**
	 * Constructor
	 *
	 * @param 	object 	An optional KConfig object with configuration options.
	 */
	public function __construct(KConfig $config)
	{
		parent::__construct($config);

		// Set the view identifier
		$this->_menubar = $config->menubar;
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
    		'menubar' => 'menubar',
        ));
 
        parent::_initialize($config);
    }
    	
	/**
	 * Get the menubar object
	 *
	 * @throws  KControllerException if the menubar cannot be found.
	 * @return	KControllerToolbarAbstract 
	 */
    public function getMenubar()
    { 
        if(!$this->_menubar instanceof KControllerToolbarAbstract)
		{	   
		    //Make sure we have a view identifier
		    if(!($this->_menubar instanceof KIdentifier)) {
		        $this->setMenubar($this->_menubar);
			}
		
			$config = array(
			    'controller' => $this->getMixer()
			);
			
			$this->_menubar = KFactory::tmp($this->_menubar, $config);
		}    
         
        return $this->_menubar;
    }
	
	/**
	 * Method to set a menubar object attached to the controller
	 *
	 * @param	mixed	An object that implements KObjectIdentifiable, an object that
	 *                  implements KIdentifierInterface or valid identifier string
	 * @throws	KControllerBehaviorException	If the identifier is not a view identifier
	 * @return	KControllerToolbarAbstract 
	 */
    public function setMenubar($menubar)
    {
        if(!($menubar instanceof KControllerToolbarAbstract))
		{
			if(is_string($menubar) && strpos($menubar, '.') === false ) 
		    {
			    $identifier         = clone $this->_identifier;
                $identifier->path   = array('controller', 'toolbar');
                $identifier->name   = $menubar;
			}
			else $identifier = KFactory::identify($menubar);
			
			if($identifier->path[1] != 'toolbar') {
				throw new KControllerBehaviorException('Identifier: '.$identifier.' is not a toolbar identifier');
			}

			$menubar = $identifier;
		}
		
		$this->_menubar = $menubar;
        
        return $this;
    }
      
    /**
	 * Add default toolbar commands
	 * .
	 * @param	KCommandContext	A command context object
	 */
    public function _afterGet(KCommandContext $context)
    {    
        if($this->isDispatched() && ($this->getView() instanceof KViewHtml))
        {
            //Render the toolbar
	        $document = KFactory::get('lib.joomla.document');
            $config   = array('toolbar' => $this->getToolbar());
	            
	        $toolbar = $this->getView()->getTemplate()->getHelper('toolbar')->render($config);
            $document->setBuffer($toolbar, 'modules', 'toolbar');
                
            $title = $this->getView()->getTemplate()->getHelper('toolbar')->title($config);
            $document->setBuffer($title, 'modules', 'title');
	      
            //Render the menubar
            $document = KFactory::get('lib.joomla.document');
            $config   = array('menubar' => $this->getMenubar());
                
            $menubar = $this->getView()->getTemplate()->getHelper('menubar')->render($config);
            $document->setBuffer($menubar, 'modules', 'submenu');
        }
    }
}