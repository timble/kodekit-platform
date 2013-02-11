<?php
/**
 * @package     Nooku_Server
 * @subpackage  Extensions
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Default Controller Permissions Class
 *
 * @author      Ercan Ozkaya <http://nooku.assembla.com/profile/ercanozkaya>
 * @package     Nooku_Server
 * @subpackage  Extensions
 */
class ComExtensionsControllerPermissionDefault extends ComDefaultControllerPermissionDefault
{
    public function canAdd()
    {
        return false;
    }

    public function canDelete()
    {
        return false;
    }
}