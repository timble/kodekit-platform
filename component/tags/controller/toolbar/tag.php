<?php
/**
 * Kodekit Component - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-tags for the canonical source repository
 */

namespace Kodekit\Component\Tags;

use Kodekit\Library;

/**
 * Tag Controller Toolbar
 *
 * @author  Tom Janssens <http://github.com/tomjanssens>
 * @package Kodekit\Component\Tags
 */
class ControllerToolbarTag extends Library\ControllerToolbarActionbar
{
    protected function _commandNew(Library\ControllerToolbarCommand $command)
    {
        $component = $this->getController()->getIdentifier()->package;
        $view      = Library\StringInflector::singularize($this->getIdentifier()->name);

        $command->href = 'component='.$component.'&view='.$view;
    }
}