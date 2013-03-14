<?php
/**
 * @package     Nooku_Server
 * @subpackage  Terms
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Terms Toolbar Class
 *
 * @author      Tom Janssens <http://nooku.assembla.com/profile/tomjanssens>
 * @package     Nooku_Server
 * @subpackage  Terms   
 */
class ComTermsControllerToolbarTerm extends ComDefaultControllerToolbarDefault
{    
    protected function _commandNew(KControllerToolbarCommand $command)
    {
        $option = $this->getController()->getIdentifier()->package;
		$view	= KInflector::singularize($this->getIdentifier()->name);
		$table  = $this->getController()->getModel()->get('table');
		
        $command->href = 'option=com_'.$option.'&view='.$view.'&table='.$table;
    }
}