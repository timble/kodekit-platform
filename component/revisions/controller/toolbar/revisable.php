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
class ControllerToolbarRevisable extends Library\ControllerToolbarDecorator
{
    /**
     * Add default toolbar commands
     * .
     * @param	Library\CommandContext	$context A command context object
     */
    protected function _afterControllerBrowse(Library\CommandContext $context)
    {
        $controller = $this->getController();

        if($controller->canEdit()) {
            $this->addCommand('restore');
        }

        if($controller->canDelete()) {
            $this->addCommand('delete');
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