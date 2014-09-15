<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Component\Tags;

use Nooku\Library;

/**
 * Tag Controller Toolbar
 *
 * @author  Tom Janssens <http://github.com/tomjanssens>
 * @package Nooku\Component\Tags
 */
class ControllerToolbarTag extends Library\ControllerToolbarActionbar
{    
    protected function _commandNew(Library\ControllerToolbarCommand $command)
    {
        $component = $this->getController()->getIdentifier()->package;
		$view	   = Library\StringInflector::singularize($this->getIdentifier()->name);
		$table     = $this->getController()->getModel()->getState()->table;
		
        $command->href = 'component='.$component.'&view='.$view.'&table='.$table;
    }
}