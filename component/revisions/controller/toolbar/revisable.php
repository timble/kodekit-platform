<?php
/**
 * Kodekit Component - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-revisions for the canonical source repository
 */

namespace Kodekit\Component\Revisions;

use Kodekit\Library;

/**
 * Revisable Controller Toolbar
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Kodekit\Component\Revisions
 */
class ControllerToolbarRevisable extends Library\ControllerToolbarDecorator
{
    /**
     * Add default toolbar commands
     * .
     * @param   Library\Command	$context A command context object
     */
    protected function _afterBrowse(Library\ControllerContextModel $context)
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