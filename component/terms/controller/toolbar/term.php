<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git
 */

namespace Nooku\Component\Terms;

use Nooku\Library;

/**
 * Term Controller Toolbar
 *
 * @author  Tom Janssens <http://nooku.assembla.com/profile/tomjanssens>
 * @package Nooku\Component\Terms
 */
class ControllerToolbarTerm extends Library\ControllerToolbarModel
{    
    protected function _commandNew(Library\ControllerToolbarCommand $command)
    {
        $option = $this->getController()->getIdentifier()->package;
		$view	= Library\StringInflector::singularize($this->getIdentifier()->name);
		$table  = $this->getController()->getModel()->getState()->table;
		
        $command->href = 'option=com_'.$option.'&view='.$view.'&table='.$table;
    }
}