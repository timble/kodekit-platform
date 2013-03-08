<?php
/**
 * @package     Nooku_Components
 * @subpackage  Default
 * @copyright  	Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license   	GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

use Nooku\Framework;

/**
 * Default Toolbar
 *
 * @author      Johan Janssens <johan@nooku.org>
 * @package     Nooku_Components
 * @subpackage  Default
 */
class ComDefaultControllerToolbarDefault extends Framework\ControllerToolbarDefault
{
	/**
	 * Push the toolbar into the view
	 * .
	 * @param	Framework\Event	A event object
	 */
    public function onBeforeControllerRender(Framework\Event $event)
    {   
        $event->getTarget()->getView()->toolbar = $this;
    }
	
	/**
	 * Add default toolbar commands and set the toolbar title
	 * .
	 * @param	Framework\Event	A event object
	 */
    public function onAfterControllerRead(Framework\Event $event)
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
            $this->addCommand('save');
            $this->addCommand('apply');
        }
                   
        $this->addCommand('cancel',  array('attribs' => array('data-novalidate' => 'novalidate')));       
    }
      
    /**
	 * Add default toolbar commands
	 * .
	 * @param	Framework\Event	A event object
	 */
    public function onAfterControllerBrowse(Framework\Event $event)
    {    
        if($this->getController()->canAdd()) 
        {
            $identifier = $this->getController()->getIdentifier();
            $config     = array('href' => 'option=com_'.$identifier->package.'&view='.$identifier->name);
                    
            $this->addCommand('new', $config);
        }
            
        if($this->getController()->canDelete()) {
            $this->addCommand('delete');    
        }
    }
       
    /**
     * Enable toolbar command
     *
     * @param   object  A Framework\ControllerToolbarCommand object
     * @return  void
     */
    protected function _commandEnable(Framework\ControllerToolbarCommand $command)
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
     * @param   object  A Framework\ControllerToolbarCommand object
     * @return  void
     */
    protected function _commandDisable(Framework\ControllerToolbarCommand $command)
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
     * @param   object  A Framework\ControllerToolbarCommand object
     * @return  void
     */
    protected function _commandExport(Framework\ControllerToolbarCommand $command)
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

        $command->href = 'option=com_'.$option.'&view='.$view.'&'.$query;
    }

    /**
     * Dialog toolbar command
     *
     * @param   object  A Framework\ControllerToolbarCommand object
     * @return  void
     */
    protected function _commandDialog(Framework\ControllerToolbarCommand $command)
    {
        $option = $this->getIdentifier()->package;

        $command->append(array(
            'width'   => '640',
            'height'  => '480',
        ))->append(array(
            'attribs' => array(
                'class' => array('modal'),
                'rel'   => '{handler: \'url\', ajaxOptions:{method:\'get\'}}',
            )
        ));
    }
}