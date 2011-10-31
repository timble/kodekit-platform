<?php
/**
 * @version   	$Id$
 * @category	Nooku
 * @package     Nooku_Components
 * @subpackage  Default
 * @copyright  	Copyright (C) 2007 - 2010 Johan Janssens. All rights reserved.
 * @license   	GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Default Toolbar
.*
 * @author      Johan Janssens <johan@nooku.org>
 * @category    Nooku
 * @package     Nooku_Components
 * @subpackage  Default
 */
class ComDefaultControllerToolbarDefault extends KControllerToolbarDefault
{
	/**
	 * Array of parts to render
	 *
	 * @var array
	 */
	protected $_render;
	
	/**
	 * Constructor
	 *
	 * @param 	object 	An optional KConfig object with configuration options.
	 */
	public function __construct(KConfig $config)
	{
		parent::__construct($config);

		$this->_render  = KConfig::unbox($config->render);
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
    	    'render'  => array('toolbar', 'title')
        ));
 
        parent::_initialize($config);
    }
	
	/**
	 * Add default toolbar commands and set the toolbar title
	 * .
	 * @param	KEvent	A event object
	 */
    public function onAfterControllerRead(KEvent $event)
    { 
        $name = ucfirst($this->getController()->getIdentifier()->name);
            
        if($this->getController()->getModel()->getState()->isUnique()) 
        {        
            $saveable = $this->getController()->canEdit();
            $title    = 'Edit '.$name;
        } 
        else 
        {
            $saveable = $this->getController()->canAdd();
            $title    = 'New '.$name;  
        }
            
        if($saveable)
        {
            $this->setTitle($title)
                 ->addCommand('save')
                 ->addCommand('apply');
        }
                   
        $this->addCommand('cancel',  array('attribs' => array('data-novalidate' => 'novalidate')));       
    }
      
    /**
	 * Add default toolbar commands
	 * .
	 * @param	KEvent	A event object
	 */
    public function onAfterControllerBrowse(KEvent $event)
    {    
        if($this->getController()->canAdd()) 
        {
            $identifier = $this->getController()->getIdentifier();
            $config     = array('attribs' => array(
                    		'href' => JRoute::_( 'index.php?option=com_'.$identifier->package.'&view='.$identifier->name)
                          ));
                    
            $this->addCommand('new', $config);
        }
            
        if($this->getController()->canDelete()) {
            $this->addCommand('delete');    
        }
    }
    
 	/**
	 * Push the toolbar into the view
	 * .
	 * @param	KEvent	A event object
	 */
    public function onBeforeControllerGet(KEvent $event)
    {   
        $event->caller->getView()->toolbar = $this;
    }
    
    /**
     * Enable toolbar command
     * 
     * @param   object  A KControllerToolbarCommand object
     * @return  void
     */
    protected function _commandEnable(KControllerToolbarCommand $command)
    {
        $command->icon = 'icon-32-publish'; 
        
        $command->append(array(
            'attribs' => array(
                'data-action' => 'edit',
                'data-data'   => '{enabled:1}'
            )
        ));
    }
    
    /**
     * Disable toolbar command
     * 
     * @param   object  A KControllerToolbarCommand object
     * @return  void
     */
    protected function _commandDisable(KControllerToolbarCommand $command)
    {
        $command->icon = 'icon-32-unpublish';
        
        $command->append(array(   
            'attribs' => array(
                'data-action' => 'edit',
                'data-data'   => '{enabled:0}'
            )
        ));
    }
    
    /**
     * Export toolbar command
     * 
     * @param   object  A KControllerToolbarCommand object
     * @return  void
     */
    protected function _commandExport(KControllerToolbarCommand $command)
    {
        //Get the states
        $states = $this->getController()->getModel()->getState()->toArray(); 
        
        unset($states['limit']);
        unset($states['offset']);
        
        $states['format'] = 'csv';
          
        //Get the query options
        $query  = http_build_query($states);
        $option = $this->getIdentifier()->package;
        $view   = $this->getIdentifier()->name;
        
        $command->append(array(
            'attribs' => array(
                'href' =>  JRoute::_('index.php?option=com_'.$option.'&view='.$view.'&'.$query)
            )
        ));
    }
      
    /**
     * Modal toolbar command
     * 
     * @param   object  A KControllerToolbarCommand object
     * @return  void
     */
    protected function _commandModal(KControllerToolbarCommand $command)
    { 
        $option = $this->getIdentifier()->package;
        
        $command->append(array(
            'width'   => '640',
            'height'  => '480',
            'href'	  => ''
        ))->append(array(
            'attribs' => array(
                'class' => array('modal'),
                'href'  => $command->href,
                'rel'   => '{handler: \'iframe\', size: {x: '.$command->width.', y: '.$command->height.'}}'
            )
        ));
    }
}