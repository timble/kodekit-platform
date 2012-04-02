<?php
/**
 * @version     $Id$
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Articles
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Article Toolbar Class
 *
 * @author      Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Articles
 */
class ComVersionsControllerToolbarRevisable extends KControllerToolbarAbstract
{
    protected function _initialize(KConfig $config)
    {
        $config->append(array(
    		'priority'  => KCommand::PRIORITY_LOW
        ));

        parent::_initialize($config);
    }
    
    public function onAfterControllerBrowse(KEvent $event)
    {     
        $state = $this->getController()->getModel()->getState();
        $name  = $this->getController()->getIdentifier()->name;
        
        if($state->trashed == true) 
        {    
            $toolbar = $this->getController()->getToolbar($name);
            
            $toolbar->reset();
                 
            if($this->getController()->canEdit()) {
                $toolbar->addCommand($this->getCommand('restore'));
            }
            
            if($this->getController()->canDelete()) {
                $toolbar->addCommand($this->getCommand('delete'));
            }
        } 
    }
    
    protected function _commandRestore(KControllerToolbarCommand $command)
    {
        $command->append(array(
            'attribs'  => array(
                'data-action' => 'edit'
            )
        )); 
    }
    
    protected function _commandDelete(KControllerToolbarCommand $command)
    {
        $command->append(array(
            'attribs'  => array(
                'data-action' => 'delete',
                'label' => 'Delete forever'
            )
        )); 
    }
}