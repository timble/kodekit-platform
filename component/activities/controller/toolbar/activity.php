<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Component\Activities;

use Nooku\Library;

/**
 * Activity Controller Toolbar.
 *
 * @author  Arunas Mazeika <https://github.com/amazeika>
 * @package Nooku\Component\Activities
 */
class ControllerToolbarActivity extends Library\ControllerToolbarActionbar
{
    protected function _afterBrowse(Library\ControllerContextInterface $context)
    {
        if ($this->getController()->canPurge()) {
            $this->addPurge();
        }

        return parent::_afterBrowse($context);
    }

    protected function _commandPurge(Library\ControllerToolbarCommandInterface $command)
    {
        $command->append(array(
            'attribs' => array(
                'data-action'     => 'purge',
                'data-novalidate' => 'novalidate',
                'data-prompt'     => $this->getObject('translator')
                                          ->translate('Deleted items will be lost forever. Would you like to continue?')
            )
        ));
    }
}