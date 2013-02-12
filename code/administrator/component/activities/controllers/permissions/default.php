<?php
/**
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Activities
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Executable Controller Behavior
 *
 * @author      Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Activities
 */
class ComActivitiesControllerPermissionDefault extends ComDefaultControllerPermissionDefault
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