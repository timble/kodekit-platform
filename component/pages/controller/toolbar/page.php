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
 * Pages Controller Toolbar
 *
 * @author  Gergo Erdosi <http://github.com/gergoerdosi>
 * @package Kodekit\Component\Pages
 */
class ControllerToolbarPage extends Library\ControllerToolbarActionbar
{
    protected function _afterBrowse(Library\ControllerContextModel $context)
    {
        parent::_afterBrowse($context);

        $this->addSeparator();


        $this->addEnable(array(
            'label' => 'publish',
            'data'  => array('data' => array('published' => 1))
        ));

        $this->addDisable(array(
            'label' => 'unpublish',
            'data'  => array('data' => array('published' => 0))
        ));

        $this->addSeparator();
        $this->addDefault();
    }

    protected function _commandDefault(Library\ControllerToolbarCommand $command)
    {
        $command->label = $this->getObject('translator')->translate('Make Default');

        $command->append(array(
            'data' => array(
                'action' => 'edit',
                'data'   => array('default' => 1)
            )
        ));
    }

    protected function _commandRestore(Library\ControllerToolbarCommand $command)
    {
        $command->append(array(
            'data' => array(
                'action' => 'edit',
            )
        ));
    }

    protected function _commandNew(Library\ControllerToolbarCommand $command)
    {
        $menu = $this->getController()->getModel()->getState()->menu;
        $command->href = 'component=pages&view=page&menu='.$menu;
    }
}
