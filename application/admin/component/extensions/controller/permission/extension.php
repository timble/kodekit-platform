<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git
 */

use Nooku\Library;

/**
 * Default Controller Permissions
 *
 * @author  Ercan Ozkaya <http://nooku.assembla.com/profile/ercanozkaya>
 * @package Nooku\Component\Extensions
 */
class ExtensionsControllerPermissionExtension extends ApplicationControllerPermissionDefault
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