<?php
/**
 * Kodekit Component - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-pages for the canonical source repository
 */

namespace Kodekit\Component\Pages;

use Kodekit\Library;

/**
 * Module Controller Toolbar
 *
 * @author  Stian Didriksen <http://github.com/stipsan>
 * @package Kodekit\Component\Pages
 */
class ControllerToolbarModule extends Library\ControllerToolbarActionbar
{
    protected function _afterBrowse(Library\ControllerContextInterface $context)
    {
        parent::_afterBrowse($context);

        $this->addSeparator();
        $this->addEnable(array('label' => 'publish', 'attribs' => array('data-data' => '{published:1}')));
        $this->addDisable(array('label' => 'unpublish', 'attribs' => array('data-data' => '{published:0}')));
    }

    protected function _commandNew(Library\ControllerToolbarCommand &$command)
    {
        $command = $this->getCommand('dialog', array('label' => 'new'));
        $command->href = 'component=pages&view=modules&layout=list&installed=1&tmpl=dialog';
    }
}