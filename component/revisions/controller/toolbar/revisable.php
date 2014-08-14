<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Component\Revisions;

use Nooku\Library;

/**
 * Revisable Controller Toolbar
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Nooku\Component\Revisions
 */
class ControllerToolbarRevisable extends Library\ControllerToolbarDecorator
{
    /**
     * Add default toolbar commands
     * .
     * @param	Library\Command	$context A command context object
     */
    protected function _afterBrowse(Library\ControllerContextInterface $context)
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