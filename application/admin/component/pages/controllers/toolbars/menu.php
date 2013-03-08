<?php
/**
 * @package     Nooku_Server
 * @subpackage  Pages
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

use Nooku\Framework;

/**
 * Menu Toolbar Class
 *
 * @author      Gergo Erdosi <http://nooku.assembla.com/profile/gergoerdosi>
 * @package     Nooku_Server
 * @subpackage  Pages
 */
class ComPagesControllerToolbarMenu extends ComBaseControllerToolbarDefault
{
    protected function _commandNew(Framework\ControllerToolbarCommand $command)
    {
        $application = $this->getController()->getModel()->application;
        $command->href = 'option=com_pages&view=menu&application='.$application;
    }
}
