<?php
/**
 * @version     $Id$
 * @category    Nooku
 * @package     Nooku_Components
 * @subpackage  Logs
 * @copyright   Copyright (C) 2010 Timble CVBA and Contributors. (http://www.timble.net)
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Logs Toolbar
 *
 * @author      Israel Canasa <israel@timble.net>
 * @category    Nooku
 * @package     Nooku_Components
 * @subpackage  Logs
 */
class ComLogsControllerToolbarLogs extends ComDefaultControllerToolbarDefault
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
                'data-url' => 'index.php?option=com_logs&view=logs',
                'data-action' => 'delete'
            )
        ));
    }
}