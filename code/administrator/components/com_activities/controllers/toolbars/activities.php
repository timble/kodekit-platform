<?php
/**
 * @version     $Id: logs.php 1041 2011-10-09 00:04:40Z johanjanssens $
 * @category    Nooku
 * @package     Nooku_Components
 * @subpackage  Activities
 * @copyright   Copyright (C) 2010 Timble CVBA and Contributors. (http://www.timble.net)
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Activities Toolbar
 *
 * @author      Israel Canasa <israel@timble.net>
 * @category    Nooku
 * @package     Nooku_Components
 * @subpackage  Activities
 */
class ComActivitiesControllerToolbarActivities extends ComDefaultControllerToolbarDefault
{
    public function getCommands()
    {
        $this->reset()
             ->addDelete();

        return parent::getCommands();
    }
    
    protected function _commandDelete(KControllerToolbarCommand $command)
    {
        $command->append(array(
            'attribs' => array(
                'data-url' => 'index.php?option=com_activities&view=activities',
                'data-action' => 'delete'
            )
        ));
    }
}