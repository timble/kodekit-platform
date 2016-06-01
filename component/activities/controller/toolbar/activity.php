<?php
/**
 * Kodekit Component - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-activities for the canonical source repository
 */

namespace Kodekit\Component\Activities;

use Kodekit\Library;

/**
 * Activity Controller Toolbar.
 *
 * @author  Arunas Mazeika <https://github.com/amazeika>
 * @package Kodekit\Component\Activities
 */
class ControllerToolbarActivity extends Library\ControllerToolbarActionbar
{
    protected function _afterBrowse(Library\ControllerContextModel $context)
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