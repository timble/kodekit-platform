<?php
/**
 * @version     $Id$
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Info
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Default Toolbar Class
 *
 * @author      Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Info
 */
class ComInfoControllerToolbarDefault extends ComDefaultControllerToolbarDefault
{
    public function getTitle()
    {
        $title = $this->_title;
        $name = $this->getController()->getIdentifier()->name;
              
        switch($name)
        {
            case 'system'         : $title = 'System Information'; break;
            case 'configuration'   : $title = 'Configuration File'; break;
            case 'directory'      : $title = 'Directory Permissions'; break;
            case 'phpinformation' : $title = 'PHP Information'; break;
            case 'phpsetting'     : $title = 'PHP Settings'; break;
        }
   
        return $title;
    }
}