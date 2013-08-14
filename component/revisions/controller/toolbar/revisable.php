<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

namespace Nooku\Component\Revisions;

use Nooku\Library;

/**
 * Revisable Controller Toolbar
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Component\Revisions
 */
class ControllerToolbarRevisable extends Library\ControllerToolbarAbstract
{
    protected function _initialize(Library\ObjectConfig $config)
    {
        $config->append(array(
    		'priority'  => Library\CommandChain::PRIORITY_LOW
        ));

        parent::_initialize($config);
    }
    
    public function onAfterControllerBrowse(Library\Event $event)
    {     
        $controller = $this->getController();
        $state      = $controller->getModel()->getState();
        
        if($state->trashed == true) 
        {
            $name    = $controller->getIdentifier()->name;
            $toolbar = $this->getController()->getToolbar($name);
            $toolbar->reset();
                 
            if($controller->canEdit()) {
                $toolbar->addCommand($this->getCommand('restore'));
            }
            
            if($controller->canDelete()) {
                $toolbar->addCommand($this->getCommand('delete'));
            }
        } 
    }
    
    protected function _commandRestore(Library\ControllerToolbarCommand $command)
    {
        $command->append(array(
            'attribs'  => array(
                'data-action' => 'edit',
            )
        )); 
    }
    
    protected function _commandDelete(Library\ControllerToolbarCommand $command)
    {
        $command->append(array(
            'attribs'  => array(
                'label'       => 'Delete forever',
                'data-action' => 'delete'
            )
        )); 
    }
}