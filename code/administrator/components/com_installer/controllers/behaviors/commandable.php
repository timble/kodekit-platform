<?php
/**
 * @version     $Id$
 * @category	Nooku
 * @package     Nooku_Components
 * @subpackage  Installer
 * @copyright   Copyright (C) 2007 - 2010 Johan Janssens. All rights reserved.
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Commandable Controller Behavior Class
 *
 * @author      Stian Didriksen <http://nooku.assembla.com/profile/stiandidriksen>
 * @category    Nooku
 * @package     Nooku_Components
 * @subpackage  Installer
 */
class ComInstallerControllerBehaviorCommandable  extends ComDefaultControllerBehaviorCommandable
{
    /**
	 * Sidebar object or identifier (APP::com.COMPONENT.model.NAME)
	 *
	 * @var	string|object
	 */
	protected $_sidebar;
	
	/**
	 * Constructor
	 *
	 * @param 	object 	An optional KConfig object with configuration options.
	 */
	public function __construct(KConfig $config)
	{
		parent::__construct($config);

		// Set the view identifier
		$this->_sidebar = $config->sidebar;
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
    		'sidebar' => 'sidebar',
    		'menubar' => 'com://admin/extensions.controller.toolbar.menubar'
        ));
 
        parent::_initialize($config);
    }
    	
	/**
	 * Get the sidebar object
	 *
	 * @throws  KControllerException if the sidebar cannot be found.
	 * @return	KControllerToolbarAbstract 
	 */
    public function getSidebar()
    { 
        if(!$this->_sidebar instanceof KControllerToolbarAbstract)
		{	   
		    //Make sure we have a view identifier
		    if(!($this->_sidebar instanceof KIdentifier)) {
		        $this->setSidebar($this->_sidebar);
			}
		
			$config = array(
			    'controller' => $this->getMixer()
			);
			
			$this->_sidebar = KFactory::get($this->_sidebar, $config);
		}    
         
        return $this->_sidebar;
    }
	
	/**
	 * Method to set a sidebar object attached to the controller
	 *
	 * @param	mixed	An object that implements KObjectIdentifiable, an object that
	 *                  implements KIdentifierInterface or valid identifier string
	 * @throws	KControllerBehaviorException	If the identifier is not a view identifier
	 * @return	KControllerToolbarAbstract 
	 */
    public function setSidebar($sidebar)
    {
        if(!($sidebar instanceof KControllerToolbarAbstract))
		{
			if(is_string($sidebar) && strpos($sidebar, '.') === false ) 
		    {
			    $identifier         = clone $this->_identifier;
                $identifier->path   = array('controller', 'toolbar');
                $identifier->name   = $sidebar;
			}
			else $identifier = KFactory::identify($sidebar);
			
			if($identifier->path[1] != 'toolbar') {
				throw new KControllerBehaviorException('Identifier: '.$identifier.' is not a toolbar identifier');
			}

			$sidebar = $identifier;
		}
		
		$this->_sidebar = $sidebar;
        
        return $this;
    }

    /**
     * Push the sidebar and installer section to the buffers
     *
     * @param	KCommandContext	A command context object
     */
    public function _beforeGet(KCommandContext $context)
    {
        //If parent isn't called, the toolbar wont be set and an exception will be thrown
        parent::_beforeGet($context);

        if($this->isDispatched() && ($this->getView() instanceof KViewHtml))
        {
            //Render the sidebar
            $document = JFactory::getDocument();
            $config   = array('sidebar' => $this->getSidebar());
                
            $sidebar = $this->getView()->getTemplate()->getHelper('sidebar')->render($config);
            $document->setBuffer($sidebar, 'modules', 'sidebar');

            //Render installer area
            $controller = KFactory::get('com://admin/installer.controller.install');
            $controller->getModel()->set($this->getRequest());
            $view = $controller->display();
            $document->setBuffer($view, 'modules', 'install');
        }
    }
}