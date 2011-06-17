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
     * Constructor
     *
     * @param   object  An optional KConfig object with configuration options
     */
    public function __construct(KConfig $config)
    {
        parent::__construct($config);
       
        $name  = $this->_identifier->name;
        
        //Insert the default commands
        if($config->auto_defaults)
        {
            if(KInflector::isPlural($name))
            {        
                $this->addCommand('new')
                     ->addCommand('delete');   
            } 
            else
            {    
                $this->addCommand('save')
                     ->addCommand('apply')
                     ->addCommand('cancel');
            }
        }
    }
    
	/**
     * Set the toolbar's title
     * 
     * Override the toolbar title for singlural toolbars.
     *
     * @param   string  Title
     * @return  KToolbarInterface
     */
    public function setTitle($title)
    {
        $name = $this->_identifier->name;
        
        if(KInflector::isSingular($name))
        {     
            $state = $this->getController()->getModel()->getState();   
            
            $name  = ucfirst($this->getName());  
            $title = $state->isUnique() ? 'Edit '.$name : 'New '.$name;
        }
        
        return parent::setTitle($title);
    }
    
	/**
     * New toolbar command
     * 
     * @param   object  A KControllerToolbarCommand object
     * @return  void
     */
    protected function _commandNew(KControllerToolbarCommand $command)
    {
        $option = $this->_identifier->package;
        $view   = KInflector::singularize($this->_identifier->name);
        
        $command->append(array(
            'attribs' => array(
                'href'     => JRoute::_( 'index.php?option=com_'.$option.'&view='.$view)
            )
        ));
    }
    
    /**
     * Cancel toolbar command
     * 
     * @param   object  A KControllerToolbarCommand object
     * @return  void
     */
    protected function _commandCancel(KControllerToolbarCommand $command)
    {  
        $command->append(array(
        	'attribs' => array(
                'data-action' 	  => 'cancel',
        		'data-novalidate' => 'novalidate'
            )
        ));	
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
        $option = $this->_identifier->package;
        $view   = $this->_identifier->name;
        
        $command->append(array(
            'attribs' => array(
                'href' =>  JRoute::_('index.php?option=com_'.$option.'&view='.$view.'&'.$query)
            )
        ));
    }
      
    /**
     * Preferences toolbar command
     * 
     * @param   object  A KControllerToolbarCommand object
     * @return  void
     */
    protected function _commandPreferences(KControllerToolbarCommand $command)
    { 
        $option = $this->_identifier->package;
        
        $command->append(array(
            'width'   => '640',
            'height'  => '480',
        ))->append(array(
            'attribs' => array(
                'class' => array('modal'),
                'href'  => 'index.php?option=com_config&controller=component&component=com_'.$option,
                'rel'   => '{handler: \'iframe\', size: {x: '.$command->width.', y: '.$command->height.'}}'
            )
        ));
    }
}