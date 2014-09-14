<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Component\Files;

use Nooku\Library;

/**
 * File Controller Toolbar
 *
 * @author  Ercan Ozkaya <http://github.com/ercanozkaya>
 * @package Nooku\Component\Files
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