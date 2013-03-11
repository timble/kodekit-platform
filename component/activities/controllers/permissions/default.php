<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git
 */

namespace Nooku\Component\Activities;

use Nooku\Framework;

/**
 * Executable Controller Behavior
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Component\Activities
 */
class ControllerPermissionDefault extends \ComBaseControllerPermissionDefault
{  
    public function canAdd()
    {
        return false; 
    }
    
    public function canEdit()
    { 
        return false; 
    }
}