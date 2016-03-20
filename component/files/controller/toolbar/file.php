<?php
/**
 * Kodekit Component - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-files for the canonical source repository
 */

namespace Kodekit\Component\Files;

use Kodekit\Library;

/**
 * File Controller Toolbar
 *
 * @author  Ercan Ozkaya <http://github.com/ercanozkaya>
 * @package Kodekit\Component\Files
 */
class ControllerToolbarFile extends Library\ControllerToolbarActionbar
{
    public function getCommands()
    {
        $controller = $this->getController();

        if ($controller->canAdd())
        {
            $this->addCommand('upload');
            $this->addNew(array('label' => 'New Folder'));
        }

        if ($controller->canDelete()) {
            $this->addDelete();
        }

        return parent::getCommands();
    }
}